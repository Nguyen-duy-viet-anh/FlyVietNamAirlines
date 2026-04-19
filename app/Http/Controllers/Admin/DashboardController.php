<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\AppBooking;
use App\Models\Flight;
use App\Models\Airport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang Dashboard Admin
     */
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'bookings_count' => AppBooking::count(),
            'flights_count' => Flight::count(),
            'airports_count' => Airport::count(),
        ];

        // Lấy 5 đơn vé mới nhất
        $recentBookings = AppBooking::latest()->limit(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentBookings'));
    }

    /**
     * API Lấy dữ liệu biểu đồ
     */
    public function getChartData(Request $request)
    {
        $range = $request->query('range', 'week');
        $query = AppBooking::query();
        $labels = [];
        $data = [];

        switch ($range) {
            case 'today':
                $bookings = $query->whereDate('created_at', Carbon::today())
                    ->select(DB::raw('HOUR(created_at) as label'), DB::raw('count(*) as total'))
                    ->groupBy('label')
                    ->orderBy('label')
                    ->get();
                // Điền đủ 24 giờ
                for ($i = 0; $i < 24; $i++) {
                    $labels[] = $i . ':00';
                    $found = $bookings->firstWhere('label', $i);
                    $data[] = $found ? $found->total : 0;
                }
                break;

            case 'week':
                $days = 7;
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('d/m');
                    $data[] = AppBooking::whereDate('created_at', $date)->count();
                }
                break;

            case 'month':
                $days = 30;
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('d/m');
                    $data[] = AppBooking::whereDate('created_at', $date)->count();
                }
                break;

            case '6_months':
                for ($i = 5; $i >= 0; $i--) {
                    $date = Carbon::today()->subMonths($i);
                    $labels[] = 'Tháng ' . $date->format('m/Y');
                    $data[] = AppBooking::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                }
                break;

            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::today()->subMonths($i);
                    $labels[] = 'Tháng ' . $date->format('m/Y');
                    $data[] = AppBooking::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                }
                break;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
