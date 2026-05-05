@extends('layouts.admin')
@section('title')
    Quản lý Đơn vé <small style="color: #666; font-size: 16px; font-weight: normal; margin-left:10px;">({{ $bookings->total() }} đơn)</small>
@endsection

@section('content')
<div class="card">
    <!-- Bộ lọc -->
    <div style="margin-bottom: 20px; padding: 15px; border-bottom: 1px solid #eee;">
        <form action="{{ route('admin.bookings.index') }}" method="GET" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 5px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm SĐT, Email, Tên, Mã..." style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; outline: none; min-width: 250px;">
            </div>

            <div style="display: flex; align-items: center; gap: 5px;">
                <label style="font-weight: 500; font-size: 14px; margin-bottom: 0;">Lọc theo ngày:</label>
                <select name="date_filter" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; outline: none; min-width: 150px;">
                    <option value="">Tất cả thời gian</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                    <option value="yesterday" {{ request('date_filter') == 'yesterday' ? 'selected' : '' }}>Hôm qua</option>
                    <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>Tuần này</option>
                    <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>Tháng này</option>
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 5px;">
                <label style="font-weight: 500; font-size: 14px; margin-bottom: 0;">Thanh toán:</label>
                <select name="payment_status" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; outline: none; min-width: 150px;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Lỗi thanh toán</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                </select>
            </div>

            <button type="submit" style="background: #003580; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: 500;">
                <i class="fas fa-filter"></i> Lọc KQ
            </button>
            <a href="{{ route('admin.bookings.index') }}" style="padding: 7px 15px; background: #f8f9fa; color: #333; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; font-size: 14px;">Xóa lọc</a>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px; text-align: left;">STT</th>
                <th style="width: 15%; text-align: left;">Mã PNR</th>
                <th style="width: 20%; text-align: left;">Khách hàng</th>
                <th style="width: 20%; text-align: center;">Hành trình</th>
                <th style="width: 20%; text-align: center;">Số vé</th>
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
                <td style="text-align: center;">
                    {{ $booking->adult_count + $booking->child_count + $booking->infant_count }} vé
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
        {{ $bookings->onEachSide(1)->links('pagination.admin') }}
    </div>
</div>
@endsection