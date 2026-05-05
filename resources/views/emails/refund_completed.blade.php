@php
    $booking = $refund->booking;
    $passengerName = $booking->passenger_name ?? 'Khach hang';
    $bookingCode = $booking->booking_code ?? $booking->id ?? '';
    $currency = $refund->currency ?? 'VND';
    $ratePercent = (int) round($refundRate * 100);
@endphp

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hoan tien thanh cong</title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; }
        .meta { margin-bottom: 12px; }
        .amount { font-size: 18px; font-weight: bold; color: #d84a1d; }
    </style>
</head>
<body>
    <h2>Hoan tien thanh cong</h2>

    <p>Chao {{ $passengerName }},</p>

    <p>Yeu cau hoan ve cua ban da duoc xu ly thanh cong. Chi tiet hoan tien:</p>

    <div class="meta">
        <strong>Ma dat ve (PNR):</strong> {{ $bookingCode }}<br>
        <strong>So tien ban dau:</strong> {{ number_format($originalAmount, 0, ',', '.') }} {{ $currency }}<br>
        <strong>Ty le hoan:</strong> {{ $ratePercent }}%
    </div>

    <p class="amount">So tien hoan: {{ number_format($refundAmount, 0, ',', '.') }} {{ $currency }}</p>

    <p>So tien hoan se duoc chuyen ve phuong thuc thanh toan ban dau theo quy dinh cua cong thanh toan.</p>

    <p>Neu can ho tro, vui long lien he CSKH de duoc giai dap.</p>

    <p>Tran trong,<br>He thong dat ve</p>
</body>
</html>
