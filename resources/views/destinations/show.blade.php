@extends('layouts.public')

@section('content')
    <div style="position: relative; width: 100%; height: 400px; border-radius: 15px; overflow: hidden; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
        <img src="{{ $destination->image }}" style="width: 100%; height: 100%; object-fit: cover; filter: brightness(0.7);">

        <div style="position: absolute; bottom: 40px; left: 40px; color: white;">
            <span style="background: #ff9800; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 14px; margin-bottom: 10px; display: inline-block;">
                ✈️ Sân bay {{ $destination->code }}
            </span>
            <h1 style="font-size: 48px; margin: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                Khám phá {{ $destination->city }}
            </h1>
            <p style="font-size: 18px; max-width: 600px; margin-top: 10px; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                {{ $destination->description }}
            </p>
        </div>
    </div>

    <div class="grid-2" style="display: grid; grid-template-columns: 1fr 350px; gap: 40px;">
        <div>
            <h2 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 25px;">
                📸 Các điểm đến không thể bỏ lỡ
            </h2>

            @forelse($destination->landmarks ?? [] as $landmark)
                <div style="display: flex; gap: 20px; margin-bottom: 25px; background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <img src="{{ $landmark['image'] }}" style="width: 150px; height: 120px; object-fit: cover; border-radius: 8px;">
                    <div>
                        <h3 style="margin-top: 0; color: #d35400; margin-bottom: 8px;">{{ $landmark['name'] }}</h3>
                        <p style="color: #555; font-size: 14px; line-height: 1.5;">{{ $landmark['description'] }}</p>
                    </div>
                </div>
            @empty
                <p style="color: #7f8c8d;">Đang cập nhật danh lam thắng cảnh cho địa điểm này...</p>
            @endforelse
        </div>

        <div>
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); position: sticky; top: 20px; text-align: center;">
                <h3 style="color: #2c3e50; margin-top: 0;">Sẵn sàng tới {{ $destination->city }}?</h3>
                <p style="color: #666; margin-bottom: 25px;">Hàng trăm chuyến bay giá rẻ đang chờ đón bạn. Đừng bỏ lỡ cơ hội khám phá vùng đất tuyệt vời này!</p>

                <a href="/?destination_id={{ $destination->id }}" class="btn btn-primary"
                    style="display: block; width: 100%; font-size: 18px; padding: 15px; border-radius: 30px; box-shadow: 0 4px 10px rgba(255, 152, 0, 0.3); text-decoration: none; color: white;">
                    ✈️ TÌM VÉ ĐI {{ mb_strtoupper($destination->city) }} NGAY
                </a>

                <a href="{{ route('destinations.index') }}"
                    style="display: inline-block; margin-top: 15px; color: #95a5a6; text-decoration: none;">
                    ⬅️ Xem các điểm đến khác
                </a>
            </div>
        </div>
    </div>
@endsection