@extends('layouts.admin')
@section('title', 'Quản lý Đơn vé')

@section('content')
<div class="card">
    <table>
        <thead>
            <tr>
                <th>Mã PNR</th>
                <th>Khách hàng</th>
                <th>Hành trình</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Thanh toán</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td><strong>{{ $booking->booking_code }}</strong><br><small>{{ $booking->created_at->format('d/m/Y H:i') }}</small></td>
                <td>{{ $booking->passenger_name }}<br><small>{{ $booking->passenger_phone }}</small></td>
                <td>
                    {{ $booking->outboundFlight->origin->code }} → {{ $booking->outboundFlight->destination->code }}
                    @if($booking->flight_type == 'round_trip') <br><small>(Khứ hồi)</small> @endif
                </td>
                <td class="danger-text" style="font-weight:bold;">{{ number_format($booking->total_amount, 0, ',', '.') }}đ</td>
                <td>
                    @if($booking->status == 'confirmed') <span class="badge badge-confirmed">Đã xác nhận</span>
                    @elseif($booking->status == 'cancelled') <span class="badge badge-cancelled">Đã hủy</span>
                    @else <span class="badge badge-pending">Chờ xử lý</span> @endif
                </td>
                <td>
                    {{ $booking->outboundFlight->origin->code }} → {{ $booking->outboundFlight->destination->code }}
                    @if($booking->flight_type == 'round_trip') <br><small>(Khứ hồi)</small> @endif
                    
                    <br>
                    @if($booking->ticket_class == 'business')
                        <span style="color: #d35400; font-weight: bold; font-size: 12px;">Thương gia</span>
                    @else
                        <span style="color: #7f8c8d; font-size: 12px;">Phổ thông</span>
                    @endif
                </td>
                <td>
                    @if($booking->payment_status == 'paid') <span style="color: green;">Đã thanh toán</span>
                    @else <span style="color: red;">Chưa TT</span> @endif
                </td>
                <td>
                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-primary">Xem chi tiết</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $bookings->links() }}
    </div>
</div>
@endsection