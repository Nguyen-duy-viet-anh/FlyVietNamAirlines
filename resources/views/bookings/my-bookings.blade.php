@extends('layouts.public')

@section('content')
<div class="card">
    <h2 class="section-title section-title--blue">Tai khoan cua toi</h2>

    <div class="grid-2">
        <div>
            <p><strong>Ho ten:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>So dien thoai:</strong> {{ $user->phone ?? 'Chua cap nhat' }}</p>
        </div>
        <div>
            @php
                $roleLabels = [
                    'admin' => 'Quan tri vien',
                    'super_admin' => 'Quan tri vien',
                    'admin_booking' => 'Quan tri dat ve',
                    'admin_airport' => 'Quan tri san bay',
                    'customer' => 'Khach hang',
                ];
                $statusLabels = [
                    'active' => ['Dang hoat dong', 'badge-confirmed'],
                    'blocked' => ['Bi khoa', 'badge-cancelled'],
                ];
                $statusInfo = $statusLabels[$user->status ?? 'active'] ?? ['Khong xac dinh', 'badge-pending'];
            @endphp
            <p><strong>Vai tro:</strong> {{ $roleLabels[$user->role ?? 'customer'] ?? 'Khach hang' }}</p>
            <p><strong>Trang thai:</strong> <span class="badge {{ $statusInfo[1] }}">{{ $statusInfo[0] }}</span></p>
            <p><strong>Ngay tham gia:</strong> {{ optional($user->created_at)->format('d/m/Y') }}</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="flex-between">
        <h2 class="section-title section-title--blue">Lich su dat ve</h2>
        <span class="small-muted">Tong: {{ $bookings->count() }} ve</span>
    </div>

    @php
        $bookingStatusMap = [
            'pending' => ['Dang xu ly', 'badge-pending'],
            'confirmed' => ['Da xac nhan', 'badge-confirmed'],
            'completed' => ['Hoan tat', 'badge-confirmed'],
            'cancelled' => ['Da huy', 'badge-cancelled'],
        ];
        $paymentStatusMap = [
            'unpaid' => ['Chua thanh toan', 'badge-pending'],
            'paid' => ['Da thanh toan', 'badge-confirmed'],
            'refunded' => ['Hoan tien', 'badge-cancelled'],
        ];
    @endphp

    @if($bookings->isEmpty())
        <div class="panel-muted">
            <p class="muted">Ban chua co don dat ve nao.</p>
            <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 10px;">Tim chuyen bay</a>
        </div>
    @else
        @foreach($bookings as $booking)
            @php
                $statusInfo = $bookingStatusMap[$booking->status] ?? ['Khong xac dinh', 'badge-pending'];
                $paymentInfo = $paymentStatusMap[$booking->payment_status] ?? ['Khong xac dinh', 'badge-pending'];
                $outbound = $booking->outboundFlight;
                $return = $booking->returnFlight;
            @endphp

            <div class="card-panel">
                <div class="flex-between">
                    <div>
                        <div class="small-muted">Ma dat ve</div>
                        <strong>{{ $booking->booking_code }}</strong>
                    </div>
                    <div>
                        <span class="badge {{ $statusInfo[1] }}">{{ $statusInfo[0] }}</span>
                    </div>
                </div>

                <div class="grid-2" style="margin-top: 15px;">
                    <div>
                        <div class="section-subtitle">Chieu di</div>
                        <div><strong>{{ $outbound->origin->code ?? '---' }} -> {{ $outbound->destination->code ?? '---' }}</strong></div>
                        <div class="small-muted">
                            {{ $outbound && $outbound->departure_time ? $outbound->departure_time->format('d/m/Y H:i') : '' }}
                            -
                            {{ $outbound && $outbound->arrival_time ? $outbound->arrival_time->format('d/m/Y H:i') : '' }}
                        </div>
                        <div class="small-muted">
                            {{ $outbound->airline->name ?? '---' }}{{ $outbound && $outbound->flight_number ? ' • ' . $outbound->flight_number : '' }}
                        </div>
                    </div>

                    <div>
                        <div class="section-subtitle">Chieu ve</div>
                        @if($return)
                            <div><strong>{{ $return->origin->code ?? '---' }} -> {{ $return->destination->code ?? '---' }}</strong></div>
                            <div class="small-muted">
                                {{ $return && $return->departure_time ? $return->departure_time->format('d/m/Y H:i') : '' }}
                                -
                                {{ $return && $return->arrival_time ? $return->arrival_time->format('d/m/Y H:i') : '' }}
                            </div>
                            <div class="small-muted">
                                {{ $return->airline->name ?? '---' }}{{ $return && $return->flight_number ? ' • ' . $return->flight_number : '' }}
                            </div>
                        @else
                            <div class="muted">Mot chieu</div>
                        @endif
                    </div>
                </div>

                <div class="grid-3" style="margin-top: 15px;">
                    <div>
                        <span class="small-muted">Hang ve</span>
                        <div>{{ $booking->ticket_class ? ucfirst($booking->ticket_class) : 'Economy' }}</div>
                    </div>
                    <div>
                        <span class="small-muted">Thanh toan</span>
                        <div><span class="badge {{ $paymentInfo[1] }}">{{ $paymentInfo[0] }}</span></div>
                    </div>
                    <div>
                        <span class="small-muted">Tong tien</span>
                        <div class="price">{{ number_format($booking->total_amount, 0, ',', '.') }} VND</div>
                    </div>
                </div>

                <div class="small-muted" style="margin-top: 10px;">
                    Dat luc: {{ optional($booking->created_at)->format('d/m/Y H:i') }}
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
