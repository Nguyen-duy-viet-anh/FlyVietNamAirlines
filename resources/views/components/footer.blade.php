<footer class="main-footer">
    <!-- Newsletter Section (Top) -->
    <div class="footer-newsletter-row">
        <div class="newsletter-container">
            <span class="newsletter-label">Đăng ký nhận tin</span>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                @csrf
                <input type="email" name="email" class="newsletter-input" placeholder="Địa chỉ Email" required>
                <button type="submit" class="newsletter-btn">Đăng ký</button>
                
                @if(session('success_newsletter'))
                    <div class="newsletter-message success-message">
                        {{ session('success_newsletter') }}
                    </div>
                @endif
                @if(session('error_newsletter'))
                    <div class="newsletter-message error-message">
                        {{ session('error_newsletter') }}
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Main Footer Links Section (Middle) -->
    <div class="footer-links-row">
        <div class="footer-container grid-5-cols">
            
            <!-- Col 1: Brand & Social -->
            <div class="footer-col brand-col">
                <h3 class="footer-heading">Chào mừng đến với FlyVietNamAirlines</h3>
                <p class="footer-text">
                    Một kỳ nghỉ là cơ hội tuyệt vời để ngắm nhìn những cảnh quan mới, gặp gỡ những người bạn mới, và trải nghiệm những nền văn hóa khác biệt. Việc ghé thăm những điểm đến tuyệt đẹp cùng chúng tôi chắc chắn sẽ là một lựa chọn lý tưởng. Chúng tôi sẽ chứng minh cho bạn thấy rằng việc bay cùng chúng tôi chưa bao giờ dễ dàng, an toàn và thú vị đến thế.
                </p>
                <div class="footer-social-payment">
                    <div class="social-box">
                        <h4 class="sub-heading">Theo dõi chúng tôi</h4>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-google-plus-g"></i></a>
                        </div>
                    </div>
                    <div class="payment-box">
                        <h4 class="sub-heading">Thanh toán</h4>
                        <div class="payment-icons">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-jcb"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Col 2: About -->
            <div class="footer-col">
                <h3 class="footer-heading">FlyVietNamAirlines</h3>
                <ul class="footer-list">
                    <li><a href="#">Về chúng tôi</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>

            <!-- Col 3: Travel Info -->
            <div class="footer-col">
                <h3 class="footer-heading">Thông tin du lịch</h3>
                <ul class="footer-list">
                    <li><a href="#">Tin tức & Cập nhật</a></li>
                    <li><a href="#">Chính sách hành lý</a></li>
                    <li><a href="#">Mạng lưới đường bay</a></li>
                    <li><a href="#">Đội bay</a></li>
                    <li><a href="#">Điểm đến yêu thích</a></li>
                </ul>
            </div>

            <!-- Col 4: Manage Booking -->
            <div class="footer-col">
                <h3 class="footer-heading">Quản lý đặt chỗ</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('booking.mybooking') }}">Trạng thái đặt vé</a></li>
                    <li><a href="#">Yêu cầu hoàn vé</a></li>
                    <li><a href="#">Điều kiện giá vé</a></li>
                    <li><a href="#">Thay đổi chuyến bay</a></li>
                    <li><a href="#">Hướng dẫn thanh toán</a></li>
                </ul>
            </div>

            <!-- Col 5: Extra Services -->
            <div class="footer-col">
                <h3 class="footer-heading">Dịch vụ bổ sung</h3>
                <ul class="footer-list">
                    <li><a href="#">Khách sạn</a></li>
                    <li><a href="#">Tour du lịch</a></li>
                    <li><a href="#">Thuê ô tô</a></li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Copyright Section (Bottom) -->
    <div class="footer-copyright-row">
        <p>Copyright &copy; {{ date('Y') }} All Rights Reserved.</p>
    </div>
</footer>

