{{-- Contact Information Section --}}
<div class="review-section">
    <div class="section-title">Thông tin liên hệ</div>
    <div class="section-content">
        <table class="review-table">
            <thead>
                <tr>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Yêu cầu đặc biệt</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ ($passengerData['passenger_country_code'] ?? '') }}{{ $passengerData['passenger_phone'] }}
                    </td>
                    <td>{{ $passengerData['passenger_email'] }}</td>
                    <td>{{ $passengerData['notes'] ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
