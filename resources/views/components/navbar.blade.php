<nav class="navbar">
    <div class="logo">
        <a href="/">
            <h2>FlightBooking</h2>
        </a>
    </div>
    <div class="menu">
        <a href="{{ route('destinations.index') }}">Điểm đến</a>
        @auth
            <a href="{{ route('logout') }}" class="logout-link" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Đăng xuất
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}">Đăng nhập</a>
            <a href="/register">Đăng ký</a>
        @endauth
    </div>

</nav>
