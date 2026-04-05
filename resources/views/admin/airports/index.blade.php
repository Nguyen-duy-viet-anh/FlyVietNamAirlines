@extends('layouts.admin')

@section('content')
<div class="card">
    <h2 style="color: #2c3e50; margin-top: 0; margin-bottom: 20px;">🌍 Quản lý Hình ảnh Địa điểm</h2>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Tên Sân bay / Thành phố</th>
                <th>Hình ảnh hiện tại</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($airports as $airport)
            <tr>
                <td><strong>{{ $airport->code }}</strong></td>
                <td>
                    <strong style="color: #0056b3; font-size: 16px;">{{ $airport->city }}</strong><br>
                    <small style="color: #666;">{{ $airport->name }}</small>
                </td>
                <td>
                    @if($airport->image)
                        <img src="{{ $airport->image }}" alt="Ảnh" style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc;">
                    @else
                        <span style="color: #e74c3c; font-size: 12px;">❌ Chưa có ảnh</span>
                    @endif
                </td>
                <td>
                    <div style="max-width: 300px; max-height: 60px; overflow: hidden; text-overflow: ellipsis; color: #555; font-size: 13px;">
                        {{ $airport->description ?? 'Chưa có mô tả...' }}
                    </div>
                </td>
                <td>
                    <a href="{{ route('admin.airports.edit', $airport->id) }}" class="btn" style="background: #f39c12; color: white; padding: 5px 10px; text-decoration: none; font-size: 13px;">✏️ Cập nhật</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection