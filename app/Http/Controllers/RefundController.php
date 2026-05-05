<?php

namespace App\Http\Controllers;

use App\Models\AppBooking;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RefundRequestMail;

class RefundController extends Controller
{
    /**
     * Show refund request form for a booking.
     */
    public function create(AppBooking $booking)
    {
        // Nếu booking liên kết user và không phải chủ booking thì chặn
        if ($booking->user_id && Auth::id() && $booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('refunds.create', compact('booking'));
    }

    /**
     * Store refund request and send email to passenger.
     */
    public function store(Request $request, AppBooking $booking)
    {
        if ($booking->user_id && Auth::id() && $booking->user_id !== Auth::id()) {
            abort(403);
        }

        $existingRefund = Refund::where('booking_id', $booking->id)->first();
        if ($existingRefund) {
            return back()->withInput()->with('error_refund', 'Đơn này đã có yêu cầu hoàn vé. Vui lòng chờ xử lý.');
        }

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reason_type' => ['nullable', 'string', 'in:cancelled_flight,schedule_change,wrong_booking,customer_change,other'],
            'reason_custom' => ['nullable', 'string', 'max:2000'],
            'method' => ['nullable', 'string', 'max:50'],
        ]);

        // Xác định lý do thực tế: ưu tiên nội dung do user nhập (reason_custom),
        // nếu không có thì map reason_type sang label người dùng dễ hiểu.
        $reason = null;
        if (!empty($data['reason_custom'])) {
            $reason = trim($data['reason_custom']);
        } elseif (!empty($data['reason_type'])) {
            $map = [
                'cancelled_flight' => 'Hủy chuyến',
                'schedule_change' => 'Thay đổi lịch',
                'wrong_booking' => 'Trùng đặt / sai thông tin',
                'customer_change' => 'Khách thay đổi kế hoạch',
                'other' => null,
            ];
            $reason = $map[$data['reason_type']] ?? null;
        }

        // Lấy original transaction nếu có
        $originalTx = $booking->transaction ?? null;

        $refund = Refund::create([
            'booking_id' => $booking->id,
            'original_transaction_id' => $originalTx->id ?? null,
            'amount' => $data['amount'],
            'currency' => $booking->currency ?? 'VND',
            'method' => $data['method'] ?? null,
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
            // Không dừng luồng, chỉ ghi log nếu cần
            // logger()->error('Refund mail send failed: '.$e->getMessage());
            return back()->withInput()->with('error_refund', 'Có lỗi xảy ra, vui lòng thử lại sau. ' . $e->getMessage());
        }
    }
}
