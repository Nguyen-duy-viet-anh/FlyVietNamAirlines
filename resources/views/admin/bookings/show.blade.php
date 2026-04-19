


@extends('layouts.admin')
@section('title', 'Chi tiết Đơn vé: ' . $booking->booking_code)

@section('content')
<div class="booking-wrapper">
    <!-- TOP HEADER -->
    <div class="booking-top-header">
        <div class="booking-id-group">
            <h1>Đơn hàng #{{ $booking->booking_code }}</h1>
            <div class="booking-date">
                <i class="far fa-calendar-alt"></i> Ngày đặt: {{ $booking->created_at->format('H:i - d/m/Y') }}
            </div>
        </div>
        <div class="booking-status-box">
            <span class="status-badge status-{{ $booking->status }}">
                {{ $booking->status == 'pending' ? 'Chờ xử lý' : ($booking->status == 'confirmed' ? 'Đã xác nhận' : ($booking->status == 'cancelled' ? 'Đã hủy' : 'Hoàn thành')) }}
            </span>
        </div>
    </div>

    <div class="booking-grid">
        <!-- LEFT COLUMN: MAIN INFO -->
        <div class="main-column">
            
            <!-- ITINERARY CARD -->
            <div class="p-card">
                <div class="p-card-header">
                    <i class="fas fa-plane"></i>
                    <h3>Hành trình chuyến bay</h3>
                </div>
                <div class="p-card-body">
                               <!-- Outbound Flight -->
                    <div class="flight-route-container">
                        <span class="route-label">CHUYẾN ĐI</span>
                        
                        <div class="itinerary-row">
                            <!-- Departure -->
                            <div class="node left">
                                <span class="node-time">{{ $booking->outboundFlight->departure_time->format('H:i') }}</span>
                                <span class="node-code">{{ $booking->outboundFlight->origin->code }}</span>
                                <span class="node-city">{{ $booking->outboundFlight->origin->city }}</span>
                            </div>

                            <!-- Connector -->
                            <div class="connector">
                                <div class="details-above">
                                    <img src="{{ asset('images/' . ($booking->outboundFlight->airline->name == 'Vietjet Air' ? 'Logo-VietjetAir.jpg' : ($booking->outboundFlight->airline->name == 'Bamboo Airways' ? 'logo-bamboo-airways.jpg' : 'logo-vietnamAirlines.png'))) }}" 
                                         class="airline-mini-logo" alt="Airline">
                                    <span>{{ $booking->outboundFlight->flight_number }}</span>
                                    <span class="class-tag">{{ $booking->ticket_class }}</span>
                                </div>
                                <div class="connector-line">
                                    <div class="plane-icon"><i class="fas fa-plane"></i></div>
                                </div>
                                <div class="details-below">
                                    @php
                                        $duration = $booking->outboundFlight->departure_time->diff($booking->outboundFlight->arrival_time);
                                        echo $duration->format('%hh %Im');
                                    @endphp
                                    • Bay thẳng
                                </div>
                            </div>

                            <!-- Arrival -->
                            <div class="node right">
                                <span class="node-time">{{ $booking->outboundFlight->arrival_time->format('H:i') }}</span>
                                <span class="node-code">{{ $booking->outboundFlight->destination->code }}</span>
                                <span class="node-city">{{ $booking->outboundFlight->destination->city }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Return Flight (if exists) -->
                    @if($booking->returnFlight)
                    <div class="flight-route-container mt-30 pt-20 border-top-dashed">
                        <span class="route-label text-secondary">CHUYẾN VỀ</span>
                        
                        <div class="itinerary-row">
                            <!-- Departure -->
                            <div class="node left">
                                <span class="node-time">{{ $booking->returnFlight->departure_time->format('H:i') }}</span>
                                <span class="node-code">{{ $booking->returnFlight->origin->code }}</span>
                                <span class="node-city">{{ $booking->returnFlight->origin->city }}</span>
                            </div>

                            <!-- Connector -->
                            <div class="connector">
                                <div class="details-above">
                                    <img src="{{ asset('images/' . ($booking->returnFlight->airline->name == 'Vietjet Air' ? 'Logo-VietjetAir.jpg' : ($booking->returnFlight->airline->name == 'Bamboo Airways' ? 'logo-bamboo-airways.jpg' : 'logo-vietnamAirlines.png'))) }}" 
                                         class="airline-mini-logo" alt="Airline">
                                    <span>{{ $booking->returnFlight->flight_number }}</span>
                                    <span class="class-tag">{{ $booking->ticket_class }}</span>
                                </div>
                                <div class="connector-line">
                                    <div class="plane-icon"><i class="fas fa-plane" style="transform: rotate(180deg);"></i></div>
                                </div>
                                <div class="details-below">
                                    @php
                                        $duration = $booking->returnFlight->departure_time->diff($booking->returnFlight->arrival_time);
                                        echo $duration->format('%hh %Im');
                                    @endphp
                                    • Bay thẳng
                                </div>
                            </div>

                            <!-- Arrival -->
                            <div class="node right">
                                <span class="node-time">{{ $booking->returnFlight->arrival_time->format('H:i') }}</span>
                                <span class="node-code">{{ $booking->returnFlight->destination->code }}</span>
                                <span class="node-city">{{ $booking->returnFlight->destination->city }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SINGLE MASTER PASSENGER TABLE -->
            <div class="p-card">
                <div class="p-card-header">
                    <i class="fas fa-users-cog"></i>
                    <h3>Thông tin Hành khách & Liên hệ</h3>
                </div>
                <div class="p-card-body p-0">
                    
                    @if($booking->passenger_details)
                    <table class="w-100 border-collapse font-size-13">
                        <thead>
                            <tr class="table-header-styled">
                                <th class="table-cell-padding-12 text-align-center w-40 border-right-light text-muted">STT</th>
                                <th class="table-cell-padding-12 text-align-left border-right-light text-muted">Họ và Tên hành khách</th>
                                <th class="table-cell-padding-12 text-align-left border-right-light text-muted">Ngày sinh</th>
                                <th class="table-cell-padding-12 text-align-left border-right-light text-muted">Loại khách</th>
                                <th class="table-cell-padding-12 text-align-left border-right-light text-muted">Danh xưng</th>
                                <th class="table-cell-padding-12 text-align-left border-right-light text-muted">Số điện thoại</th>
                                <th class="table-cell-padding-12 text-align-left text-muted">Email liên hệ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $stt = 1; @endphp
                            @foreach($booking->passenger_details as $type => $paxList)
                                @foreach($paxList as $index => $pax)
                                <tr class="border-bottom-eee bg-light-stripe">
                                    <td class="table-cell-padding-12 text-align-center border-right-light text-gray-999">{{ $stt }}</td>
                                    <td class="table-cell-padding-12 border-right-light text-bold text-dark-blue font-size-14">
                                        {{ strtoupper(($pax['first_name'] ?? '') . ' ' . ($pax['last_name'] ?? '')) }}
                                    </td>
                                    <td class="table-cell-padding-12 border-right-light color-444">
                                        {{ $pax['dob_day'] ?? '' }}/{{ $pax['dob_month'] ?? '' }}/{{ $pax['dob_year'] ?? '' }}
                                    </td>
                                    <td class="table-cell-padding-12 border-right-light">
                                        <span class="pax-type-badge pax-{{ $type }}">
                                            {{ $type == 'adult' ? 'NGƯỜI LỚN' : ($type == 'child' ? 'TRẺ EM' : 'EM BÉ') }}
                                        </span>
                                    </td>
                                    <td class="table-cell-padding-12 border-right-light color-444">
                                        @php
                                            $titleVn = [
                                                'Mr' => 'Ông', 'Ms' => 'Bà', 'Mdm' => 'Bà', 
                                                'Miss' => 'Cô bé', 'Master' => 'Cậu bé'
                                            ];
                                            echo $titleVn[$pax['title'] ?? ''] ?? ($pax['title'] ?? '-');
                                        @endphp
                                    </td>
                                    <td class="table-cell-padding-12 border-right-light text-bold text-blue">
                                        @if($stt == 1) {{ $booking->passenger_phone }} @else <span class="text-gray-ccc">-</span> @endif
                                    </td>
                                    <td class="table-cell-padding-12 text-muted">
                                        @if($stt == 1) {{ $booking->passenger_email }} @else <span class="text-gray-ccc">-</span> @endif
                                    </td>
                                </tr>
                                @php $stt++; @endphp
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="p-30 text-align-center">
                        <div class="alert-warning-styled">
                            <i class="fas fa-exclamation-triangle"></i> Thông tin hành khách chi tiết không khả dụng.
                        </div>
                    </div>
                    @endif

                    @if($booking->notes)
                    <div class="notes-container">
                        <span class="info-label text-bold text-secondary font-size-12 d-block mb-5">GHI CHÚ / YÊU CẦU ĐẶC BIỆT:</span>
                        <div class="notes-content">
                            "{{ $booking->notes }}"
                        </div>
                    </div>
                    @endif
             
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: SIDEBAR -->
        <div class="sidebar-column">
            
            <!-- PAYMENT CARD -->
            <div class="p-card">
                <div class="payment-summary">
                    <span class="font-size-14 opacity-9">TỔNG THANH TOÁN</span>
                    <div class="total-amount">{{ number_format($booking->total_amount, 0, ',', '.') }} VND</div>
                    <div class="payment-status-tag">
                        @if($booking->payment_status == 'paid')
                            <i class="fas fa-check-circle"></i> ĐÃ THANH TOÁN
                        @else
                            <i class="fas fa-clock"></i> CHƯA THANH TOÁN
                        @endif
                    </div>
                </div>
                <div class="transaction-details">
                    <div class="info-item">
                        <span class="info-label">Phương thức</span>
                        <span class="info-value">VNPay (Online)</span>
                    </div>
                    @if($booking->transaction)
                    <div class="info-item">
                        <span class="info-label">Mã giao dịch VNPay</span>
                        <span class="info-value text-secondary">{{ $booking->transaction->transaction_code }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ACTION CARD -->
            <div class="p-card">
                <div class="p-card-header">
                    <i class="fas fa-cog"></i>
                    <h3>Quản lý đơn hàng</h3>
                </div>
                <div class="p-card-body">
                    <form action="{{ route('admin.bookings.update_status', $booking->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="info-label mb-5">Trạng thái vé</label>
                            <select name="status" class="p-select">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Hủy bỏ (Refund)</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            </select>
                        </div>
                        <button type="submit" class="p-btn btn-update">
                            Cập nhật trạng thái
                        </button>
                    </form>
                    
                    <div class="mt-20 text-align-center">
                        <a href="{{ route('admin.bookings.index') }}" class="text-muted text-small text-decoration-none">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
