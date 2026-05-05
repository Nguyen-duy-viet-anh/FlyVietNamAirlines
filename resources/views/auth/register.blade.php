@extends('layouts.public')

@section('content')
<div class="card" style="max-width: 500px; margin: 50px auto;">
    <h2 class="text-center section-title--blue" style="margin-bottom:20px;">Đăng ký Tài khoản</h2>

    @if($errors->any())
        <div class="warning-box">
            @foreach($errors->all() as $error)
                <p style="margin: 0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register.submit') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Họ và tên</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Nhập họ và tên">
        </div>

        <div class="form-group">
            <label>Email của bạn</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="email@example.com">
        </div>
        
        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Nhập số điện thoại">
        </div>

        <div class="form-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control" required placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)">
        </div>

        <div class="form-group">
            <label>Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control" required placeholder="Nhập lại mật khẩu">
        </div>

        <button type="submit" class="btn btn-primary btn-full" style="margin-top:25px; font-size:16px;">
            Đăng ký
        </button>

        <div class="text-center" style="margin-top: 15px;">
            Đã có tài khoản? <a href="{{ route('login') }}" style="color: #003580; font-weight: bold; text-decoration: none;">Đăng nhập</a>
        </div>
    </form>
</div>
@endsection
