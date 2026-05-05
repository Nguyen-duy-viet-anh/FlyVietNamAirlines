@extends('layouts.admin')
@section('title', 'Danh sách hành khách - Chuyến bay ' . $flight->flight_number)

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.flights.index') }}" style="text-decoration: none; color: #003580; font-weight: 500;">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách chuyến bay
    </a>
</div>

<div class="card" style="margin-bottom: 30px; border-left: 4px solid #003580;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin-bottom: 5px;">Thông tin chuyến bay</h3>
            <div style="font-size: 14px; color: #666;">
                {{ $flight->airline->name }} | {{ $flight->flight_number }} | 
                <strong>{{ $flight->origin->city }} ({{ $flight->origin->code }})</strong> đến <strong>{{ $flight->destination->city }} ({{ $flight->destination->code }})</strong>
            </div>
            <div style="font-size: 14px; color: #666; margin-top: 5px;">
                <i class="far fa-calendar-alt"></i> Ngày bay: {{ $flight->departure_time->format('d/m/Y') }} | 
                <i class="far fa-clock"></i> Khởi hành: {{ $flight->departure_time->format('H:i') }}
            </div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 13px; color: #888;">Tổng số hành khách</div>
            <div style="font-size: 24px; font-weight: 700; color: #003580;">{{ count($passengers) }}</div>
        </div>
    </div>
</div>

<div class="card">
    @if(count($passengers) > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 50px; text-align: left;">STT</th>
                <th style="width: 25%; text-align: left;">Họ và Tên</th>
                <th style="width: 15%; text-align: center;">Loại khách</th>
                <th style="width: 20%; text-align: left;">Mã đặt chỗ (PNR)</th>
                <th style="width: 20%; text-align: left;">Liên hệ</th>
                <th style="text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($passengers as $index => $passenger)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong style="color: #003580;">{{ strtoupper($passenger['name']) }}</strong>
                </td>
                <td style="text-align: center;">
                    <span class="badge" style="background: {{ $passenger['type'] == 'adult' ? '#e1f5fe; color: #01579b;' : ($passenger['type'] == 'child' ? '#e8f5e9; color: #1b5e20;' : '#fff3e0; color: #e65100;') }}; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                        {{ $passenger['type'] == 'adult' ? 'NGƯỜI LỚN' : ($passenger['type'] == 'child' ? 'TRẺ EM' : 'EM BÉ') }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.bookings.show', $passenger['booking_id']) }}" style="text-decoration: none; color: inherit; font-weight: 600;">
                        {{ $passenger['booking_code'] }}
                    </a>
                </td>
                <td>
                    <div style="font-size: 13px;">{{ $passenger['phone'] }}</div>
                    <div style="font-size: 12px; color: #888;">{{ $passenger['email'] }}</div>
                </td>
                <td style="text-align: right;">
                    <a href="{{ route('admin.bookings.show', $passenger['booking_id']) }}" class="btn-detail" title="Xem chi tiết đơn hàng">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="padding: 40px; text-align: center; color: #999;">
        <i class="fas fa-user-slash" style="font-size: 40px; margin-bottom: 15px; display: block;"></i>
        Chưa có hành khách nào đặt chỗ trên chuyến bay này.
    </div>
    @endif
</div>
@endsection