<style>
/* Main Footer Wrapper */
.main-footer {
    background-color: #2b3b64; /* Adjust to match the image accurately */
    background-image: linear-gradient(to bottom, #2b3b64, #212c4f); /* Slight gradient to give depth */
    color: #fff;
    width: 100%;
    margin-top: 50px;
    font-family: 'Poppins', sans-serif;
}

/* Common Container */
.footer-container, .newsletter-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

/* --- Newsletter Row --- */
.footer-newsletter-row {
    padding: 30px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
}
.newsletter-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 30px;
    position: relative;
}
.newsletter-label {
    font-weight: 700;
    font-size: 16px;
    letter-spacing: 0.5px;
}
.newsletter-form {
    display: flex;
    align-items: center;
    position: relative;
    height: 40px;
}
.newsletter-input {
    padding: 0 15px;
    height: 100%;
    border: none;
    width: 320px;
    outline: none;
    font-size: 14px;
    font-family: inherit;
    border-radius: 3px 0 0 3px;
}
.newsletter-input::placeholder {
    color: #888;
}
.newsletter-btn {
    background-color: #f05a28;
    color: white;
    border: none;
    height: 100%;
    padding: 0 30px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    font-family: inherit;
    transition: background-color 0.2s;
    border-radius: 0 3px 3px 0;
}
.newsletter-btn:hover {
    background-color: #d84a1d;
}
.newsletter-message {
    position: absolute;
    bottom: -22px;
    left: 0;
    font-size: 12px;
    font-weight: 500;
}
.success-message { color: #4caf50; }
.error-message { color: #ff5252; }


/* --- Main Links Row --- */
.footer-links-row {
    padding: 50px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
}
.grid-5-cols {
    display: grid;
    grid-template-columns: 2.2fr 1fr 1fr 1fr 1fr;
    gap: 30px;
}
.footer-heading {
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #fff;
    letter-spacing: 0.5px;
}
.footer-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.footer-list li {
    margin-bottom: 12px;
}
.footer-list a {
    color: #cbd5e1;
    text-decoration: none;
    font-size: 13px;
    transition: color 0.2s;
}
.footer-list a:hover {
    color: #fff;
    text-decoration: underline;
}

/* Brand Column Details */
.footer-text {
    font-size: 13px;
    line-height: 1.7;
    color: #cbd5e1;
    margin-bottom: 30px;
    text-align: justify;
    padding-right: 20px;
}

.footer-social-payment {
    display: flex;
    gap: 40px;
}
.sub-heading {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #fff;
}
.social-icons {
    display: flex;
    gap: 10px;
}
.social-icons a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #fff;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}
.social-icons a:hover {
    background-color: #fff;
    color: #2b3b64;
}
.payment-icons {
    display: flex;
    gap: 12px;
    font-size: 28px;
    color: #cbd5e1;
}

/* --- Copyright Row --- */
.footer-copyright-row {
    text-align: center;
    padding: 20px 0;
    font-size: 12px;
    color: #cbd5e1;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .grid-5-cols {
        grid-template-columns: 1fr 1fr;
    }
    .brand-col {
        grid-column: span 2;
    }
    .footer-text {
        padding-right: 0;
    }
}

@media (max-width: 768px) {
    .grid-5-cols {
        grid-template-columns: 1fr;
    }
    .brand-col {
        grid-column: span 1;
    }
    .newsletter-container {
        flex-direction: column;
        gap: 15px;
    }
    .newsletter-form {
        width: 100%;
    }
    .newsletter-input {
        width: 100%;
        border-radius: 3px;
        margin-bottom: 10px;
    }
    .newsletter-btn {
        width: 100%;
        border-radius: 3px;
        position: absolute;
        bottom: 0;
    }
    .newsletter-form {
        height: auto;
        flex-direction: column;
        background: transparent;
    }
    .newsletter-btn {
        position: relative;
        padding: 12px 30px;
        margin-top: 10px;
    }
    .footer-social-payment {
        flex-direction: column;
        gap: 20px;
    }
}
</style>
