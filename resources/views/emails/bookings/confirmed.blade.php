@component('mail::message')
# Xác Nhận Đặt Vé Thành Công

Chào **{{ $booking->passenger_name }}**,

Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của FlyVietNamAirlines. Giao dịch của bạn đã được thực hiện thành công. Dưới đây là thông tin chi tiết về chuyến bay của bạn:

**Mã đặt vé (PNR):** `{{ $booking->booking_code }}`

---

### Thông tin chuyến bay

**Chuyến đi:**
- **Số hiệu:** {{ $booking->outboundFlight->flight_number }}
- **Hãng hàng không:** {{ $booking->outboundFlight->airline->name }}
- **Từ:** {{ $booking->outboundFlight->origin->name }} ({{ $booking->outboundFlight->origin->code }})
- **Đến:** {{ $booking->outboundFlight->destination->name }} ({{ $booking->outboundFlight->destination->code }})
- **Khởi hành:** {{ $booking->outboundFlight->departure_time->format('H:i d/m/Y') }}
- **Hạng vé:** {{ ucfirst($booking->ticket_class ?? 'Phổ thông') }}

@if($booking->return_flight_id)
**Chuyến về:**
- **Số hiệu:** {{ $booking->returnFlight->flight_number }}
- **Hãng hàng không:** {{ $booking->returnFlight->airline->name }}
- **Từ:** {{ $booking->returnFlight->origin->name }} ({{ $booking->returnFlight->origin->code }})
- **Đến:** {{ $booking->returnFlight->destination->name }} ({{ $booking->returnFlight->destination->code }})
- **Khởi hành:** {{ $booking->returnFlight->departure_time->format('H:i d/m/Y') }}
- **Hạng vé:** {{ ucfirst($booking->ticket_class ?? 'Phổ thông') }}
@endif

---

### Chi phí thanh toán
- **Tổng số tiền:** {{ number_format($booking->total_amount, 0, ',', '.') }} VNĐ
- **Trạng thái:** Đã thanh toán

@component('mail::button', ['url' => config('app.url')])
Xem chi tiết tại website
@endcomponent

Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với bộ phận CSKH của chúng tôi.

Trân trọng,<br>
Đội ngũ FlyVietNamAirlines
@endcomponent
