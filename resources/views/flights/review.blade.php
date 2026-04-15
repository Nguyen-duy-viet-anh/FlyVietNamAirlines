@extends('layouts.public')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/review_styles.css') }}">
    @endpush

    @include('layouts.search.booking_stepper', ['currentStep' => 3])

    <div class="review-container">
        <div class="review-header">
            <h1>Kiểm tra thông tin đặt vé</h1>
            <div class="order-code">Mã đặt hàng: <span style="color: #0056b3;">#PG-{{ strtoupper(Str::random(5)) }}</span>
            </div>
            <div class="order-note">Mã đặt hàng này chỉ dùng để tham khảo, KHÔNG dùng để làm thủ tục check-in hay lên máy
                bay!</div>
        </div>

        {{-- Itinerary Section --}}
        <div class="review-section">
            <div class="section-title">Hành trình</div>
            <div class="section-content">
                {{-- Outbound Flight --}}
                <div class="flight-segment">
                    <div class="segment-route-info">
                        <div class="route-header">
                            {{ $outboundFlight->origin->city }} - {{ $outboundFlight->destination->city }} &nbsp;
                            <span
                                style="color: #666; font-weight: normal;">{{ $outboundFlight->departure_time->translatedFormat('D, d M Y') }}</span>
                        </div>
                        <div class="time-display">
                            <div class="time-box">
                                <span class="time">{{ $outboundFlight->departure_time->format('H:i') }}</span>
                                <span class="airport-code">{{ $outboundFlight->origin->code }}</span>
                            </div>
                            <div class="flight-connector">
                                <span class="duration-text">
                                    @php
                                        $duration = $outboundFlight->departure_time->diff($outboundFlight->arrival_time);
                                        echo $duration->format('%hh %Im');
                                    @endphp
                                </span>
                                <div class="connector-line">
                                    <i class="fas fa-plane"></i>
                                </div>
                                <span class="duration-text">Bay thẳng</span>
                            </div>
                            <div class="time-box">
                                <span class="time">{{ $outboundFlight->arrival_time->format('H:i') }}</span>
                                <span class="airport-code">{{ $outboundFlight->destination->code }}</span>
                            </div>
                        </div>
                        <div class="segment-details">
                            <img src="{{ asset('images/' . ($outboundFlight->airline->name == 'Vietjet Air' ? 'Logo-VietjetAir.jpg' : ($outboundFlight->airline->name == 'Bamboo Airways' ? 'logo-bamboo-airways.jpg' : 'logo-vietnamAirlines.png'))) }}"
                                class="airline-logo" alt="Airline">
                            <span
                                style="font-size: 13px; color: #444; font-weight: 500;">{{ $outboundFlight->flight_number }}</span>
                        </div>
                    </div>
                    <div class="segment-class-info">
                        <div>Hạng vé: {{ ucfirst($passengerData['ticket_class'] ?? 'Phổ thông') }} Flex</div>
                        <div>Hành lý: 20KG</div>
                    </div>
                </div>

                {{-- Return Flight --}}
                @if($returnFlight)
                    <div class="flight-segment" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                        <div class="segment-route-info">
                            <div class="route-header">
                                {{ $returnFlight->origin->city }} - {{ $returnFlight->destination->city }} &nbsp;
                                <span
                                    style="color: #666; font-weight: normal;">{{ $returnFlight->departure_time->translatedFormat('D, d M Y') }}</span>
                            </div>
                            <div class="time-display">
                                <div class="time-box">
                                    <span class="time">{{ $returnFlight->departure_time->format('H:i') }}</span>
                                    <span class="airport-code">{{ $returnFlight->origin->code }}</span>
                                </div>
                                <div class="flight-connector">
                                    <span class="duration-text">
                                        @php
                                            $duration = $returnFlight->departure_time->diff($returnFlight->arrival_time);
                                            echo $duration->format('%hh %Im');
                                        @endphp
                                    </span>
                                    <div class="connector-line">
                                        <i class="fas fa-plane"></i>
                                    </div>
                                    <span class="duration-text">Bay thẳng</span>
                                </div>
                                <div class="time-box">
                                    <span class="time">{{ $returnFlight->arrival_time->format('H:i') }}</span>
                                    <span class="airport-code">{{ $returnFlight->destination->code }}</span>
                                </div>
                            </div>
                            <div class="segment-details">
                                <img src="{{ asset('images/' . ($returnFlight->airline->name == 'Vietjet Air' ? 'Logo-VietjetAir.jpg' : ($returnFlight->airline->name == 'Bamboo Airways' ? 'logo-bamboo-airways.jpg' : 'logo-vietnamAirlines.png'))) }}"
                                    class="airline-logo" alt="Airline">
                                <span
                                    style="font-size: 13px; color: #444; font-weight: 500;">{{ $returnFlight->flight_number }}</span>
                            </div>
                        </div>
                        <div class="segment-class-info">
                            <div>Hạng vé: {{ ucfirst($passengerData['ticket_class'] ?? 'Phổ thông') }} Flex</div>
                            <div>Hành lý: 20KG</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Price Section --}}
        <div class="review-section">
            <div class="section-title">Giá vé</div>
            <div class="section-content">
                <table class="review-table price-table">
                    <thead>
                        <tr>
                            <th>Dịch vụ</th>
                            <th>Số tiền (VND)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Giá vé cơ bản</td>
                            <td>{{ number_format($priceBreakdown['base_adult_single'] * $passengerData['adult_count'] + ($priceBreakdown['base_child_single'] ?? 0) * ($passengerData['child_count'] ?? 0), 0, ',', '.') }}
                                VND</td>
                        </tr>
                        <tr>
                            <td>Phí dịch vụ</td>
                            <td>{{ number_format($priceBreakdown['total_service'], 0, ',', '.') }} VND</td>
                        </tr>
                        <tr>
                            <td>Thuế & Phí</td>
                            <td>{{ number_format($priceBreakdown['total_vat'] + ($priceBreakdown['total_infant'] ?? 0), 0, ',', '.') }}
                                VND</td>
                        </tr>
        @php
            $paxTotal = 0;
            if ($passengerData['adult_count'] > 0)
                $paxTotal++;
        @endphp
                        <tr>
                            <td>x {{ $passengerData['adult_count'] }} Người lớn</td>
                            <td>{{ number_format($passengerData['total_amount'], 0, ',', '.') }} VND</td>
                        </tr>
                    </tbody>
                </table>
                <div class="grand-total">
                    <span>Tổng cộng</span>
                    <span
                        style="font-size: 18px; color: #333;">{{ number_format($passengerData['total_amount'], 0, ',', '.') }}
                        VND</span>
                </div>
            </div>
        </div>

        {{-- Passenger details Section --}}
        <div class="review-section">
            <div class="section-title">Thông tin hành khách</div>
            <div class="section-content">
                <table class="review-table">
                    <thead>
                        <tr>
                            <th>Họ và tên</th>
                            <th>Ngày sinh</th>
                            <th>Hộ chiếu</th>
                            <th>Số thẻ thành viên</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Adult Passengers --}}
                        @if(isset($passengerData['passengers']['adult']))
                            @foreach($passengerData['passengers']['adult'] as $p)
                                <tr>
                                    <td>{{ ($p['title'] == 'Mr' ? 'Ông' : 'Bà') }}.
                                        {{ strtoupper(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? '')) }}</td>
                                    <td>{{ $p['dob_day'] }}/{{ $p['dob_month'] }}/{{ $p['dob_year'] }}</td>
                                    <td>112233</td> {{-- Placeholder --}}
                                    <td>-</td>
                                </tr>
                            @endforeach
                        @endif
                        {{-- Child Passengers --}}
                        @if(isset($passengerData['passengers']['child']))
                            @foreach($passengerData['passengers']['child'] as $p)
                                <tr>
                                    <td>{{ ($p['title'] == 'Master' ? 'Cậu bé' : 'Cô bé') }}.
                                        {{ strtoupper(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? '')) }}</td>
                                    <td>{{ $p['dob_day'] }}/{{ $p['dob_month'] }}/{{ $p['dob_year'] }}</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Contact Information Section --}}
        <div class="review-section">
            <div class="section-title">Thông tin liên hệ</div>
            <div class="section-content">
                <table class="review-table">
                    <thead>
                        <tr>
                            <th>Điện thoại</th>
                            <th>Email</th>
                            <th>Yêu cầu đặc biệt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ ($passengerData['passenger_country_code'] ?? '') }}{{ $passengerData['passenger_phone'] }}
                            </td>
                            <td>{{ $passengerData['passenger_email'] }}</td>
                            <td>{{ $passengerData['notes'] ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Payment Option Section --}}
        <div class="review-section">
            <div class="section-title">Phương thức thanh toán</div>
            <div class="section-content">
                <div class="vnpay-info-box" style="display: flex; align-items: center; gap: 15px;">
                    <div class="payment-selection">
                        <input type="radio" name="payment_method" value="vnpay" checked style="width: 20px; height: 20px; accent-color: #0056b3;">
                    </div>
                    <img src="{{ asset('images/logo_vnpay.png') }}" alt="VNPay" class="vnpay-logo-large" style="height: 40px;">
                    <div class="vnpay-text">
                        <p style="font-weight: 600; margin-bottom: 5px; color: #333;">Cổng thanh toán VNPay</p>
                        <p style="font-size: 13px; color: #666; margin: 0;">Thanh toán an toàn qua Ứng dụng ngân hàng, Thẻ ATM, Visa, Master Card...</p>
                    </div>
                </div>
                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; font-size: 13px; color: #666; font-style: italic;">
                    * Lưu ý: Bạn sẽ được chuyển đến trang thanh toán của VNPay để hoàn tất giao dịch.
                </div>
            </div>
        </div>

        {{-- Term of Use Section --}}
        <div class="review-section">
            <div class="section-title">Điều khoản sử dụng</div>
            <div class="section-content">
                <div class="terms-box">
                    <strong>Điều kiện sử dụng trang web</strong><br><br>
                    Bằng việc truy cập và sử dụng trang web của chúng tôi cho bất kỳ mục đích tìm kiếm, tham khảo hoặc đặt
                    vé nào, quý khách xác nhận rằng mình đã hiểu rõ và chấp nhận, cũng như đồng ý không vi phạm Các Điều
                    khoản và Điều kiện của chúng tôi.<br><br>
                    Chúng tôi khuyên quý khách nên đọc kỹ các điều khoản và điều kiện sau đây trước khi mua sản phẩm của
                    chúng tôi. Nếu quý khách không đồng ý với bất kỳ phần nào, vui lòng rời khỏi trang web ngay lập
                    tức.<br><br>
                    Xin lưu ý rằng Hãng hàng không Philippine Airlines có quyền hủy hoặc chấm dứt bất kỳ yêu cầu đặt vé nào
                    nếu rơi vào các trường hợp sau:<br>
                    - (i) pháp luật yêu cầu.<br>
                    - (ii) thanh toán không được thực hiện đúng hạn.<br>
                    - (iii) vấn đề về còn chỗ của vé.<br>
                    - (iv) lỗi hệ thống hoặc bất kỳ sự cố kỹ thuật nào có thể gây cản trở trong việc xử lý yêu cầu của khách
                    hàng.
                </div>
                <label class="agree-checkbox">
                    <input type="checkbox" id="agreeCheckbox" required>
                    <span>Tôi đã đọc và đồng ý với tất cả <a href="#" style="color: #0056b3;">Điều khoản sử dụng</a> được
                        quy định.</span>
                </label>
            </div>
        </div>

        <form action="{{ route('flights.payment') }}" method="POST" id="paymentForm">
            @csrf
            @foreach($passengerData as $key => $value)
                @if(is_array($value))
                    @php $flatArray = Arr::dot([$key => $value]); @endphp
                    @foreach($flatArray as $flatKey => $flatValue)
                        <input type="hidden" name="{{ $flatKey }}" value="{{ $flatValue }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <div class="clearfix" style="margin-top: 30px; margin-bottom: 50px;">
                <button type="submit" id="btnContinue" class="btn-continue-review" disabled>Tiếp tục</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('agreeCheckbox');
            const btn = document.getElementById('btnContinue');

            checkbox.addEventListener('change', function () {
                btn.disabled = !this.checked;
            });
        });
    </script>
@endsection