<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function search(Request $request)
    {
        // Kiểm tra logic số lượng khách: 
        // 1. Sơ sinh không được lớn hơn Người lớn
        // 2. Tổng số khách (Người lớn + Trẻ em + Sơ sinh) <= 9
        $totalPax = $request->adult_count + $request->child_count + $request->infant_count;
        if ($request->infant_count > $request->adult_count || $totalPax > 9) {
            $errors = [];
            if ($request->infant_count > $request->adult_count) {
                $errors['infant_count'] = 'Số lượng trẻ sơ sinh không được vượt quá số lượng người lớn.';
            }
            if ($totalPax > 9) {
                $errors['total_pax'] = 'Tổng số khách không được vượt quá 9 người.';
            }

            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        // Bắt thông số từ Form tìm kiếm
        $outboundFlightId = $request->outbound_flight_id;
        $returnFlightId = $request->return_flight_id;
        $flightType = $request->flight_type;

        // Tự động nhận diện loại chuyến bay nếu thiếu flight_type
        if (!$flightType && $outboundFlightId) {
            $flightType = 'round_trip';
        }

        $outboundFlight = null;
        if ($outboundFlightId) {
            $outboundFlight = Flight::with(['airline', 'origin', 'destination'])->find($outboundFlightId);
        }

        $returnFlight = null;
        if ($returnFlightId) {
            $returnFlight = Flight::with(['airline', 'origin', 'destination'])->find($returnFlightId);
        }

        $noReturnAvailable = false;

        // TRƯỜNG HỢP 1: KHỨ HỒI - BƯỚC 1 (Chưa chọn chiều đi)
        if ($flightType == 'round_trip' && !$outboundFlightId) {
            $flights = Flight::with(['airline', 'origin', 'destination'])
                ->where('origin_id', $request->origin_id)
                ->where('destination_id', $request->destination_id)
                ->whereDate('departure_time', $request->departure_date)
                ->orderBy('price', 'asc')
                ->get();
            
            // KIỂM TRA XEM CÓ CHIỀU VỀ KHÔNG?
            if ($flights->isNotEmpty()) {
                $hasReturnFlights = Flight::where('origin_id', $request->destination_id)
                    ->where('destination_id', $request->origin_id)
                    ->whereDate('departure_time', $request->return_date)
                    ->exists();
                
                if (!$hasReturnFlights) {
                    $flights = collect(); // Không cho chọn chiều đi nếu không có chiều về
                    $noReturnAvailable = true;
                }
            }
                
            $step = 'outbound'; 
            $title = 'Chọn chuyến bay Chiều Đi';
        }
        // TRƯỜNG HỢP 2: KHỨ HỒI - BƯỚC 2 (Đã chọn chiều đi -> Giờ tìm chiều về)
        elseif ($flightType == 'round_trip' && $outboundFlightId) {
            $flights = Flight::with(['airline', 'origin', 'destination'])
                ->where('origin_id', $request->destination_id) 
                ->where('destination_id', $request->origin_id)
                ->whereDate('departure_time', $request->return_date)
                ->orderBy('price', 'asc')
                ->get();
                
            $step = 'return'; 
            $title = 'Chọn chuyến bay Chiều Về';
        }
        // TRƯỜNG HỢP 3: MỘT CHIỀU
        else {
            $flights = Flight::with(['airline', 'origin', 'destination'])
                ->where('origin_id', $request->origin_id)
                ->where('destination_id', $request->destination_id)
                ->whereDate('departure_time', $request->departure_date)
                ->orderBy('price', 'asc')
                ->get();
                
            $step = 'one_way'; 
            $title = 'Chọn chuyến bay';
        }

        $airports = Airport::all();

        return view('flights.search', compact('flights', 'step', 'title', 'request', 'outboundFlightId', 'outboundFlight', 'returnFlightId', 'returnFlight', 'airports', 'noReturnAvailable'));
    }
}