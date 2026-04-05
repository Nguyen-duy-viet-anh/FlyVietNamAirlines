@extends('layouts.admin')
@section('title', 'Chi tiết Đơn vé: ' . $booking->booking_code)

@section('content')
<div class="grid-two-cols">
    
    <div>
        <div class="card" style="margin-bottom: 20px;">
            <h3 class="section-title">Thông tin Hành khách</h3>
            <p><strong>Họ tên:</strong> {{ $booking->passenger_name }} (Giới tính: {{ $booking->passenger_gender }})</p>
            <p><strong>Email:</strong> {{ $booking->passenger_email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $booking->passenger_phone }}</p>
            <p><strong>Số lượng vé:</strong> {{ $booking->adult_count }} Người lớn, {{ $booking->child_count }} Trẻ em</p>
            
            <p style="margin-top: 10px;"><strong>Hạng vé:</strong> 
                @if($booking->ticket_class == 'business')
                    <span class="badge badge-business">Thương gia (Business)</span>
                @else
                    <span class="badge badge-economy">Phổ thông (Economy)</span>
                @endif
            </p>

            <p style="margin-top: 10px;"><strong>Ghi chú:</strong> {{ $booking->notes ?? 'Không có' }}</p>
        </div>

        <div class="card">
            <h3 class="section-title">Chi tiết Hành trình</h3>
            
            <h4 class="section-title--blue">[CHUYẾN ĐI] {{ $booking->outboundFlight->origin->city }} → {{ $booking->outboundFlight->destination->city }}</h4>
            <p>Hãng bay: {{ $booking->outboundFlight->airline->name }} ({{ $booking->outboundFlight->flight_number }})</p>
            <p>Khởi hành: {{ $booking->outboundFlight->departure_time->format('H:i - d/m/Y') }}</p>
            
            @if($booking->returnFlight)
            <h4 style="color: #3498db; margin-top: 15px;">[CHUYẾN VỀ] {{ $booking->returnFlight->origin->city }} → {{ $booking->returnFlight->destination->city }}</h4>
            <p>Hãng bay: {{ $booking->returnFlight->airline->name }} ({{ $booking->returnFlight->flight_number }})</p>
            <p>Khởi hành: {{ $booking->returnFlight->departure_time->format('H:i - d/m/Y') }}</p>
            @endif
        </div>
    </div>

    <div>
        <div class="card" style="margin-bottom: 20px;">
            <h3 class="section-title">Thanh toán (VNPay)</h3>
            <h2 style="color: #e74c3c; margin-bottom: 10px;">{{ number_format($booking->total_amount, 0, ',', '.') }} VNĐ</h2>
            
            <p><strong>Tình trạng:</strong> 
                @if($booking->payment_status == 'paid') <span style="color: green; font-weight: bold;">Đã thanh toán</span>
                @else <span style="color: red; font-weight: bold;">Chưa thanh toán</span> @endif
            </p>
            
            @if($booking->transaction)
                <p><strong>Mã GD VNPay:</strong> {{ $booking->transaction->transaction_code }}</p>
            @endif
        </div>

        <div class="card">
            <h3 class="section-title">Xử lý Đơn</h3>
            
            <form action="{{ route('admin.bookings.update_status', $booking->id) }}" method="POST">
                @csrf
                <select name="status" class="form-select">
                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Hủy vé</option>
                </select>
                <button type="submit" class="btn btn-warning" style="width: 100%;">Cập nhật trạng thái</button>
            </form>
        </div>
    </div>
</div>
@endsection