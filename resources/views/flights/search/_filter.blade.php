@php
    $firstFlight = $flights->first();
    $origin = $firstFlight->origin;
    $destination = $firstFlight->destination;
    $searchDate = \Carbon\Carbon::parse($step == 'return' ? request('return_date') : request('departure_date', now()));
    $sortBy = request('sort_by', 'price_asc');
    $sortAliases = [
        'time' => 'time_asc',
        'price' => 'price_asc',
    ];
    $sortBy = $sortAliases[$sortBy] ?? $sortBy;
    $sortLabels = [
        'time_asc' => 'Giờ sớm',
        'price_asc' => 'Giá tăng',
    ];
    $sortLabel = $sortLabels[$sortBy] ?? 'Giá tăng';
@endphp

<div class="departing-header">
    <h2>{{ $step == 'return' ? 'Chuyến bay chiều về' : 'Chuyến bay chiều đi' }}</h2>
    <div class="route-info">
        <span>{{ $origin->city }} ({{ $origin->code }}), {{ $origin->country }}</span>
        <i class="fas fa-plane" style="{{ $step == 'return' ? 'transform: rotate(180deg);' : '' }}"></i>
        <span>{{ $destination->city }} ({{ $destination->code }}), {{ $destination->country }}</span>
        <span class="muted">• {{ $searchDate->translatedFormat('D, d M Y') }}</span>
    </div>
</div>

<div class="filter-bar">
    <div class="sort-dropdown" id="sortDropdown">
        <button class="btn-sort" type="button" onclick="toggleSortMenu(event)">
            <div class="sort-btn-content">
                <span class="sort-label">Sắp xếp theo: <strong class="text-dark">{{ $sortLabel }}</strong></span>
            </div>
            <i class="fas fa-chevron-down chevron-icon"></i>
        </button>
        <div class="sort-menu" id="sortMenu" role="menu" aria-label="Sắp xếp theo">
            <a class="sort-option {{ $sortBy === 'time_asc' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort_by' => 'time_asc']) }}">
                <div class="sort-text">Giờ sớm</div>
                @if($sortBy === 'time_asc')<i class="fas fa-check check-icon"></i>@endif
            </a>
            <a class="sort-option {{ $sortBy === 'price_asc' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort_by' => 'price_asc']) }}">
                <div class="sort-text">Giá tăng</div>
                @if($sortBy === 'price_asc')<i class="fas fa-check check-icon"></i>@endif
            </a>
        </div>
    </div>
</div>

<script>
    function toggleSortMenu(e) {
        e.stopPropagation();
        const menu = document.getElementById('sortMenu');
        const dropdown = document.getElementById('sortDropdown');
        
        menu.classList.toggle('show');
        dropdown.classList.toggle('active');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('sortDropdown');
        const menu = document.getElementById('sortMenu');
        if (dropdown && !dropdown.contains(event.target)) {
            menu.classList.remove('show');
            dropdown.classList.remove('active');
        }
    });
</script>
