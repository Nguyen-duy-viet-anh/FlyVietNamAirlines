<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy danh sách tất cả sân bay để hiển thị ở Select box
        $airports = Airport::all();

        // Lấy các tuyến bay phổ biến (ví dụ 8 tuyến từ database)
        $popularRoutes = Flight::with(['origin', 'destination'])
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MIN(id)'))
                    ->from('flights')
                    ->whereIn('price', function($q) {
                        $q->select(DB::raw('MIN(price)'))
                            ->from('flights')
                            ->groupBy('origin_id', 'destination_id');
                    })
                    ->groupBy('origin_id', 'destination_id');
            })
            ->take(8)
            ->get()
            ->map(function($flight, $key) {
                // Pha trộn giữa One Way và Round Trip (ví dụ: lẻ là Round Trip, chẵn là One Way)
                $flight->trip_type = ($key % 2 == 0) ? 'one_way' : 'round_trip';
                return $flight;
            });

        return view('home', compact('airports', 'popularRoutes'));
    }
}