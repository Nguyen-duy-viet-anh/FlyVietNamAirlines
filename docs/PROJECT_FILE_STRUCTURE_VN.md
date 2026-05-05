# FlyVietNamAirlines — Cấu trúc dự án (mô tả tiếng Việt đầy đủ)

Tạo: 2026-04-27

Dưới đây là danh sách file/thư mục trong repository kèm mô tả ngắn (Tiếng Việt).

Root
- `vite.config.js` — cấu hình Vite để build JS/CSS.
- `todo.md` — danh sách việc cần làm trong repo.
- `.editorconfig` — quy tắc format code chung cho editor.
- `README.md` — tài liệu giới thiệu, hướng dẫn chạy dự án.
- `postcss.config.js` — cấu hình PostCSS cho CSS build.
- `package.json` — npm scripts và dependency frontend.
- `phpunit.xml` — cấu hình PHPUnit cho test.
- `package-lock.json` — lockfile npm.
- `tailwind.config.js` — cấu hình Tailwind CSS.
- `tests/TestCase.php` — lớp base cho các test PHPUnit.
- `tests/CreatesApplication.php` — helper tạo app cho tests.
- `artisan` — Laravel CLI (chạy migration, queue, schedule...).
- `composer.json` — dependency PHP và autoload config.
- `composer.lock` — lockfile Composer.

Routes & entry
- `routes/web.php` — routes web (UI + admin).
- `routes/console.php` — console command bindings.
- `routes/channels.php` — broadcast channel authorizations.
- `routes/auth.php` — các route auth helper (nếu có).
- `routes/api.php` — API endpoints trả JSON.

Environment & config
- `.env.example` — mẫu .env để tham khảo.
- `config/view.php` — cấu hình view/template.
- `config/session.php` — session driver/cấu hình.
- `config/services.php` — cấu hình service bên thứ ba (map .env).
- `config/sanctum.php` — cấu hình Laravel Sanctum.
- `config/queue.php` — cấu hình queue (database driver được dùng).
- `config/mail.php` — mailer (SMTP) cấu hình.
- `config/logging.php` — cấu hình logging.
- `config/hashing.php` — hashing (bcrypt/argon) cấu hình.
- `config/filesystems.php` — storage disk definitions.
- `config/database.php` — kết nối DB.
- `config/cors.php` — CORS rules.
- `config/cache.php` — cache stores.
- `config/broadcasting.php` — broadcast drivers.
- `config/auth.php` — authentication guards/providers.
- `config/app.php` — cài đặt chung ứng dụng.

Public (assets tĩnh)
- `public/index.php` — entry point web (bootstrap Laravel).
- `public/.htaccess` — Apache rewrite rules (nếu dùng Apache).
- `public/robots.txt` — robot policy cho search engines.

public/js
- `public/js/flightHelper.js` — logic giao diện đặt/vụ tìm chuyến (validate form, swap sân bay, passenger counts, step flow).
- `public/js/box_date.js` — đồng bộ và giới hạn ngày đi/đến cho datepickers.
- `public/js/offers_slider.js` — carousel/slider ưu đãi và hành vi prefill tìm kiếm.

public/css
- `public/css/style.css` — stylesheet chính cho giao diện công khai.
- `public/css/review_styles.css` — style cho trang review/checkout.
- `public/css/public.css` — styles bổ trợ public.
- `public/css/mybooking.css` — styles cho trang quản lý booking cá nhân.
- `public/css/flight-search.css` — style cho trang tìm chuyến.
- `public/css/booking_styles.css` — style cho flow đặt vé.
- `public/css/admin.css` — style cho admin dashboard.

public/images
- `public/images/*` — hình ảnh (logo, banner, destinations) dùng trên site.

Resources (source frontend & Blade views)
- `resources/js/bootstrap.js` — thiết lập axios, CSRF token, global JS.
- `resources/js/app.js` — entry JS cho Vite / Alpine bootstrapping.
- `resources/css/app.css` — entry CSS (Tailwind + PostCSS).
- `resources/views/welcome.blade.php` — view mặc định (welcome/home).

