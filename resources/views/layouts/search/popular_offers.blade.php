@props(['popularRoutes'])

<div class="offers-section">
    <div class="container-fluid">
        <div class="section-header">
            <h2>Hành trình hot được quan tâm nhiều</h2>
            <div class="underline"></div>
        </div>

        <div class="carousel-wrapper">
            <button class="nav-btn prev">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="carousel-container">
                <div class="carousel-track">
                    @foreach($popularRoutes as $route)
                        @php
                            $cityLower = strtolower(Str::slug($route->destination->city));
                            $imagePath = asset('images/destinations/' . $cityLower . '.png');
                            // Fallback if image doesn't exist
                            if ($cityLower == 'ha-noi') $imagePath = asset('images/destinations/hanoi.png');
                            if ($cityLower == 'ho-chi-minh') $imagePath = asset('images/destinations/saigon.png');
                            if ($cityLower == 'da-nang') $imagePath = asset('images/destinations/danang.png');
                        @endphp
                        <div class="offer-card" 
                             data-origin-id="{{ $route->origin_id }}" 
                             data-destination-id="{{ $route->destination_id }}"
                             data-flight-type="{{ $route->trip_type }}"
                             style="cursor: pointer;">
                            <div class="offer-image" style="background-image: url('{{ $imagePath }}'), url('{{ asset('images/banner-bg.jpg') }}');"></div>
                            <div class="offer-details">
                                <h3>{{ $route->origin->city }} to {{ $route->destination->city }}</h3>
                                <div class="offer-meta">
                                    <span><i class="fas fa-plane"></i> {{ $route->trip_type == 'one_way' ? 'One Way' : 'Round Trip' }}</span>
                                </div>
                                <div class="offer-price">
                                    <span>From</span>
                                    <span class="price-val">VND {{ number_format($route->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button class="nav-btn next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>
