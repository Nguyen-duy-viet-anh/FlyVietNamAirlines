<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airline;

class AirlineSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = [
            ['code' => 'VN', 'name' => 'Vietnam Airlines'],
            ['code' => 'VJ', 'name' => 'VietJet Air'],
            ['code' => 'QH', 'name' => 'Bamboo Airways'],
            ['code' => 'VU', 'name' => 'Vietravel Airlines'],
        ];

        foreach ($airlines as $airline) {
            Airline::create($airline);
        }
    }
}