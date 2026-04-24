<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flight;
use App\Models\FlightSegment;
use App\Models\Airport;
use App\Models\Airline;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class FlightSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $airports = Airport::pluck('id')->toArray();
        $airlines = Airline::pluck('id')->toArray();

        if (empty($airports) || empty($airlines)) {
            return;
        }

        $totalFlights = 50000;
        $chunkSize = 1000;

        $flightsData = [];
        $segmentsData = [];

        // Lấy ID tiếp theo để nối chặng bay chính xác (Tránh lỗi nếu không chạy migrate:fresh)
        $currentFlightId = Flight::max('id') + 1;
        if (!$currentFlightId) $currentFlightId = 1;

        $now = Carbon::now();

        $this->command->info("Đang tạo $totalFlights chuyến bay (Có chia ghế Phổ thông/Thương gia)... Vui lòng đợi!");

        for ($i = 0; $i < $totalFlights; $i++) {
            $origin_id = $faker->randomElement($airports);
            do {
                $destination_id = $faker->randomElement($airports);
            } while ($origin_id == $destination_id);

            $departure_time = Carbon::now()->addDays($faker->numberBetween(0, 60))
                ->setTime($faker->numberBetween(5, 22), $faker->randomElement([0, 15, 30, 45]));

            $isTransit = $faker->boolean(20);
            $stops = $isTransit ? 1 : 0;
            $flightDurationHours = $isTransit ? $faker->numberBetween(4, 8) : $faker->numberBetween(1, 3);
            $arrival_time = $departure_time->copy()->addHours($flightDurationHours)->addMinutes($faker->randomElement([0, 15, 30]));
            
            // --- LOGIC TÍNH GHẾ MỚI ---
            $total_seats = $faker->randomElement([150, 180, 200, 250, 300, 350]);
            
            // Thương gia chiếm 5% - 15% tổng ghế
            $business_capacity = (int)($total_seats * $faker->randomFloat(2, 0.05, 0.15));
            // Phổ thông là phần còn lại
            $economy_capacity = $total_seats - $business_capacity;

            // Số ghế còn trống ngẫu nhiên
            $business_available = $faker->numberBetween(0, $business_capacity); 
            $economy_available = $faker->numberBetween(10, $economy_capacity); 
            // --------------------------

            $airline_id = $faker->randomElement($airlines);
            $flight_number = strtoupper($faker->lexify('??')) . '-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT);

            // Gom dữ liệu chuyến bay vào mảng
            $flightsData[] = [
                'id' => $currentFlightId,
                'airline_id' => $airline_id,
                'flight_number' => $flight_number,
                'origin_id' => $origin_id,
                'destination_id' => $destination_id,
                'departure_time' => $departure_time->format('Y-m-d H:i:s'),
                'arrival_time' => $arrival_time->format('Y-m-d H:i:s'),
                'stops' => $stops,
                'price' => $faker->numberBetween(10, 50) * 100000,
                
                // Cột ghế đã được cập nhật
                'total_seats' => $total_seats,
                'economy_available' => $economy_available,
                'business_available' => $business_available,
                'available_seats' => $economy_available + $business_available,
                
                'status' => 'scheduled',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($isTransit) {
                do {
                    $transit_id = $faker->randomElement($airports);
                } while ($transit_id == $origin_id || $transit_id == $destination_id);

                $segment1_arrival = $departure_time->copy()->addMinutes(($flightDurationHours * 60) * 0.4);
                $segmentsData[] = [
                    'flight_id' => $currentFlightId,
                    'airline_id' => $airline_id,
                    'flight_number' => $flight_number . 'A',
                    'origin_id' => $origin_id,
                    'destination_id' => $transit_id,
                    'departure_time' => $departure_time->format('Y-m-d H:i:s'),
                    'arrival_time' => $segment1_arrival->format('Y-m-d H:i:s'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $segment2_departure = $segment1_arrival->copy()->addHours($faker->numberBetween(1, 2));
                $segmentsData[] = [
                    'flight_id' => $currentFlightId,
                    'airline_id' => $airline_id,
                    'flight_number' => $flight_number . 'B',
                    'origin_id' => $transit_id,
                    'destination_id' => $destination_id,
                    'departure_time' => $segment2_departure->format('Y-m-d H:i:s'),
                    'arrival_time' => $arrival_time->format('Y-m-d H:i:s'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $currentFlightId++;

            // Khi gom đủ 1000 chuyến, đẩy vào DB 1 lượt rồi reset mảng
            if (count($flightsData) >= $chunkSize) {
                Flight::insert($flightsData);
                FlightSegment::insert($segmentsData);
                $flightsData = [];
                $segmentsData = [];
            }
        }

        // Đẩy nốt số lượng còn dư (nếu có)
        if (count($flightsData) > 0) {
            Flight::insert($flightsData);
            FlightSegment::insert($segmentsData);
        }

        $this->command->info("Đã tạo thành công 10.000 chuyến bay có phân hạng ghế!");
    }
}