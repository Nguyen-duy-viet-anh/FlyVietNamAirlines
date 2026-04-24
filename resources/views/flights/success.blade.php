@extends('layouts.public')

@section('content')
<div class="container success-wrapper">
    <div class="success-card">
        
        @if(str_contains($message, 'thành công'))
            <h2 class="success-title">Giao dịch thành công</h2>
            
            <p class="success-text">Xin chào <strong>{{ $booking->passenger_name }}</strong>,</p>
            <p class="success-subtext">Chuyến bay của bạn đã được đặt thành công. Hệ thống đã gửi thông tin vé điện tử về địa chỉ email của bạn.</p>

            <div class="pnr-box">
                <p class="pnr-label">Mã đặt vé (PNR)</p>
                <strong class="pnr-code">{{ $booking->booking_code }}</strong>
            </div>

            <div class="summary-details">
                <p class="summary-row">Hành khách: <span>{{ $booking->passenger_name }}</span></p>
                <p class="summary-row">Đã thanh toán: <span>{{ number_format($booking->total_amount, 0, ',', '.') }} VND</span></p>
            </div>
        @else
            <h2 class="error-title">Thanh toán chưa hoàn tất</h2>
            <p class="success-subtext">Giao dịch thanh toán đã bị hủy hoặc gặp lỗi kỹ thuật. Vui lòng kiểm tra lại tài khoản hoặc thử lại sau.</p>
        @endif

        <div class="success-footer">
            <a href="/" class="back-link">Quay lại trang chủ</a>
        </div>
    </div>
</div>
@endsection