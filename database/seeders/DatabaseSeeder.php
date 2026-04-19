<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Airport;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. TẠO TÀI KHOẢN ADMIN
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Quản Trị Viên',
                'password' => bcrypt('password'),
                'phone' => '0988888888',
                'role' => 'admin',
            ]
        );

        // 2. TẠO DANH SÁCH HÃNG HÀNG KHÔNG
        $this->command->info('Đang tạo Hãng hàng không...');
        DB::table('airlines')->insert([
            ['code' => 'VN', 'name' => 'Vietnam Airlines', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'VJ', 'name' => 'Vietjet Air', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'QH', 'name' => 'Bamboo Airways', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'VU', 'name' => 'Vietravel Airlines', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. TẠO SÂN BAY VÀ DANH LAM THẮNG CẢNH (LƯU DẠNG JSON)
        $this->command->info('Đang tạo Sân bay và Danh lam thắng cảnh...');
        
        $destinationsData = [
            [
                'code' => 'HAN', 'city' => 'Hà Nội', 'name' => 'Sân bay Quốc tế Nội Bài', 
                'image' => 'https://images.unsplash.com/photo-1599708153386-62b212f7105a?w=600',
                'description' => 'Thủ đô ngàn năm văn hiến cổ kính, nhộn nhịp 36 phố phường và nền ẩm thực đường phố nức tiếng.',
                'landmarks' => [
                    ['name' => 'Hồ Hoàn Kiếm & Đền Ngọc Sơn', 'image' => 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?w=600', 'description' => 'Trái tim của thủ đô với Tháp Rùa cổ kính nằm soi bóng.'],
                    ['name' => 'Khu phố cổ Hà Nội', 'image' => 'https://images.unsplash.com/photo-1528127269322-539801943592?w=600', 'description' => 'Khám phá 36 phố phường nhộn nhịp, nếm thử phở cuốn.'],
                ]
            ],
            [
                'code' => 'SGN', 'city' => 'TP. Hồ Chí Minh', 'name' => 'Sân bay Quốc tế Tân Sơn Nhất', 
                'image' => 'https://images.unsplash.com/photo-1583417319070-4a69db38a482?w=600',
                'description' => 'Thành phố mang tên Bác năng động, sầm uất bậc nhất. Nơi giao thoa văn hóa, kiến trúc tráng lệ.',
                'landmarks' => [
                    ['name' => 'Nhà thờ Đức Bà & Bưu điện', 'image' => 'https://images.unsplash.com/photo-1583417319070-4a69db38a482?w=600', 'description' => 'Kiến trúc Pháp tuyệt đẹp nằm ngay giữa trung tâm Quận 1.'],
                    ['name' => 'Landmark 81', 'image' => 'https://images.unsplash.com/photo-1563810237072-04dbdc7d0483?w=600', 'description' => 'Tòa nhà cao nhất Việt Nam, biểu tượng cho sự phát triển.'],
                ]
            ],
            [
                'code' => 'DAD', 'city' => 'Đà Nẵng', 'name' => 'Sân bay Quốc tế Đà Nẵng', 
                'image' => 'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?w=600',
                'description' => 'Thành phố đáng sống. Khám phá Cầu Vàng sương mù, Ngũ Hành Sơn và bờ sông Hàn thơ mộng.',
                'landmarks' => [
                    ['name' => 'Bà Nà Hills & Cầu Vàng', 'image' => 'https://images.unsplash.com/photo-1583417657208-cb8c87948c08?w=600', 'description' => 'Bước đi trên đôi bàn tay khổng lồ giữa mây ngàn.'],
                    ['name' => 'Bán đảo Sơn Trà', 'image' => 'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?w=600', 'description' => 'Lá phổi xanh của Đà Nẵng, nơi có chùa Linh Ứng.'],
                ]
            ],
            [
                'code' => 'PQC', 'city' => 'Phú Quốc', 'name' => 'Sân bay Quốc tế Phú Quốc', 
                'image' => 'https://images.unsplash.com/photo-1555921015-5532091f6026?w=600',
                'description' => 'Đảo Ngọc vẫy gọi với bãi biển cát trắng mịn, nước trong xanh và các nghỉ dưỡng đẳng cấp.',
                'landmarks' => [
                    ['name' => 'Bãi Sao', 'image' => 'https://images.unsplash.com/photo-1580835003507-2e68fb26487e?w=600', 'description' => 'Một trong những bãi biển đẹp nhất hành tinh với cát trắng.'],
                    ['name' => 'Cáp treo Hòn Thơm', 'image' => 'https://images.unsplash.com/photo-1555921015-5532091f6026?w=600', 'description' => 'Tuyến cáp treo vượt biển dài nhất thế giới.'],
                ]
            ],
            [
                'code' => 'DLI', 'city' => 'Đà Lạt', 'name' => 'Sân bay Liên Khương', 
                'image' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?w=600',
                'description' => 'Thành phố ngàn hoa chìm trong sương mù. Trải nghiệm cái lạnh se se và rừng thông mộng mơ.',
                'landmarks' => [
                    ['name' => 'Hồ Tuyền Lâm', 'image' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?w=600', 'description' => 'Hồ nước ngọt lớn nhất Đà Lạt, được bao quanh bởi đồi thông.'],
                    ['name' => 'Quảng trường Lâm Viên', 'image' => 'https://images.unsplash.com/photo-1629853334641-a1b7216a7605?w=600', 'description' => 'Biểu tượng hoa dã quỳ bằng kính khổng lồ.'],
                ]
            ],
            [
                'code' => 'CXR', 'city' => 'Nha Trang', 'name' => 'Sân bay Quốc tế Cam Ranh', 
                'image' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?w=600',
                'description' => 'Hòn ngọc biển Đông, sỡ hữu những bãi tắm tuyệt đẹp, các hòn đảo hoang sơ và san hô rực rỡ.',
                'landmarks' => []
            ],
            [
                'code' => 'HPH', 'city' => 'Hải Phòng', 'name' => 'Sân bay Quốc tế Cát Bi', 
                'image' => 'https://images.unsplash.com/photo-1575408264798-b50b252663e6?w=600',
                'description' => 'Thành phố hoa phượng đỏ. Cửa ngõ để khám phá Vịnh Hạ Long kỳ quan và đảo Cát Bà hùng vĩ.',
                'landmarks' => []
            ],
            [
                'code' => 'UIH', 'city' => 'Quy Nhơn', 'name' => 'Sân bay Phù Cát', 
                'image' => 'https://images.unsplash.com/photo-1620956920197-068d813735b5?w=600',
                'description' => 'Bức tranh thủy mặc với Kỳ Co trong vắt, Eo Gió lộng gió và những tháp Chàm cổ kính.',
                'landmarks' => []
            ],
            [
                'code' => 'VCA', 'city' => 'Cần Thơ', 'name' => 'Sân bay Quốc tế Cần Thơ', 
                'image' => 'https://images.unsplash.com/photo-1614902150937-21a416bdfbce?w=600',
                'description' => 'Thủ phủ miền Tây sông nước trù phú. Dạo chợ nổi Cái Răng và nghe đờn ca tài tử.',
                'landmarks' => []
            ],
            [
                'code' => 'VII', 'city' => 'Vinh', 'name' => 'Sân bay Quốc tế Vinh', 
                'image' => 'https://images.unsplash.com/photo-1629555295573-047f636cc68f?w=600',
                'description' => 'Quê hương Bác Hồ. Điểm xuất phát lý tưởng để tắm biển Cửa Lò và thăm làng Sen yên bình.',
                'landmarks' => []
            ]
        ];

        // LƯU TRỰC TIẾP (Vì chúng ta đã có cột JSON 'landmarks' và ép kiểu array trong Model)
        foreach ($destinationsData as $data) {
            Airport::create($data); 
        }

        // 4. CHẠY FLIGHT SEEDER
        $this->command->info('Đang tạo Chuyến bay ảo...');
        $this->call([
            FlightSeeder::class,
        ]);
        
        $this->command->info('Hoàn tất toàn bộ dữ liệu mẫu!');
    }
}