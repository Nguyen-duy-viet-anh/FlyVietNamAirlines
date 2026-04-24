@php
    $firstFlight = $flights->first();
    $origin = $firstFlight->origin;
    $destination = $firstFlight->destination;
    $searchDate = \Carbon\Carbon::parse($step == 'return' ? request('return_date') : request('departure_date', now()));
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
    <button class="btn-sort"><i class="fas fa-sort-amount-down"></i> Sắp xếp theo <i class="fas fa-chevron-down"></i></button>
    <button class="btn-filter"><i class="fas fa-filter"></i> Lọc <i class="fas fa-chevron-down"></i></button>
</div>
