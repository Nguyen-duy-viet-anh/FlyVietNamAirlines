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

        $adultCount = $request->adult_count ?? 1;
        $childCount = $request->child_count ?? 0;
        $infantCount = $request->infant_count ?? 0;
        
        // 1. CẤU HÌNH CÁC LOẠI PHÍ CỐ ĐỊNH (Tính theo 1 chiều bay)
        $taxPerPerson = 120000;      // Thuế & Phí an ninh sân bay (Taxes)
        $serviceFeePerPerson = 50000; // Phí xuất vé của Đại lý (Service Charges)
        $infantFixedFee = 150000;     // Phí em bé (Miễn giá vé, chỉ thu phí cố định)

        $multiplier = ($request->ticket_class == 'business') ? 1.5 : 1;
        $flightsCount = $returnFlight ? 2 : 1; // Khứ hồi thì nhân đôi Thuế phí lên

        // 2. TÍNH BASE FARE (Giá vé cơ bản)
        $outboundBase = $outboundFlight->price * $multiplier;
        $returnBase = $returnFlight ? ($returnFlight->price * $multiplier) : 0;
        
        $totalBasePerAdult = $outboundBase + $returnBase;
        // Trẻ em (2-12 tuổi) thường được hãng bay giảm 20% Base Fare
        $totalBasePerChild = $totalBasePerAdult * 0.8; 

        // 3. TỔNG KẾT TỪNG HẠNG MỤC
        $totalBaseFare = ($totalBasePerAdult * $adultCount) + ($totalBasePerChild * $childCount);
        $totalTaxes = $taxPerPerson * ($adultCount + $childCount) * $flightsCount;
        $totalService = $serviceFeePerPerson * ($adultCount + $childCount) * $flightsCount;
        $totalInfantFee = $infantFixedFee * $infantCount * $flightsCount;

        // TỔNG TIỀN CUỐI CÙNG
        $totalAmount = $totalBaseFare + $totalTaxes + $totalService + $totalInfantFee;

        // 4. Đóng gói mảng dữ liệu Hóa đơn để đẩy ra Giao diện
        $priceBreakdown = [
            'base_adult_single' => $totalBasePerAdult,
            'base_child_single' => $totalBasePerChild,
            'total_base_fare' => $totalBaseFare,
            'total_taxes' => $totalTaxes,
            'total_service' => $totalService,
            'total_infant' => $totalInfantFee,
        ];

        $bookingData = $request->all();
        $bookingData['total_amount'] = $totalAmount; // Đè lại tổng tiền mới nhất

        return view('flights.book', compact('outboundFlight', 'returnFlight', 'bookingData', 'priceBreakdown'));
    }

    // 3. Xử lý Lưu Database & Tạo URL VNPay (Sau khi khách bấm xác nhận ở trang Review)
    public function submitBooking(Request $request)
    {
        try {
            DB::beginTransaction();

            $bookingCode = 'BKG-' . strtoupper(Str::random(8));

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
                'passenger_name' => $request->passenger_name,
                'passenger_email' => $request->passenger_email,
                'passenger_phone' => $request->passenger_phone,
                'passenger_gender' => $request->passenger_gender,
                'notes' => $request->notes,
            ]);

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
        $vnp_Amount = $booking->total_amount * 100; // VNPay yêu cầu nhân 100
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
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Redirect sang cổng VNPay
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
        $adultCount = $request->adult_count ?? 1;
        $childCount = $request->child_count ?? 0;
        $infantCount = $request->infant_count ?? 0;
        
        $taxPerPerson = 120000;
        $serviceFeePerPerson = 50000;
        $infantFixedFee = 150000;

        $multiplier = ($request->ticket_class == 'business') ? 1.5 : 1;
        $flightsCount = $returnFlight ? 2 : 1;

        $outboundBase = $outboundFlight->price * $multiplier;
        $returnBase = $returnFlight ? ($returnFlight->price * $multiplier) : 0;
        
        $totalBasePerAdult = $outboundBase + $returnBase;
        $totalBasePerChild = $totalBasePerAdult * 0.8; 

        $totalBaseFare = ($totalBasePerAdult * $adultCount) + ($totalBasePerChild * $childCount);
        $totalTaxes = $taxPerPerson * ($adultCount + $childCount) * $flightsCount;
        $totalService = $serviceFeePerPerson * ($adultCount + $childCount) * $flightsCount;
        $totalInfantFee = $infantFixedFee * $infantCount * $flightsCount;

        $priceBreakdown = [
            'base_adult_single' => $totalBasePerAdult,
            'base_child_single' => $totalBasePerChild,
            'total_base_fare' => $totalBaseFare,
            'total_taxes' => $totalTaxes,
            'total_service' => $totalService,
            'total_infant' => $totalInfantFee,
        ];

        // 4. Đẩy tất cả sang View Review
        return view('flights.review', compact('passengerData', 'bookingData', 'outboundFlight', 'returnFlight', 'priceBreakdown'));
    }
}