Views — layouts & components
- `resources/views/layouts/public.blade.php` — layout chung cho site public.
- `resources/views/layouts/admin.blade.php` — layout cho admin panel.
- `resources/views/layouts/_head.blade.php` — phần <head> chung.
- `resources/views/layouts/_nav.blade.php` — partial navigation.
- `resources/views/components/navbar.blade.php` — component navbar.
- `resources/views/components/footer.blade.php` — footer partial.
- `resources/views/components/modal.blade.php` — modal component.
- `resources/views/components/text-input.blade.php` — input component có xử lý lỗi.
- `resources/views/components/*` — nhiều partials nhỏ (buttons, dropdowns, input-error, input-label).

Views — search & booking
- `resources/views/search/mybooking.blade.php` — trang tìm/tra cứu booking.
- `resources/views/layouts/search/form_search_box.blade.php` — form tìm chuyến (partial).
- `resources/views/layouts/search/date_slider.blade.php` — date slider partial.
- `resources/views/layouts/search/booking_stepper.blade.php` — stepper hiển thị bước đặt vé.
- `resources/views/layouts/search/popular_offers.blade.php` — ô ưu đãi phổ biến.

Views — flights (flow đặt vé)
- `resources/views/flights/search/index.blade.php` — trang kết quả tìm chuyến.
- `resources/views/flights/search/_flight_list.blade.php` — danh sách chuyến.
- `resources/views/flights/search/_filter.blade.php` — bộ lọc tìm chuyến.
- `resources/views/flights/search/_summary_panel.blade.php` — panel tóm tắt giá/chọn.
- `resources/views/flights/search/_selected_flights.blade.php` — hiển thị chuyến đã chọn.
- `resources/views/flights/search/_no_results.blade.php` — hiển thị khi không có kết quả.
- `resources/views/flights/success.blade.php` — trang thông báo đặt vé thành công.

Views — passenger & review
- `resources/views/flights/passenger/book.blade.php` — form nhập hành khách (passenger info).
- `resources/views/flights/passenger/_customer_info.blade.php` — partial thông tin hành khách.
- `resources/views/flights/passenger/_contact_info.blade.php` — partial thông tin liên hệ.
- `resources/views/flights/review/index.blade.php` — trang xem lại + thanh toán.
- `resources/views/flights/review/_payment_card.blade.php` — partial thẻ thanh toán.
- `resources/views/flights/review/_terms_card.blade.php` — partial điều khoản.
- `resources/views/flights/review/_passenger_card.blade.php` — hiển thị thông tin hành khách.
- `resources/views/flights/review/_contact_card.blade.php` — hiển thị contact.

Auth views & controllers
- `resources/views/auth/login.blade.php` — form đăng nhập.
- `app/Http/Controllers/Auth/*` — controllers cho đăng ký, đăng nhập, reset password, verify email (RegisteredUserController, AuthenticatedSessionController, PasswordResetLinkController, NewPasswordController, VerifyEmailController, v.v.).
- `app/Http/Requests/Auth/LoginRequest.php` — validation logic cho login.

App (backend core)
- `app/Http/Controllers/Controller.php` — Base controller.
- `app/Http/Controllers/HomeController.php` — logic trang chủ.
- `app/Http/Controllers/FlightController.php` — endpoint tìm chuyến, lọc, trả JSON/Blade.
- `app/Http/Controllers/DestinationController.php` — trang điểm đến.
- `app/Http/Controllers/BookingController.php` — xử lý tạo booking: validate, reserve seats, persist `AppBooking`, tạo transaction.
- `app/Http/Controllers/PaymentController.php` — tích hợp VNPay: build URL redirect, verify signature(HMAC), xử lý IPN/Return.
- `app/Http/Controllers/AuthController.php` — controller auth tùy chỉnh nếu có.

App — Admin controllers
- `app/Http/Controllers/Admin/DashboardController.php` — dashboard admin.
- `app/Http/Controllers/Admin/FlightController.php` — quản lý chuyến trong admin.
- `app/Http/Controllers/Admin/BookingController.php` — quản lý booking (admin view).
- `app/Http/Controllers/Admin/AirportController.php` — quản lý sân bay (admin).

Jobs / Mail / Notifications
- `app/Jobs/SendBookingConfirmationEmail.php` — job queue gửi email xác nhận (nên chạy bằng `queue:work`).
- `app/Mail/BookingConfirmed.php` — mailable template gửi mail xác nhận.
- `app/Mail/NewsletterSubscription.php` — mailable đăng ký newsletter.
- `app/Notifications/BookingConfirmedNotification.php` — notification wrapper dùng để gửi qua mail/other channels.

