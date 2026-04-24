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
