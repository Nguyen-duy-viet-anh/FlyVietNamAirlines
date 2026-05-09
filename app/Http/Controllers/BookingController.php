<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\AppBooking;
use App\Models\AppBookingTransaction;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RefundRequestMail;

class BookingController extends Controller
{
    /**
     * BookingController
     * Quản lý luồng đặt vé: hiển thị form đặt vé, review, lưu booking,
     * tạo URL VNPay, xử lý tra cứu và lookup.
     * Các thao tác quan trọng: reserve seats (atomic), idempotency key,
     * và transaction DB để đảm bảo consistency khi tạo booking + transaction.
     */
    public function showBookForm(Request $request)
    {
        // Hiển thị form đặt vé. Tính toán bảng giá (priceBreakdown)
        // dựa trên helper `FlightPriceHelper::calculate` rồi truyền sang view.
        $outboundFlight = Flight::find($request->outbound_flight_id);
        $returnFlight = $request->return_flight_id ? Flight::find($request->return_flight_id) : null;

        $adultCount = (int) $request->adult_count ?? 1;
        $childCount = (int) $request->child_count ?? 0;
        $infantCount = (int) $request->infant_count ?? 0;
        $outboundClass = $request->outbound_class ?? $request->ticket_class ?? 'economy';
        $returnClass = $request->return_class ?? $request->ticket_class ?? 'economy';

        // 1. USE UNIFIED HELPER
        $priceBreakdown = \App\Helpers\FlightPriceHelper::calculate(
            $outboundFlight,
            $returnFlight,
            $adultCount,
            $childCount,
            $infantCount,
            $outboundClass,
            $returnClass
        );

        $totalAmount = $priceBreakdown['grand_total'];

        $bookingData = $request->all();
        $bookingData['total_amount'] = $totalAmount;

        return view('flights.passenger.book', compact('outboundFlight', 'returnFlight', 'bookingData', 'priceBreakdown'));
    }

    // 3. Xử lý Lưu Database & Tạo URL VNPay (Sau khi khách bấm xác nhận ở trang Review)
    public function submitBooking(Request $request)
    {
        // Xử lý lưu booking và tạo URL VNPay.
        // - Hỗ trợ Idempotency-Key: nếu client gửi key, tránh tạo booking trùng.
        // - Dùng DB::transaction() để đảm bảo reserve seats, insert booking và transaction là atomic.
        // - Nếu seat reservation thất bại sẽ ném exception 'SOLD_OUT'.
        // Basic validation to ensure required parameters are present and well-formed
        $request->validate([
            'outbound_flight_id' => 'required|integer|exists:flights,id',
            'adult_count' => 'required|integer|min:1',
            'child_count' => 'nullable|integer|min:0',
            'infant_count' => 'nullable|integer|min:0',
            'ticket_class' => 'nullable|in:economy,business',
            'passenger_email' => 'nullable|email',
            'passengers' => 'nullable|array',
        ]);
        $idempotencyKey = $request->header('Idempotency-Key') ?? $request->input('idempotency_key');

        if ($idempotencyKey) {
            $existingBooking = AppBooking::with('transaction')
                ->where('idempotency_key', $idempotencyKey)
                ->first();

            if ($existingBooking && $existingBooking->transaction) {
                if ($existingBooking->transaction->status === 'success') {
                    return redirect()->route('booking.success', ['booking_code' => $existingBooking->booking_code]);
                }

                return $this->createVNPayUrl($existingBooking, $existingBooking->transaction->transaction_code);
            }
        }

        try {
            $bookingPayload = DB::transaction(function () use ($request, $idempotencyKey) {
                $ticketClass = $request->ticket_class ?? 'economy';
                $seatCount = $this->getRequestedSeatCount($request);

                if ($seatCount < 1) {
                    throw new \RuntimeException('NO_PASSENGER');
                }

                if (!Flight::reserveSeats((int) $request->outbound_flight_id, $seatCount, $ticketClass)) {
                    throw new \RuntimeException('SOLD_OUT');
                }

                if ($request->return_flight_id && !Flight::reserveSeats((int) $request->return_flight_id, $seatCount, $ticketClass)) {
                    throw new \RuntimeException('SOLD_OUT');
                }

                // Tạo mã tạm thời
                $bookingCode = 'TEMP-' . Str::random(5);

                // 1. Process primary passenger from the new nested structure
                $primaryAdult = $request->input('passengers.adult.1');
                $passengerName = '';
                $passengerGender = 'other';

                if ($primaryAdult) {
                    $passengerName = strtoupper($primaryAdult['title'] . ' ' . $primaryAdult['first_name'] . ' ' . $primaryAdult['last_name']);
                    $title = $primaryAdult['title'];
                    if ($title == 'Mr') {
                        $passengerGender = 'male';
                    } elseif (in_array($title, ['Ms', 'Mdm', 'Miss'])) {
                        $passengerGender = 'female';
                    }
                } else {
                    // Fallback for old form or missing data
                    $passengerName = $request->passenger_name ?? 'UNKNOWN';
                    $passengerGender = $request->passenger_gender ?? 'other';
                }

                $booking = AppBooking::create([
                    'booking_code' => $bookingCode,
                    'user_id' => Auth::check() ? Auth::id() : null,
                    'flight_type' => $request->flight_type,
                    'ticket_class' => $ticketClass,
                    'outbound_class' => $request->outbound_class ?? $ticketClass,
                    'return_class' => $request->return_class ?? $ticketClass,
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
                    'idempotency_key' => $idempotencyKey,
                    'seats_reserved' => true,
                ]);

                // Sau khi có ID, tạo mã PNR chính thức: [ĐI][ĐẾN]-[ID+1000]
                $outbound = Flight::with(['origin', 'destination'])->find($request->outbound_flight_id);
                $routePrefix = $outbound ? ($outbound->origin->code . $outbound->destination->code) : 'BKG';
                $finalBookingCode = strtoupper($routePrefix) . '-' . (1000 + $booking->id);

                // Cập nhật lại booking_code chính thức
                $booking->update(['booking_code' => $finalBookingCode]);

                $transactionCode = time() . random_int(100, 999);
                AppBookingTransaction::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_amount,
                    'payment_method' => 'VNPay',
                    'transaction_code' => $transactionCode,
                    'status' => 'pending',
                ]);

                return [
                    'booking' => $booking->fresh(),
                    'transaction_code' => $transactionCode,
                ];
            }, 3);

