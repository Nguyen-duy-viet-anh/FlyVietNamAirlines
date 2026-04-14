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

        {{-- Outbound Flight Selected Card --}}
        @if(($step == 'return' || $returnFlight) && $outboundFlight)
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

        {{-- Main Flight Results List (Hide if both selected for review) --}}
        @if (!$flights->isEmpty() && !($outboundFlight && $returnFlight))
            @php
                $firstFlight = $flights->first();
                $origin = $firstFlight->origin;
                $destination = $firstFlight->destination;
                $searchDate = \Carbon\Carbon::parse($step == 'return' ? request('return_date') : request('departure_date', now()));
                $baseParams = request()->except(['ticket_class', 'outbound_flight_id', 'return_flight_id']);
                $dateParamKey = $step == 'return' ? 'return_date' : 'departure_date';
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
                                            <i class="fas fa-plane" style="{{ $step == 'return' ? 'transform: rotate(180deg);' : '' }}"></i>
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
                                         style="width: 20px; height: 20px; flex-shrink: 0; object-fit: contain;"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/20x20?text=✈️';">
                                    <span>{{ $flight->airline->name }} {{ $flight->flight_number }}</span>
                                    <a href="#" class="more-link" onclick="event.preventDefault(); toggleDropdown('details-{{ $flight->id }}')">Chi tiết</a>
                                </div>
                            </td>

                            <td class="fare-col lowest-fare">
                                @if ($flight->economy_available > 0)
                                    @php
                                        // STAY ON SEARCH IF ROUND TRIP and select return
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
        @elseif($flights->isEmpty() && !($outboundFlight && $returnFlight))
            {{-- Search Alert section --}}
            <div class="alert alert-warning" style="margin-top: 50px; text-align: center; padding: 40px; border-radius: 8px; border: 1px dashed #f05a28; background: #fff5f2;">
                <i class="fas fa-exclamation-triangle" style="font-size: 40px; margin-bottom: 20px; display: block; color: #f05a28;"></i>
                
                @if(isset($noReturnAvailable) && $noReturnAvailable)
                    <h3 style="color: #d84a1d;">Không có chuyến bay chiều về khả dụng</h3>
                    <p style="font-size: 16px; color: #666; max-width: 600px; margin: 0 auto;">
                        Chúng tôi tìm thấy chuyến bay chiều đi, nhưng <strong>không có chiều về</strong> khả dụng cho ngày bạn chọn ({{ \Carbon\Carbon::parse(request('return_date'))->format('d/m/Y') }}). 
                        Đối với đặt vé khứ hồi, cả hai chiều phải có chuyến bay.
                    </p>
                    <div style="margin-top: 25px;">
                        <p style="font-weight: 600; margin-bottom: 15px;">Gợi ý:</p>
                        <ul style="display: inline-block; text-align: left; color: #555;">
                            <li>Nên thử một <strong>Ngày về</strong> khác</li>
                            <li>Đổi loại hành trình thành <strong>Một chiều</strong> nếu bạn chỉ cần chiều đi</li>
                        </ul>
                    </div>
                @else
                    <h3>Không tìm thấy chuyến bay</h3>
                    <p>Rất tiếc, chúng tôi không tìm thấy chuyến bay nào cho lộ trình và ngày bạn chọn. Vui lòng chọn ngày khác hoặc tìm kiếm lại.</p>
                @endif
                
                <div style="margin-top: 30px;">
                    <a href="#" id="btnEditSearchAgain" class="btn-continue" style="background: #003366; padding: 10px 25px; font-size: 16px;">
                        <i class="fas fa-search"></i> Chỉnh sửa tìm kiếm
                    </a>
                </div>
            </div>

            <script>
                document.getElementById('btnEditSearchAgain').addEventListener('click', function(e) {
                    e.preventDefault();
                    const formContainer = document.getElementById('editSearchFormContainer');
                    if (formContainer) {
                        formContainer.classList.add('show-edit-form');
                        formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            </script>
        @endif

        {{-- Bottom Summary Panel --}}
        @if($outboundFlight)
            <div class="selection-summary-panel">
                <h3 class="summary-title">Lựa chọn của bạn</h3>
                
                <div class="summary-grid">
                    {{-- Departing Leg --}}
                    <div class="summary-column">
                        <div class="summary-route">
                            <span>Chiều đi</span>
                            <span>{{ $outboundFlight->departure_time->format('D, d M Y') }}</span>
                        </div>
                        <div class="summary-times">
                            <div>
                                <span class="summary-time">{{ $outboundFlight->departure_time->format('H:i') }}</span>
                                <span class="summary-city">{{ $outboundFlight->origin->code }}</span>
                            </div>
                            <div class="summary-arrow"><i class="fas fa-long-arrow-alt-right"></i></div>
                            <div>
                                <span class="summary-time">{{ $outboundFlight->arrival_time->format('H:i') }}</span>
                                <span class="summary-city">{{ $outboundFlight->destination->code }}</span>
                            </div>
                        </div>
                        @php
                            $classMap = ['economy' => 'Phổ thông', 'business' => 'Thương gia'];
                            $currentClass = $classMap[request('ticket_class', 'economy')] ?? request('ticket_class');
                        @endphp
                        <div style="font-weight: 600; font-size: 14px; margin-top: 10px;">
                            {{ $currentClass }} (Tiết kiệm)
                        </div>
                        <div style="font-size: 13px; color: #666; margin-top: 5px;">
                            <i class="fas fa-suitcase"></i> Hành lý: 20 kg
                        </div>
                    </div>

                    <div class="summary-divider"></div>

                    {{-- Returning Leg --}}
                    <div class="summary-column" style="{{ !$returnFlight ? 'opacity: 0.7; border: 1px dashed #007bff; background: #f0f7ff;' : '' }}">
                        @if($returnFlight)
                            <div class="summary-route">
                                <span>Chiều về</span>
                                <span>{{ $returnFlight->departure_time->format('D, d M Y') }}</span>
                            </div>
                            <div class="summary-times">
                                <div>
                                    <span class="summary-time">{{ $returnFlight->departure_time->format('H:i') }}</span>
                                    <span class="summary-city">{{ $returnFlight->origin->code }}</span>
                                </div>
                                <div class="summary-arrow"><i class="fas fa-long-arrow-alt-right"></i></div>
                                <div>
                                    <span class="summary-time">{{ $returnFlight->arrival_time->format('H:i') }}</span>
                                    <span class="summary-city">{{ $returnFlight->destination->code }}</span>
                                </div>
                            </div>
                            <div style="font-weight: 600; font-size: 14px; margin-top: 10px;">
                                {{ $classMap[request('ticket_class', 'economy')] ?? request('ticket_class') }} (Tiết kiệm)
                            </div>
                            <div style="font-size: 13px; color: #666; margin-top: 5px;">
                                <i class="fas fa-suitcase"></i> Hành lý: 20 kg
                            </div>
                        @elseif($step == 'return' || request('flight_type') == 'round_trip')
                            <div class="summary-route">
                                <span>Chiều về</span>
                                <span>{{ request('return_date') ? \Carbon\Carbon::parse(request('return_date'))->format('D, d M Y') : 'Chưa đặt' }}</span>
                            </div>
                            <div class="summary-times" style="opacity: 0.5;">
                                <div>
                                    <span class="summary-time">--:--</span>
                                    <span class="summary-city">{{ $outboundFlight->destination->code }}</span>
                                </div>
                                <div class="summary-arrow"><i class="fas fa-long-arrow-alt-right"></i></div>
                                <div>
                                    <span class="summary-time">--:--</span>
                                    <span class="summary-city">{{ $outboundFlight->origin->code }}</span>
                                </div>
                            </div>
                            <div style="text-align: center; margin-top: 15px; color: #007bff; font-weight: 600;">
                                <i class="fas fa-plane-arrival"></i> Đang chọn chiều về...
                            </div>
                        @else
                            <div style="text-align: center; padding: 40px; color: #999;">
                                Không cần chiều về
                            </div>
                        @endif
                    </div>
                </div>

                <div class="summary-footer">
                    <div class="traveler-box" style="width: 100%;">
                        <div class="traveler-info">
                            <i class="fas fa-users" style="color: #666;"></i>
                            <span style="font-weight: 600; color: #333;">Hành khách:</span> {{ request('adult_count', 1) }} người lớn
                        </div>

                        {{-- Price Breakdown Dropdown --}}
                        <div class="price-breakdown-wrapper">
                            @php
                                $isBusiness = request('ticket_class') == 'business';
                                $adultCount = (int)request('adult_count', 1);
                                $childCount = (int)request('child_count', 0);
                                $infantCount = (int)request('infant_count', 0);
                                
                                // USE UNIFIED HELPER
                                $price = \App\Helpers\FlightPriceHelper::calculate(
                                    $outboundFlight, 
                                    $returnFlight, 
                                    $adultCount, 
                                    $childCount, 
                                    $infantCount, 
                                    $isBusiness ? 'business' : 'economy'
                                );

                                $grandTotal = $price['grand_total'];
                            @endphp

                            <div class="price-breakdown-header" onclick="toggleDropdownContent('priceBreakdownContent')">
                                <span><i class="fas fa-caret-down"></i> 
                                    {{ $adultCount }} Người lớn
                                    @if($childCount > 0), {{ $childCount }} Trẻ em @endif
                                    @if($infantCount > 0), {{ $infantCount }} Sơ sinh @endif
                                </span>
                                <span>VND {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>

                            <div id="priceBreakdownContent" class="price-breakdown-content">
                                <div style="font-weight: 700; margin-bottom: 12px; color: #333; font-size: 15px;">Tóm tắt giá</div>
                                
                                <div class="price-row">
                                    <span>Giá vé cơ bản</span>
                                    <span>VND {{ number_format($price['total_base_fare'], 0, ',', '.') }}</span>
                                </div>
                                <div class="price-row">
                                    <span>Phí dịch vụ</span>
                                    <span>VND {{ number_format($price['total_service'], 0, ',', '.') }}</span>
                                </div>
                                <div class="price-row">
                                    <span>Thuế VAT (10%)</span>
                                    <span>VND {{ number_format($price['total_vat'], 0, ',', '.') }}</span>
                                </div>
                                @if($infantCount > 0)
                                <div class="price-row">
                                    <span>Phí sơ sinh</span>
                                    <span>VND {{ number_format($price['total_infant'], 0, ',', '.') }}</span>
                                </div>
                                @endif
                                
                                <div class="price-row summary-item">
                                    <span>Tổng cộng</span>
                                    <span>VND {{ number_format($grandTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; border-top: 2px solid #0066cc; padding-top: 20px;">
                    <div class="total-box" style="text-align: left;">
                        <span class="total-label" style="font-size: 18px; font-weight: 700; color: #003366;">Tổng cộng</span>
                    </div>
                    <div class="total-price" style="color: #0066cc;">
                        <span class="total-currency">VND</span> {{ number_format($grandTotal, 0, ',', '.') }}
                    </div>
                </div>

                @php
                    $canContinue = ($step == 'one_way' || $returnFlight);
                @endphp

                <div style="text-align: right; margin-top: 25px;">
                    @if($canContinue)
                        <a href="{{ route('flights.book', request()->all()) }}" class="btn-continue">Tiếp tục điền thông tin</a>
                    @else
                        <button class="btn-continue" style="opacity: 0.5; cursor: not-allowed;" disabled>Vui lòng chọn chiều về</button>
                    @endif
                </div>
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
