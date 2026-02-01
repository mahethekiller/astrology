@php
    $ashtakavarga = $data['data']['ashtakavarga'] ?? [];
    $prastara = $ashtakavarga['prastara']['houses'] ?? [];
    $trikona = $ashtakavarga['trikona']['houses'] ?? [];
    $ekaadhipatya = $ashtakavarga['ekaadhipatya']['houses'] ?? [];
    // Controller merges 'selected_planet' as lowercase.
    $selectedPlanet = strtolower(request('planet', 'sun'));
    $planetsList = ['sun', 'moon', 'mercury', 'venus', 'mars', 'jupiter', 'saturn'];
    $currentParams = request()->query();
@endphp

<div class="mb-3">
    <label for="planetSelector" class="form-label fw-bold">Select Planet for Bhinnashtakavarga:</label>
    <div class="d-flex flex-wrap gap-2">
        @foreach($planetsList as $planet)
            <a href="{{ route('kundli.detailed', array_merge($currentParams, ['tab' => 'ashtakavarga', 'planet' => $planet])) }}"
               class="btn btn-sm {{ $selectedPlanet == $planet ? 'btn-primary' : 'btn-outline-primary' }}">
                {{ ucfirst($planet) }}
            </a>
        @endforeach
    </div>
</div>

<ul class="nav nav-tabs mb-3" id="ashtakavargaTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="prastara-tab" data-bs-toggle="tab" data-bs-target="#prastara" type="button"
            role="tab">Prastara Ashtakavarga</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="trikona-tab" data-bs-toggle="tab" data-bs-target="#trikona" type="button"
            role="tab">Trikona Shodhana</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="ekaadhipatya-tab" data-bs-toggle="tab" data-bs-target="#ekaadhipatya"
            type="button" role="tab">Ekaadhipatya Shodhana</button>
    </li>
</ul>

<div class="tab-content" id="ashtakavargaTabsContent">
    {{-- Prastara Table --}}
    <div class="tab-pane fade show active" id="prastara" role="tabpanel">
        <div class="table-responsive">
            <p class="text-muted small">Bhinnashtakavarga for {{ $ashtakavarga['planet'] ?? 'Planet' }}</p>
            <table class="table table-bordered table-sm text-center small">
                <thead class="table-light">
                    <tr>
                        <th>House</th>
                        <th>Rasi</th>
                        <th>Su</th>
                        <th>Mo</th>
                        <th>Me</th>
                        <th>Ve</th>
                        <th>Ma</th>
                        <th>Ju</th>
                        <th>Sa</th>
                        <th>Lag</th>
                        <th class="table-dark">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prastara as $house)
                        <tr>
                            <td>{{ $house['house']['number'] }}</td>
                            <td>{{ $house['rasi']['name'] }}</td>
                            @foreach($house['planets'] as $pScore)
                                <td>{{ $pScore['score'] }}</td>
                            @endforeach
                            <td class="fw-bold table-active">{{ $house['score'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="fw-bold">
                    <tr>
                        <td colspan="2">Total</td>
                        @php
                            $totals = array_fill(0, 8, 0); // 7 planets + Lagna
                            $grandTotal = 0;
                        @endphp
                        @foreach($prastara as $house)
                            @foreach($house['planets'] as $idx => $pScore)
                                @php 
                                    if(isset($totals[$idx])) {
                                        $totals[$idx] += $pScore['score']; 
                                    }
                                @endphp
                            @endforeach
                            @php $grandTotal += $house['score']; @endphp
                        @endforeach

                        @foreach($totals as $t)
                            <td>{{ $t }}</td>
                        @endforeach
                        <td class="table-dark">{{ $grandTotal }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Trikona Shodhana --}}
    <div class="tab-pane fade" id="trikona" role="tabpanel">
         <div class="table-responsive">
            <p class="text-muted small">Trikona Shodhana Points</p>
            <table class="table table-bordered table-sm text-center">
                <thead class="table-light">
                    <tr>
                        <th>House</th>
                        <th>Rasi</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trikona as $house)
                        <tr>
                            <td>{{ $house['house']['number'] }}</td>
                            <td>{{ $house['rasi']['name'] }}</td>
                            <td>{{ $house['score'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Ekaadhipatya Shodhana --}}
    <div class="tab-pane fade" id="ekaadhipatya" role="tabpanel">
         <div class="table-responsive">
            <p class="text-muted small">Ekaadhipatya Shodhana Points</p>
            <table class="table table-bordered table-sm text-center">
                <thead class="table-light">
                    <tr>
                        <th>House</th>
                        <th>Rasi</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ekaadhipatya as $house)
                        <tr>
                            <td>{{ $house['house']['number'] }}</td>
                            <td>{{ $house['rasi']['name'] }}</td>
                            <td>{{ $house['score'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>