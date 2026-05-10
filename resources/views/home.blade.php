@extends('layouts.public')

@section('hero')
    <section class="banner-hero">
        <div class="banner-content">
            <h1 class="hero-title">Tìm kiếm chuyến bay</h1>
            <p class="hero-subtitle">Khám phá hàng ngàn chuyến bay giá tốt nhất</p>

            @include('layouts.search.form_search_box', ['airports' => $airports])
        </div>
    </section>

    <script>
        window.airportFullOptions = [
            @foreach($airports as $airport)
                { value: "{{ $airport->id }}", text: "{{ $airport->city }} ({{ $airport->code }})" },
            @endforeach
        ];
    </script>
@section('content')
    @include('layouts.search.popular_offers', ['popularRoutes' => $popularRoutes])
    @include('layouts.search.destination')
    @include('layouts.search.extra_service')
    @include('layouts.search.partners')
@endsection

@section('scripts')
    <script src="{{ asset('js/offers_slider.js') }}"></script>
@endsection
