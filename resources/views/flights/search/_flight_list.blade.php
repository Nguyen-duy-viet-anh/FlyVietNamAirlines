@php
    $searchDate = \Carbon\Carbon::parse($step == 'return' ? request('return_date') : request('departure_date', now()));
    
    // Nếu đang chọn chiều về, phải giữ lại outbound_flight_id trong link chuyển ngày
    $excludeParams = ['ticket_class', 'return_flight_id'];
    if ($step != 'return') {
        $excludeParams[] = 'outbound_flight_id';
    }
    
    $baseParams = request()->except($excludeParams);
    $dateParamKey = $step == 'return' ? 'return_date' : 'departure_date';
@endphp

<!-- Date Slider Navigation -->
@include('layouts.search.date_slider', ['searchDate' => $searchDate, 'baseParams' => $baseParams, 'dateParamKey' => $dateParamKey])

<table class="flight-table">
    <thead>
        <tr>
            <th class="details-header">Thông tin chuyến bay</th>
            <th>Phổ thông</th>
            <th class="business-header">Thương gia</th>
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
                                <i class="fas fa-plane {{ $step == 'return' ? 'icon-return' : '' }}"></i>
                            </div>
                            <span class="viz-duration">{{ $durationStr }}</span>
                            <span class="viz-stops">Bay thẳng</span>
                        </div>

                        <div class="flight-time-box">
                            <span class="time">{{ $flight->arrival_time->format('H:i') }}</span>
                            <span class="city">{{ $flight->destination->code }}</span>
                        </div>
                    </div>

                    <div class="flight-detail-footer">
                        <img src="{{ asset('images/' . (['vietjet-air' => 'Logo-VietjetAir.jpg', 'bamboo-airways' => 'logo-bamboo-airways.jpg', 'vietnam-airlines' => 'logo-vietnamAirlines.png'][Str::slug($flight->airline->name)] ?? Str::slug($flight->airline->name) . '.png')) }}" 
                             alt="{{ $flight->airline->name }}" 
                             class="airline-logo-xs">
                        <span>{{ $flight->airline->name }} {{ $flight->flight_number }}</span>
                    </div>
                </td>

                <td class="fare-col lowest-fare">
                    @if ($flight->economy_available > 0)
                        @php
                            $targetRoute = 'flights.search';
                            $finalParams = array_merge($baseParams, [
                                'outbound_flight_id' => $step == 'return' ? $outboundFlightId : $flight->id,
                                'return_flight_id' => $step == 'return' ? $flight->id : null,
                                'ticket_class' => 'economy'
                            ]);
                        @endphp
                        <a href="{{ route($targetRoute, $finalParams) }}" class="fare-select-btn">
                            <div class="fare-radio-ui"></div>
                            <span class="fare-price-display">{{ number_format($flight->price, 0, ',', '.') }}đ</span>
                            <span class="fare-seats-left">Còn {{ $flight->economy_available }} chỗ</span>
                        </a>
                        <i class="fas fa-tag lowest-fare-tag"></i>
                    @else
                        <span class="sold-out-text">Hết chỗ</span>
                    @endif
                </td>

                <td class="fare-col">
                    @if ($flight->business_available > 0)
                        @php
                            $finalParams = array_merge($baseParams, [
                                'outbound_flight_id' => $step == 'return' ? $outboundFlightId : $flight->id,
                                'return_flight_id' => $step == 'return' ? $flight->id : null,
                                'ticket_class' => 'business'
                            ]);
                        @endphp
                        <a href="{{ route($targetRoute, $finalParams) }}" class="fare-select-btn">
                            <div class="fare-radio-ui"></div>
                            <span class="fare-price-display">{{ number_format($flight->price * 1.5, 0, ',', '.') }}đ</span>
                            <span class="fare-seats-left">Còn {{ $flight->business_available }} chỗ</span>
                        </a>
                    @else
                        <span class="sold-out-text">Hết chỗ</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
