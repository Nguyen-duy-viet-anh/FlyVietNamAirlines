<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppBooking extends Model
{
    use HasFactory;
    // Hạn chế mass-assignment: chỉ cho phép trường cần thiết
    protected $fillable = [
        'booking_code',
        'user_id',
        'flight_type',
        'ticket_class',
        'outbound_class',
        'return_class',
        'outbound_flight_id',
        'return_flight_id',
        'adult_count',
        'child_count',
        'infant_count',
        'total_amount',
        'status',
        'payment_status',
        'passenger_name',
        'passenger_email',
        'passenger_phone',
        'passenger_gender',
        'passenger_details',
        'notes',
        'idempotency_key',
        'seats_reserved',
    ];

    /**
     * AppBooking
     * Model lưu đơn đặt vé: chứa thông tin hành khách, trạng thái booking, trạng thái thanh toán,
     * và liên kết đến AppBookingTransaction.
     * `passenger_details` được cast sang array để dễ thao tác khi tải lên/hiển thị.
     */

    protected $casts = [
        'passenger_details' => 'array',
    ];

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

    /**
     * Mối quan hệ: Đơn vé -> Yêu cầu hoàn tiền
     */
    public function refunds()
    {
        return $this->hasMany(Refund::class, 'booking_id');
    }
}