@extends('layouts.admin')

@section('content')
<div class="card admin-card-600">
    <h2 class="admin-title">Cập nhật: {{ $airport->city }}</h2>

    <form action="{{ route('admin.airports.update', $airport->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group-mb20">
            <label class="form-label-bold">Upload Ảnh mới (Từ máy tính)</label>
            <input type="file" name="upload_image" accept="image/*" class="form-input-dashed">
            
            @if($airport->image)
                <div class="mt-20">
                    <small class="text-muted">Ảnh đang sử dụng:</small><br>
                    <img src="{{ $airport->image }}" class="img-current-preview">
                </div>
            @endif
        </div>

        <div class="form-group-mb20">
            <label class="form-label-bold">Viết Mô tả hấp dẫn</label>
            <textarea name="description" rows="4" class="form-textarea-styled">{{ $airport->description }}</textarea>
        </div>

        <div class="flex-between">
            <a href="{{ route('admin.airports.index') }}" class="btn btn-secondary-custom">Trở lại</a>
            <button type="submit" class="btn btn-primary text-blue-16">Lưu thay đổi</button>
        </div>
    </form>
</div>
@endsection