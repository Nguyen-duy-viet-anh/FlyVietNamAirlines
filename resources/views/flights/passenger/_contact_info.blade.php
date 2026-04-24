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
