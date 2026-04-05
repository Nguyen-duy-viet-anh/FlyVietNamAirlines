<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - FlightBooking</title>
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="{{ route('admin.bookings.index') }}">Quản lý Đơn vé</a>
        
        <a href="{{ route('logout') }}" style="color: #ff6b6b; border-top: 1px solid #34495e; margin-top: 20px;">
            Đăng xuất
        </a>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>@yield('title', 'Bảng điều khiển')</h2>
            <div>
                Xin chào, <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
            </div>
        </div>
        
        @yield('content')
    </div>
</body>
</html>