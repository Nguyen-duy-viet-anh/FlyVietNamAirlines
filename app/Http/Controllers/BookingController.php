<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\AppBooking;
use App\Models\AppBookingTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function showBookForm(Request $request)
    {
        $outboundFlight = Flight::find($request->outbound_flight_id);
        $returnFlight = $request->return_flight_id ? Flight::find($request->return_flight_id) : null;

        $adultCount = (int) $request->adult_count ?? 1;
        $childCount = (int) $request->child_count ?? 0;
        $infantCount = (int) $request->infant_count ?? 0;
        $ticketClass = $request->ticket_class ?? 'economy';

        // 1. USE UNIFIED HELPER
        $priceBreakdown = \App\Helpers\FlightPriceHelper::calculate(
            $outboundFlight,
            $returnFlight,
            $adultCount,
            $childCount,
            $infantCount,
            $ticketClass
        );

        $totalAmount = $priceBreakdown['grand_total'];

        $bookingData = $request->all();
        $bookingData['total_amount'] = $totalAmount;

        return view('flights.passenger.book', compact('outboundFlight', 'returnFlight', 'bookingData', 'priceBreakdown'));
    }

    // 3. Xử lý Lưu Database & Tạo URL VNPay (Sau khi khách bấm xác nhận ở trang Review)
    public function submitBooking(Request $request)
    {
        try {
            DB::beginTransaction();

            // Tạo mã tạm thời
            $bookingCode = 'TEMP-' . Str::random(5);

            // 1. Process primary passenger from the new nested structure
            $primaryAdult = $request->input('passengers.adult.1');
            $passengerName = '';
            $passengerGender = 'other';

            if ($primaryAdult) {
                $passengerName = strtoupper($primaryAdult['title'] . ' ' . $primaryAdult['first_name'] . ' ' . $primaryAdult['last_name']);
                $title = $primaryAdult['title'];
                if ($title == 'Mr')
                    $passengerGender = 'male';
                elseif (in_array($title, ['Ms', 'Mdm', 'Miss']))
                    $passengerGender = 'female';
            } else {
                // Fallback for old form or missing data
                $passengerName = $request->passenger_name ?? 'UNKNOWN';
                $passengerGender = $request->passenger_gender ?? 'other';
            }

            $booking = AppBooking::create([
                'booking_code' => $bookingCode,
                'user_id' => Auth::check() ? Auth::id() : null,
                'flight_type' => $request->flight_type,
                'ticket_class' => $request->ticket_class,
                'outbound_flight_id' => $request->outbound_flight_id,
                'return_flight_id' => $request->return_flight_id,
                'adult_count' => $request->adult_count,
                'child_count' => $request->child_count,
                'infant_count' => $request->infant_count,
                'total_amount' => $request->total_amount,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'passenger_name' => $passengerName,
                'passenger_email' => $request->passenger_email,
                'passenger_phone' => ($request->passenger_country_code ?? '') . ' ' . $request->passenger_phone,
                'passenger_gender' => $passengerGender,
                'passenger_details' => $request->input('passengers'),
                'notes' => $request->notes,
            ]);

            // Sau khi có ID, tạo mã PNR chính thức: [ĐI][ĐẾN]-[ID+1000]
            $outbound = Flight::with(['origin', 'destination'])->find($request->outbound_flight_id);
            $routePrefix = $outbound ? ($outbound->origin->code . $outbound->destination->code) : 'BKG';
            $finalBookingCode = strtoupper($routePrefix) . '-' . (1000 + $booking->id);

            // Cập nhật lại booking_code chính thức
            $booking->update(['booking_code' => $finalBookingCode]);

            $transactionCode = time() . rand(100, 999);
            AppBookingTransaction::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'payment_method' => 'VNPay',
                'transaction_code' => $transactionCode,
                'status' => 'pending',
            ]);

            DB::commit();

            return $this->createVNPayUrl($booking, $transactionCode);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/')->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    // 3. Hàm nội bộ: Sinh URL VNPay
    private function createVNPayUrl($booking, $transactionCode)
    {
        $vnp_TmnCode = env('VNP_TMN_CODE');
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $vnp_Url = env('VNP_URL');
        $vnp_Returnurl = route('vnpay.return');

        $vnp_TxnRef = $transactionCode;
        $vnp_OrderInfo = "Thanh toan ve may bay " . $booking->booking_code;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = (int) ($booking->total_amount * 100); // VNPay yêu cầu nhân 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect()->away($vnp_Url);
    }

    // 4. Trang hiển thị kết quả thành công (Gọi từ Step 6)
    public function success($booking_code)
    {
        $booking = AppBooking::where('booking_code', $booking_code)->firstOrFail();
        return view('flights.success', compact('booking'));
    }
    public function reviewBooking(Request $request)
    {
        // 1. Dữ liệu khách hàng
        $passengerData = $request->all();
        $bookingData = $request->all(); // Tạo thêm biến này để khớp với tên gọi ở View của bạn

        // 2. Lấy thông tin chuyến bay
        $outboundFlight = \App\Models\Flight::with(['airline', 'origin', 'destination'])
            ->find($request->outbound_flight_id);

        $returnFlight = $request->return_flight_id
            ? \App\Models\Flight::with(['airline', 'origin', 'destination'])->find($request->return_flight_id)
            : null;

        // 3. TÍNH LẠI BẢNG BÓC TÁCH GIÁ ĐỂ HIỂN THỊ Ở TRANG REVIEW (Bảo mật hơn)
        $adultCount = (int) ($request->adult_count ?? 1);
        $childCount = (int) ($request->child_count ?? 0);
        $infantCount = (int) ($request->infant_count ?? 0);
        $ticketClass = $request->ticket_class ?? 'economy';

        $priceBreakdown = \App\Helpers\FlightPriceHelper::calculate(
            $outboundFlight,
            $returnFlight,
            $adultCount,
            $childCount,
            $infantCount,
            $ticketClass
        );

        // 0. Process name for display in Review page
        $primaryAdult = $request->input('passengers.adult.1');
        if ($primaryAdult) {
            $passengerData['passenger_name'] = strtoupper($primaryAdult['title'] . ' ' . $primaryAdult['first_name'] . ' ' . $primaryAdult['last_name']);
        }

        // 4. Đẩy tất cả sang View Review
        return view('flights.review.index', compact('passengerData', 'bookingData', 'outboundFlight', 'returnFlight', 'priceBreakdown'));
    }
}
