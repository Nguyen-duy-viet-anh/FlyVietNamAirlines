<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterSubscription;

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
                
                // Nếu là khứ hồi, giá hiển thị = giá 1 chiều * 2
                $flight->display_price = ($flight->trip_type == 'round_trip') ? ($flight->price * 2) : $flight->price;
                
                return $flight;
            });

        return view('home', compact('airports', 'popularRoutes'));
    }

    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ], [
            'email.unique' => 'Email này đã đăng ký trước đó rồi.'
        ]);

        try {
            // Lưu vào database
            \App\Models\Newsletter::create(['email' => $request->email]);

            // Gửi email thông báo cho Admin
            $adminEmail = config('mail.from.address', 'admin@example.com');
            Mail::to($adminEmail)->send(new NewsletterSubscription($request->email));
            
            return back()->with('success_newsletter', 'Cảm ơn bạn đã đăng ký nhận bản tin!');
        } catch (\Exception $e) {
            return back()->with('error_newsletter', 'Có lỗi xảy ra, vui lòng thử lại sau.');
        }
    }
}