@extends('layouts.public')

@section('content')
    <div class="container">
<h2 class="section-title section-title--blue">
            @if ($step == 'outbound')
                BƯỚC 1: {{ $title }}
            @elseif($step == 'return')
                BƯỚC 2: {{ $title }}
            @else
                {{ $title }}
            @endif
        </h2>

        @if ($step == 'return')
            <div class="panel-muted">
                Bạn đã chọn xong Chiều Đi. Vui lòng chọn tiếp Chiều Về dưới đây.
                <a href="javascript:history.back()" class="danger-text">(Quay lại đổi chiều đi)</a>
            </div>
        @endif

        @if ($flights->isEmpty())
            <div class="alert alert-warning">Rất tiếc, không tìm thấy chuyến bay nào phù hợp.</div>
        @else
            @php
                // Lấy các tham số cũ trên URL để nối vào nút bấm
                $baseParams = request()->except(['ticket_class', 'outbound_flight_id', 'return_flight_id']);
            @endphp

            <div class="flight-list">
                @foreach ($flights as $flight)
                    <div class="card card-inline">

                        <div class="flex-between" style="padding:20px; cursor:pointer; background:#fff;"
                            onclick="toggleDropdown('ticket-class-{{ $flight->id }}')">
                            <div>
                                <h4 style="margin: 0; color: #333;">{{ $flight->airline->name }}
                                    ({{ $flight->flight_number }})</h4>
                                <p style="margin:5px 0 0 0; color:#666;">
                                    Khởi hành: <strong>{{ $flight->departure_time->format('H:i') }}</strong>
                                    → Đến: <strong>{{ $flight->arrival_time->format('H:i') }}</strong>
                                </p>
                            </div>

                            <div class="text-right">
                                <span class="muted small-muted">Giá vé chỉ từ</span>
                                <h3 class="price" style="margin:0;">{{ number_format($flight->price, 0, ',', '.') }}đ</h3>
                                <small class="muted" style="font-weight:bold; display:block; margin-top:8px;">Bấm để chọn vé</small>
                            </div>
                        </div>

                        <div id="ticket-class-{{ $flight->id }}" class="hidden" style="border-top:1px dashed #ccc; padding:20px; background:#f8f9fa;">
                            <h4 style="margin-top:0; margin-bottom:15px; color:#2c3e50;">Chọn hạng ghế cho chuyến bay này:</h4>

                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 15px; background: #fff; border: 1px solid #eee; border-radius: 5px;">
                                <div>
                                    <strong style="font-size:16px;">Phổ thông (Economy)</strong><br>
                                    <small class="muted">Hành lý xách tay 7kg</small><br>
                                    @if ($flight->economy_available <= 10)
                                        <span style="color:#e74c3c; font-size:12px; font-weight:bold;">Chỉ còn {{ $flight->economy_available }} ghế</span>
                                    @else
                                        <span style="color:#27ae60; font-size:12px;">Còn {{ $flight->economy_available }} ghế</span>
                                    @endif
                                </div>
                                <div style="text-align: right;">
                                    <strong style="color:#ff9800; font-size:18px;">{{ number_format($flight->price, 0, ',', '.') }}đ</strong><br>
                                    @if ($step == 'outbound')
                                        <a href="{{ route('flights.search', array_merge($baseParams, ['outbound_flight_id' => $flight->id, 'ticket_class' => 'economy'])) }}"
                                            class="btn"
                                            style="background:#e0e0e0; color:#333; padding:8px 15px; margin-top:5px; text-decoration:none; display:inline-block; border-radius:4px;">Chọn chuyến đi</a>
                                    @else
                                        <a href="{{ route('flights.book', array_merge($baseParams, ['outbound_flight_id' => $step == 'return' ? $outboundFlightId : $flight->id, 'return_flight_id' => $step == 'return' ? $flight->id : null, 'ticket_class' => 'economy'])) }}"
                                            class="btn btn-success"
                                            style="padding: 8px 15px; margin-top: 5px; text-decoration: none; display: inline-block;">Chọn
                                            & Đặt vé</a>
                                    @endif
                                </div>
                            </div>

                            <div
                                style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #fff; border: 1px solid #eee; border-radius: 5px;">
                                <div>
                                    <strong style="color: #d35400; font-size: 16px;">Thương gia (Business)</strong><br>
                                    <small class="muted">Phòng chờ VIP, Hành lý ký gửi 30kg</small><br>
                                    @if ($flight->business_available <= 5)
                                        <span style="color:#e74c3c; font-size:12px; font-weight:bold;">Chỉ còn {{ $flight->business_available }} ghế</span>
                                    @else
                                        <span style="color:#27ae60; font-size:12px;">Còn {{ $flight->business_available }} ghế</span>
                                    @endif
                                </div>
                                <div style="text-align: right;">
                                    <strong
                                        style="color: #ff9800; font-size: 18px;">{{ number_format($flight->price * 1.5, 0, ',', '.') }}đ</strong><br>
                                    @if ($step == 'outbound')
                                        <a href="{{ route('flights.search', array_merge($baseParams, ['outbound_flight_id' => $flight->id, 'ticket_class' => 'business'])) }}"
                                            class="btn btn-primary"
                                            style="padding: 8px 15px; margin-top: 5px; text-decoration: none; display: inline-block;">Chọn
                                            chuyến đi</a>
                                    @else
                                        <a href="{{ route('flights.book', array_merge($baseParams, ['outbound_flight_id' => $step == 'return' ? $outboundFlightId : $flight->id, 'return_flight_id' => $step == 'return' ? $flight->id : null, 'ticket_class' => 'business'])) }}"
                                            class="btn btn-success"
                                            style="padding: 8px 15px; margin-top: 5px; text-decoration: none; display: inline-block;">Chọn
                                            & Đặt vé</a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
