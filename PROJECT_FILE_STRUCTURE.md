# Dự án: FlyVietNamAirlines — Cấu trúc file (tự động)

Tạo: 2026-04-27
# FlyVietNamAirlines — Cấu trúc dự án (mô tả tiếng Việt)

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

## Sơ đồ kiến trúc (Mermaid)

Dưới đây là sơ đồ kiến trúc hệ thống ở mức cao, dùng Mermaid để dễ hình dung. Nếu muốn, mình có thể render ảnh từ sơ đồ này và lưu `public/docs/arch.svg`.

```mermaid
flowchart LR
    subgraph Browser["Trình duyệt / Client"]
        BrowserUI["UI (HTML / JS / CSS)"]
    end

    BrowserUI -->|HTTP| PublicIndex[/public/index.php<br/>Laravel Kernel/]
    PublicIndex --> Router["Router (routes/web.php, api.php)"]
    Router --> Controllers["Controllers\n(FlightController, BookingController,\nPaymentController, Admin/*)"]
    Controllers --> Views["Blade Views\nresources/views/*"]
    Controllers --> Models["Eloquent Models\nFlight, AppBooking, AppBookingTransaction, User"]
    Models --> DB["Database (migrations -> tables)"]

    BookingController["BookingController"] -->|create booking| AppBooking[(AppBooking)]
    BookingController -->|create txn| Transaction[(AppBookingTransaction)]
    BookingController -->|dispatch job| EmailJob[/SendBookingConfirmationEmail (queue)/]
    EmailJob --> Mailables["Mailable / Notification\napp/Mail/, app/Notifications/"]

    PaymentController -->|redirect/verify| VNPay[(VNPay Gateway)]
    Router --> PublicAssets["public/js, public/css, images"]
    BrowserUI --> PublicAssets

    subgraph Infra["Hạ tầng"]
        DB -->|storage| Storage["storage/, filesystems"]
        Queue["Queue (database)"] --> EmailJob
    end

    AdminUI["Admin Dashboard (Admin Controllers)"] --> Controllers

    click VNPay "https://vnpayment.vn" "VNPay (external)"
```

Giải thích ngắn:
- **Browser/UI**: tải assets tĩnh từ `public/` và gọi route.
- **Router**: phân hướng request tới controllers tương ứng.
- **Controllers**: xử lý business logic, gọi models / dispatch jobs.
- **Models**: lưu trữ bằng Eloquent vào DB (migrations tạo bảng).
- **Queue**: xử lý email bất đồng bộ (`php artisan queue:work`).

 -- Kết thúc sơ đồ --

Giải thích ngắn:
- **Browser/UI**: tải assets tĩnh từ `public/` và gọi route.
- **Router**: phân hướng request tới controllers tương ứng.
- **Controllers**: xử lý business logic, gọi models / dispatch jobs.
- **Models**: lưu trữ bằng Eloquent vào DB (migrations tạo bảng).
- **Queue**: xử lý email bất đồng bộ (`php artisan queue:work`).

## Giải thích chi tiết các file chính

Phần này mô tả ý nghĩa, vị trí và lưu ý khi chỉnh sửa cho các file/thư mục chính trong repo (Tiếng Việt). Nếu bạn muốn giải thích sâu "từng file một" cho toàn bộ repo, mình đề xuất làm theo từng thư mục (ví dụ: `app/` → `resources/views/` → `public/js/`) — nói folder nào mình sẽ xuất file chi tiết cho folder đó.

Root (file gốc):
- `artisan`: CLI entry của Laravel. Dùng để chạy migration, seed, start queue, chạy lệnh schedule, v.v.
- `composer.json` / `composer.lock`: khai báo dependency PHP và autoload. Thay đổi bằng `composer require` và commit `composer.lock` khi cần.
- `package.json` / `package-lock.json`: scripts và dependency frontend (Vite/Tailwind). Dùng `npm install`, `npm run dev`/`build`.
- `vite.config.js`: cấu hình Vite (entrypoints, alias). Nếu thêm file JS/CSS mới, cập nhật entry tại đây.
- `postcss.config.js`: config PostCSS (Tailwind, autoprefixer).
- `tailwind.config.js`: cấu hình theme/plugins/content paths.
- `README.md`: hướng dẫn cài đặt cơ bản (composer install, npm install, .env, migrate, queue worker).

Environment & config:
- `.env.example`: mẫu các biến môi trường bắt buộc: `APP_URL`, DB settings, `MAIL_*`, `VNP_TMN_CODE`, `VNP_HASH_SECRET`, `VNP_URL`, `QUEUE_CONNECTION`.
- `config/*.php`: chứa thiết lập cho app. Thay đổi config phải chạy `php artisan config:clear` hoặc deploy lại cache config.

Routes & entry:
- `public/index.php`: bootstrap Laravel kernel.
- `routes/web.php`: routes web (Blade + form POST). Thường định nghĩa route cho search, booking, payment return.
- `routes/api.php`: API trả JSON (stateless). Kiểm tra middleware `api`.