Models (Eloquent)
- `app/Models/User.php` — model người dùng (casts, relations, auth).
- `app/Models/Flight.php` — model chuyến bay (số ghế, reserve/release logic).
- `app/Models/FlightSegment.php` — đoạn hành trình (legs).
- `app/Models/AppBooking.php` — model lưu booking (passenger details, status, idempotency key) — lưu ý dùng `$fillable`/`$casts` an toàn.
- `app/Models/AppBookingTransaction.php` — lưu transaction thanh toán (status, raw response).
- `app/Models/Airport.php` — data sân bay.
- `app/Models/Airline.php` — data hãng hàng không.

Helpers & Providers
- `app/Helpers/FlightPriceHelper.php` — helper tính giá vé, fees, breakdown.
- `app/Providers/*` — service providers (AppServiceProvider, AuthServiceProvider, EventServiceProvider, BroadcastServiceProvider, RouteServiceProvider).

Middleware
- `app/Http/Middleware/VerifyCsrfToken.php` — CSRF protection.
- `app/Http/Middleware/ValidateSignature.php` — validate signed requests (nếu dùng).
- `app/Http/Middleware/TrustProxies.php`, `TrustHosts.php` — cấu hình proxy/host.
- `app/Http/Middleware/TrimStrings.php` — trim request strings.
- `app/Http/Middleware/RedirectIfAuthenticated.php` — redirect khi đã login.
- `app/Http/Middleware/PreventRequestsDuringMaintenance.php` — maintenance mode handling.
- `app/Http/Middleware/EncryptCookies.php` — cookie encryption.
- `app/Http/Middleware/CheckAdmin.php` — middleware bảo vệ route admin.
- `app/Http/Middleware/Authenticate.php` — middleware xác thực user.

Database
- `database/factories/UserFactory.php` — factory tạo mẫu user cho tests/seed.
- `database/seeders/DatabaseSeeder.php` — seeder chính.
- `database/seeders/AirlineSeeder.php` — seed airlines.
- `database/seeders/AirportSeeder.php` — seed airports.
- `database/seeders/FlightSeeder.php` — seed sample flights.
- `database/migrations/*` — các migration tạo bảng (users, flights, app_bookings, app_booking_transactions, jobs, v.v.).
  - `2014_10_12_000000_create_users_table.php` — bảng users.
  - `2014_10_12_100000_create_password_reset_tokens_table.php` — password resets.
  - `2019_08_19_000000_create_failed_jobs_table.php` — failed_jobs (queue).
  - `2019_12_14_000001_create_personal_access_tokens_table.php` — personal access tokens (sanctum).
  - `2024_03_24_133945_create_airlines_table.php` — airlines.
  - `2024_03_24_133946_create_flights_table.php` — flights.
  - `2024_03_24_133947_create_app_bookings_table.php` — app_bookings.
  - `2024_03_24_133949_create_app_booking_transactions_table.php` — transactions.
  - Các migration 2026_* — bổ sung fields: passenger details, seats_reserved, idempotency_key, jobs table, v.v.

Storage & runtime
- `storage/` — runtime writable (sessions, cache, views, logs).
- `storage/framework/*` — cache, sessions, views (framework-managed).
- `storage/logs/laravel.log` — application logs.

Tests
- `tests/Feature/` — test tích hợp/feature.
- `tests/Unit/` — unit tests.
- `tests/TestCase.php` — base test class.

Bootstrap & vendor
- `bootstrap/app.php` — tạo instance ứng dụng.
- `bootstrap/cache/.gitignore` — ignore cache.
- `vendor/` — composer packages (thường không commit vào repo).

Một số lưu ý bảo mật & vận hành
- KHÔNG commit `.env` chứa secrets (SMTP, VNPay keys). Dùng secret manager khi deploy.
- Trong `PaymentController` phải so sánh HMAC bằng `hash_equals()` để tránh timing attack.
- Sử dụng explicit `$fillable` / `$casts` trong models để tránh mass-assignment hoặc lỗi cast.
- Đảm bảo worker queue đang chạy (`php artisan queue:work`) để job gửi mail hoạt động.

Muốn mình commit file này và tạo Pull Request với thay đổi không? Hoặc bạn muốn mình chèn trực tiếp mô tả tiếng Việt vào `PROJECT_FILE_STRUCTURE.md` (thay thế), hay giữ file tiếng Anh gốc và thêm file VN như vừa tạo?
