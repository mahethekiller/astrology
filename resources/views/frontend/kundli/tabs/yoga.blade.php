@if(isset($data['data']))
    @php
        $yogaData = $data['data']['yoga_details'] ?? $data['data'];
        // Ensure it's iterable
        if (!is_array($yogaData)) {
            $yogaData = [];
        }
    @endphp
    <div class="row">
        @foreach($yogaData as $yoga)
            @if(isset($yoga['yoga_list']))
                {{-- Advanced Structure (Groups) --}}
                <div class="col-12 mb-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2">{{ $yoga['name'] }}</h5>
                    <p class="text-muted">{{ $yoga['description'] }}</p>
                    <div class="row">
                        @foreach($yoga['yoga_list'] as $subYoga)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 {{ $subYoga['has_yoga'] ? 'border-success' : 'border-light bg-light' }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="card-title fw-bold {{ $subYoga['has_yoga'] ? 'text-success' : 'text-muted' }}">
                                                {{ $subYoga['name'] }}
                                            </h6>
                                            @if($subYoga['has_yoga'])
                                                <span class="badge bg-success">Present</span>
                                            @endif
                                        </div>
                                        <p class="card-text small text-secondary">{{ $subYoga['description'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Basic Structure (Flat List) --}}
                <div class="col-md-6 mb-3">
                    <div
                        class="card h-100 shadow-sm {{ ($yoga['has_yoga'] ?? false) ? 'border-success' : 'border-light bg-light' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="card-title fw-bold {{ ($yoga['has_yoga'] ?? false) ? 'text-success' : 'text-muted' }}">
                                    {{ $yoga['name'] }}
                                </h6>
                                @if($yoga['has_yoga'] ?? false)
                                    <span class="badge bg-success">Present</span>
                                @endif
                            </div>
                            <div class="card-text small text-secondary">
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