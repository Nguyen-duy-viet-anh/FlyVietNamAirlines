@extends('layouts.public')

@section('content')
    <div class="grid-2">
        <div class="card">
            <h2>Thông tin cá nhân (Người đặt vé)</h2>
            <hr class="hr-dashed">

            <form action="{{ route('flights.review') }}" method="POST">
                @csrf
                <input type="hidden" name="ticket_class" value="{{ $bookingData['ticket_class'] ?? 'economy' }}">
                <input type="hidden" name="flight_type" value="{{ $bookingData['flight_type'] }}">
                <input type="hidden" name="outbound_flight_id" value="{{ $bookingData['outbound_flight_id'] }}">
                <input type="hidden" name="return_flight_id" value="{{ $bookingData['return_flight_id'] ?? '' }}">
                <input type="hidden" name="adult_count" value="{{ $bookingData['adult_count'] }}">
                <input type="hidden" name="child_count" value="{{ $bookingData['child_count'] }}">
                <input type="hidden" name="infant_count" value="{{ $bookingData['infant_count'] }}">
                <input type="hidden" name="total_amount" value="{{ $bookingData['total_amount'] }}">

                <div class="form-group">
                    <label>Họ và tên (In hoa không dấu)</label>
                    <input type="text" name="passenger_name" class="form-control"
                        value="{{ auth()->check() ? strtoupper(auth()->user()->name) : '' }}" required>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="passenger_email" class="form-control"
                            value="{{ auth()->check() ? auth()->user()->email : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="passenger_phone" class="form-control"
                            value="{{ auth()->check() ? auth()->user()->phone : '' }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Giới tính</label>
                    <select name="passenger_gender" class="form-control" required>
                        <option value="male">Nam</option>
                        <option value="female">Nữ</option>
                        <option value="other">Khác</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ghi chú thêm</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-full btn-large" style="margin-top:10px;">
                    Tiếp tục (Kiểm tra thông tin)
                </button>
            </form>
        </div>

        
    </div>
@endsection
