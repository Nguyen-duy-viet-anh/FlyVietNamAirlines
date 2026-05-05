<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Flight extends Model
{
    /**
     * Flight model
     * Lưu thông tin chuyến bay, bao gồm số ghế và logic reserve/release seats.
     * Hai hàm tĩnh `reserveSeats` và `releaseSeats` dùng update atomic query
     * với DB::raw để tránh race condition trong trạng thái concurrent booking.
     */
    use SoftDeletes;

    protected $fillable = [
        'airline_id', 'flight_number', 'origin_id', 'destination_id', 
        'departure_time', 'arrival_time', 'stops', 'price', 
        'total_seats', 'available_seats', 'economy_available', 'business_available', 'status'
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

    public static function reserveSeats(int $flightId, int $seatCount, string $ticketClass): bool
    {
        // Cố gắng giảm số lượng available_seats và class-specific column.
        // Trả về true nếu update thành công (1 row affected) — nghĩa là còn đủ ghế.
        $classColumn = self::resolveClassAvailabilityColumn($ticketClass);
        $seatCount = max(1, $seatCount);

        return self::whereKey($flightId)
            ->where('available_seats', '>=', $seatCount)
            ->where($classColumn, '>=', $seatCount)
            ->update([
                'available_seats' => DB::raw('available_seats - ' . $seatCount),
                $classColumn => DB::raw($classColumn . ' - ' . $seatCount),
            ]) === 1;
    }

    public static function releaseSeats(int $flightId, int $seatCount, string $ticketClass): bool
    {
        // Tăng lại số ghế khi hủy/failed payment. Đây là thao tác đối lập với reserveSeats.
        $classColumn = self::resolveClassAvailabilityColumn($ticketClass);
        $seatCount = max(1, $seatCount);

        return self::whereKey($flightId)
            ->update([
                'available_seats' => DB::raw('available_seats + ' . $seatCount),
                $classColumn => DB::raw($classColumn . ' + ' . $seatCount),
            ]) === 1;
    }

    private static function resolveClassAvailabilityColumn(string $ticketClass): string
    {
        // Map ticket class -> column name (business/economy)
        return $ticketClass === 'business' ? 'business_available' : 'economy_available';
    }
}
