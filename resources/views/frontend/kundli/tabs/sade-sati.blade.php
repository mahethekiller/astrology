@if(isset($data['data']))
    <div class="mb-4">
        <h5 class="mb-3 {{ ($data['data']['is_in_sade_sati'] ?? false) ? 'text-danger' : 'text-success' }}">
            {{ is_array($data['data']['description']) ? implode(' ', $data['data']['description']) : $data['data']['description'] }}
        </h5>

        @if(isset($data['data']['transit_phase']))
            <p><strong>Current Phase:</strong> {{ $data['data']['transit_phase'] }}</p>
        @endif

        @php
            $phases = $data['data']['transits'] ?? $data['data']['all_phases'] ?? [];
        @endphp

        @if(count($phases) > 0)
            <h6 class="mt-4 text-secondary">All Phases</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Phase</th>
                            <th>Saturn Sign</th>
                            <th>Start</th>
                            <th>End</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($phases as $phase)
                            <tr class="{{ ($phase['is_sorted'] ?? false) ? 'table-warning' : '' }}">
                                <td>{{ $phase['phase'] }}</td>
                                <td>{{ $phase['saturn_sign'] ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($phase['start'])->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($phase['end'])->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endif