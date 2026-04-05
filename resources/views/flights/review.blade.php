@extends('layouts.public')

@section('content')
<div class="card" style="max-width:800px; margin:0 auto;">
    <h2 class="text-center section-title--blue">Kiểm tra thông tin Đặt vé</h2>
    <p class="text-center muted">Vui lòng kiểm tra kỹ các thông tin dưới đây trước khi thanh toán.</p>
    <hr class="hr-dashed">

    <div class="panel-muted">
        <h3 class="section-title">Thông tin Người đặt</h3>
        <p><strong>Họ tên:</strong> {{ $passengerData['passenger_name'] }}</p>
        <p><strong>Email:</strong> {{ $passengerData['passenger_email'] }}</p>
        <p><strong>Số điện thoại:</strong> {{ $passengerData['passenger_phone'] }}</p>
        <p><strong>Ghi chú:</strong> {{ $passengerData['notes'] ?? 'Không có' }}</p>
    </div>

    <div style="background:#fff; border:1px solid #ddd; padding:20px; border-radius:8px; margin-bottom:20px;">
        <h3 class="section-title">Thông tin Chuyến bay</h3>
        <p><strong>Loại vé:</strong> {{ $passengerData['flight_type'] == 'one_way' ? 'Một chiều' : 'Khứ hồi' }}</p>
        <p><strong>Số lượng khách:</strong> {{ $passengerData['adult_count'] }} Người lớn, {{ $passengerData['child_count'] }} Trẻ em, {{ $passengerData['infant_count'] }} Sơ sinh.</p>
        
        <div style="margin-top: 15px;">
            <strong class="highlight">[CHUYẾN ĐI]</strong> {{ $outboundFlight->origin->city }} → {{ $outboundFlight->destination->city }} <br>
            Hãng bay: {{ $outboundFlight->airline->name }} ({{ $outboundFlight->flight_number }}) <br>
            Khởi hành: {{ $outboundFlight->departure_time->format('H:i d/m/Y') }}
        </div>

        @if($returnFlight)
        <div style="margin-top:15px;">
            <strong class="highlight">[CHUYẾN VỀ]</strong> {{ $returnFlight->origin->city }} → {{ $returnFlight->destination->city }} <br>
            Hãng bay: {{ $returnFlight->airline->name }} ({{ $returnFlight->flight_number }}) <br>
            Khởi hành: {{ $returnFlight->departure_time->format('H:i d/m/Y') }}
        </div>
        @endif
    </div>
        <div class="card" style="background-color:#f9f9f9;">
            <h2>Tóm tắt đơn hàng</h2>
            <hr class="hr-dashed">
            <p><strong>Loại vé:</strong> {{ $bookingData['flight_type'] == 'one_way' ? 'Một chiều' : 'Khứ hồi' }}</p>
            <p><strong>Hành khách:</strong> {{ $bookingData['adult_count'] }} Người lớn, {{ $bookingData['child_count'] }}
                Trẻ em</p>
            <h4 class="section-title--blue" style="margin-top:15px;">Chuyến đi</h4>
            <p>{{ $outboundFlight->airline->name }} ({{ $outboundFlight->flight_number }})</p>
            <p>{{ $outboundFlight->origin->city }} → {{ $outboundFlight->destination->city }}</p>
            @if ($returnFlight)
                <h4 style="margin-top: 15px; color: #0056b3;">Chuyến về</h4>
                <p>{{ $returnFlight->airline->name }} ({{ $returnFlight->flight_number }})</p>
                <p>{{ $returnFlight->origin->city }} → {{ $returnFlight->destination->city }}</p>
            @endif
            <hr style="margin: 15px 0;">
            <div
                style="background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #e1e8ed; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h3 style="border-bottom:2px solid #3498db; padding-bottom:10px; margin-top:0; color:#2c3e50;">Chi tiết giá vé</h3>

                <h4 style="color: #7f8c8d; margin-bottom: 10px; margin-top: 20px;">1. Tiền vé cơ bản (Base Fare)</h4>

                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>Người lớn (x{{ $bookingData['adult_count'] }})</span>
                    <strong>{{ number_format($priceBreakdown['base_adult_single'] * $bookingData['adult_count'], 0, ',', '.') }}đ</strong>
                </div>
                <small class="small-muted">{{ number_format($priceBreakdown['base_adult_single'], 0, ',', '.') }}đ / khách</small>

                @if ($bookingData['child_count'] > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span>Trẻ em (x{{ $bookingData['child_count'] }})</span>
                        <strong>{{ number_format($priceBreakdown['base_child_single'] * $bookingData['child_count'], 0, ',', '.') }}đ</strong>
                    </div>
                    <small class="small-muted">Giảm 20%: {{ number_format($priceBreakdown['base_child_single'], 0, ',', '.') }}đ / khách</small>
                @endif


                <h4 class="section-subtitle">2. Thuế & Phí (Taxes & Fees)</h4>

                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Thuế & Phí sân bay</span>
                    <span>{{ number_format($priceBreakdown['total_taxes'], 0, ',', '.') }}đ</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Phí dịch vụ & Hệ thống</span>
                    <span>{{ number_format($priceBreakdown['total_service'], 0, ',', '.') }}đ</span>
                </div>

                @if ($bookingData['infant_count'] > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #d35400;">
                        <span>Phí em bé (x{{ $bookingData['infant_count'] }})</span>
                        <span>{{ number_format($priceBreakdown['total_infant'], 0, ',', '.') }}đ</span>
                    </div>
                @endif

                <hr class="hr-dashed">

                <div class="flex-between">
                    <span style="font-size:18px; font-weight:bold; color:#2c3e50;">TỔNG CỘNG:</span>
                    <span class="price-large">{{ number_format($bookingData['total_amount'], 0, ',', '.') }} VNĐ</span>
                </div>
                <small class="muted" style="text-align:right; display:block; margin-top:5px;">(Đã bao gồm VAT)</small>
            </div>
        </div>
    <div class="text-right" style="margin-bottom:20px;">
        <h2>Tổng thanh toán: <span style="color:#e60000;">{{ number_format($passengerData['total_amount'], 0, ',', '.') }} VNĐ</span></h2>
    </div>

    <form action="{{ route('flights.payment') }}" method="POST">
        @csrf
        @foreach($passengerData as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        
        <div class="flex-between">
            <a href="javascript:history.back()" class="btn" style="background:#ccc; color:#333; text-decoration:none;">Quay lại sửa</a>
            <button type="submit" class="btn btn-primary btn-large">Xác nhận & Thanh toán VNPay</button>
        </div>
    </form>
</div>
@endsection