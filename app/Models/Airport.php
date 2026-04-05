<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'landmarks' => 'array',
    ];

    // Mối quan hệ với chuyến bay giữ nguyên
    public function outboundFlights()
    {
        return $this->hasMany(Flight::class, 'origin_id');
    }

    public function returnFlights()
    {
        return $this->hasMany(Flight::class, 'destination_id');
    }
}
