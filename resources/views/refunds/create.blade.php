@extends('layouts.public')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/refund.css') }}">
@endpush

@section('content')
    <div class="refund-wrapper">
        <div class="refund-card">
            <div class="refund-header">
                <h1>Yêu cầu hoàn tiền</h1>
                <div class="refund-meta">
                    <span>Mã đặt chỗ: <strong>{{ $booking->booking_code }}</strong></span>
                    <span>Hành khách: <strong>{{ $booking->passenger_name }}</strong></span>
                    <span>Email: <strong>{{ $booking->passenger_email }}</strong></span>
                </div>
                <p class="refund-note">Vui lòng điền thông tin để gửi yêu cầu hoàn tiền trực tiếp đến bộ phận hỗ trợ.</p>
            </div>

            @if(session('success_refund'))
                <div class="alert alert-success">{{ session('success_refund') }}</div>
            @endif
            @if(session('error_refund'))
                <div class="alert alert-danger">{{ session('error_refund') }}</div>
            @endif

            <form method="POST" action="{{ route('refunds.store', $booking) }}" class="refund-form">
                @csrf

                <div class="refund-grid">
                    <div class="form-group">
                        <label>Số tiền hoàn ({{ $booking->currency ?? 'VND' }})</label>
                        <input type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount', $booking->total_amount) }}" required>
                        @error('amount')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Phương thức hoàn (tùy chọn)</label>
                        <input type="text" name="method" value="{{ old('method') }}" placeholder="Ví dụ: chuyển khoản, ví điện tử...">
                        @error('method')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Lý do hoàn tiền</label>
                        <select name="reason_type" id="reason_type">
                            <option value="" {{ old('reason_type')=='' ? 'selected' : '' }}>-- Chọn lý do --</option>
                            <option value="cancelled_flight" {{ old('reason_type')=='cancelled_flight' ? 'selected' : '' }}>Hủy chuyến</option>
                            <option value="schedule_change" {{ old('reason_type')=='schedule_change' ? 'selected' : '' }}>Thay đổi lịch</option>
                            <option value="wrong_booking" {{ old('reason_type')=='wrong_booking' ? 'selected' : '' }}>Trùng đặt / sai thông tin</option>
                            <option value="customer_change" {{ old('reason_type')=='customer_change' ? 'selected' : '' }}>Khách thay đổi kế hoạch</option>
                            <option value="other" {{ old('reason_type')=='other' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('reason_type')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group full" id="reason_custom_wrap" style="display: {{ old('reason_type')=='other' || old('reason_custom') ? 'block' : 'none' }};">
                        <label>Nội dung lý do (nếu chọn 'Khác')</label>
                        <textarea name="reason_custom" id="reason_custom" rows="4" placeholder="Nhập lý do hoàn vé cụ thể">{{ old('reason_custom', old('reason')) }}</textarea>
                        @error('reason_custom')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="refund-actions">
                    <button type="submit" class="btn-primary">Gửi yêu cầu hoàn tiền</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
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
