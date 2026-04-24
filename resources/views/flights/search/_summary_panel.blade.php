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
                <div class="summary-class-info">
                    {{ $currentClass }} (Tiết kiệm)
                </div>
                <div class="summary-luggage-info">
                    <i class="fas fa-suitcase"></i> Hành lý: 20 kg
                </div>
            </div>

            <div class="summary-divider"></div>

            {{-- Returning Leg --}}
            <div class="summary-column {{ !$returnFlight ? 'summary-placeholder' : '' }}">
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
                    <div class="summary-class-info">
                        {{ $classMap[request('ticket_class', 'economy')] ?? request('ticket_class') }} (Tiết kiệm)
                    </div>
                    <div class="summary-luggage-info">
                        <i class="fas fa-suitcase"></i> Hành lý: 20 kg
                    </div>
                @elseif($step == 'return' || request('flight_type') == 'round_trip')
                    <div class="summary-route">
                        <span>Chiều về</span>
                        <span>{{ request('return_date') ? \Carbon\Carbon::parse(request('return_date'))->format('D, d M Y') : 'Chưa đặt' }}</span>
                    </div>
                    <div class="summary-times text-muted" style="opacity: 0.5;">
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
                    <div class="summary-selecting-text">
                        <i class="fas fa-plane-arrival"></i> Đang chọn chiều về...
                    </div>
                @else
                    <div class="summary-empty-box">
                        Không cần chiều về
                    </div>
                @endif
            </div>
        </div>

        <div class="summary-footer">
            <div class="traveler-box" style="width: 100%;">
                <div class="traveler-info">
                    <i class="fas fa-users text-muted"></i>
                    <span class="text-bold">Hành khách:</span> {{ request('adult_count', 1) }} người lớn
                </div>

                {{-- Price Breakdown Dropdown --}}
                <div class="price-breakdown-wrapper">
                    @php
                        $isBusiness = request('ticket_class') == 'business';
                        $adultCount = (int)request('adult_count', 1);
                        $childCount = (int)request('child_count', 0);
                        $infantCount = (int)request('infant_count', 0);
                        
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
                        <div class="price-breakdown-title">Tóm tắt giá</div>
                        
                        <div class="price-row text-bold">
                            <span>{{ $adultCount }} Người lớn</span>
                            <span>VND {{ number_format($price['total_adults_full'], 0, ',', '.') }}</span>
                        </div>
                        <div class="price-row price-row-detail">
                            <span>Giá vé cơ bản</span>
                            <span>VND {{ number_format($price['total_adults_base'], 0, ',', '.') }}</span>
                        </div>
                        <div class="price-row price-row-detail-compact">
                            <span>Phí dịch vụ</span>
                            <span>VND {{ number_format($price['total_adults_service'], 0, ',', '.') }}</span>
                        </div>
                        <div class="price-row price-row-detail-compact mb-5">
                            <span>Thuế VAT</span>
                            <span>VND {{ number_format($price['total_adults_vat'], 0, ',', '.') }}</span>
                        </div>

                        @if($childCount > 0)
                        <div class="price-row text-bold mt-5">
                            <span>{{ $childCount }} Trẻ em</span>
                            <span>VND {{ number_format($price['total_children_full'], 0, ',', '.') }}</span>
                        </div>
                        <div class="price-row price-row-detail">
                            <span>Giá vé cơ bản (90%)</span>
                            <span>VND {{ number_format($price['total_children_base'], 0, ',', '.') }}</span>
                        </div>
                        <div class="price-row price-row-detail-compact">
                            <span>Phí dịch vụ</span>
                            <span>VND {{ number_format($price['total_children_service'], 0, ',', '.') }}</span>
                        </div>
                        <div class="price-row price-row-detail-compact mb-5">
                            <span>Thuế VAT</span>
                            <span>VND {{ number_format($price['total_children_vat'], 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($infantCount > 0)
                        <div class="price-row text-bold mt-5">
                            <span>{{ $infantCount }} Sơ sinh</span>
                            <span>VND {{ number_format($price['total_infant_full'], 0, ',', '.') }}</span>
                        </div>
                        <div class="price-row price-row-detail">
                            <span>Giá vé cơ bản (10%)</span>
                            <span>VND {{ number_format($price['total_infants_base'], 0, ',', '.') }}</span>
                        </div>
                        <div class="price-row price-row-detail-compact">
                            <span>Phí dịch vụ</span>
                            <span>VND {{ number_format($price['total_infants_service'], 0, ',', '.') }}</span>
                        </div>
                        <div class="price-row price-row-detail-compact mb-5">
                            <span>Thuế VAT</span>
                            <span>VND {{ number_format($price['total_infants_vat'], 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div class="price-row price-row-total">
                            <span>Tổng cộng</span>
                            <span>VND {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grand-total-row">
            <div class="total-box">
                <span class="grand-total-label">Tổng cộng</span>
            </div>
            <div class="total-price text-primary">
                <span class="total-currency">VND</span> {{ number_format($grandTotal, 0, ',', '.') }}
            </div>
        </div>

        @php
            $canContinue = ($step == 'one_way' || $returnFlight);
            $hideButton = $hideButton ?? false;
        @endphp

        @if(!$hideButton)
            <div class="text-right mt-25">
                @if($canContinue)
                    <a href="{{ route('flights.book', request()->all()) }}" class="btn-continue">Tiếp tục điền thông tin</a>
                @else
                    <button class="btn-continue btn-disabled" disabled>Vui lòng chọn chiều về</button>
                @endif
            </div>
        @endif
    </div>
@endif
