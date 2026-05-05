@extends('layouts.admin')
@section('title', 'Quản lý Chuyến bay')

@section('content')
<div class="card">
    <div style="margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #eee;">
        <form action="{{ route('admin.flights.index') }}" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
            <div style="display: flex; flex-direction: column; gap: 5px;">
                <label style="font-size: 12px; font-weight: 600; color: #555;">Mã chuyến bay</label>
                <input type="text" name="flight_number" placeholder="Ví dụ: VN123" value="{{ request('flight_number') }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 150px;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 5px;">
                <label style="font-size: 12px; font-weight: 600; color: #555;">Hãng hàng không</label>
                <select name="airline_id" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 180px;">
                    <option value="">-- Tất cả hãng --</option>
                    @foreach($airlines as $airline)
                        <option value="{{ $airline->id }}" {{ request('airline_id') == $airline->id ? 'selected' : '' }}>
                            {{ $airline->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; flex-direction: column; gap: 5px;">
                <label style="font-size: 12px; font-weight: 600; color: #555;">Ngày đi</label>
                <input type="date" name="departure_date" value="{{ request('departure_date') }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 5px;">
                <label style="font-size: 12px; font-weight: 600; color: #555;">Ngày về</label>
                <input type="date" name="arrival_date" value="{{ request('arrival_date') }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary" style="background: #003580; color: white; border: none; padding: 9px 20px; border-radius: 4px; cursor: pointer; font-weight: 600;">
                    Lọc dữ liệu
                </button>
                @if(request()->anyFilled(['flight_number', 'airline_id', 'departure_date', 'arrival_date']))
                    <a href="{{ route('admin.flights.index') }}" style="padding: 9px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-size: 13px;">Xóa lọc</a>
                @endif
            </div>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px; text-align: left;">STT</th>
                <th style="width: 15%; text-align: left;">Mã chuyến</th>
                <th style="width: 20%; text-align: left;">Hãng hàng không</th>
                <th style="width: 25%; text-align: center;">Hành trình</th>
                <th style="width: 20%; text-align: left;">Thời gian khởi hành</th>
                <th style="width: 20%; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flights as $flight)
            <tr>
                <td>{{ ($flights->currentPage() - 1) * $flights->perPage() + $loop->iteration }}</td>
                <td>
                    <strong>{{ $flight->flight_number }}</strong>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                         <img src="{{ asset('images/' . ($flight->airline->name == 'Vietjet Air' ? 'Logo-VietjetAir.jpg' : ($flight->airline->name == 'Bamboo Airways' ? 'logo-bamboo-airways.jpg' : 'logo-vietnamAirlines.png'))) }}" 
                              style="width: 24px; height: 24px; object-fit: contain;" alt="">
                         {{ $flight->airline->name }}
                    </div>
                </td>
                <td style="text-align: center;">
                    <div style="font-weight: 500;">
                        {{ $flight->origin->code }} 
                        <i class="fas fa-arrow-right" style="font-size: 10px; color: #999; margin: 0 5px;"></i> 
                        {{ $flight->destination->code }}
                    </div>
                </td>
                <td>
                    {{ $flight->departure_time->format('d/m/Y H:i') }}
                </td>
                <td style="text-align: right;">
                    <a href="{{ route('admin.flights.passengers', $flight->id) }}" class="btn-view" style="text-decoration: none; color: #003580; font-weight: 600; font-size: 13px;">
                        <i class="fas fa-users"></i> Xem hành khách
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="mt-20">
        {{ $flights->onEachSide(1)->links('pagination.admin') }}
    </div>
</div>
@endsection
