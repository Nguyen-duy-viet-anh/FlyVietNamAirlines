@extends('layouts.public')

@section('content')
<div class="card" style="text-align: center; padding: 50px 20px;">
    @if(str_contains($message, 'thành công'))
        <h2 class="success-text" style="font-size:28px; margin-top:10px;">Thanh toán thành công!</h2>
        <p style="font-size: 18px; margin-top: 20px;">Cảm ơn anh/chị <strong>{{ $booking->passenger_name }}</strong> đã đặt vé.</p>
        
            <div class="panel-muted" style="display:inline-block; margin-top:20px; text-align:left;">
                <p><strong>Mã đặt vé (PNR):</strong> <span class="highlight" style="font-size:24px;">{{ $booking->booking_code }}</span></p>
                <p><strong>Hành khách:</strong> {{ $booking->passenger_name }}</p>
                <p><strong>Email:</strong> {{ $booking->passenger_email }}</p>
                <p><strong>Tổng tiền:</strong> {{ number_format($booking->total_amount, 0, ',', '.') }} VNĐ</p>
            </div>
            <p class="muted" style="margin-top:20px;">Một email chứa thông tin vé điện tử sẽ được gửi tới hòm thư <b>{{ $booking->passenger_email }}</b> của bạn.</p>
    @else
        <h2 class="danger-text" style="font-size:28px; margin-top:10px;">Thanh toán Thất bại</h2>
        <p style="font-size: 18px; margin-top: 20px;">Giao dịch đã bị hủy hoặc có lỗi xảy ra từ ngân hàng.</p>
        <p style="margin-top: 20px; color: #666;">Vui lòng kiểm tra lại số dư hoặc thử lại sau.</p>
    @endif

    <div style="margin-top: 30px;">
           <a href="/" class="btn btn-primary" style="text-decoration: none;">Về Trang Chủ</a>
    </div>
</div>
@endsection