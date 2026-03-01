@if(isset($data['data']))
    @php
        $isInSadeSati = $data['data']['is_in_sade_sati'] ?? false;
        $description = is_array($data['data']['description']) ? implode(' ', $data['data']['description']) : $data['data']['description'];
    @endphp

    <div class="rounded-4 overflow-hidden border {{ $isInSadeSati ? 'border-danger' : 'border-success' }} mb-4 shadow-sm">
        <div class="p-4 bg-{{ $isInSadeSati ? 'danger' : 'success' }} bg-opacity-10 d-md-flex align-items-center">
            <div class="p-3 rounded-circle bg-{{ $isInSadeSati ? 'danger' : 'success' }} text-white me-4 mb-3 mb-md-0 d-inline-flex align-items-center justify-content-center"
                style="width: 64px; height: 60px;">
                <i class="bi {{ $isInSadeSati ? 'bi-hourglass-split' : 'bi-check-circle-fill' }} fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 {{ $isInSadeSati ? 'text-danger' : 'text-success' }}">
                    {{ $isInSadeSati ? 'Currently in Sade Sati' : 'Not in Sade Sati' }}
                </h4>
                <p class="mb-0 text-muted small">{{ $description }}</p>
            </div>
        </div>
        @if(isset($data['data']['transit_phase']))
            <div class="px-4 py-2 bg-white border-top border-bottom small text-muted">
                <span class="fw-bold text-dark me-2">Current Phase:</span> {{ $data['data']['transit_phase'] }}
            </div>
        @endif
    </div>

    @php
        $phases = $data['data']['transits'] ?? $data['data']['all_phases'] ?? [];
    @endphp

    @if(count($phases) > 0)
        <div class="mt-4">
            <h6 class="text-uppercase text-muted fw-bold mb-3 small letter-spacing-1 px-1">Transit Timeline</h6>
            <div class="table-responsive rounded-4 border bg-white shadow-sm overflow-hidden">
                <table class="table table-hover mb-0 kundli-report-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-secondary small text-uppercase">Phase</th>
                            <th class="px-4 py-3 text-secondary small text-uppercase">Saturn Sign</th>
                            <th class="px-4 py-3 text-secondary small text-uppercase">Start</th>
                            <th class="px-4 py-3 text-secondary small text-uppercase">End</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($phases as $phase)
                            <tr
                                class="{{ ($phase['is_sorted'] ?? false) || ($phase['is_active'] ?? false) ? 'bg-primary bg-opacity-10' : '' }}">
                                <td class="px-4 py-3 fw-medium">{{ $phase['phase'] }}</td>
                                <td class="px-4 py-3">{{ $phase['saturn_sign'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-muted small">{{ \Carbon\Carbon::parse($phase['start'])->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-muted small">{{ \Carbon\Carbon::parse($phase['end'])->format('d M Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endif