@extends('layouts.public')

@section('content')
    @include('layouts.search.booking_stepper', ['currentStep' => 1])

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/flight-search.css') }}">
@endpush

    <div class="container">
        <!-- Hidden Search Form for 'Edit search' -->
        <div id="editSearchFormContainer">
            @include('layouts.search.form_search_box', ['airports' => $airports])
        </div>

        {{-- Selected Flight Display (Top) --}}
        @include('flights.search._selected_flights')

        @php
            $isOneWay = request('flight_type') == 'one_way';
            $isRoundTrip = request('flight_type') == 'round_trip';
            
            // Ẩn danh sách nếu:
            // 1. Nếu là 1 chiều và đã chọn xong outbound
            // 2. Nếu là khứ hồi và đã chọn xong cả 2 chiều
            $isSelectionComplete = ($isOneWay && $outboundFlight) || ($isRoundTrip && $outboundFlight && $returnFlight);
        @endphp

        {{-- Main Flight Results List (Hide if selection is complete) --}}
        @if (!$flights->isEmpty() && !$isSelectionComplete)
            
            {{-- Header (Route, Sor/Filter) --}}
            @include('flights.search._filter')

            {{-- Table with Results --}}
            @include('flights.search._flight_list')

        @elseif($flights->isEmpty() && !($outboundFlight && $returnFlight))
            
            {{-- Empty Results Alert --}}
            @include('flights.search._no_results')

        @endif

        {{-- Bottom Summary Panel (Sticky) --}}
        @include('flights.search._summary_panel')

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnEditSearch = document.getElementById('btnEditSearch');
            const editFormContainer = document.getElementById('editSearchFormContainer');

            if (btnEditSearch && editFormContainer) {
                btnEditSearch.addEventListener('click', function(e) {
                    e.preventDefault();
                    editFormContainer.classList.toggle('show-edit-form');
                    
                    if (editFormContainer.classList.contains('show-edit-form')) {
                        editFormContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            }
        });

        function toggleDropdown(id) {
            const el = document.getElementById(id);
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }

        function toggleDropdownContent(id) {
            const el = document.getElementById(id);
            el.classList.toggle('show');
            const header = el.previousElementSibling;
            const icon = header.querySelector('i');
            if (el.classList.contains('show')) {
                icon.classList.remove('fa-caret-down');
                icon.classList.add('fa-caret-up');
            } else {
                icon.classList.remove('fa-caret-up');
                icon.classList.add('fa-caret-down');
            }
        }
    </script>
@endsection
