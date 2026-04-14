<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* INLINE CRITICAL CSS: Hide body instantly */
        body { opacity: 0 !important; visibility: hidden !important; }
        body.ready { opacity: 1 !important; visibility: visible !important; }
    </style>
    <script>
        // Reveal body as soon as possible
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('ready');
        });
        // Fallback for slow connections: reveal after 3 seconds anyway
        setTimeout(function() { document.body.classList.add('ready'); }, 3000);
    </script>
    
    <title>Hệ thống Đặt vé Máy bay</title>
    
    <!-- Load local CSS first -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    
    <!-- External assets -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('styles')
</head>

<body>
    @include('components.navbar')

    @yield('hero')

    <div class="container">
        @yield('content')
    </div>
    <script src="{{ asset('js/flightHelper.js') }}"></script>
    <script src="{{ asset('js/box_date.js') }}"></script>
    @yield('scripts')
</body>

</html>
