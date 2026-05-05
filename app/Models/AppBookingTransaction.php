<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppBookingTransaction extends Model
{
    /**
     * AppBookingTransaction
     * Model lưu giao dịch thanh toán liên quan tới AppBooking.
     * - `status`: pending|success|failed
     * - `payment_response` cast sang array để lưu raw response từ cổng VNPay.
     */
    protected $fillable = [
        'booking_id', 'amount', 'payment_method', 
        'transaction_code', 'status', 'payment_response'
    ];

    protected $casts = [
        'payment_response' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(AppBooking::class, 'booking_id');
    }

    /**
     * Mối quan hệ: Giao dịch -> Yêu cầu hoàn tiền liên quan
     */
    public function refunds()
    {
        return $this->hasMany(Refund::class, 'original_transaction_id');
    }
}
