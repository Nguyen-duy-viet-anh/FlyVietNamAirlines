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

        if (!$transactionCode) {
            return redirect('/')->withErrors(['error' => 'Đường dẫn không hợp lệ!']);
        }

        $transaction = AppBookingTransaction::where('transaction_code', $transactionCode)->first();
        if (!$transaction) {
            return redirect('/')->withErrors(['error' => 'Không tìm thấy giao dịch!']);
        }

        $booking = AppBooking::find($transaction->booking_id);
        $bookingCode = $booking->booking_code ?? 'LỖI_MẤT_MÃ_VÉ';

        if ($vnp_ResponseCode == '00') {

            // ===== BẮT ĐẦU: XỬ LÝ CHỐT ĐƠN VÀ TRỪ GHẾ =====
            if ($transaction->status == 'pending') {
                // 1. Cập nhật trạng thái
                $transaction->update(['status' => 'success']);
                $booking->update(['status' => 'confirmed', 'payment_status' => 'paid']);

                // 2. TRỪ GHẾ ĐÚNG HẠNG
                $totalPassengers = $booking->adult_count + $booking->child_count;
                // Nếu khách đặt business thì trừ cột business, ngược lại trừ economy
                $columnToDecrement = $booking->ticket_class == 'business' ? 'business_available' : 'economy_available';

                // Trừ chuyến đi
                $outboundFlight = Flight::find($booking->outbound_flight_id);
                if ($outboundFlight) {
                    $outboundFlight->decrement($columnToDecrement, $totalPassengers);
                }

                // Trừ chuyến về (nếu có)
                if ($booking->return_flight_id) {
                    $returnFlight = Flight::find($booking->return_flight_id);
                    if ($returnFlight) {
                        $returnFlight->decrement($columnToDecrement, $totalPassengers);
                    }
                }
            }
            // ===== KẾT THÚC XỬ LÝ =====

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

    // 2. IPN Webhook (VNPay gọi ngầm để báo kết quả chính xác)
    public function vnpayIpn(Request $request)
    {
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $transactionCode = $request->input('vnp_TxnRef');

        $transaction = AppBookingTransaction::where('transaction_code', $transactionCode)->first();

        if ($transaction) {
            $booking = AppBooking::find($transaction->booking_id);

            // Chỉ xử lý nếu giao dịch đang ở trạng thái pending (chưa xử lý)
            if ($transaction->status == 'pending') {
                if ($vnp_ResponseCode == '00') {
                    // Cập nhật trạng thái thành công
                    $transaction->update(['status' => 'success', 'payment_response' => $request->all()]);
                    $booking->update(['status' => 'confirmed', 'payment_status' => 'paid']);

                    // Trừ số ghế của chuyến bay (Người lớn + Trẻ em)
                    $totalPassengers = $booking->adult_count + $booking->child_count;

                    // Trừ ghế chuyến đi
                    $outboundFlight = Flight::find($booking->outbound_flight_id);
                    if ($outboundFlight) {
                        $outboundFlight->decrement('available_seats', $totalPassengers);
                    }

                    // Trừ ghế chuyến về (nếu có)
                    if ($booking->return_flight_id) {
                        $returnFlight = Flight::find($booking->return_flight_id);
                        if ($returnFlight) {
                            $returnFlight->decrement('available_seats', $totalPassengers);
                        }
                    }

                    // Gọi Job gửi Email chạy ngầm
                    // SendBookingConfirmationEmail::dispatch($booking);

                    return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
                } else {
                    // Nếu thất bại
                    $transaction->update(['status' => 'failed', 'payment_response' => $request->all()]);
                    $booking->update(['status' => 'cancelled']);

                    return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
                }
            }
        }

        return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
    }
}
