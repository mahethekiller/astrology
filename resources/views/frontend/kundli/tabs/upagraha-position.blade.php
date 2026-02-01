@if(isset($data['data']))
    <div class="table-responsive">
        <table class="table table-striped table-hover shadow-sm rounded border">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Planet</th>
                    <th>Position</th>
                    <th>Degree</th>
                    <th>Rasi</th>
                    <th>Rasi Lord</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $planets = $data['data']['upagraha_position'] ?? $data['data'] ?? [];
                @endphp
                @foreach($planets as $planet)
                    <tr>
                        <td class="fw-bold">
                            {{ $planet['name'] }}
                            @if($planet['is_retrograde'])
                                <span class="badge bg-warning text-dark" title="Retrograde">(R)</span>
                            @endif
                        </td>
                        <td>{{ $planet['position'] }}</td>
                        <td>{{ number_format($planet['degree'], 2) }}&deg;</td>
                        <td>{{ $planet['rasi']['name'] }}</td>
                        <td>{{ $planet['rasi']['lord']['name'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">No Upagraha Position data available.</div>
@endif