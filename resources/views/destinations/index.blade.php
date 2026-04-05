@extends('layouts.public')

@section('content')
<div class="container">
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="color: #2c3e50; font-size: 36px; margin-bottom: 10px;">🌍 Khám phá các Điểm đến tuyệt vời</h1>
        <p style="color: #7f8c8d; font-size: 18px;">Những vùng đất mới đang chờ bạn khám phá. Đặt vé ngay hôm nay!</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
        
        @forelse($destinations as $dest)
        <div class="card" style="padding: 0; overflow: hidden; border-radius: 12px; transition: transform 0.3s ease; box-shadow: 0 10px 20px rgba(0,0,0,0.08);">
            
            <div style="height: 200px; overflow: hidden; position: relative;">
                <img src="{{ $dest->image }}" alt="{{ $dest->city }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                
                <div style="position: absolute; top: 15px; right: 15px; background: rgba(0,0,0,0.6); color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                    ✈️ {{ $dest->code }}
                </div>
            </div>

            <div style="padding: 20px;">
                <h3 style="margin-top: 0; color: #0056b3; font-size: 22px;">{{ $dest->city }}</h3>
                <p style="color: #666; font-size: 14px; line-height: 1.6; height: 65px; overflow: hidden; text-overflow: ellipsis;">
                    {{ $dest->description }}
                </p>
                
                <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
                
                <a href="{{ route('destinations.show', $dest->id) }}" class="btn btn-primary" style="display: block; text-align: center; border-radius: 20px; background-color: #3498db;">
                    📖 Xem chi tiết
                </a>
            </div>
        </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: white; border-radius: 8px;">
                <h3 style="color: #95a5a6;">Đang cập nhật hình ảnh các điểm đến...</h3>
            </div>
        @endforelse

    </div>
</div>
@endsection