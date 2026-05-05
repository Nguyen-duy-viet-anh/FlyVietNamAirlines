@extends('layouts.admin')
@section('title', 'Chi tiết Hoàn vé: ' . ($refund->booking->booking_code ?? $refund->id))

@section('content')
    @php $booking = $refund->booking; @endphp

    @if(!$booking)
        <div class="alert-warning-styled">Không tìm thấy đơn đặt vé liên quan.</div>
    @else
    <div class="booking-wrapper">
        <!-- TOP HEADER -->
        <div class="booking-top-header">
            <div class="booking-id-group">
                <h1>Hoàn vé #{{ $booking->booking_code }}</h1>
                <div class="booking-date">
                    Ngày yêu cầu: {{ $refund->created_at->format('H:i - d/m/Y') }}
                </div>
            </div>
            <div class="booking-status-box">
                <span class="status-badge status-{{ $refund->status }}">
                    @if($refund->status === 'requested') Chờ xác nhận
                    @elseif($refund->status === 'processing') Đang xử lý
                    @elseif($refund->status === 'completed') Đã hoàn
                    @elseif($refund->status === 'failed') Thất bại
                    @else Đã hủy
                    @endif
                </span>
            </div>
        </div>

        <div class="booking-grid">
            <!-- LEFT COLUMN: MAIN INFO -->
            <div class="main-column">

                <!-- ITINERARY CARD -->
                <div class="p-card">
                    <div class="p-card-header">
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
                                    <div class="connector-line"></div>
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
                                        <div class="connector-line"></div>
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

                <!-- PASSENGERS -->
                <div class="p-card">
                    <div class="p-card-header">
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
                                                            'Mr' => 'Ông',
                                                            'Ms' => 'Bà',
                                                            'Mdm' => 'Bà',
                                                            'Miss' => 'Cô bé',
                                                            'Master' => 'Cậu bé'
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
                                <div class="alert-warning-styled">Thông tin hành khách chi tiết không khả dụng.</div>
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
                <div class="p-card">
                    <div class="p-card-header">
                        <h3>Thông tin hoàn vé</h3>
                    </div>
                    <div class="p-card-body">
                        <div class="info-item mb-10 d-flex flex-between align-center">
                            <span class="info-label">Trạng thái:</span>
                            <span class="status-badge status-{{ $refund->status }}">
                                @if($refund->status === 'requested') Chờ xác nhận
                                @elseif($refund->status === 'processing') Đang xử lý
                                @elseif($refund->status === 'completed') Đã hoàn
                                @elseif($refund->status === 'failed') Thất bại
                                @else Đã hủy
                                @endif
                            </span>
                        </div>
                        <div class="info-item mb-10">
                            <span class="info-label">Số tiền hoàn:</span>
                            <span class="info-value text-orange-bold">{{ number_format($refund->amount, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="info-item mb-10">
                            <span class="info-label">Lý do:</span>
                            <span class="info-value">{{ $refund->reason ?? '-' }}</span>
                        </div>
                        <div class="info-item mb-10">
                            <span class="info-label">Người gửi:</span>
                            <span class="info-value">{{ $refund->requester->name ?? $booking->passenger_name }}</span>
                        </div>
                        <div class="info-item mb-10">
                            <span class="info-label">Xử lý bởi:</span>
                            <span class="info-value">{{ $refund->processor->name ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Xử lý lúc:</span>
                            <span class="info-value">{{ $refund->processed_at ? $refund->processed_at->format('H:i - d/m/Y') : '-' }}</span>
                        </div>

                        @if($refund->status !== 'completed')
                            <form action="{{ route('admin.refunds.update_status', $refund->id) }}" method="POST" style="margin-top: 15px;">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success" style="width: 100%;">Xác nhận hoàn vé</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="p-card">
                    <div class="p-card-header">
                        <h3>Chi tiết thanh toán</h3>
                    </div>
                    <div class="p-card-body">
                        <div class="price-row d-flex flex-between align-center">
                            <span class="text-bold">TỔNG CỘNG</span>
                            <span style="font-size: 20px; font-weight: 800; color: #d84a1d;">
                                {{ number_format($booking->total_amount, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <div class="mt-20 pt-20 border-top-dashed">
                            <div class="info-item mb-10">
                                <span class="info-label">Phương thức:</span>
                                <span class="info-value">VNPay (Thanh toán Online)</span>
                            </div>
                            <div class="info-item mb-10 d-flex flex-between align-center">
                                <span class="info-label">Trạng thái:</span>
                                @if($booking->payment_status == 'paid')
                                    <span class="badge badge-confirmed">ĐÃ THANH TOÁN</span>
                                @elseif($booking->payment_status == 'refunded')
                                    <span class="badge badge-cancelled">ĐÃ HOÀN TIỀN</span>
                                @else
                                    <span class="badge badge-pending">CHƯA THANH TOÁN</span>
                                @endif
                            </div>
                            @if($booking->transaction)
                                <div class="info-item">
                                    <span class="info-label">Mã giao dịch:</span>
                                    <code class="text-danger" style="background: #fff5f5; padding: 2px 4px; border-radius: 3px;">{{ $booking->transaction->transaction_code }}</code>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
