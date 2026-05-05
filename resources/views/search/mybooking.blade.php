@extends('layouts.public')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/mybooking.css') }}">
@endpush

@section('content')
<div class="mybooking-wrapper">
    <div class="mybooking-container">
        <h1 class="mybooking-title">Trạng thái đặt vé</h1>
        <p class="mybooking-subtitle">Kiểm tra trạng thái đặt chỗ của bạn</p>

        <form action="{{ route('booking.lookup') }}" method="POST" class="mybooking-form">
            @csrf
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('success_refund'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success_refund') }}
                </div>
            @endif
            @if(session('error_refund'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error_refund') }}
                </div>
            @endif

            <div class="form-group">
                <label for="booking_code">Mã đặt chỗ<span class="required">*</span></label>
                <input type="text" id="booking_code" name="booking_code" placeholder="Mã đặt chỗ" required value="{{ old('booking_code') }}">
            </div>

            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="Email" required value="{{ old('email') }}">
            </div>

            <div class="refund-section">
                <div class="refund-header">
                    <h3>Yêu cầu hoàn vé</h3>
                    <p>Điền lý do để gửi yêu cầu hoàn vé trực tiếp cho bộ phận hỗ trợ.</p>
                </div>

                <div class="form-group">
                    <label for="reason_type">Lý do hoàn vé</label>
                    <select id="reason_type" name="reason_type">
                        <option value="" {{ old('reason_type')=='' ? 'selected' : '' }}>-- Chọn lý do --</option>
                        <option value="cancelled_flight" {{ old('reason_type')=='cancelled_flight' ? 'selected' : '' }}>Hủy chuyến</option>
                        <option value="schedule_change" {{ old('reason_type')=='schedule_change' ? 'selected' : '' }}>Thay đổi lịch</option>
                        <option value="wrong_booking" {{ old('reason_type')=='wrong_booking' ? 'selected' : '' }}>Trùng đặt / sai thông tin</option>
                        <option value="customer_change" {{ old('reason_type')=='customer_change' ? 'selected' : '' }}>Khách thay đổi kế hoạch</option>
                        <option value="other" {{ old('reason_type')=='other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div class="form-group" id="reason_custom_wrap" style="display: {{ old('reason_type')=='other' || old('reason_custom') ? 'block' : 'none' }};">
                    <label for="reason_custom">Nội dung lý do (nếu chọn 'Khác')</label>
                    <textarea id="reason_custom" name="reason_custom" placeholder="Nhập lý do hoàn vé cụ thể">{{ old('reason_custom', old('refund_reason')) }}</textarea>
                </div>
            </div>

            <div class="info-box">
                <i class="fa-solid fa-circle-info info-icon"></i>
                <p>Lưu ý: Chỉ áp dụng cho các giao dịch đặt vé trên website của chúng tôi!</p>
            </div>

            <div class="form-actions">
                <button type="submit" name="action" value="refund" class="btn-send btn-primary">GỬI YÊU CẦU HOÀN</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var sel = document.getElementById('reason_type');
        var wrap = document.getElementById('reason_custom_wrap');
        if (!sel || !wrap) return;
        sel.addEventListener('change', function () {
            wrap.style.display = this.value === 'other' ? 'block' : 'none';
        });
    });
</script>
@endsection