App - Controllers (tóm tắt & lưu ý):
- `app/Http/Controllers/FlightController.php`: xử lý tìm chuyến và filter. Nên làm pagination và cache các truy vấn tốn kém.
- `app/Http/Controllers/BookingController.php`: luồng tạo booking: validate input → DB transaction → reserve seats (atomic update) → tạo `AppBooking` → tạo `AppBookingTransaction` (status `pending`) → redirect tới VNPay. Lưu ý dùng `idempotency_key` và kiểm tra seat availability bằng cập nhật atomic để tránh oversell.
- `app/Http/Controllers/PaymentController.php`: xây dựng URL VNPay, tạo/kiểm tra `vnp_SecureHash` bằng `hash_hmac('sha512', data, VNP_HASH_SECRET)` và so sánh bằng `hash_equals()`. Xử lý `return`/`ipn` phải idempotent (API có thể gửi nhiều lần).
- `app/Http/Controllers/Admin/*`: CRUD quản lý (Flights, Airports, Bookings). Bảo vệ bằng middleware admin.

App - Models (tóm tắt):
- `app/Models/Flight.php`: fields: `total_seats`, `seats_available`, `price`, relations tới `FlightSegment`. Implement `reserveSeats($n)` với câu lệnh atomic: `UPDATE flights SET seats_available = seats_available - ? WHERE id = ? AND seats_available >= ?` và kiểm tra affected rows.
- `app/Models/AppBooking.php`: lưu booking (passenger details JSON, status, reference). Dùng `protected $fillable = [...]` và `protected $casts = ['passenger_details' => 'array']`.
- `app/Models/AppBookingTransaction.php`: lưu giao dịch (amount, status, gateway_response). Giữ `raw_response` dạng JSON để debug.
- `app/Models/User.php`: model auth. Đảm bảo `casts` cho datetime và boolean đúng.

Database (migrations & seeders):
- `database/migrations/*`: kiểm tra migration tạo các cột: `app_bookings` có `passenger_details` (json), `seats_reserved` (int), `idempotency_key` (string), `status` (enum/string).
- `database/seeders/*`: seed data (AirlineSeeder, AirportSeeder, FlightSeeder). Dùng `php artisan db:seed`.

Jobs / Mail / Notifications:
- `app/Jobs/SendBookingConfirmationEmail.php`: queued job, nhận booking id, gửi mailable/notification. Đảm bảo worker queue: `php artisan queue:work` chạy trong background (supervisor/systemd).
- `app/Mail/BookingConfirmed.php` / `app/Notifications/BookingConfirmedNotification.php`: template email/notification.

Resources & Frontend:
- `resources/js/bootstrap.js`: cấu hình axios (CSRF, baseURL). Kiểm tra `axios.defaults.headers.common['X-CSRF-TOKEN']`.
- `resources/js/app.js`: entry, import Alpine/Stimulus nếu dùng.
- `resources/css/app.css`: entry CSS (Tailwind imports).
- `resources/views/layouts/public.blade.php`: layout chính, chứa head, nav, footer và `@yield('content')`.
- `resources/views/flights/*`: views cho search, passenger, review, success. Chỉnh view để thay đổi flow hoặc thêm tracking.

Public assets (client-side helpers):
- `public/js/flightHelper.js`: xử lý UI đặt vé (validate, swap sân bay, số lượng hành khách, stepper). Nếu logic grows, hãy di chuyển vào `resources/js` và build qua Vite.
- `public/js/box_date.js`: quản lý datepicker (min/max dates, sync return date).
- `public/js/offers_slider.js`: carousel ưu đãi.

Helpers & Providers:
- `app/Helpers/FlightPriceHelper.php`: tách logic tính giá (base fare, tax, fee). Khi thay đổi cách tính giá, sửa ở đây để toàn hệ thống dùng cùng rule.
- `app/Providers/*`: đăng ký service bindings, event listeners, broadcast channels.

Middleware & bảo mật:
- `app/Http/Middleware/VerifyCsrfToken.php`: bảo vệ CSRF cho POST.
- `app/Http/Middleware/CheckAdmin.php`: xác thực quyền admin.
- Lưu ý bảo mật: tránh `protected $guarded = []`, sử dụng `fillable`; so sánh HMAC bằng `hash_equals()`; không commit `.env`.

Storage & runtime:
- `storage/` chứa logs, cache, sessions. Đừng commit.

Tests:
- `tests/Feature/*` và `tests/Unit/*`: viết tests cho luồng booking và verify payment signature. Chạy: `vendor/bin/phpunit`.

Chạy nhanh (dev):
```powershell
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev
php artisan queue:work
php artisan serve --host=0.0.0.0 --port=8000
```

Ghi chú cuối: mình đã mở rộng mô tả cho các file/thư mục chính. Nếu bạn muốn mình giải thích "từng file" toàn bộ repo, chọn folder để mình xuất tài liệu chi tiết theo folder (mỗi folder một file). Bạn muốn bắt đầu với folder nào?

-- Kết thúc phần giải thích chi tiết --

*** End Patch
resources/views/admin/dashboard/_recent_bookings.blade.php
