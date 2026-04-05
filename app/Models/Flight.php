<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flight extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'airline_id', 'flight_number', 'origin_id', 'destination_id', 
        'departure_time', 'arrival_time', 'stops', 'price', 
        'total_seats', 'available_seats', 'status'
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    public function origin()
    {
        return $this->belongsTo(Airport::class, 'origin_id');
    }

    public function destination()
    {
        return $this->belongsTo(Airport::class, 'destination_id');
    }

    public function segments()
    {
        return $this->hasMany(FlightSegment::class);
    }
}
