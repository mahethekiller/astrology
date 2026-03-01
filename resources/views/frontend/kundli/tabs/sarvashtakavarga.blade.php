@php
    $sarvashtakavarga = $data['data']['sarvashtakavarga'] ?? [];
    $prastara = $sarvashtakavarga['prastara']['houses'] ?? [];
    $trikona = $sarvashtakavarga['trikona']['houses'] ?? [];
    $ekaadhipatya = $sarvashtakavarga['ekaadhipatya']['houses'] ?? [];
@endphp

<ul class="nav nav-tabs mb-3" id="sarvashtakavargaTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="sarva-prastara-tab" data-bs-toggle="tab" data-bs-target="#sarva-prastara" type="button" role="tab">Prastara Ashtakavarga</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="sarva-trikona-tab" data-bs-toggle="tab" data-bs-target="#sarva-trikona" type="button" role="tab">Trikona Shodhana</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="sarva-ekaadhipatya-tab" data-bs-toggle="tab" data-bs-target="#sarva-ekaadhipatya" type="button" role="tab">Ekaadhipatya Shodhana</button>
    </li>
</ul>

<div class="tab-content" id="sarvashtakavargaTabsContent">
    {{-- Prastara Table --}}
    <div class="tab-pane fade show active" id="sarva-prastara" role="tabpanel">
        <div class="table-responsive">
            <p class="text-muted small">Sarvashtakavarga Table</p>
            <table class="table kundli-report-table table-bordered table-sm text-center small">
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
                            $totals = array_fill(0, 7, 0); // 7 planets (no Asc in this JSON usually, or need to check key)
                            // Checking JSON: Planets array has 7 items (indices 0-6 corresponding to Sun..Saturn). Ascendant (id 100) is missing in Sarvashtakavarga usually or present?
                            // In the user provided JSON, planets array has 7 items. The Ascendant is NOT present in the planets list for Sarvashtakavarga houses.
                            // Indices: 0:Sun, 1:Moon, 2:Mercury, 3:Venus, 4:Mars, 5:Jupiter, 6:Saturn.
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
    <div class="tab-pane fade" id="sarva-trikona" role="tabpanel">
         <div class="table-responsive">
            <p class="text-muted small">Trikona Shodhana Points</p>
            <table class="table kundli-report-table table-bordered table-sm text-center">
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
    <div class="tab-pane fade" id="sarva-ekaadhipatya" role="tabpanel">
         <div class="table-responsive">
            <p class="text-muted small">Ekaadhipatya Shodhana Points</p>
            <table class="table kundli-report-table table-bordered table-sm text-center">
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
