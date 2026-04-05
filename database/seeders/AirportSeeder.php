<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            ['code' => 'HAN', 'name' => 'Sân bay Quốc tế Nội Bài', 'city' => 'Hà Nội'],
            ['code' => 'SGN', 'name' => 'Sân bay Quốc tế Tân Sơn Nhất', 'city' => 'Hồ Chí Minh'],
            ['code' => 'DAD', 'name' => 'Sân bay Quốc tế Đà Nẵng', 'city' => 'Đà Nẵng'],
            ['code' => 'CXR', 'name' => 'Sân bay Quốc tế Cam Ranh', 'city' => 'Nha Trang'],
            ['code' => 'PQC', 'name' => 'Sân bay Quốc tế Phú Quốc', 'city' => 'Phú Quốc'],
            ['code' => 'HPH', 'name' => 'Sân bay Quốc tế Cát Bi', 'city' => 'Hải Phòng'],
            ['code' => 'VCA', 'name' => 'Sân bay Quốc tế Cần Thơ', 'city' => 'Cần Thơ'],
        ];

        foreach ($airports as $airport) {
            Airport::create($airport);
        }
    }
}