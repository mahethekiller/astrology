@if(isset($data['data']))
    <div class="row g-4">
        {{-- Nakshatra & Rasi Summary --}}
        @if(isset($data['data']['nakshatra_details']))
            @php $n = $data['data']['nakshatra_details']; @endphp
            <div class="col-12">
                <h5 class="fw-bold mb-4 text-primary d-flex align-items-center">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                    Core Astro Summary
                </h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="p-3 rounded-4 border bg-white h-100 shadow-sm text-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 mx-auto mb-3 text-primary"
                                style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-stars fs-4"></i>
                            </div>
                            <div class="small text-muted mb-1">Nakshatra</div>
                            <div class="fw-bold text-dark">{{ $n['nakshatra']['name'] }}</div>
                            <div class="extra-small text-muted">({{ $n['nakshatra']['lord']['name'] }})</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 rounded-4 border bg-white h-100 shadow-sm text-center">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 mx-auto mb-3 text-success"
                                style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-moon-stars-fill fs-4"></i>
                            </div>
                            <div class="small text-muted mb-1">Moon Sign</div>
                            <div class="fw-bold text-dark">{{ $n['chandra_rasi']['name'] }}</div>
                            <div class="extra-small text-muted">({{ $n['chandra_rasi']['lord']['name'] }})</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 rounded-4 border bg-white h-100 shadow-sm text-center">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 mx-auto mb-3 text-warning"
                                style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-sun-fill fs-4"></i>
                            </div>
                            <div class="small text-muted mb-1">Sun Sign</div>
                            <div class="fw-bold text-dark">{{ $n['soorya_rasi']['name'] }}</div>
                            <div class="extra-small text-muted">({{ $n['soorya_rasi']['lord']['name'] }})</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 rounded-4 border bg-white h-100 shadow-sm text-center">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 mx-auto mb-3 text-info"
                                style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-compass-fill fs-4"></i>
                            </div>
                            <div class="small text-muted mb-1">Ascendant (Lagna)</div>
                            <div class="fw-bold text-dark">{{ $data['data']['ascendant'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Mangal Dosha Detailed Section --}}
        @if(isset($data['data']['mangal_dosha']))
            @php
                $mangalDosha = $data['data']['mangal_dosha'];
                $hasDosha = $mangalDosha['has_mangal_dosha'] ?? $mangalDosha['has_dosha'] ?? false;
            @endphp
            <div class="col-12 mt-4">
                <div class="rounded-4 overflow-hidden border {{ $hasDosha ? 'border-danger' : 'border-success' }} shadow-sm">
                    <div class="p-3 bg-{{ $hasDosha ? 'danger' : 'success' }} bg-opacity-10 d-flex align-items-center">
                        <div class="p-2 rounded-circle bg-{{ $hasDosha ? 'danger' : 'success' }} text-white me-3">
                            <i class="bi {{ $hasDosha ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' }}"></i>
                        </div>
                        <h6 class="mb-0 fw-bold {{ $hasDosha ? 'text-danger' : 'text-success' }}">
                            Mangal Dosha: {{ $hasDosha ? 'Present' : 'Not Present' }}
                        </h6>
                    </div>
                    <div class="p-4 bg-white">
                        <div class="text-muted lh-base">
                            @if(is_array($mangalDosha['description']))
                                @foreach($mangalDosha['description'] as $desc)
                                    <p class="mb-2">{{ $desc }}</p>
                                @endforeach
                            @else
                                <p class="mb-0">{{ $mangalDosha['description'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Yoga Summary Grid --}}
        @if(isset($data['data']['yoga_details']))
            <div class="col-12 mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-primary d-flex align-items-center">
                        <i class="bi bi-lightning-charge-fill me-2"></i>
                        Key Planetary Yogas
                    </h5>
                    <a href="{{ route('kundli.detailed', array_merge(request()->except('tab'), ['tab' => 'yoga'])) }}"
                        class="btn btn-sm btn-link text-primary fw-bold text-decoration-none">
                        View All Yogas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="row g-3">
                    @foreach(array_slice($data['data']['yoga_details'], 0, 4) as $yoga)
                        <div class="col-md-6">
                            <div class="p-4 rounded-4 border bg-gray-50 h-100 shadow-sm transition-hover">
                                <h6 class="fw-bold text-dark mb-2 d-flex align-items-center">
                                    <span class="p-1 rounded bg-primary bg-opacity-10 text-primary me-2"
                                        style="font-size: 0.8rem;">Yoga</span>
                                    {{ $yoga['name'] }}
                                </h6>
                                <p class="small text-muted mb-0 lh-base">{{ Str::limit($yoga['description'], 140) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endif