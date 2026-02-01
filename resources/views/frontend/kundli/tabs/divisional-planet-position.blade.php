@php
    $positions = $data['data']['divisional_positions'] ?? [];
    $selectedChart = request('chart_type', 'lagna');
    $charts = [
        'lagna' => 'D1 - Rasi',
        'hora' => 'D2 - Hora',
        'drekkana' => 'D3 - Drekkana',
        'chaturthamsa' => 'D4 - Chaturthamsa',
        'panchamsa' => 'D5 - Panchamsa',
        'shashtamsa' => 'D6 - Shashtamsa',
        'saptamsa' => 'D7 - Saptamsa',
        'ashtamsa' => 'D8 - Ashtamsa',
        'navamsa' => 'D9 - Navamsa',
        'dasamsa' => 'D10 - Dasamsa',
        'dwadasamsa' => 'D12 - Dwadasamsa',
        'shodasamsa' => 'D16 - Shodasamsa',
        'vimsamsa' => 'D20 - Vimsamsa',
        'chaturvimsamsa' => 'D24 - Chaturvimsamsa',
        'saptavimsamsa' => 'D27 - Saptavimsamsa',
        'trimsamsa' => 'D30 - Trimsamsa',
        'khavedamsa' => 'D40 - Khavedamsa',
        'akshavedamsa' => 'D45 - Akshavedamsa',
        'shashtyamsa' => 'D60 - Shashtyamsa',
    ];
@endphp

<div class="mb-3">
    <label for="chartTypeSelector" class="form-label fw-bold">Select Divisional Chart:</label>
    <div class="d-flex flex-wrap gap-2">
        @foreach($charts as $key => $label)
            <a href="{{ url()->current() }}?active_tab=divisional-planet-position&chart_type={{ $key }}"
                class="btn btn-sm {{ $selectedChart == $key ? 'btn-primary' : 'btn-outline-primary' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>House</th>
                        <th>Rasi</th>
                        <th>Planets</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($positions as $pos)
                        <tr>
                            <td>
                                <span class="badge bg-secondary rounded-pill">{{ $pos['house']['number'] }}</span>
                                <br>
                                <small class="text-muted">{{ $pos['house']['name'] }}</small>
                            </td>
                            <td>
                                {{ $pos['rasi']['name'] }}
                                <br>
                                <small class="text-muted">Lord: {{ $pos['rasi']['lord']['name'] }}</small>
                            </td>
                            <td class="text-start">
                                @if(!empty($pos['planet_positions']))
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($pos['planet_positions'] as $p)
                                            <div class="border rounded p-2 bg-white small shadow-sm">
                                                <strong>{{ $p['planet']['name'] }}</strong>
                                                <br>
                                                Pos:
                                                {{ is_array($p['longitude_dms']) ? json_encode($p['longitude_dms']) : $p['longitude_dms'] }}
                                                <br>
                                                Nak: {{ $p['nakshatra']['name'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>