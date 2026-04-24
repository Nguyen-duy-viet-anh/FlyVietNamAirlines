@extends('layouts.public')

@section('content')
    @include('layouts.search.booking_stepper', ['currentStep' => 2])

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/booking_styles.css') }}">
    @endpush

    <div class="booking-container">
        <h1 class="booking-header">Hành khách</h1>

        {{-- Notice Box --}}
        <div class="info-notice">
            <p>
                *Vui lòng đảm bảo rằng quý khách nhập tên của tất cả hành khách chính xác như trên hộ chiếu hoặc giấy tờ tùy
                thân.
                Việc đổi tên sẽ không được phép sau khi quá trình đặt vé hoàn tất. Độ dài tối đa cho HỌ TÊN là 32 ký tự.
                Nếu tên quá dài, vui lòng sử dụng tên viết tắt (ví dụ: Nguyen Duy Viet Anh -> Nguyen D V Anh).
            </p>
        </div>

        <form id="bookingForm" action="{{ route('flights.review') }}" method="POST">
            @csrf
            {{-- Hidden Fields for Booking Context --}}
            <input type="hidden" name="ticket_class" value="{{ $bookingData['ticket_class'] ?? 'economy' }}">
            <input type="hidden" name="flight_type" value="{{ $bookingData['flight_type'] }}">
            <input type="hidden" name="outbound_flight_id" value="{{ $bookingData['outbound_flight_id'] }}">
            <input type="hidden" name="return_flight_id" value="{{ $bookingData['return_flight_id'] ?? '' }}">
            <input type="hidden" name="adult_count" value="{{ $bookingData['adult_count'] }}">
            <input type="hidden" name="child_count" value="{{ $bookingData['child_count'] }}">
            <input type="hidden" name="infant_count" value="{{ $bookingData['infant_count'] }}">
            <input type="hidden" name="total_amount" value="{{ $bookingData['total_amount'] }}">

            @include('flights.passenger._customer_info')

            @include('flights.passenger._contact_info')

            <div class="booking-footer-note">
                <div class="protection-icon">
                    <i class="fas fa-shield-alt text-danger"></i>
                </div>
                <p>Chúng tôi cam kết bảo mật tuyệt đối dữ liệu cá nhân của quý khách</p>
            </div>

            <div class="clearfix">
                <button type="submit" id="btnSubmit" class="btn-submit-booking" disabled>Tiếp tục</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookingForm');
            const btnSubmit = document.getElementById('btnSubmit');
            
            function checkForm() {
                const requiredInputs = form.querySelectorAll('[required]');
                let allFilled = true;
                
                requiredInputs.forEach(input => {
                    if (input.type === 'checkbox') {
                        if (!input.checked) allFilled = false;
                    } else if (input.tagName === 'SELECT') {
                        if (input.value === '') allFilled = false;
                    } else {
                        if (input.value.trim() === '') allFilled = false;
                    }
                });
                
                // Extra check for email confirmation
                const email = form.querySelector('input[name="passenger_email"]');
                const emailConfirm = form.querySelector('input[name="passenger_email_confirm"]');
                const emailError = document.getElementById('emailError');

                if (email && emailConfirm) {
                    if (emailConfirm.value.trim() !== '' && email.value !== emailConfirm.value) {
                        allFilled = false;
                        emailError.style.display = 'block';
                        emailConfirm.style.borderColor = '#D20526';
                    } else {
                        emailError.style.display = 'none';
                        emailConfirm.style.borderColor = '';
                    }
                }

                btnSubmit.disabled = !allFilled;
            }

            form.addEventListener('input', checkForm);
            form.addEventListener('change', checkForm);
            
            // Initial check
            checkForm();
            setTimeout(checkForm, 500);
        });
    </script>
@endsection