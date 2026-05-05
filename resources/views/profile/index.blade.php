@extends('layouts.public')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="profile-container">
    <div class="profile-card">
        <h2 class="profile-title">
            <i class="fas fa-user-circle"></i> Hồ sơ cá nhân
        </h2>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            
            <div class="form-grid">
                <div>
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input">
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
            </div>

            <div class="password-section">
                <h4 class="password-title">Đổi mật khẩu (Nếu cần)</h4>
                
                <div class="form-group-mb15">
                    <label style="display: block; margin-bottom: 5px; font-size: 14px;">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" class="form-input" style="padding: 8px;">
                </div>

                <div class="password-grid">
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-size: 14px;">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-input" style="padding: 8px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-size: 14px;">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" class="form-input" style="padding: 8px;">
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <a href="{{ route('home') }}" class="btn-cancel">Hủy bỏ</a>
                <button type="submit" class="btn-submit">
                    Cập nhật hồ sơ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
