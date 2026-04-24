@extends('layouts.public')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/review_styles.css') }}">
        <link rel="stylesheet" href="{{ asset('css/flight-search.css') }}">
    @endpush

    @include('layouts.search.booking_stepper', ['currentStep' => 3])

    <div class="review-container">
        <div class="review-header">
            <h1>Kiểm tra thông tin đặt vé</h1>
            <div class="order-code">Mã đặt hàng: <span style="color: #0056b3;">#PG-{{ strtoupper(Str::random(5)) }}</span>
            </div>
            <div class="order-note">Mã đặt hàng này chỉ dùng để tham khảo, KHÔNG dùng để làm thủ tục check-in hay lên máy
                bay!</div>
        </div>

        @include('flights.search._summary_panel', [
            'outboundFlight' => $outboundFlight, 
            'returnFlight' => $returnFlight,
            'step' => 'review',
            'hideButton' => true
        ])

        @include('flights.review._passenger_card')

        @include('flights.review._contact_card')

        @include('flights.review._payment_card')

        @include('flights.review._terms_card')

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
                <button type="submit" id="btnContinue" class="btn-continue-review" disabled>Tiếp tục</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('agreeCheckbox');
            const btn = document.getElementById('btnContinue');

            checkbox.addEventListener('change', function () {
                btn.disabled = !this.checked;
            });
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