            return $this->createVNPayUrl($bookingPayload['booking'], $bookingPayload['transaction_code']);
        } catch (QueryException $e) {
            if ($idempotencyKey && $this->isUniqueConstraintViolation($e)) {
                $existingBooking = AppBooking::with('transaction')
                    ->where('idempotency_key', $idempotencyKey)
                    ->first();

                if ($existingBooking && $existingBooking->transaction) {
                    return $this->createVNPayUrl($existingBooking, $existingBooking->transaction->transaction_code);
                }
            }

            return redirect('/')->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'SOLD_OUT') {
                return redirect()->back()->withErrors(['error' => 'Chuyến bay đã hết ghế, vui lòng chọn chuyến khác.'])->withInput();
            }

            if ($e->getMessage() === 'NO_PASSENGER') {
                return redirect()->back()->withErrors(['error' => 'Dữ liệu hành khách không hợp lệ.'])->withInput();
            }

            return redirect('/')->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            return redirect('/')->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    private function getRequestedSeatCount(Request $request): int
    {
        // Tính tổng số ghế (Người lớn + Trẻ em). Sơ sinh không tính ghế.
        $adultCount = (int) ($request->adult_count ?? 0);
        $childCount = (int) ($request->child_count ?? 0);

        return max(0, $adultCount + $childCount);
    }

    private function isUniqueConstraintViolation(QueryException $e): bool
    {
        // Kiểm tra mã lỗi SQL để phát hiện unique constraint violation
        return in_array((string) $e->getCode(), ['23000', '23505'], true);
    }

    // 3. Hàm nội bộ: Sinh URL VNPay
    private function createVNPayUrl($booking, $transactionCode)
    {
        // Sinh URL VNPay (thuat toan: gom inputData, sap xep, tao hash_hmac sha512)
        // Trả về redirect()->away($vnp_Url) để chuyển người dùng sang cổng thanh toán.
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
        // Trang hiển thị thanh toán thành công/chi tiết booking
        $booking = AppBooking::where('booking_code', $booking_code)->firstOrFail();
        return view('flights.success', compact('booking'));
    }

    // 5. Trang tài khoản: thông tin người dùng + lịch sử đặt vé
    public function myBookings()
    {
        if (!Auth::check()) {
            return view('search.mybooking');
        }

        $user = Auth::user();

        $bookings = AppBooking::with([
            'outboundFlight.airline',
            'outboundFlight.origin',
            'outboundFlight.destination',
            'returnFlight.airline',
            'returnFlight.origin',
            'returnFlight.destination',
            'transaction',
        ])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('bookings.my-bookings', compact('user', 'bookings'));
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
        $outboundClass = $request->outbound_class ?? $request->ticket_class ?? 'economy';
        $returnClass = $request->return_class ?? $request->ticket_class ?? 'economy';

        $priceBreakdown = \App\Helpers\FlightPriceHelper::calculate(
            $outboundFlight,
            $returnFlight,
            $adultCount,
            $childCount,
            $infantCount,
            $outboundClass,
            $returnClass
        );

        // 0. Process name for display in Review page
        $primaryAdult = $request->input('passengers.adult.1');
        if ($primaryAdult) {
            $passengerData['passenger_name'] = strtoupper($primaryAdult['title'] . ' ' . $primaryAdult['first_name'] . ' ' . $primaryAdult['last_name']);
        }

        // 4. Đẩy tất cả sang View Review
        return view('flights.review.index', compact('passengerData', 'bookingData', 'outboundFlight', 'returnFlight', 'priceBreakdown'));
    }

    public function showLookupForm()
    {
        return view('search.mybooking');
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|string',
            'email' => 'required|email'
        ], [
            'booking_code.required' => 'Vui lòng nhập mã vé.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.'
        ]);

        $booking = AppBooking::with([
            'outboundFlight.airline', 'outboundFlight.origin', 'outboundFlight.destination',
            'returnFlight.airline', 'returnFlight.origin', 'returnFlight.destination',
            'transaction'
        ])
            ->where('booking_code', strtoupper($request->booking_code))
            ->where('passenger_email', strtolower($request->email))
            ->first();

        // Fallback for case-sensitivity on email if stored differently
        if (!$booking) {
            $booking = AppBooking::with([
                'outboundFlight.airline', 'outboundFlight.origin', 'outboundFlight.destination',
                'returnFlight.airline', 'returnFlight.origin', 'returnFlight.destination',
                'transaction'
            ])
                ->where('booking_code', strtoupper($request->booking_code))
                ->where('passenger_email', $request->email)
                ->first();
        }

        if (!$booking) {
            return back()->with('error', 'Không tìm thấy vé với mã PNR và email này. Vui lòng kiểm tra lại.');
        }

        // Nếu người dùng chọn gửi yêu cầu hoàn, xử lý ngay từ form tra cứu
        if ($request->input('action') === 'refund') {
            $existingRefund = Refund::where('booking_id', $booking->id)->first();
            if ($existingRefund) {
                return back()->withInput()->with('error_refund', 'Đơn này đã có yêu cầu hoàn vé. Vui lòng chờ xử lý.');
            }

            $request->validate([
                'reason_type' => ['nullable', 'string', 'in:cancelled_flight,schedule_change,wrong_booking,customer_change,other'],
                'reason_custom' => ['nullable', 'string', 'max:2000'],
                'refund_reason' => ['nullable', 'string', 'max:2000'],
            ]);

            $reason = null;
            $legacyReason = trim((string) $request->input('refund_reason'));
            $customReason = trim((string) $request->input('reason_custom'));
            $reasonType = $request->input('reason_type');

            if ($legacyReason !== '') {
                $reason = $legacyReason;
            } elseif ($customReason !== '') {
                $reason = $customReason;
            } elseif (!empty($reasonType)) {
                $map = [
                    'cancelled_flight' => 'Hủy chuyến',
                    'schedule_change' => 'Thay đổi lịch',
                    'wrong_booking' => 'Trùng đặt / sai thông tin',
                    'customer_change' => 'Khách thay đổi kế hoạch',
                    'other' => null,
                ];
                $reason = $map[$reasonType] ?? null;
            }

            if (!$reason) {
                return back()->withInput()->with('error_refund', 'Vui lòng nhập lý do hoàn vé.');
            }

            $originalTx = $booking->transaction ?? null;

            $refund = Refund::create([
                'booking_id' => $booking->id,
                'original_transaction_id' => $originalTx->id ?? null,
                'amount' => $booking->total_amount,
                'currency' => $booking->currency ?? 'VND',
                'reason' => $reason,
                'status' => 'requested',
                'requested_by' => Auth::check() ? Auth::id() : null,
            ]);

            try {
                $adminEmail = config('mail.from.address', 'admin@example.com');
                Mail::to($adminEmail)->send(new RefundRequestMail($refund));
                $refund->email_sent = true;
                $refund->save();

                return back()->with('success_refund', 'Yêu cầu hoàn tiền đã được gửi.');
            } catch (\Exception $e) {
                return back()->withInput()->with('error_refund', 'Có lỗi xảy ra, vui lòng thử lại sau. ' . $e->getMessage());
            }
        }

        // Tái tạo passengerData cho component review
        $passengerData = [
            'passengers' => $booking->passenger_details,
            'passenger_phone' => $booking->passenger_phone,
            'passenger_email' => $booking->passenger_email,
            'notes' => $booking->notes
        ];

        $priceBreakdown = \App\Helpers\FlightPriceHelper::calculate(
            $booking->outboundFlight,
            $booking->returnFlight,
            $booking->adult_count,
            $booking->child_count,
            $booking->infant_count,
            $booking->outbound_class ?? $booking->ticket_class,
            $booking->return_class ?? $booking->ticket_class
        );

        $outboundFlight = $booking->outboundFlight;
        $returnFlight = $booking->returnFlight;

        $bookingData = [
            'outbound_flight_id' => $booking->outbound_flight_id,
            'return_flight_id' => $booking->return_flight_id,
            'adult_count' => $booking->adult_count,
            'child_count' => $booking->child_count,
            'infant_count' => $booking->infant_count,
            'ticket_class' => $booking->ticket_class,
            'outbound_class' => $booking->outbound_class ?? $booking->ticket_class,
            'return_class' => $booking->return_class ?? $booking->ticket_class,
            'flight_type' => $booking->flight_type,
            'total_amount' => $booking->total_amount,
            'booking_code' => $booking->booking_code
        ];

        // Ghi đè vào Request để component _summary_panel hiểu được
        request()->merge($bookingData);

        $isLookup = true;

        return view('flights.review.index', compact('passengerData', 'bookingData', 'outboundFlight', 'returnFlight', 'priceBreakdown', 'isLookup', 'booking'));
    }
}
