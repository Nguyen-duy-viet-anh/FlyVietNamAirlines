<?php

namespace App\Http\Controllers;

use App\Models\Flight;
// use App\Models\Airport;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function search(Request $request)
    {
        // Bắt thông số từ Form tìm kiếm
        $flightType = $request->flight_type; // 'one_way' hoặc 'round_trip'
        $outboundFlightId = $request->outbound_flight_id; // Sẽ có dữ liệu nếu khách đã chọn xong chiều đi

        // TRƯỜNG HỢP 1: KHỨ HỒI - BƯỚC 1 (Chưa chọn chiều đi)
        if ($flightType == 'round_trip' && !$outboundFlightId) {
            $flights = Flight::with('airline')
                ->where('origin_id', $request->origin_id)
                ->where('destination_id', $request->destination_id)
                ->whereDate('departure_time', $request->departure_date)
                ->orderBy('price', 'asc')
                ->get();
                
            $step = 'outbound'; // Đánh dấu đang ở bước chọn chiều đi
            $title = 'Chọn chuyến bay Chiều Đi';
        }
        // TRƯỜNG HỢP 2: KHỨ HỒI - BƯỚC 2 (Đã chọn chiều đi -> Giờ tìm chiều về)
        elseif ($flightType == 'round_trip' && $outboundFlightId) {
            // LƯU Ý: Phải đảo ngược Origin và Destination cho chuyến về
            $flights = Flight::with('airline')
                ->where('origin_id', $request->destination_id) 
                ->where('destination_id', $request->origin_id)
                ->whereDate('departure_time', $request->return_date)
                ->orderBy('price', 'asc')
                ->get();
                
            $step = 'return'; // Đánh dấu đang ở bước chọn chiều về
            $title = 'Chọn chuyến bay Chiều Về';
        }
        // TRƯỜNG HỢP 3: MỘT CHIỀU
        else {
            $flights = Flight::with('airline')
                ->where('origin_id', $request->origin_id)
                ->where('destination_id', $request->destination_id)
                ->whereDate('departure_time', $request->departure_date)
                ->orderBy('price', 'asc')
                ->get();
                
            $step = 'one_way'; 
            $title = 'Chọn chuyến bay';
        }

        return view('flights.search', compact('flights', 'step', 'title', 'request', 'outboundFlightId'));
    }
}