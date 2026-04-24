{{-- Passenger details Section --}}
<div class="review-section">
    <div class="section-title">Thông tin hành khách</div>
    <div class="section-content">
        <table class="review-table">
            <thead>
                <tr>
                    <th class="col-pax">Hành khách</th>
                    <th class="col-type">Loại vé</th>
                    <th>Họ và tên</th>
                    <th>Ngày sinh</th>
                </tr>
            </thead>
            <tbody>
                {{-- Adult Passengers --}}
                @if(isset($passengerData['passengers']['adult']))
                    @foreach($passengerData['passengers']['adult'] as $index => $p)
                        <tr>
                            <td class="pax-label">Người lớn {{ $index }}</td>
                            <td>Người lớn</td>
                            <td>
                                <span class="pax-title">{{ ($p['title'] == 'Mr' ? 'Ông' : 'Bà') }}.</span>
                                <span>{{ strtoupper(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? '')) }}</span>
                            </td>
                            <td>{{ $p['dob_day'] }}/{{ $p['dob_month'] }}/{{ $p['dob_year'] }}</td>
                        </tr>
                    @endforeach
                @endif

                {{-- Child Passengers --}}
                @if(isset($passengerData['passengers']['child']))
                    @foreach($passengerData['passengers']['child'] as $index => $p)
                        <tr>
                            <td class="pax-label">Trẻ em {{ $index }}</td>
                            <td>Trẻ em</td>
                            <td>
                                <span class="pax-title">{{ ($p['title'] == 'Master' ? 'Cậu bé' : 'Cô bé') }}.</span>
                                <span>{{ strtoupper(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? '')) }}</span>
                            </td>
                            <td>{{ $p['dob_day'] }}/{{ $p['dob_month'] }}/{{ $p['dob_year'] }}</td>
                        </tr>
                    @endforeach
                @endif

                {{-- Infant Passengers --}}
                @if(isset($passengerData['passengers']['infant']))
                    @foreach($passengerData['passengers']['infant'] as $index => $p)
                        <tr>
                            <td class="pax-label">Sơ sinh {{ $index }}</td>
                            <td>Sơ sinh</td>
                            <td>
                                <span class="pax-title">{{ ($p['title'] == 'Master' ? 'Bé trai' : 'Bé gái') }}.</span>
                                <span>{{ strtoupper(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? '')) }}</span>
                            </td>
                            <td>{{ $p['dob_day'] }}/{{ $p['dob_month'] }}/{{ $p['dob_year'] }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
