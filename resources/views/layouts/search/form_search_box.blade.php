@props(['airports'])

@if ($errors->any())
    <div class="warning-box">
        @foreach ($errors->all() as $error)
            <p class="text-danger">{{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('flights.search') }}" method="GET" id="searchForm" class="hero-form">
    <div class="radio-row">
        <label class="{{ request('flight_type', 'round_trip') == 'round_trip' ? 'active' : '' }}">
            <input type="radio" name="flight_type" value="round_trip" {{ request('flight_type', 'round_trip') == 'round_trip' ? 'checked' : '' }}> Khứ hồi
        </label>
        <label class="{{ request('flight_type') == 'one_way' ? 'active' : '' }}">
            <input type="radio" name="flight_type" value="one_way" {{ request('flight_type') == 'one_way' ? 'checked' : '' }}> Một chiều
        </label>
    </div>
    <div class="grid-5">
        <div class="form-group">
            <label>Điểm khởi hành</label>
            <select name="origin_id" id="origin_id" class="form-control" required>
                <option value="">-- Chọn điểm đi --</option>
                @foreach ($airports as $airport)
                    <option value="{{ $airport->id }}" {{ request('origin_id') == $airport->id ? 'selected' : '' }}>
                        {{ $airport->city }} ({{ $airport->code }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="swap-column" style="margin-bottom: 15px;">
            <button type="button" class="swap-btn"><i class="fas fa-exchange-alt"></i></button>
        </div>
        <div class="form-group">
            <label>Điểm đến</label>
            <select name="destination_id" id="destination_id" class="form-control" required>
                <option value="">-- Chọn điểm đến --</option>
                @foreach ($airports as $airport)
                    <option value="{{ $airport->id }}" {{ request('destination_id') == $airport->id ? 'selected' : '' }}>
                        {{ $airport->city }} ({{ $airport->code }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group departure-date-group">
            <label>Ngày đi</label>
            <input type="date" name="departure_date" class="form-control" required min="{{ date('Y-m-d') }}" value="{{ request('departure_date') }}">
        </div>
        <div class="form-group return-date-group" style="{{ request('flight_type') == 'one_way' ? 'display:none;' : '' }}">
            <label>Ngày về</label>
            <input type="date" name="return_date" class="form-control" min="{{ date('Y-m-d') }}" value="{{ request('return_date') }}">
        </div>
    </div>

    <div class="grid-4">
        <div class="form-group">
            <label>Người lớn</label>
            <select name="adult_count" id="adult_count" class="form-control" required>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ request('adult_count') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="form-group">
            <label>Trẻ em</label>
            <select name="child_count" id="child_count" class="form-control">
                @for ($i = 0; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ request('child_count') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="form-group">
            <label>Sơ sinh</label>
            <select name="infant_count" id="infant_count" class="form-control">
                @for ($i = 0; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ request('infant_count') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            <small class="text-danger hidden" id="infantError">Sơ sinh không được lớn hơn người lớn!</small>
        </div>
        <div style="align-self: end; margin-bottom: 13px;">
            <button type="submit" class="btn btn-primary btn-large hero-btn" style="width: 100%;">Tìm chuyến bay</button>
        </div>
    </div>
</form>
