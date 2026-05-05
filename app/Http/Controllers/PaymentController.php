<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppBooking;
use App\Models\AppBookingTransaction;
use App\Models\Flight;
use App\Jobs\SendBookingConfirmationEmail;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * PaymentController
     * Xử lý callback VNPay:
     * - `vnpayReturn`: redirect user sau thanh toán (GET)
     * - `vnpayIpn`: xử lý IPN (server-to-server)
     * - Các hàm phụ trợ: validateSignature, confirmSuccessfulPayment, markFailedPayment
     * Lưu ý: so sánh chữ ký phải dùng hash_equals để chống timing attack (đã cập nhật).
     */
    public function vnpayReturn(Request $request)
    {
        // Handler khi VNPay redirect người dùng về (thường dùng để show result page)
        // Kiểm tra chữ ký, so sánh số tiền, cập nhật trạng thái nếu cần.
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $transactionCode = $request->input('vnp_TxnRef');
        $vnp_Amount = $request->input('vnp_Amount'); // Số tiền từ VNPAY (đã nhân 100)

        if (!$transactionCode) {
            return redirect('/')->withErrors(['error' => 'Đường dẫn không hợp lệ!']);
        }

        $transaction = AppBookingTransaction::where('transaction_code', $transactionCode)->first();
        $booking = $transaction ? AppBooking::find($transaction->booking_id) : null;

        // 1. Kiểm tra chữ ký và đối soát số tiền
        $isSignatureValid = $this->validateSignature($request->all());
        $expectedAmount = $booking ? $booking->total_amount : 0;
        $paidAmount = $vnp_Amount / 100;
        $isAmountMatch = ($paidAmount == $expectedAmount);

        if (!$transaction || !$isSignatureValid || !$isAmountMatch) {
            $errorMsg = !$isSignatureValid ? 'Chữ ký không hợp lệ!' : (!$isAmountMatch ? 'Số tiền không khớp!' : 'Không tìm thấy giao dịch!');
            return redirect('/')->withErrors(['error' => 'Xác thực thanh toán thất bại: ' . $errorMsg]);
        }

        $bookingCode = $booking->booking_code ?? 'LỖI_MẤT_MÃ_VÉ';

        if ($vnp_ResponseCode == '00') {
            $processed = $this->confirmSuccessfulPayment($transaction, $booking, $request->all());

            return view('flights.success', [
                'booking' => $booking,
                'message' => $processed
                    ? 'Thanh toán thành công! Mã đặt vé của bạn là: ' . $bookingCode
                    : 'Đơn hàng đã được xác nhận trước đó. Mã đặt vé của bạn là: ' . $bookingCode
            ]);
        } else {
            $this->markFailedPayment($transaction, $booking, $request->all());

            return view('flights.success', [
                'booking' => $booking,
                'message' => 'Thanh toán thất bại hoặc đã bị hủy. Vui lòng thử lại!'
            ]);
        }
    }

    public function vnpayIpn(Request $request)
    {
        // Handler IPN (Instant Payment Notification) – VNPay gửi POST tới endpoint này để notify.
        // Xử lý tương tự vnpayReturn nhưng trả JSON cho VNPay (RspCode/Message).
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $transactionCode = $request->input('vnp_TxnRef');
        $vnp_Amount = $request->input('vnp_Amount');

        $transaction = AppBookingTransaction::where('transaction_code', $transactionCode)->first();
        $booking = $transaction ? AppBooking::find($transaction->booking_id) : null;

        // 1. Kiểm tra chữ ký và đối soát số tiền
        $isSignatureValid = $this->validateSignature($request->all());
        $expectedAmount = $booking ? $booking->total_amount : 0;
        $paidAmount = $vnp_Amount / 100;
        $isAmountMatch = ($paidAmount == $expectedAmount);

        if ($transaction && $isSignatureValid && $isAmountMatch) {
            if ($vnp_ResponseCode == '00') {
                $processed = $this->confirmSuccessfulPayment($transaction, $booking, $request->all());

                return response()->json([
                    'RspCode' => $processed ? '00' : '02',
                    'Message' => $processed ? 'Confirm Success' : 'Order already confirmed'
                ]);
            }

            $processed = $this->markFailedPayment($transaction, $booking, $request->all());
            return response()->json([
                'RspCode' => $processed ? '00' : '02',
                'Message' => $processed ? 'Confirm Success' : 'Order already confirmed'
            ]);
        }

        $failMsg = !$isSignatureValid ? 'Invalid signature' : (!$isAmountMatch ? 'Amount mismatch' : 'Order not found');
        return response()->json(['RspCode' => '01', 'Message' => $failMsg]);
    }

    private function validateSignature($inputData)
    {
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        // Dùng hash_equals để tránh timing attack khi so sánh HMAC
        return hash_equals($secureHash, $vnp_SecureHash);
    }

    private function confirmSuccessfulPayment(AppBookingTransaction $transaction, AppBooking $booking, array $paymentResponse): bool
    {
        // Xác nhận thanh toán thành công trong DB: lock hàng, cập nhật transaction và booking,
        // và dispatch email confirmation job. Trả về true nếu cập nhật thành công.
        return DB::transaction(function () use ($transaction, $booking, $paymentResponse) {
            $lockedTransaction = AppBookingTransaction::whereKey($transaction->id)->lockForUpdate()->first();
            $lockedBooking = AppBooking::whereKey($booking->id)->lockForUpdate()->first();

            if (!$lockedTransaction || !$lockedBooking || $lockedTransaction->status !== 'pending') {
                return false;
            }

            $lockedTransaction->update([
                'status' => 'success',
                'payment_response' => $paymentResponse,
            ]);

            $lockedBooking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);

            SendBookingConfirmationEmail::dispatch($lockedBooking);

            return true;
        });
    }

    private function markFailedPayment(AppBookingTransaction $transaction, AppBooking $booking, array $paymentResponse): bool
    {
        // Ghi trạng thái thất bại, release seats nếu cần và cập nhật booking cancelled.
        return DB::transaction(function () use ($transaction, $booking, $paymentResponse) {
            $lockedTransaction = AppBookingTransaction::whereKey($transaction->id)->lockForUpdate()->first();
            $lockedBooking = AppBooking::whereKey($booking->id)->lockForUpdate()->first();

            if (!$lockedTransaction || !$lockedBooking || $lockedTransaction->status !== 'pending') {
                return false;
            }

            $lockedTransaction->update([
                'status' => 'failed',
                'payment_response' => $paymentResponse,
            ]);

            $this->releaseReservedSeats($lockedBooking);

            $lockedBooking->update([
                'status' => 'cancelled',
                'seats_reserved' => false,
            ]);

            return true;
        });
    }

    private function releaseReservedSeats(AppBooking $booking): void
    {
        if (!(bool) ($booking->seats_reserved ?? false)) {
            return;
        }

        $seatCount = $this->getReservedSeatCount($booking);
        $ticketClass = $booking->ticket_class ?? 'economy';

        if ($seatCount < 1) {
            return;
        }

        Flight::releaseSeats((int) $booking->outbound_flight_id, $seatCount, $ticketClass);

        if ($booking->return_flight_id) {
            Flight::releaseSeats((int) $booking->return_flight_id, $seatCount, $ticketClass);
        }
    }

    private function getReservedSeatCount(AppBooking $booking): int
    {
        return max(0, (int) $booking->adult_count + (int) $booking->child_count);
    }
}
