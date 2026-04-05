@extends('layouts.admin')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2 style="color: #2c3e50; margin-top: 0; margin-bottom: 20px;">Cập nhật: {{ $airport->city }}</h2>

    <form action="{{ route('admin.airports.update', $airport->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 10px;">📸 Upload Ảnh mới (Từ máy tính)</label>
            <input type="file" name="upload_image" accept="image/*" style="width: 100%; padding: 10px; border: 1px dashed #ccc; background: #f9f9f9; border-radius: 4px;">
            
            @if($airport->image)
                <div style="margin-top: 15px;">
                    <small style="color: #666;">Ảnh đang sử dụng:</small><br>
                    <img src="{{ $airport->image }}" style="width: 200px; height: 120px; object-fit: cover; border-radius: 8px; margin-top: 5px; border: 2px solid #ddd;">
                </div>
            @endif
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 10px;">📝 Viết Mô tả hấp dẫn</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: Arial;">{{ $airport->description }}</textarea>
        </div>

        <div style="display: flex; justify-content: space-between;">
            <a href="{{ route('admin.airports.index') }}" class="btn" style="background: #ecf0f1; color: #333; text-decoration: none;">Trở lại</a>
            <button type="submit" class="btn btn-primary" style="font-size: 16px;">💾 Lưu thay đổi</button>
        </div>
    </form>
</div>
@endsection