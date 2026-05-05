@extends('layouts.admin')
@section('title')
    Quản lý Hoàn vé <small style="color: #666; font-size: 16px; font-weight: normal; margin-left:10px;">({{ $refunds->total() }} yêu cầu)</small>
@endsection

@section('content')
<div class="card">
    <div style="margin-bottom: 20px; padding: 15px; border-bottom: 1px solid #eee;">
        <form action="{{ route('admin.refunds.index') }}" method="GET" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 5px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm mã PNR, tên, email, SĐT..." style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; outline: none; min-width: 250px;">
            </div>

            <div style="display: flex; align-items: center; gap: 5px;">
                <label style="font-weight: 500; font-size: 14px; margin-bottom: 0;">Trạng thái:</label>
                <select name="status" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; outline: none; min-width: 160px;">
                    <option value="">Tất cả</option>
                    <option value="requested" {{ request('status') == 'requested' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            <button type="submit" style="background: #003580; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: 500;">
                <i class="fas fa-filter"></i> Lọc
            </button>
            <a href="{{ route('admin.refunds.index') }}" style="padding: 7px 15px; background: #f8f9fa; color: #333; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; font-size: 14px;">Xóa lọc</a>
        </form>
    </div>

    @if(session('success'))
        <div class="alert-success" style="margin-bottom: 15px;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 50px; text-align: left;">STT</th>
                <th style="width: 15%; text-align: left;">Mã PNR</th>
                <th style="width: 20%; text-align: left;">Khách hàng</th>
                <th style="width: 15%; text-align: right;">Số tiền</th>
                <th style="width: 20%; text-align: left;">Lý do</th>
                <th style="width: 15%; text-align: center;">Trạng thái</th>
                <th style="width: 15%; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($refunds as $refund)
                @php $booking = $refund->booking; @endphp
                <tr>
                    <td>{{ ($refunds->currentPage() - 1) * $refunds->perPage() + $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('admin.refunds.show', $refund->id) }}" style="text-decoration: none; color: inherit;">
                            <strong>{{ $booking->booking_code ?? 'N/A' }}</strong>
                        </a>
                        <br><small>{{ $refund->created_at->format('d/m/Y H:i') }}</small>
                    </td>
                    <td>
                        {{ $booking->passenger_name ?? '-' }}<br>
                        <small>{{ $booking->passenger_phone ?? '-' }}</small>
                    </td>
                    <td style="text-align: right;">
                        <span style="font-size: 15px; font-weight: 700; color: #d84a1d;">
                            {{ number_format($refund->amount, 0, ',', '.') }}đ
                        </span>
                    </td>
                    <td>{{ $refund->reason ?? '-' }}</td>
                    <td style="text-align: center;">
                        <span class="status-badge status-{{ $refund->status }}">
                            @if($refund->status === 'requested') Chờ xác nhận
                            @elseif($refund->status === 'processing') Đang xử lý
                            @elseif($refund->status === 'completed') Đã hoàn
                            @elseif($refund->status === 'failed') Thất bại
                            @else Đã hủy
                            @endif
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.refunds.show', $refund->id) }}" class="btn-view" style="text-decoration: none; color: #003580; font-weight: 600; font-size: 13px;">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-20">
        {{ $refunds->onEachSide(1)->links('pagination.admin') }}
    </div>
</div>
@endsection
