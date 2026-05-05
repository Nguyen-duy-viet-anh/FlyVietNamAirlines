<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\AppBooking;

class FlightController extends Controller
{
    /**
     * Danh sách các chuyến bay
     */
    public function index(Request $request)
    {
        $query = Flight::with(['airline', 'origin', 'destination']);

        // Lọc theo mã chuyến bay
        if ($request->filled('flight_number')) {
            $query->where('flight_number', 'like', '%' . $request->flight_number . '%');
        }

        // Lọc theo hãng hàng không
        if ($request->filled('airline_id')) {
            $query->where('airline_id', $request->airline_id);
        }

        // Lọc theo ngày khởi hành (YYYY-MM-DD)
        if ($request->filled('departure_date')) {
            $query->whereDate('departure_time', $request->departure_date);
        }

        // Lọc theo ngày đến (YYYY-MM-DD)
        if ($request->filled('arrival_date')) {
            $query->whereDate('arrival_time', $request->arrival_date);
        }

        $flights = $query->orderBy('departure_time', 'desc')->paginate(15)->withQueryString();
        $airlines = \App\Models\Airline::all();

        return view('admin.flights.index', compact('flights', 'airlines'));
    }

    /**
     * Danh sách hành khách trên một chuyến bay cụ thể
     */
    public function passengers($id)
    {
        $flight = Flight::with(['airline', 'origin', 'destination'])->findOrFail($id);

        // Lấy tất cả các đơn đặt vé liên quan đến chuyến bay này (cả đi và về)
        $bookings = AppBooking::where('outbound_flight_id', $id)
            ->orWhere('return_flight_id', $id)
            ->where('status', 'confirmed') // Thường chỉ tính vé đã xác nhận
            ->get();

        $passengers = [];
        foreach ($bookings as $booking) {
            if ($booking->passenger_details) {
                foreach ($booking->passenger_details as $type => $paxList) {
                    foreach ($paxList as $pax) {
                        $passengers[] = [
                            'name' => ($pax['first_name'] ?? '') . ' ' . ($pax['last_name'] ?? ''),
                            'type' => $type,
                            'booking_code' => $booking->booking_code,
                            'booking_id' => $booking->id,
                            'phone' => $booking->passenger_phone,
                            'email' => $booking->passenger_email
                        ];
                    }
                }
            }
        }

        return view('admin.flights.passengers', compact('flight', 'passengers'));
    }
}
