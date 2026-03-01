@if(isset($data['data']))
    @php
        $hasDosha = $data['data']['has_kaal_sarp_dosha'] ?? $data['data']['has_dosha'] ?? false;
    @endphp

    <div class="rounded-4 overflow-hidden border {{ $hasDosha ? 'border-warning' : 'border-success' }} mb-4 shadow-sm">
        <div class="p-4 bg-{{ $hasDosha ? 'warning' : 'success' }} bg-opacity-10 d-md-flex align-items-center">
            <div class="p-3 rounded-circle bg-{{ $hasDosha ? 'warning' : 'success' }} text-white me-4 mb-3 mb-md-0 d-inline-flex align-items-center justify-content-center"
                style="width: 64px; height: 60px;">
                <i class="bi {{ $hasDosha ? 'bi-activity' : 'bi-check-circle-fill' }} fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 {{ $hasDosha ? 'text-warning-emphasis' : 'text-success' }}">
                    {{ $hasDosha ? 'Kaal Sarp Dosha Present' : 'No Kaal Sarp Dosha' }}
                </h4>
                <p class="mb-0 text-muted small">Comprehensive analysis of planetary nodes Rahu and Ketu.</p>
            </div>
        </div>
        <div class="p-4 bg-white border-top">
            <div class="text-muted lh-base">
                @if(is_array($data['data']['description']))
                    @foreach($data['data']['description'] as $desc)
                        <p class="{{ !$loop->last ? 'mb-3' : 'mb-0' }}">{{ $desc }}</p>
                    @endforeach
                @else
                    <p class="mb-0">{{ $data['data']['description'] }}</p>
                @endif
            </div>
        </div>
    </div>

    @if(isset($data['data']['dosha_type']))
        <div class="card rounded-4 border-0 shadow-sm bg-light overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="badge bg-primary px-3 py-2 rounded-pill me-2">Type</div>
                    <h5 class="mb-0 fw-bold">{{ $data['data']['dosha_type'] }}</h5>
                </div>
                <div class="p-3 bg-white rounded-3 border">
                    <p class="mb-0 text-dark fw-medium">{{ $data['data']['one_line'] ?? 'No specific details provided.' }}</p>
                </div>
            </div>
        </div>
    @endif
@endif