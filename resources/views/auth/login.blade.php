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
            <input type="email" name="email" class="form-control" required placeholder="admin@gmail.com">
        </div>

        <div class="form-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control" required placeholder="password">
        </div>

        <button type="submit" class="btn btn-primary btn-full" style="margin-top:25px; font-size:16px;">
            Đăng nhập
        </button>
    </form>
</div>
@endsection