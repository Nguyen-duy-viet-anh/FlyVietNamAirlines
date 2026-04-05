<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppBooking;

class BookingController extends Controller
{
    // 1. Danh sách tất cả đơn đặt vé
    public function index()
    {
        // Lấy tất cả vé, sắp xếp mới nhất lên đầu, phân trang 15 đơn/trang
        $bookings = AppBooking::with(['outboundFlight.origin', 'outboundFlight.destination'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
                    
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

        return view('admin.bookings.show', compact('booking'));
    }

    // 3. Cập nhật trạng thái đơn (Phòng trường hợp khách gọi điện xin hủy vé)
    public function updateStatus(Request $request, $id)
    {
        $booking = AppBooking::findOrFail($id);
        $booking->update(['status' => $request->status]);
        
        return back()->with('success', 'Đã cập nhật trạng thái đơn vé!');
    }
}