@extends('layouts.admin')
@section('title', 'Quản lý Đơn vé')

@section('content')
<div class="card">
    <table>
        <thead>
            <tr>
                <th style="width: 50px; text-align: left;">STT</th>
                <th style="width: 15%; text-align: left;">Mã PNR</th>
                <th style="width: 25%; text-align: left;">Khách hàng</th>
                <th style="width: 20%; text-align: center;">Hành trình</th>
                <th style="width: 18%; text-align: right; padding-right: 30px;">Tổng tiền</th>
                <th style="width: 17%; text-align: right;">Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td>{{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}</td>
                <td>
                    <a href="{{ route('admin.bookings.show', $booking->id) }}" style="text-decoration: none; color: inherit;">
                        <strong>{{ $booking->booking_code }}</strong>
                    </a>
                    <br><small>{{ $booking->created_at->format('d/m/Y H:i') }}</small>
                </td>
                <td>{{ $booking->passenger_name }}<br><small>{{ $booking->passenger_phone }}</small></td>
                <td style="text-align: center;">
                    <div style="font-weight: 500;">
                        {{ $booking->outboundFlight->origin->code }} 
                        <i class="fas fa-arrow-right" style="font-size: 10px; color: #999; margin: 0 5px;"></i> 
                        {{ $booking->outboundFlight->destination->code }}
                    </div>
                    <small style="color: #888;">({{ $booking->flight_type == 'round_trip' ? 'Khứ hồi' : 'Một chiều' }})</small>
                </td>
                <td style="text-align: right; padding-right: 30px;">
                    <span style="font-size: 16px; font-weight: 700; color: #d84a1d;">
                        {{ number_format($booking->total_amount, 0, ',', '.') }}đ
                    </span>
                </td>
                <td style="text-align: right;">
                    @if($booking->status == 'confirmed') 
                        <span class="badge badge-confirmed" style="display: inline-block; min-width: 100px; text-align: center;">Đã xác nhận</span>
                    @elseif($booking->status == 'cancelled') 
                        <span class="badge badge-cancelled" style="display: inline-block; min-width: 100px; text-align: center;">Đã hủy</span>
                    @else 
                        <span class="badge badge-pending" style="display: inline-block; min-width: 100px; text-align: center;">Chờ xử lý</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="mt-20">
        {{ $bookings->links() }}
    </div>
</div>
@endsection