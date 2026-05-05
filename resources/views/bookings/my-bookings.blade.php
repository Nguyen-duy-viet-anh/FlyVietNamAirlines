@extends('layouts.public')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/my-bookings.css') }}">
@endpush

@section('content')
<div class="profile-container">
    <div class="profile-card">
        <h2 class="profile-title">
            Thông tin tài khoản
        </h2>

        <div class="form-grid">
            <div>
                <p class="form-label">Họ và tên</p>
                <div class="form-input" style="background: #f9f9f9;">{{ $user->name }}</div>
            </div>
            <div>
                <p class="form-label">Email</p>
                <div class="form-input" style="background: #f9f9f9;">{{ $user->email }}</div>
            </div>
        </div>

        <div class="form-grid">
            <div>
                <p class="form-label">Số điện thoại</p>
                <div class="form-input" style="background: #f9f9f9;">{{ $user->phone ?? 'Chưa cập nhật' }}</div>
            </div>
            <div>
                @php
                    $roleLabels = [
                        'admin' => 'Quản trị viên',
                        'super_admin' => 'Quản trị viên cấp cao',
                        'customer' => 'Khách hàng',
                    ];
                    $statusLabels = [
                        'active' => ['Đang hoạt động', 'badge-confirmed'],
                        'blocked' => ['Bị khóa', 'badge-cancelled'],
                    ];
                    $statusInfo = $statusLabels[$user->status ?? 'active'] ?? ['Không xác định', 'badge-pending'];
                @endphp
                <p class="form-label">Vai trò & Trạng thái</p>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <span class="badge" style="background: #fff; color: #666; border-color: #eee;">
                        {{ $roleLabels[$user->role ?? 'customer'] ?? 'Khách hàng' }}
                    </span>
                    <span class="badge {{ $statusInfo[1] }}">{{ $statusInfo[0] }}</span>
                </div>
            </div>
        </div>
        
        <p style="margin-top: 15px; font-size: 0.9rem; color: #666;">
            Ngày tham gia: {{ optional($user->created_at)->format('d/m/Y') }}
        </p>
    </div>

    <div class="profile-card booking-history-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="profile-title" style="margin-bottom: 0; border-bottom: none; padding-bottom: 0;">
                Lịch sử đặt vé
            </h2>
            <span class="total-count-label" style="font-size: 0.85rem; color: #999; border: 1px solid #eee; padding: 2px 10px; border-radius: 2px;">
                Tổng: {{ $bookings->count() }} vé
            </span>
        </div>

        @php
            $bookingStatusMap = [
                'pending' => ['Đang xử lý', 'badge-pending'],
                'confirmed' => ['Đã xác nhận', 'badge-confirmed'],
                'completed' => ['Hoàn tất', 'badge-confirmed'],
                'cancelled' => ['Đã hủy', 'badge-cancelled'],
            ];
            $paymentStatusMap = [
                'unpaid' => ['Chưa thanh toán', 'badge-pending'],
                'paid' => ['Đã thanh toán', 'badge-confirmed'],
                'refunded' => ['Đã hoàn tiền', 'badge-cancelled'],
            ];
        @endphp

        @if($bookings->isEmpty())
            <div class="empty-state">
                <p style="color: #666; margin-bottom: 15px;">Bạn chưa có đơn đặt vé nào.</p>
                <a href="{{ route('home') }}" class="btn-neutral">Tìm chuyến bay ngay</a>
            </div>
        @else
            @foreach($bookings as $booking)
                @php
                    $statusInfo = $bookingStatusMap[$booking->status] ?? ['Không xác định', 'badge-pending'];
                    $paymentInfo = $paymentStatusMap[$booking->payment_status] ?? ['Không xác định', 'badge-pending'];
                    $outbound = $booking->outboundFlight;
                    $return = $booking->returnFlight;
                @endphp

                <div class="booking-item">
                    <div class="booking-header">
                        <div>
                            <span style="font-size: 0.8rem; color: #667085; display: block; text-transform: uppercase;">Mã đặt vé</span>
                            <span class="booking-code">{{ $booking->booking_code }}</span>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge {{ $statusInfo[1] }}">{{ $statusInfo[0] }}</span>
                            <span style="display: block; font-size: 0.75rem; color: #999; margin-top: 4px;">
                                {{ optional($booking->created_at)->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>

                    <div class="flight-info-grid">
                        {{-- Chiều đi --}}
                        <div class="flight-leg">
                            <div class="leg-title">Chiều đi</div>
                            @if($outbound)
                                <div class="route-display">
                                    {{ $outbound->origin?->code ?? '---' }} 
                                    -
                                    {{ $outbound->destination?->code ?? '---' }}
                                </div>
                                <div style="font-size: 0.9rem; color: #4a5568;">
                                    <strong>{{ $outbound->departure_time?->format('H:i') }}</strong>, {{ $outbound->departure_time?->format('d/m/Y') }}
                                </div>
                                <div style="font-size: 0.85rem; color: #718096; margin-top: 4px;">
                                    {{ $outbound->airline?->name ?? 'Hãng hàng không' }} • {{ $outbound->flight_number }}
                                </div>
                            @else
                                <div class="text-muted">Thông tin chuyến bay không khả dụng</div>
                            @endif
                        </div>

                        {{-- Chiều về --}}
                        <div class="flight-leg">
                            <div class="leg-title">Chiều về</div>
                            @if($return)
                                <div class="route-display">
                                    {{ $return->origin?->code ?? '---' }} 
                                    -
                                    {{ $return->destination?->code ?? '---' }}
                                </div>
                                <div style="font-size: 0.9rem; color: #4a5568;">
                                    <strong>{{ $return->departure_time?->format('H:i') }}</strong>, {{ $return->departure_time?->format('d/m/Y') }}
                                </div>
                                <div style="font-size: 0.85rem; color: #718096; margin-top: 4px;">
                                    {{ $return->airline?->name ?? 'Hãng hàng không' }} • {{ $return->flight_number }}
                                </div>
                            @else
                                <div style="display: flex; align-items: center; height: 100%; color: #a0aec0; font-style: italic;">
                                    Chuyến bay một chiều
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="booking-footer">
                        <div>
                            <div style="font-size: 0.85rem; color: #667085; margin-bottom: 4px;">
                                Hạng vé: <span style="color: #2d3748; font-weight: 600;">{{ $booking->ticket_class ? ucfirst($booking->ticket_class) : 'Economy' }}</span>
                            </div>
                            <div style="font-size: 0.85rem; color: #667085;">
                                Thanh toán: <span class="badge {{ $paymentInfo[1] }}" style="font-size: 0.75rem;">{{ $paymentInfo[0] }}</span>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 0.85rem; color: #667085; margin-bottom: 2px;">Tổng tiền</div>
                            <div class="price-tag">{{ number_format($booking->total_amount ?? 0, 0, ',', '.') }} VND</div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection


