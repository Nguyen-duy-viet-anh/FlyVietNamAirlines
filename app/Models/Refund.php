<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'original_transaction_id',
        'amount',
        'currency',
        'method',
        'reason',
        'status',
        'requested_by',
        'processed_by',
        'processed_at',
        'meta',
        'email_sent',
    ];

    protected $casts = [
        'meta' => 'array',
        'processed_at' => 'datetime',
        'email_sent' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(AppBooking::class, 'booking_id');
    }

    public function originalTransaction()
    {
        return $this->belongsTo(AppBookingTransaction::class, 'original_transaction_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
