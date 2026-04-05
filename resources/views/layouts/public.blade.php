<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Đặt vé Máy bay</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">



</head>

<body>
    @include('components.navbar')

    @yield('hero')

    <div class="container">
        @yield('content')
    </div>
    <script src="{{ asset('js/flightHelper.js') }}"></script>
    <script src="{{ asset('js/box_date.js') }}"></script>
</body>

</html>
