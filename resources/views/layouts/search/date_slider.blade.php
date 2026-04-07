<!-- Date Slider Navigation Component -->
<div class="date-slider">
    @for ($i = -3; $i <= 3; $i++)
        @php
            $date = clone $searchDate;
            $date->addDays($i);
            $isActive = $i === 0;
            $urlParams = array_merge($baseParams, [
                $dateParamKey => $date->format('Y-m-d')
            ]);
        @endphp
        <a href="{{ route('flights.search', $urlParams) }}" class="date-item {{ $isActive ? 'active' : '' }}">
            <span>{{ $date->translatedFormat('D, d M') }}</span>
        </a>
    @endfor
</div>
