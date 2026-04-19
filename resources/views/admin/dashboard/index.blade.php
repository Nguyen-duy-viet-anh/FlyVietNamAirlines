@extends('layouts.admin')

@section('title', 'Bảng điều khiển Admin')

@section('content')

    <div class="quick-welcome">
        <h3>Xin chào, {{ auth()->user()->name }}!</h3>
        <p>Chào mừng bạn quay trở lại hệ thống quản lý bay FlyVietNam. Chúc bạn một ngày làm việc hiệu quả!</p>
    </div>
    @include('admin.dashboard._stats')
    <div class="dash-grid">
        <!-- Cột trái: Biểu đồ -->
        <div class="dash-main">
            @include('admin.dashboard._chart')
        </div>

        <!-- Cột phải: Đơn vé mới & Chào mừng -->
        <div class="dash-sidebar">


            @include('admin.dashboard._recent_bookings')
        </div>
    </div>

@endsection