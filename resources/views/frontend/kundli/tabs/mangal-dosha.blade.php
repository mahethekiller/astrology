@if(isset($data['data']))
    @php
        $hasDosha = $data['data']['has_mangal_dosha'] ?? $data['data']['has_dosha'] ?? false;
    @endphp

    <div class="rounded-4 overflow-hidden border {{ $hasDosha ? 'border-danger' : 'border-success' }} mb-4 shadow-sm">
        <div class="p-4 bg-{{ $hasDosha ? 'danger' : 'success' }} bg-opacity-10 d-md-flex align-items-center">
            <div class="p-3 rounded-circle bg-{{ $hasDosha ? 'danger' : 'success' }} text-white me-4 mb-3 mb-md-0 d-inline-flex align-items-center justify-content-center"
                style="width: 64px; height: 60px;">
                <i class="bi {{ $hasDosha ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' }} fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 {{ $hasDosha ? 'text-danger' : 'text-success' }}">
                    {{ $hasDosha ? 'Mangal Dosha Present' : 'No Mangal Dosha' }}
                </h4>
                @if(isset($data['data']['type']))
                    <span class="badge {{ $hasDosha ? 'bg-danger' : 'bg-success' }} px-3 py-2 rounded-pill text-uppercase">
                        {{ $data['data']['type'] }}
                    </span>
                @endif
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

    <div class="row g-4">
        @if(isset($data['data']['exceptions']) && count($data['data']['exceptions']) > 0)
            <div class="col-md-6">
                <div class="card h-100 rounded-4 border-success border-opacity-25 shadow-sm">
                    <div class="card-header bg-success bg-opacity-10 border-0 py-3 px-4">
                        <h6 class="mb-0 fw-bold text-success d-flex align-items-center">
                            <i class="bi bi-shield-check me-2"></i> Exceptions
                        </h6>
                    </div>
                    <div class="card-body px-4">
                        <ul class="list-unstyled mb-0">
                            @foreach($data['data']['exceptions'] as $exception)
                                <li class="d-flex align-items-start mb-3">
                                    <i class="bi bi-dot text-success fs-4 mt-n1"></i>
                                    <span class="small text-muted">
                                        @if(is_array($exception))
                                            {{ implode(', ', $exception) }}
                                        @else
                                            {{ $exception }}
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($data['data']['remedies']) && count($data['data']['remedies']) > 0)
            <div class="col-md-6">
                <div class="card h-100 rounded-4 border-info border-opacity-25 shadow-sm">
                    <div class="card-header bg-info bg-opacity-10 border-0 py-3 px-4">
                        <h6 class="mb-0 fw-bold text-info d-flex align-items-center">
                            <i class="bi bi-heart-pulse-fill me-2"></i> Remedies
                        </h6>
                    </div>
                    <div class="card-body px-4">
                        <ul class="list-unstyled mb-0">
                            @foreach($data['data']['remedies'] as $remedy)
                                <li class="d-flex align-items-start mb-3">
                                    <i class="bi bi-dot text-info fs-4 mt-n1"></i>
                                    <span class="small text-muted">
                                        @if(is_array($remedy))
                                            {{ implode(', ', $remedy) }}
                                        @else
                                            {{ $remedy }}
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif