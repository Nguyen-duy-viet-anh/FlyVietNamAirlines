<!DOCTYPE html>
<html lang="vi">

<head>
    @include('layouts._head')
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Bảng điều khiển</a>
        @can('manage-bookings')
            <a href="{{ route('admin.bookings.index') }}" class="{{ request()->is('admin/bookings*') ? 'active' : '' }}">Quản lý Đơn vé</a>
            <a href="{{ route('admin.refunds.index') }}" class="{{ request()->is('admin/refunds*') ? 'active' : '' }}">Quản lý Hoàn vé</a>
        @endcan
        <a href="{{ route('admin.flights.index') }}" class="{{ request()->is('admin/flights*') ? 'active' : '' }}">Quản lý Chuyến bay</a>
        @can('manage-airports')
            <a href="{{ route('admin.airports.index') }}" class="{{ request()->is('admin/airports*') ? 'active' : '' }}">Quản lý Địa điểm</a>
        @endcan
        <a href="{{ route('admin.users.index') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">Quản lý Tài khoản</a>
        
        <div class="sidebar-footer">
            <a href="{{ route('home') }}" class="border-top-dark">Về Trang chủ</a>
            <a href="{{ route('logout') }}" class="sidebar-logout" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                Đăng xuất
            </a>
            <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
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