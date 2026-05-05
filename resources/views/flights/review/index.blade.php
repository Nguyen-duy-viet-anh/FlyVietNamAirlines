@extends('layouts.public')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/review_styles.css') }}">
        <link rel="stylesheet" href="{{ asset('css/flight-search.css') }}">
    @endpush

    @if(!isset($isLookup) || !$isLookup)
        @include('layouts.search.booking_stepper', ['currentStep' => 3])
    @endif

    <div class="review-container">
        <div class="review-header">
            @if(isset($isLookup) && $isLookup)
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1>Chi tiết thông tin vé</h1>
                        <div class="order-code">Mã đặt chỗ (PNR): <span style="color: #0056b3;">{{ $booking->booking_code }}</span></div>
                    </div>
                    <div class="booking-status-badge status-{{ $booking->status }}" style="padding: 10px 20px; border-radius: 8px; font-weight: bold; background: #e3f2fd; color: #1976d2;">
                        @php
                            $statusLabels = [
                                'pending' => 'Chờ thanh toán',
                                'confirmed' => 'Đã xác nhận',
                                'completed' => 'Đã hoàn tất',
                                'cancelled' => 'Đã hủy',
                                'refunded' => 'Đã hoàn tiền'
                            ];
                            $statusColors = [
                                'pending' => ['bg' => '#fff3e0', 'color' => '#f57c00'],
                                'confirmed' => ['bg' => '#e8f5e9', 'color' => '#388e3c'],
                                'completed' => ['bg' => '#e3f2fd', 'color' => '#1976d2'],
                                'cancelled' => ['bg' => '#ffebee', 'color' => '#d32f2f'],
                                'refunded' => ['bg' => '#f3e5f5', 'color' => '#7b1fa2']
                            ];
                            $style = $statusColors[$booking->status] ?? ['bg' => '#eee', 'color' => '#333'];
                        @endphp
                        <span style="background: {{ $style['bg'] }}; color: {{ $style['color'] }}; padding: 8px 15px; border-radius: 5px;">
                            TRẠNG THÁI: {{ strtoupper($statusLabels[$booking->status] ?? $booking->status) }}
                        </span>
                    </div>
                </div>
            @else
                <h1>Kiểm tra thông tin đặt vé</h1>
                <div class="order-code">Mã đặt hàng: <span style="color: #0056b3;">#PG-{{ strtoupper(Str::random(5)) }}</span></div>
                <div class="order-note">Mã đặt hàng này chỉ dùng để tham khảo, KHÔNG dùng để làm thủ tục check-in hay lên máy bay!</div>
            @endif
        </div>

        {{-- Refund button removed from review page per request --}}

        @include('flights.search._summary_panel', [
            'outboundFlight' => $outboundFlight, 
            'returnFlight' => $returnFlight,
            'step' => 'review',
            'hideButton' => true
        ])

        @include('flights.review._passenger_card')

        @include('flights.review._contact_card')

        @if(!isset($isLookup) || !$isLookup)
            @include('flights.review._payment_card')
            
            @include('flights.review._terms_card')
        @endif

        <form action="{{ route('flights.payment') }}" method="POST" id="paymentForm">
            @csrf
            {{-- Correctly pass all passenger and booking data through hidden fields --}}
            @php
                function renderInputs($data, $prefix = '') {
                    foreach ($data as $key => $value) {
                        $name = $prefix ? $prefix . '[' . $key . ']' : $key;
                        if (is_array($value)) {
                            renderInputs($value, $name);
                        } else {
                            echo '<input type="hidden" name="' . $name . '" value="' . e($value) . '">';
                        }
                    }
                }
                renderInputs($passengerData);
            @endphp

            <div class="clearfix" style="margin-top: 30px; margin-bottom: 50px;">
                @if(!isset($isLookup) || !$isLookup)
                    <button type="submit" id="btnContinue" class="btn-continue-review" disabled>Tiếp tục</button>
                @endif
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('agreeCheckbox');
            const btn = document.getElementById('btnContinue');

            if (checkbox && btn) {
                checkbox.addEventListener('change', function () {
                    btn.disabled = !this.checked;
                });
            }
        });

        function toggleDropdownContent(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.toggle('show');
            const header = el.previousElementSibling;
            const icon = header.querySelector('i');
            if (icon) {
                if (el.classList.contains('show')) {
                    icon.classList.remove('fa-caret-down');
                    icon.classList.add('fa-caret-up');
                } else {
                    icon.classList.remove('fa-caret-up');
                    icon.classList.add('fa-caret-down');
                }
            }
        }
    </script>
@endsection