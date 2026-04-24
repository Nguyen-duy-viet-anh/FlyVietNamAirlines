{{-- Payment Option Section --}}
<div class="review-section">
    <div class="section-title">Phương thức thanh toán</div>
    <div class="section-content">
        <div class="vnpay-info-box payment-box">
            <div class="payment-selection">
                <input type="radio" name="payment_method" value="vnpay" checked class="payment-radio-custom">
            </div>
            <img src="{{ asset('images/logo_vnpay.png') }}" alt="VNPay" class="vnpay-logo-large payment-logo-large">
            <div class="vnpay-text">
                <p class="text-bold mb-5">Cổng thanh toán VNPay</p>
                <p class="text-muted mb-0">Thanh toán an toàn qua Ứng dụng ngân hàng, Thẻ ATM, Visa, Master Card...</p>
            </div>
        </div>
        <div class="payment-notice-box">
            * Lưu ý: Bạn sẽ được chuyển đến trang thanh toán của VNPay để hoàn tất giao dịch.
        </div>
    </div>
</div>
