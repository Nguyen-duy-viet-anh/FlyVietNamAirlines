<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSegment extends Model
{
    protected $fillable = [
        'flight_id', 'airline_id', 'flight_number', 
        'origin_id', 'destination_id', 'departure_time', 'arrival_time'
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function origin()
    {
        return $this->belongsTo(Airport::class, 'origin_id');
    }

    public function destination()
    {
        return $this->belongsTo(Airport::class, 'destination_id');
    }
}
