# Mô tả chức năng các file và thư mục chính

Tập tin này cung cấp mô tả ngắn, dễ hiểu về vai trò của các file và thư mục chính trong project.

Root
- `.env`: Biến môi trường cục bộ (chứa secrets — không commit).
- `.env.example`: Mẫu cấu hình môi trường để tham khảo.
- `artisan`: CLI của Laravel (chạy lệnh artisan).
- `composer.json`: Khai báo dependency PHP và autoload.
- `package.json`: Khai báo dependency frontend và script build.
- `vite.config.js`: Cấu hình Vite (build frontend).
- `tailwind.config.js`: Cấu hình Tailwind CSS.
- `phpunit.xml`: Cấu hình PHPUnit cho test.

Public / Static
- `public/index.php`: Entry point (front controller) của ứng dụng web.
- `public/js/flightHelper.js`: Logic UI đặt vé (số hành khách, nhập sân bay, validate form, chuyển step).
- `public/js/box_date.js`: Đồng bộ/giới hạn ngày (khi chọn ngày đi/đến).
- `public/js/offers_slider.js`: Carousel / slider ưu đãi và hành vi prefilling search.
- `public/css/*`: CSS biên dịch/stylesheet dùng cho giao diện public/admin.
- `public/images/*`: Hình ảnh dùng trên trang (logo, banner, destinations).

Resources (frontend templates & assets)
- `resources/js/bootstrap.js`: Thiết lập axios, global JS, polyfills.
- `resources/js/app.js`: Entry JS dùng cùng Vite / Alpine.js.
- `resources/css/app.css`: Entry CSS (Tailwind/postcss).
- `resources/views/`: Blade templates — chia thành `layouts/`, `components/`, `flights/`, `search/`, `admin/`.
  - `layouts/public.blade.php`: layout chính cho site public.
  - `layouts/admin.blade.php`: layout cho phần quản trị.
  - `components/*`: các partials (navbar, form input, modal, button).
  - `flights/*`: view liên quan tới tìm kiếm, review, passenger, success.

App (PHP backend)
- `app/Console/Commands/ReleaseExpiredReservations.php`: cron/command giải phóng ghế đã đặt quá hạn.
- `app/Exceptions/Handler.php`: xử lý ngoại lệ toàn cục.
- `app/Http/Controllers/BookingController.php`: Xử lý luồng đặt vé (validate request, tạo AppBooking, redirect thanh toán hoặc tạo transaction).
- `app/Http/Controllers/PaymentController.php`: Tích hợp VNPay (tạo URL, verify signature, xử lý IPN/return).
- `app/Http/Controllers/FlightController.php`: API/điều khiển tìm kiếm chuyến bay, lọc và trả kết quả.
- `app/Http/Controllers/HomeController.php` / `DestinationController.php`: trang chủ và trang điểm đến.
- `app/Http/Middleware/CheckAdmin.php`: middleware bảo vệ route admin.
- `app/Jobs/SendBookingConfirmationEmail.php`: Job queue để gửi email xác nhận đặt vé (tách luồng gửi mail khỏi request).
- `app/Mail/BookingConfirmed.php`: Mailable / template gửi mail xác nhận.
- `app/Notifications/BookingConfirmedNotification.php`: Notification wrapper dùng cho hệ thống thông báo (mail).
- `app/Helpers/FlightPriceHelper.php`: Hàm trợ giúp tính giá vé (fees, taxes, breakdown).

Models (dữ liệu)
- `app/Models/User.php`: Model người dùng (authentication, cast, relations).
- `app/Models/Flight.php`: Model chuyến bay (thông tin ghế, chứa hàm reserve/release seats).
- `app/Models/FlightSegment.php`: Thông tin từng đoạn hành trình trong booking.
- `app/Models/AppBooking.php`: Bản ghi đơn đặt vé (thông tin hành khách, trạng thái, idempotency key).
- `app/Models/AppBookingTransaction.php`: Bản ghi giao dịch thanh toán (status, response từ payment gateway).
- `app/Models/Airport.php`, `app/Models/Airline.php`: dữ liệu tham chiếu.

Config
- `config/mail.php`: Cấu hình mailer (SMTP, from, driver).
- `config/queue.php`: Driver queue (database) và job settings.
- `config/services.php`: Nơi đặt cấu hình cho VNPay, các API bên thứ ba (có thể map từ .env).

Database
- `database/migrations/*`: Các migration tạo bảng (users, flights, app_bookings, app_booking_transactions, jobs...).
- `database/seeders/*`: Seeder mẫu (AirlineSeeder, AirportSeeder, FlightSeeder) để populate dữ liệu test.
- `database/factories/*`: Factory dùng cho test/seed (UserFactory).

Routes
- `routes/web.php`: Định nghĩa routes web (front-end và admin web routes).
- `routes/api.php`: Endpoints API (nếu có).
- `routes/console.php` / `channels.php` / `auth.php`: route console / broadcast channels / auth helpers.

Storage & Cache
- `storage/framework/*`: cache, sessions, views — runtime writable by PHP.
- `storage/logs/laravel.log`: nơi ghi log ứng dụng.

Tests
- `tests/TestCase.php`, `tests/CreatesApplication.php`: base test bootstrap.
- `tests/Feature/` và `tests/Unit/`: chứa test feature và unit.

Bootstrap & Vendor
- `bootstrap/app.php`: bootstrapping Laravel app.
- `vendor/`: packages của Composer (không commit nếu chưa có vendor).

Các file quan trọng cần chú ý (bảo mật/độ tin cậy)
- `.env`: chứa secrets — KHÔNG commit; khi deploy nên dùng secret manager.
- `app/Models/AppBooking.php`: cần explicit `$fillable` hoặc `$guarded` an toàn để tránh mass-assignment.
- `app/Http/Controllers/PaymentController.php`: so sánh HMAC phải dùng `hash_equals()` để tránh timing attack.
- `app/Jobs/SendBookingConfirmationEmail.php`: job queued — kiểm tra queue worker đang chạy để gửi mail reliably.

Gợi ý tiếp theo
- Muốn mình chèn mô tả này trực tiếp vào `PROJECT_FILE_STRUCTURE.md` hay giữ file riêng `PROJECT_FILE_DESCRIPTIONS.md` như hiện tại?
- Nếu cần, mình có thể mở rộng mô tả chi tiết cho từng file (toàn bộ ~200 file) và ghi trực tiếp vào `PROJECT_FILE_STRUCTURE.md` — việc này sẽ tốn thời gian, muốn mình thực hiện tự động hay thủ công theo nhóm file ưu tiên?
