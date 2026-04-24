<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppBooking;
use App\Models\AppBookingTransaction;
use App\Models\Flight;
// use App\Jobs\SendBookingConfirmationEmail; // Import Job gửi mail ở Step 7

class PaymentController extends Controller
{
    public function vnpayReturn(Request $request)
    {
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

        $isAmountMatch = ($paidAmount == $expectedAmount);

        if (!$transaction || !$isSignatureValid || !$isAmountMatch) {
            $errorMsg = !$isSignatureValid ? 'Chữ ký không hợp lệ!' : (!$isAmountMatch ? 'Số tiền không khớp!' : 'Không tìm thấy giao dịch!');
            return redirect('/')->withErrors(['error' => 'Xác thực thanh toán thất bại: ' . $errorMsg]);
        }

        $bookingCode = $booking->booking_code ?? 'LỖI_MẤT_MÃ_VÉ';

        if ($vnp_ResponseCode == '00') {
            // ... (Phần xử trạng thái giữ nguyên)
            if ($transaction->status == 'pending') {
                $transaction->update(['status' => 'success']);
                $booking->update(['status' => 'confirmed', 'payment_status' => 'paid']);

                $totalPassengers = $booking->adult_count + $booking->child_count;
                $columnToDecrement = $booking->ticket_class == 'business' ? 'business_available' : 'economy_available';

                $outboundFlight = Flight::find($booking->outbound_flight_id);
                if ($outboundFlight) { $outboundFlight->decrement($columnToDecrement, $totalPassengers); }

                if ($booking->return_flight_id) {
                    $returnFlight = Flight::find($booking->return_flight_id);
                    if ($returnFlight) { $returnFlight->decrement($columnToDecrement, $totalPassengers); }
                }
            }

            return view('flights.success', [
                'booking' => $booking,
                'message' => 'Thanh toán thành công! Mã đặt vé của bạn là: ' . $bookingCode
            ]);
        } else {
            return view('flights.success', [
                'booking' => $booking,
                'message' => 'Thanh toán thất bại hoặc đã bị hủy. Vui lòng thử lại!'
            ]);
        }
    }

    public function vnpayIpn(Request $request)
    {
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

        $isAmountMatch = ($paidAmount == $expectedAmount);

        if ($transaction && $isSignatureValid && $isAmountMatch) {
            if ($transaction->status == 'pending') {
                if ($vnp_ResponseCode == '00') {
                    $transaction->update(['status' => 'success', 'payment_response' => $request->all()]);
                    $booking->update(['status' => 'confirmed', 'payment_status' => 'paid']);

                    $totalPassengers = $booking->adult_count + $booking->child_count;
                    $outboundFlight = Flight::find($booking->outbound_flight_id);
                    if ($outboundFlight) { $outboundFlight->decrement('available_seats', $totalPassengers); }

                    if ($booking->return_flight_id) {
                        $returnFlight = Flight::find($booking->return_flight_id);
                        if ($returnFlight) { $returnFlight->decrement('available_seats', $totalPassengers); }
                    }

                    return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
                } else {
                    $transaction->update(['status' => 'failed', 'payment_response' => $request->all()]);
                    $booking->update(['status' => 'cancelled']);
                    return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
                }
            }
            return response()->json(['RspCode' => '02', 'Message' => 'Order already confirmed']);
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
        return $secureHash === $vnp_SecureHash;
        }
}
