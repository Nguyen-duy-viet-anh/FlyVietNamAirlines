<nav class="navbar">
    <div class="logo">
        <a href="/">
            <h2>FlightBooking</h2>
        </a>
    </div>
    <div class="menu">
        <a href="{{ route('destinations.index') }}">Điểm đến</a>
        <a href="{{ route('logout') }}" class="logout-link">Đăng xuất</a>
        <a href="{{ route('login') }}">Đăng nhập</a>
        <a href="/register">Đăng ký</a>
    </div>

</nav>
