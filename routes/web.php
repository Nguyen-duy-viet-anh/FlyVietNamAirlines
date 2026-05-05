<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlightController as AdminFlightController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\RefundController as AdminRefundController;
use App\Http\Controllers\RefundController;
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

// Khách hàng (Không cần đăng nhập)
Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my.bookings');

// Khách hàng (Cần đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Yêu cầu hoàn tiền cho 1 booking (Không cần đăng nhập)
Route::get('/bookings/{booking}/refund', [RefundController::class, 'create'])->name('refunds.create');
Route::post('/bookings/{booking}/refund', [RefundController::class, 'store'])->name('refunds.store');



// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('flights', AdminFlightController::class);
    Route::get('/flights/{id}/passengers', [AdminFlightController::class, 'passengers'])->name('flights.passengers');
    Route::middleware('can:manage-bookings')->group(function () {
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{id}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update_status');

        // Quan ly hoan ve
        Route::get('/refunds', [AdminRefundController::class, 'index'])->name('refunds.index');
        Route::get('/refunds/{id}', [AdminRefundController::class, 'show'])->name('refunds.show');
        Route::post('/refunds/{id}/status', [AdminRefundController::class, 'updateStatus'])->name('refunds.update_status');
    });

    Route::middleware('can:manage-airports')->group(function () {
        // Quản lý Địa điểm (Sân bay)
        Route::get('/airports', [App\Http\Controllers\Admin\AirportController::class, 'index'])->name('airports.index');
        Route::get('/airports/{id}/edit', [App\Http\Controllers\Admin\AirportController::class, 'edit'])->name('airports.edit');
        Route::put('/airports/{id}', [App\Http\Controllers\Admin\AirportController::class, 'update'])->name('airports.update');
    });

    // Quản lý tài khoản
    Route::post('/users/send-notification', [App\Http\Controllers\Admin\UserController::class, 'sendNotification'])->name('users.send_notification');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Log Thanh toán

    // API Biểu đồ
    Route::get('/chart-data', [DashboardController::class, 'getChartData'])->name('chart_data');
    
    // Core API removed
});
// Đặt vé
Route::get('/flights/book', [BookingController::class, 'showBookForm'])->name('flights.book');
Route::post('/flights/review', [BookingController::class, 'reviewBooking'])->name('flights.review'); // Trang Review
Route::post('/flights/payment', [BookingController::class, 'submitBooking'])->name('flights.payment'); // Gọi VNPay
Route::get('/booking/{booking_code}/success', [BookingController::class, 'success'])->name('booking.success');
Route::post('/booking/lookup', [BookingController::class, 'lookup'])->name('booking.lookup'); // Tra cứu vé
Route::get('/mybooking', [BookingController::class, 'showLookupForm'])->name('booking.mybooking'); // Trang nhập mã vé



// Đăng ký nhận bản tin
Route::post('/newsletter', [HomeController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

//địa danh

Route::get('/destinations', [App\Http\Controllers\DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{id}', [App\Http\Controllers\DestinationController::class, 'show'])->name('destinations.show');

// Đăng nhập, Đăng ký & Đăng xuất
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');