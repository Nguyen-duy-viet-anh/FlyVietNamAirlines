<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlightController as AdminFlightController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Trang chủ & Tìm kiếm
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/flights/search', [FlightController::class, 'search'])->name('flights.search');

// Đặt vé
Route::get('/flights/book', [BookingController::class, 'showBookForm'])->name('flights.book');
Route::post('/flights/book', [BookingController::class, 'submitBooking'])->name('flights.book.submit');
Route::get('/booking/{booking_code}/success', [BookingController::class, 'success'])->name('booking.success');

// VNPay
Route::get('/vnpay/return', [PaymentController::class, 'vnpayReturn'])->name('vnpay.return');
Route::match(['get', 'post'], '/vnpay/ipn', [PaymentController::class, 'vnpayIpn'])->name('vnpay.ipn');

// Khách hàng (Cần đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my.bookings');
});

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('flights', AdminFlightController::class);
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update_status');

    // Quản lý Địa điểm (Sân bay)
    Route::get('/airports', [App\Http\Controllers\Admin\AirportController::class, 'index'])->name('airports.index');
    Route::get('/airports/{id}/edit', [App\Http\Controllers\Admin\AirportController::class, 'edit'])->name('airports.edit');
    Route::put('/airports/{id}', [App\Http\Controllers\Admin\AirportController::class, 'update'])->name('airports.update');

    // API Biểu đồ
    Route::get('/chart-data', [DashboardController::class, 'getChartData'])->name('chart_data');
});
// Đặt vé
Route::get('/flights/book', [BookingController::class, 'showBookForm'])->name('flights.book');
Route::post('/flights/review', [BookingController::class, 'reviewBooking'])->name('flights.review'); // Trang Review
Route::post('/flights/payment', [BookingController::class, 'submitBooking'])->name('flights.payment'); // Gọi VNPay
Route::get('/booking/{booking_code}/success', [BookingController::class, 'success'])->name('booking.success');


//địa danh

Route::get('/destinations', [App\Http\Controllers\DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{id}', [App\Http\Controllers\DestinationController::class, 'show'])->name('destinations.show');

// Đăng nhập & Đăng xuất
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');