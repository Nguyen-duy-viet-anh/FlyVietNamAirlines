<style>
.lookup-dropdown {
    position: relative;
    display: inline-block;
}
.lookup-dropdown .dropdown-toggle {
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.lookup-dropdown .dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    min-width: 180px;
    background: #fff;
    border: 1px solid #e3e8ef;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    border-radius: 6px;
    padding: 8px 0;
    z-index: 50;
}
.lookup-dropdown:hover .dropdown-menu {
    display: block;
}
.lookup-dropdown .dropdown-item {
    display: block;
    padding: 8px 14px;
    color: #2c3e50;
    text-decoration: none;
    font-size: 14px;
}
.lookup-dropdown .dropdown-item:hover {
    background: #f5f7fb;
}
</style>
<nav class="navbar">
    <div class="logo">
        <a href="/">
            <h2>FlightBooking</h2>
        </a>
    </div>
    <div class="menu">
        <a href="{{ route('destinations.index') }}">Điểm đến</a>
        
        <div class="lookup-dropdown">
            <a href="#" class="dropdown-toggle" onclick="event.preventDefault();">
                Quản lí vé
                <i class="fas fa-chevron-down" style="font-size: 10px;"></i>
            </a>
            <div class="dropdown-menu">
                @auth
                    <a href="{{ route('my.bookings') }}" class="dropdown-item">Vé của tôi</a>
                @endauth
                <a href="{{ route('booking.mybooking') }}" class="dropdown-item">Tra cứu vé</a>
                <a href="{{ route('booking.mybooking') }}" class="dropdown-item">Hoàn tiền vé</a>
            </div>
        </div>
        
        @auth
            <div class="lookup-dropdown">
                <a href="#" class="dropdown-toggle" onclick="event.preventDefault();">
                    Xin chào, {{ auth()->user()->name }}
                    <i class="fas fa-chevron-down" style="font-size: 10px;"></i>
                </a>
                <div class="dropdown-menu">
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin' || (int)auth()->user()->role === 0 || (int)auth()->user()->role === 1 || (int)auth()->user()->role === 2)
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">Trang quản trị</a>
                    @endif
                    <a href="{{ route('profile.index') }}" class="dropdown-item">Thông tin cá nhân</a>
                    <a href="{{ route('my.bookings') }}" class="dropdown-item">Lịch sử đặt vé</a>
                    <hr style="margin: 4px 0; border: none; border-top: 1px solid #eee;">
                    <a href="{{ route('logout') }}" class="dropdown-item" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Đăng xuất
                    </a>
                </div>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}">Đăng nhập</a>
            <a href="{{ route('register') }}">Đăng ký</a>
        @endauth
    </div>

</nav>
