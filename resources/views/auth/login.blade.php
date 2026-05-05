@extends('layouts.public')

@section('content')
<div class="card" style="max-width: 500px; margin: 50px auto;">
    <h2 class="text-center section-title--blue" style="margin-bottom:20px;">Đăng nhập Hệ thống</h2>

    @if($errors->any())
        <div class="warning-box">
            @foreach($errors->all() as $error)
                <p style="margin: 0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Email của bạn</label>
            <input type="email" name="email" class="form-control" required placeholder="email@example.com">
        </div>

        <div class="form-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control" required placeholder="Nhap mat khau">
        </div>

        <button type="submit" class="btn btn-primary btn-full" style="margin-top:25px; font-size:16px;">
            Đăng nhập
        </button>

        <div class="text-center" style="margin-top: 15px;">
            Chưa có tài khoản? <a href="{{ route('register') }}" style="color: #003580; font-weight: bold; text-decoration: none;">Đăng ký</a>
        </div>
    </form>
</div>
@endsection