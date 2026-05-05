@extends('layouts.admin')

@section('content')
<div class="card admin-card-600">
    <h2 class="admin-title">Cập nhật: {{ $airport->city }}</h2>

    <form action="{{ route('admin.airports.update', $airport->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Hiển thị lỗi validation --}}
        @if($errors->any())
            <div class="alert-error-custom" style="background:#fdecea;color:#b71c1c;border:1px solid #f5c6cb;padding:12px 16px;border-radius:6px;margin-bottom:20px;">
                <strong>⚠ Có lỗi xảy ra:</strong>
                <ul style="margin:8px 0 0 18px;padding:0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group-mb20">
            <label class="form-label-bold">Upload Ảnh mới (Từ máy tính)</label>
            <input type="file" name="upload_image" accept="image/jpeg,image/png,image/jpg,image/webp" class="form-input-dashed">
            <small style="color:#666;display:block;margin-top:5px;">Chấp nhận: JPG, JPEG, PNG, WEBP. Tối đa 5MB.</small>
            
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

        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            <h3 class="form-label-bold" style="font-size: 18px; color: #003580;">Quản lý các địa danh nổi tiếng</h3>
            <p style="font-size: 13px; color: #666; margin-bottom: 15px;">Thêm các địa điểm không thể bỏ lỡ tại {{ $airport->city }}</p>
            
            <div id="landmarks-container">
                @php $landmarks = $airport->landmarks ?? []; @endphp
                @foreach($landmarks as $index => $landmark)
                    <div class="landmark-item" style="background: #f9f9f9; border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 15px; position: relative;">
                        <button type="button" onclick="this.parentElement.remove()" style="position: absolute; top: 10px; right: 10px; background: #ff4d4d; color: white; border: none; border-radius: 4px; cursor: pointer; padding: 2px 8px;">&times;</button>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px;">
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: bold;">Tên địa danh</label>
                                <input type="text" name="landmarks[{{ $index }}][name]" value="{{ $landmark['name'] ?? '' }}" class="form-input-dashed" style="padding: 5px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: bold;">Ảnh địa danh</label>
                                <input type="file" name="landmarks[{{ $index }}][image_file]" class="form-input-dashed" style="padding: 5px; font-size: 12px;">
                                <input type="hidden" name="landmarks[{{ $index }}][image]" value="{{ $landmark['image'] ?? '' }}">
                                @if(!empty($landmark['image']))
                                    <img src="{{ $landmark['image'] }}" style="height: 50px; margin-top: 5px; border-radius: 4px; display: block;">
                                @endif
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: bold;">Mô tả địa danh</label>
                            <textarea name="landmarks[{{ $index }}][description]" rows="2" class="form-textarea-styled" style="padding: 5px;">{{ $landmark['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-landmark" style="background: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-size: 13px; margin-bottom: 20px;">
                + Thêm địa danh mới
            </button>
        </div>

        <script>
            document.getElementById('add-landmark').addEventListener('click', function() {
                const container = document.getElementById('landmarks-container');
                const index = Date.now(); // Dùng timestamp để tránh trùng index khi add/delete liên tục
                const html = `
                    <div class="landmark-item" style="background: #f9f9f9; border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 15px; position: relative;">
                        <button type="button" onclick="this.parentElement.remove()" style="position: absolute; top: 10px; right: 10px; background: #ff4d4d; color: white; border: none; border-radius: 4px; cursor: pointer; padding: 2px 8px;">&times;</button>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px;">
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: bold;">Tên địa danh</label>
                                <input type="text" name="landmarks[${index}][name]" class="form-input-dashed" style="padding: 5px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: bold;">Ảnh địa danh</label>
                                <input type="file" name="landmarks[${index}][image_file]" class="form-input-dashed" style="padding: 5px; font-size: 12px;">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: bold;">Mô tả địa danh</label>
                            <textarea name="landmarks[${index}][description]" rows="2" class="form-textarea-styled" style="padding: 5px;"></textarea>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            });
        </script>

        <div class="flex-between">
            <a href="{{ route('admin.airports.index') }}" class="btn btn-secondary-custom">Trở lại</a>
            <button type="submit" class="btn btn-primary text-blue-16">Lưu thay đổi</button>
        </div>
    </form>
</div>
@endsection