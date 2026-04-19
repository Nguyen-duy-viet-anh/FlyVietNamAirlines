<div class="card">
    <h3 class="admin-title">Đơn vé mới nhất</h3>
    <div class="recent-list">
        @forelse($recentBookings as $booking)
            <div class="recent-item">
                <div class="recent-info">
                    <h4>#{{ $booking->booking_code }}</h4>
                    <small>{{ $booking->passenger_name }} • {{ $booking->created_at->diffForHumans() }}</small>
                </div>
                <div class="recent-amount">
                    {{ number_format($booking->total_amount, 0, ',', '.') }}đ
                </div>
            </div>
        @empty
            <p class="text-muted text-center py-20">Chưa có đơn vé nào.</p>
        @endforelse
    </div>
    <div class="mt-20 text-center">
        <a href="{{ route('admin.bookings.index') }}" class="text-muted text-small text-decoration-none">Xem tất cả đơn vé</a>
    </div>
</div>
