<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppBooking extends Model
{
    use HasFactory;

    // Cho phép insert dữ liệu vào tất cả các cột
    protected $guarded = [];

    /**
     * Mối quan hệ: Đơn vé -> Chuyến bay ĐI
     */
    public function outboundFlight()
    {
        return $this->belongsTo(Flight::class, 'outbound_flight_id');
    }

    /**
     * Mối quan hệ: Đơn vé -> Chuyến bay VỀ (Dành cho vé khứ hồi)
     */
    public function returnFlight()
    {
        return $this->belongsTo(Flight::class, 'return_flight_id');
    }

    /**
     * Mối quan hệ: Đơn vé -> Giao dịch thanh toán VNPay
     */
    public function transaction()
    {
        // 1 Đơn vé sẽ có 1 Giao dịch
        return $this->hasOne(AppBookingTransaction::class, 'booking_id');
    }
}