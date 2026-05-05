@php
    $booking = $refund->booking;
    $submitterName = $refund->requester->name ?? $booking->passenger_name ?? 'Khách';
    $submitterEmail = $refund->requester->email ?? $booking->passenger_email ?? 'Không rõ';
@endphp

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Yêu cầu hoàn tiền</title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; }
        .meta { margin-bottom: 12px; }
    </style>
</head>
<body>
    <h2>Yêu cầu hoàn tiền cho đơn: {{ $booking->booking_code ?? '' }}</h2>

    <div class="meta">
        <strong>Người gửi:</strong> {{ $submitterName }} &lt;{{ $submitterEmail }}&gt;<br>
        <strong>Mã đơn:</strong> {{ $booking->booking_code ?? $booking->id }}<br>
        <strong>Hành khách:</strong> {{ $booking->passenger_name }} ({{ $booking->passenger_email }})
    </div>

    <p><strong>Số tiền yêu cầu hoàn:</strong> {{ number_format($refund->amount, 2) }} {{ $refund->currency }}</p>

    @if($refund->reason)
        <p><strong>Lý do:</strong> {{ $refund->reason }}</p>
    @endif

    <p><strong>Trạng thái:</strong> {{ ucfirst($refund->status) }}</p>

    <p>Vui lòng xử lý yêu cầu này trong hệ thống quản trị. Trả lời email này để liên hệ với người gửi nếu cần thêm thông tin.</p>

    <p>Trân trọng,<br>Hệ thống đặt vé</p>
</body>
</html>
