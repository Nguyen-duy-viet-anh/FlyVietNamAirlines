@extends('layouts.admin')
@section('title', 'Chi tiết Tài khoản')

@section('content')
<div class="card" style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #003580; font-size: 18px;">Thông tin khách hàng</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="text-decoration: none; background: #6c757d; color: white; padding: 8px 15px; border-radius: 4px; font-size: 14px;">Quay lại danh sách</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <p><strong>Họ tên:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Không có' }}</p>
        </div>
        <div>
            <p><strong>Vai trò:</strong> 
                @if($user->role === 'admin')
                    <span style="background: #e1001a; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Admin</span>
                @else
                    <span style="background: #28a745; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Khách</span>
                @endif
            </p>
            <p><strong>Trạng thái:</strong> 
                @if($user->status == 'active')
                    <span style="color: #28a745; font-weight: 500;">Đang hoạt động</span>
                @else
                    <span style="color: #dc3545; font-weight: 500;">Bị khóa</span>
                @endif
            </p>
            <p><strong>Ngày tham gia:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

<div class="card">
    <h3 style="margin-top: 0; margin-bottom: 20px; color: #003580; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 15px;">Lịch sử đặt vé</h3>
    
    @if($user->bookings->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 15%; text-align: left;">Mã vé</th>
                <th style="width: 25%; text-align: left;">Hành trình</th>
                <th style="width: 20%; text-align: center;">Ngày đặt</th>
                <th style="width: 15%; text-align: right;">Tổng tiền</th>
                <th style="width: 15%; text-align: center;">Trạng thái</th>
                <th style="width: 10%; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user->bookings as $booking)
            <tr>
                <td><strong>{{ $booking->booking_code }}</strong></td>
                <td>
                    @if($booking->outboundFlight)
                        {{ $booking->outboundFlight->origin->code ?? '?' }} <i class="fas fa-arrow-right" style="font-size: 10px; color: #999; margin: 0 5px;"></i> {{ $booking->outboundFlight->destination->code ?? '?' }}
                    @else
                        N/A
                    @endif
                </td>
                <td style="text-align: center;">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                <td style="text-align: right; color: #d9534f; font-weight: 600;">{{ number_format($booking->total_amount, 0, ',', '.') }} ₫</td>
                <td style="text-align: center;">
                    @if($booking->status == 'confirmed')
                        <span style="background: #e8f5e9; color: #2e7d32; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Thành công</span>
                    @elseif($booking->status == 'pending')
                        <span style="background: #fff8e1; color: #f57f17; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Chờ xử lý</span>
                    @elseif($booking->status == 'cancelled')
                        <span style="background: #ffebee; color: #c62828; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">Đã hủy</span>
                    @else
                        <span style="background: #f5f5f5; color: #616161; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">{{ ucfirst($booking->status) }}</span>
                    @endif
                </td>
                <td style="text-align: right;">
                    @can('manage-bookings')
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn-view" style="text-decoration: none; color: #003580; font-weight: 600; font-size: 13px;">
                            <i class="fas fa-eye"></i> Xem vé
                        </a>
                    @else
                        <span style="color: #999; font-size: 13px;">Không có quyền</span>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 30px; background: #f9f9f9; border-radius: 8px; color: #666;">
        <i class="fas fa-ticket-alt" style="font-size: 30px; color: #ccc; margin-bottom: 10px; display: block;"></i>
        Khách hàng này chưa có lịch sử đặt vé nào.
    </div>
    @endif
</div>
@endsection
