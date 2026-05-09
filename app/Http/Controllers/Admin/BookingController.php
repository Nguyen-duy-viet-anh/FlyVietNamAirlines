<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppBooking;

class BookingController extends Controller
{
    // 1. Danh sách tất cả đơn đặt vé
    public function index(Request $request)
    {
        $query = AppBooking::with(['outboundFlight.origin', 'outboundFlight.destination']);

        // Bộ lọc theo trạng thái thanh toán
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Bộ lọc theo ngày (dựa vào ngày đặt vé)
        if ($request->filled('date_filter')) {
            if ($request->date_filter == 'today') {
                $query->whereDate('created_at', now()->toDateString());
            } elseif ($request->date_filter == 'yesterday') {
                $query->whereDate('created_at', now()->subDay()->toDateString());
            } elseif ($request->date_filter == 'this_week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($request->date_filter == 'this_month') {
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            }
        }

        // Bộ lọc tìm kiếm từ khóa (sđt, email, tên, mã vé)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('passenger_name', 'like', "%{$search}%")
                  ->orWhere('passenger_email', 'like', "%{$search}%")
                  ->orWhere('passenger_phone', 'like', "%{$search}%");
            });
        }

        // Lấy lịch sử vé, sắp xếp mới nhất lên đầu
        $bookings = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->all());
                    
        return view('admin.bookings.index', compact('bookings'));
    }

    // 2. Xem chi tiết 1 đơn đặt vé
    public function show($id)
    {
        $booking = AppBooking::with([
            'outboundFlight.airline', 'outboundFlight.origin', 'outboundFlight.destination',
            'returnFlight.airline', 'returnFlight.origin', 'returnFlight.destination',
            'transaction'
        ])->findOrFail($id);

        // Tính lại bảng bóc tách giá chính xác từ helper
        // Booking cũ chưa có outbound_class/return_class → dùng ticket_class cho cả 2 chiều
        $outClass = $booking->outbound_class ?? $booking->ticket_class ?? 'economy';
        $retClass = $booking->return_class ?? $booking->ticket_class ?? 'economy';
        
        $priceBreakdown = \App\Helpers\FlightPriceHelper::calculate(
            $booking->outboundFlight,
            $booking->returnFlight,
            $booking->adult_count,
            $booking->child_count ?? 0,
            $booking->infant_count ?? 0,
            $outClass,
            $retClass
        );

        return view('admin.bookings.show', compact('booking', 'priceBreakdown'));
    }

    // 3. Cập nhật trạng thái đơn (Phòng trường hợp khách gọi điện xin hủy vé)
    public function updateStatus(Request $request, $id)
    {
        $booking = AppBooking::findOrFail($id);
        $booking->update(['status' => $request->status]);
        
        return back()->with('success', 'Đã cập nhật trạng thái đơn vé!');
    }
}