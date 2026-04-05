@extends('layouts.public')

@section('content')
    @include('layouts.search.booking_stepper', ['currentStep' => 1])

    <div class="container">
        <!-- Hidden Search Form for 'Edit search' -->
        <div id="editSearchFormContainer">
            @include('layouts.search.form_search_box', ['airports' => $airports])
        </div>

        @if (!$flights->isEmpty())
            @php
                $firstFlight = $flights->first();
                $origin = $firstFlight->origin;
                $destination = $firstFlight->destination;
                $searchDate = \Carbon\Carbon::parse($step == 'return' ? request('return_date') : request('departure_date', now()));
                $baseParams = request()->except(['ticket_class', 'outbound_flight_id', 'return_flight_id']);
                $dateParamKey = $step == 'return' ? 'return_date' : 'departure_date';
@endphp

            <div class="departing-header">
                <h2>{{ $step == 'return' ? 'Returning flight' : 'Departing flight' }}</h2>
                <div class="route-info">
                    <span>{{ $origin->city }} ({{ $origin->code }}), {{ $origin->country }}</span>
                    <i class="fas fa-plane"></i>
                    <span>{{ $destination->city }} ({{ $destination->code }}), {{ $destination->country }}</span>
                    <span class="muted">• {{ $searchDate->format('D, d M Y') }}</span>
                </div>
            </div>

            <div class="filter-bar">
                <button class="btn-sort"><i class="fas fa-sort-amount-down"></i> Sort by <i class="fas fa-chevron-down"></i></button>
                <button class="btn-filter"><i class="fas fa-filter"></i> Filter <i class="fas fa-chevron-down"></i></button>
            </div>

            <!-- Date Slider Navigation -->
            @include('layouts.search.date_slider', ['searchDate' => $searchDate, 'baseParams' => $baseParams, 'dateParamKey' => $dateParamKey])

            <table class="flight-table">
                <thead>
                    <tr>
                        <th class="details-header">Flight details</th>
                        <th>Economy</th>
                        <th class="business-header">Business</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($flights as $flight)
                        @php
                            $duration = $flight->departure_time->diff($flight->arrival_time);
                            $durationStr = $duration->format('%hh%im');
                        @endphp
                        <tr>
                            <td>
                                <div class="flight-main-info">
                                    <div class="flight-time-box">
                                        <span class="time">{{ $flight->departure_time->format('H:i') }}</span>
                                        <span class="city">{{ $flight->origin->code }}</span>
                                    </div>

                                    <div class="flight-path-viz">
                                        <div class="viz-line">
                                            <i class="fas fa-plane"></i>
                                        </div>
                                        <span class="viz-duration">{{ $durationStr }}</span>
                                        <span class="viz-stops">Non-Stop</span>
                                    </div>

                                    <div class="flight-time-box">
                                        <span class="time">{{ $flight->arrival_time->format('H:i') }}</span>
                                        <span class="city">{{ $flight->destination->code }}</span>
                                    </div>
                                </div>

                                <div class="flight-detail-footer">
                                    <img src="{{ asset('images/airlines/' . Str::slug($flight->airline->name) . '.png') }}" 
                                         alt="{{ $flight->airline->name }}" 
                                         style="width: 20px; height: 20px; flex-shrink: 0;"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/20x20?text=✈️';">
                                    <span>{{ $flight->airline->name }} {{ $flight->flight_number }}</span>
                                    <a href="#" class="more-link" onclick="event.preventDefault(); toggleDropdown('details-{{ $flight->id }}')">More details</a>
                                </div>
                            </td>

                            <td class="fare-col lowest-fare">
                                @if ($flight->economy_available > 0)
                                    @php
                                        $targetRoute = $step == 'outbound' ? 'flights.search' : 'flights.book';
                                        $finalParams = array_merge($baseParams, [
                                            'outbound_flight_id' => $step == 'return' ? $outboundFlightId : $flight->id,
                                            'return_flight_id' => $step == 'return' ? $flight->id : null,
                                            'ticket_class' => 'economy'
                                        ]);
                                    @endphp
                                    <a href="{{ route($targetRoute, $finalParams) }}" class="fare-select-btn">
                                        <div class="fare-radio-ui"></div>
                                        <span class="fare-price-display">{{ number_format($flight->price, 0, ',', '.') }}đ</span>
                                        <span class="fare-seats-left">{{ $flight->economy_available }} Seats left</span>
                                    </a>
                                    <i class="fas fa-tag lowest-fare-tag"></i>
                                @else
                                    <span class="sold-out-text">Sold out</span>
                                @endif
                            </td>

                            <td class="fare-col">
                                @if ($flight->business_available > 0)
                                    @php
                                        $finalParams['ticket_class'] = 'business';
                                    @endphp
                                    <a href="{{ route($targetRoute, $finalParams) }}" class="fare-select-btn">
                                        <div class="fare-radio-ui"></div>
                                        <span class="fare-price-display">{{ number_format($flight->price * 1.5, 0, ',', '.') }}đ</span>
                                        <span class="fare-seats-left">{{ $flight->business_available }} Seats left</span>
                                    </a>
                                @else
                                    <span class="sold-out-text">Sold out</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning" style="margin-top: 50px; text-align: center; padding: 40px; border-radius: 8px;">
                <i class="fas fa-exclamation-circle" style="font-size: 40px; margin-bottom: 20px; display: block; color: #ffc107;"></i>
                <h3>No flights found</h3>
                <p>Sorry, we couldn't find any flights for your selected route and date. Please try a different date or search again.</p>
            </div>
        @endif
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
    </script>
@endsection
