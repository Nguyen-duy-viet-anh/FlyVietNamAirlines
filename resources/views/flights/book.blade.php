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

            {{-- Adult Sections (>= 18 years) --}}
            @for ($i = 1; $i <= (int) ($bookingData['adult_count'] ?? 1); $i++)
                <div class="passenger-section">
                    <div class="section-label">Người lớn {{ $i }}</div>
                    <div class="section-body">
                        <div class="form-group-custom w-30">
                            <label>Danh xưng</label>
                            <select name="passengers[adult][{{ $i }}][title]" class="input-custom select-custom">
                                <option value="Mr">Ông</option>
                                <option value="Ms">Bà</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group-custom">
                                <label>Tên <span class="required">*</span></label>
                                <input type="text" name="passengers[adult][{{ $i }}][first_name]" class="input-custom"
                                    placeholder="Tên đệm" required>
                            </div>
                            <div class="form-group-custom">
                                <label>Họ <span class="required">*</span></label>
                                <input type="text" name="passengers[adult][{{ $i }}][last_name]" class="input-custom"
                                    placeholder="Họ" required>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label>Ngày sinh</label>
                            <div class="date-row">
                                <select name="passengers[adult][{{ $i }}][dob_day]" class="input-custom select-custom" required>
                                    <option value="">Ngày</option>
                                    @for($d = 1; $d <= 31; $d++) <option value="{{ $d }}">{{ $d }}</option> @endfor
                                </select>
                                <select name="passengers[adult][{{ $i }}][dob_month]" class="input-custom select-custom" required>
                                    <option value="">Tháng</option>
                                    @for($m = 1; $m <= 12; $m++) <option value="{{ $m }}">Tháng {{ $m }}</option> @endfor
                                </select>
                                <select name="passengers[adult][{{ $i }}][dob_year]" class="input-custom select-custom" required>
                                    <option value="">Năm</option>
                                    @for($y = date('Y') - 18; $y >= date('Y') - 100; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option> 
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor

            {{-- Child Sections (> 3 and < 18 years) --}}
            @for ($i = 1; $i <= (int) ($bookingData['child_count'] ?? 0); $i++)
                <div class="passenger-section">
                    <div class="section-label">Trẻ em {{ $i }}</div>
                    <div class="section-body">
                        <div class="form-group-custom w-30">
                            <label>Danh xưng</label>
                            <select name="passengers[child][{{ $i }}][title]" class="input-custom select-custom">
                                <option value="Master">Cậu bé (Master)</option>
                                <option value="Miss">Cô bé (Miss)</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group-custom">
                                <label>Tên <span class="required">*</span></label>
                                <input type="text" name="passengers[child][{{ $i }}][first_name]" class="input-custom"
                                    placeholder="Tên đệm" required>
                            </div>
                            <div class="form-group-custom">
                                <label>Họ <span class="required">*</span></label>
                                <input type="text" name="passengers[child][{{ $i }}][last_name]" class="input-custom"
                                    placeholder="Họ" required>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label>Ngày sinh <span class="required">*</span> (> 3 tuổi)</label>
                            <div class="date-row">
                                <select name="passengers[child][{{ $i }}][dob_day]" class="input-custom select-custom" required>
                                    <option value="">Ngày</option>
                                    @for($d = 1; $d <= 31; $d++) <option value="{{ $d }}">{{ $d }}</option> @endfor
                                </select>
                                <select name="passengers[child][{{ $i }}][dob_month]" class="input-custom select-custom" required>
                                    <option value="">Tháng</option>
                                    @for($m = 1; $m <= 12; $m++) <option value="{{ $m }}">Tháng {{ $m }}</option> @endfor
                                </select>
                                <select name="passengers[child][{{ $i }}][dob_year]" class="input-custom select-custom" required>
                                    <option value="">Năm</option>
                                    @for($y = date('Y') - 3 - 1; $y >= date('Y') - 17; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option> 
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor

            {{-- Infant Sections (<= 3 years) --}}
            @for ($i = 1; $i <= (int) ($bookingData['infant_count'] ?? 0); $i++)
                <div class="passenger-section">
                    <div class="section-label">Sơ sinh {{ $i }}</div>
                    <div class="section-body">
                        <div class="form-group-custom w-30">
                            <label>Danh xưng</label>
                            <select name="passengers[infant][{{ $i }}][title]" class="input-custom select-custom">
                                <option value="Master">Bé trai</option>
                                <option value="Miss">Bé gái</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group-custom">
                                <label>Tên <span class="required">*</span></label>
                                <input type="text" name="passengers[infant][{{ $i }}][first_name]" class="input-custom"
                                    placeholder="Tên đệm" required>
                            </div>
                            <div class="form-group-custom">
                                <label>Họ <span class="required">*</span></label>
                                <input type="text" name="passengers[infant][{{ $i }}][last_name]" class="input-custom"
                                    placeholder="Họ" required>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label>Ngày sinh <span class="required">*</span> (<= 3 tuổi)</label>
                            <div class="date-row">
                                <select name="passengers[infant][{{ $i }}][dob_day]" class="input-custom select-custom" required>
                                    <option value="">Ngày</option>
                                    @for($d = 1; $d <= 31; $d++) <option value="{{ $d }}">{{ $d }}</option> @endfor
                                </select>
                                <select name="passengers[infant][{{ $i }}][dob_month]" class="input-custom select-custom" required>
                                    <option value="">Tháng</option>
                                    @for($m = 1; $m <= 12; $m++) <option value="{{ $m }}">Tháng {{ $m }}</option> @endfor
                                </select>
                                <select name="passengers[infant][{{ $i }}][dob_year]" class="input-custom select-custom" required>
                                    <option value="">Năm</option>
                                    @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option> 
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor

            {{-- Contact Information --}}
            <div class="passenger-section">
                <div class="section-label">Thông tin liên hệ</div>
                <div class="section-body">
                    <div class="form-row">
                        <div class="form-group-custom">
                            <label>Mã quốc gia</label>
                            <select name="passenger_country_code" class="input-custom select-custom">
                                <option value="+84">Việt Nam (+84)</option>
                                <option value="+66">Thái Lan (+66)</option>
                                <option value="+1">Hoa Kỳ (+1)</option>
                            </select>
                        </div>
                        <div class="form-group-custom">
                            <label>Số điện thoại <span class="required">*</span></label>
                            <input type="text" name="passenger_phone" class="input-custom" placeholder="Số điện thoại"
                                value="{{ auth()->check() ? auth()->user()->phone : '' }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-custom">
                            <label>Email của bạn <span class="required">*</span></label>
                            <input type="email" name="passenger_email" class="input-custom" placeholder="Email của bạn"
                                value="{{ auth()->check() ? auth()->user()->email : '' }}" required>
                        </div>
                        <div class="form-group-custom">
                            <label>Xác nhận địa chỉ Email <span class="required">*</span></label>
                            <input type="email" name="passenger_email_confirm" class="input-custom"
                                placeholder="Xác nhận Email" value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                required>
                            <div id="emailError" style="color: #D20526; font-size: 12px; margin-top: 5px; display: none;">Email không khớp, vui lòng kiểm tra lại.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-footer-note">
                <div class="protection-icon">
                    <i class="fas fa-shield-alt" style="color: #D20526;"></i>
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