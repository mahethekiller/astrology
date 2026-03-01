@if(isset($data['data']))
    @php
        $yogaData = $data['data']['yoga_details'] ?? $data['data'];
        if (!is_array($yogaData)) {
            $yogaData = [];
        }
    @endphp
    <div class="row g-4">
        @foreach($yogaData as $yoga)
            @if(isset($yoga['yoga_list']))
                {{-- Advanced Structure (Groups) --}}
                <div class="col-12 mb-2">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3">
                            <i class="bi bi-diagram-3-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">{{ $yoga['name'] }}</h5>
                            <p class="text-muted small mb-0">{{ $yoga['description'] }}</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        @foreach($yoga['yoga_list'] as $subYoga)
                            <div class="col-md-6">
                                <div
                                    class="card h-100 rounded-4 border shadow-sm transition-hover {{ $subYoga['has_yoga'] ? 'border-success' : 'border-light bg-light opacity-75' }}">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold mb-0 {{ $subYoga['has_yoga'] ? 'text-success' : 'text-muted' }}">
                                                {{ $subYoga['name'] }}
                                            </h6>
                                            @if($subYoga['has_yoga'])
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Active
                                                </span>
                                            @endif
                                        </div>
                                        <p class="card-text small {{ $subYoga['has_yoga'] ? 'text-secondary' : 'text-muted' }} lh-base">
                                            {{ $subYoga['description'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Basic Structure (Flat List) --}}
                <div class="col-md-6">
                    <div
                        class="card h-100 rounded-4 border shadow-sm transition-hover {{ ($yoga['has_yoga'] ?? false) ? 'border-success' : 'border-light bg-light opacity-75' }}">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0 {{ ($yoga['has_yoga'] ?? false) ? 'text-success' : 'text-muted' }}">
                                    {{ $yoga['name'] }}
                                </h6>
                                @if($yoga['has_yoga'] ?? false)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                                        <i class="bi bi-check-circle-fill me-1"></i> Active
                                    </span>
                                @endif
                            </div>
                            <div
                                class="card-text small {{ ($yoga['has_yoga'] ?? false) ? 'text-secondary' : 'text-muted' }} lh-base">
                                @if(is_array($yoga['description']))
                                    @foreach($yoga['description'] as $desc)
                                        <p class="mb-1">{{ $desc }}</p>
                                    @endforeach
                                @else
                                    {{ $yoga['description'] }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif