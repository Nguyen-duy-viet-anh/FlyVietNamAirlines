{{-- Outbound Flight Selected Card --}}
@if($outboundFlight && ($step == 'return' || $step == 'one_way' || $returnFlight))
    <div class="selected-flight-card">
        <div class="selection-label">
            <i class="fas fa-check-circle"></i> Chiều đi
        </div>
        
        @php
            // Reset EVERYTHING when re-selecting outbound
            $reselectOutboundParams = request()->except(['outbound_flight_id', 'return_flight_id', 'ticket_class']);
        @endphp
        <a href="{{ route('flights.search', $reselectOutboundParams) }}" class="reselect-link">Chọn lại</a>

        <div class="selected-flight-content">
            <div class="flight-main-info" style="border-right: none; padding: 0;">
                <div class="flight-time-box">
                    <span class="time">{{ $outboundFlight->departure_time->format('H:i') }}</span>
                    <span class="city" style="font-weight: 600;">{{ $outboundFlight->origin->code }}</span>
                    <small class="d-block text-muted">{{ $outboundFlight->origin->city }}</small>
                </div>

                <div class="flight-path-viz">
                    <div class="viz-line" style="margin: 8px 0;">
                        <i class="fas fa-plane"></i>
                    </div>
                    <span class="viz-duration">{{ $outboundFlight->departure_time->diff($outboundFlight->arrival_time)->format('%hh %im') }}</span>
                    <span class="viz-stops">Bay thẳng</span>
                </div>

                <div class="flight-time-box">
                    <span class="time">{{ $outboundFlight->arrival_time->format('H:i') }}</span>
                    <span class="city" style="font-weight: 600;">{{ $outboundFlight->destination->code }}</span>
                    <small class="d-block text-muted">{{ $outboundFlight->destination->city }}</small>
                </div>

                @php
                    $classMap = ['economy' => 'Phổ thông', 'business' => 'Thương gia'];
                @endphp
                <div class="airline-class-info">
                    <div class="airline-info">
                        <div class="airline-logo-box">
                            <img src="{{ asset('images/' . (['vietjet-air' => 'Logo-VietjetAir.jpg', 'bamboo-airways' => 'logo-bamboo-airways.jpg', 'vietnam-airlines' => 'logo-vietnamAirlines.png'][Str::slug($outboundFlight->airline->name)] ?? Str::slug($outboundFlight->airline->name) . '.png')) }}" 
                                 alt="{{ $outboundFlight->airline->name }}" 
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/40x40?text=✈️';">
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 14px; color: #333;">{{ $outboundFlight->airline->name }}</div>
                            <div style="font-size: 12px; color: #777;">{{ $outboundFlight->flight_number }}</div>
                        </div>
                    </div>
                    <div style="font-weight: 700; color: #003366; font-size: 18px; min-width: 90px; text-align: right;">
                        {{ $classMap[request('ticket_class', 'economy')] ?? request('ticket_class') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Return Flight Selected Card --}}
@if($returnFlight)
    <div class="selected-flight-card">
        <div class="selection-label">
            <i class="fas fa-check-circle"></i> Chiều về
        </div>
        
        @php
            // Only reset return selection
            $reselectReturnParams = request()->except(['return_flight_id']);
            $classMap = ['economy' => 'Phổ thông', 'business' => 'Thương gia'];
        @endphp
        <a href="{{ route('flights.search', $reselectReturnParams) }}" class="reselect-link">Chọn lại</a>

        <div class="selected-flight-content">
            <div class="flight-main-info" style="border-right: none; padding: 0;">
                <div class="flight-time-box">
                    <span class="time">{{ $returnFlight->departure_time->format('H:i') }}</span>
                    <span class="city" style="font-weight: 600;">{{ $returnFlight->origin->code }}</span>
                    <small class="d-block text-muted">{{ $returnFlight->origin->city }}</small>
                </div>

                <div class="flight-path-viz">
                    <div class="viz-line" style="margin: 8px 0;">
                        <i class="fas fa-plane" style="transform: rotate(180deg);"></i>
                    </div>
                    <span class="viz-duration">{{ $returnFlight->departure_time->diff($returnFlight->arrival_time)->format('%hh %im') }}</span>
                    <span class="viz-stops">Bay thẳng</span>
                </div>

                <div class="flight-time-box">
                    <span class="time">{{ $returnFlight->arrival_time->format('H:i') }}</span>
                    <span class="city" style="font-weight: 600;">{{ $returnFlight->destination->code }}</span>
                    <small class="d-block text-muted">{{ $returnFlight->destination->city }}</small>
                </div>

                <div class="airline-class-info">
                    <div class="airline-info">
                        <div class="airline-logo-box">
                            <img src="{{ asset('images/' . (['vietjet-air' => 'Logo-VietjetAir.jpg', 'bamboo-airways' => 'logo-bamboo-airways.jpg', 'vietnam-airlines' => 'logo-vietnamAirlines.png'][Str::slug($returnFlight->airline->name)] ?? Str::slug($returnFlight->airline->name) . '.png')) }}" 
                                 alt="{{ $returnFlight->airline->name }}" 
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/40x40?text=✈️';">
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 14px; color: #333;">{{ $returnFlight->airline->name }}</div>
                            <div style="font-size: 12px; color: #777;">{{ $returnFlight->flight_number }}</div>
                        </div>
                    </div>
                    <div style="font-weight: 700; color: #003366; font-size: 18px; min-width: 90px; text-align: right;">
                        {{ $classMap[request('ticket_class', 'economy')] ?? request('ticket_class') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
