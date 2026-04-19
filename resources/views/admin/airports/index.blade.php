@extends('layouts.admin')

@section('content')
    <div class="card">
        <h2 class="admin-title">Quản lý Hình ảnh Địa điểm</h2>

        @if(session('success'))
            <div class="alert-success-custom">
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
                            <strong class="text-blue-16">{{ $airport->city }}</strong><br>
                            <small class="text-muted">{{ $airport->name }}</small>
                        </td>
                        <td>
                            @if($airport->image)
                                <img src="{{ $airport->image }}" alt="Ảnh" class="table-img-preview">
                            @else
                                <span class="badge-error">Chưa có ảnh</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-desc-truncate">
                                {{ $airport->description ?? 'Chưa có mô tả...' }}
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.airports.edit', $airport->id) }}" class="btn btn-update-orange">
                                Cập nhật</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection