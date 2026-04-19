<!DOCTYPE html>
<html lang="vi">

<head>
    @include('layouts._head')
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Bảng điều khiển</a>
        <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.index') ? 'active' : '' }}">Quản lý Đơn vé</a>
        <a href="{{ route('admin.airports.index') }}" class="{{ request()->routeIs('admin.airports.index') ? 'active' : '' }}">Quản lý Địa điểm</a>
        
        <div class="sidebar-footer">
            <a href="{{ route('home') }}" class="border-top-dark">Về Trang chủ</a>
            <a href="{{ route('logout') }}" class="sidebar-logout">
                Đăng xuất
            </a>
        </div>
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

    @stack('scripts')
</body>

</html>