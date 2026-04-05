<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppBookingTransaction extends Model
{
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
}
