@extends('layouts.public')

@section('hero')
    <section class="banner-hero">
        <div class="banner-content">
            <h1 class="hero-title">Tìm kiếm chuyến bay</h1>
            <p class="hero-subtitle">Khám phá hàng ngàn chuyến bay giá tốt nhất</p>

            <x-form_search_box :airports="$airports" />
        </div>
    </section>

    {{-- Script để hỗ trợ logic JavaScript (Airport Swap/Exclusion) --}}
    <script>
        window.airportFullOptions = [
            @foreach($airports as $airport)
                { value: "{{ $airport->id }}", text: "{{ $airport->city }} ({{ $airport->code }})" },
            @endforeach
        ];
    </script>
@endsection
