@extends('frontend.layouts.app')

@section('title', 'Your Kundli')

@section('content')
@push('styles')
    <link href="{{ asset('css/dashboard-panels.css') }}" rel="stylesheet">
    <style>
        .result-header {
            background: linear-gradient(135deg, #1a2a6c 0%, #b21f1f 100%);
            padding: 60px 0;
            margin-bottom: -50px;
            color: white;
        }
        .dosha-card {
            border-left: 5px solid;
            transition: transform 0.2s;
        }
        .dosha-card:hover { transform: scale(1.02); }
        .table-custom th { color: #6b7280; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }
        .table-custom td { font-weight: 500; color: #111827; }
    </style>
@endpush

@section('content')
    <div class="result-header">
        <div class="container text-center">
            <h1 class="fw-bold mb-2">Your Janma Kundli</h1>
            <p class="opacity-75 mb-0">
                <i class="bi bi-calendar3 me-2"></i> {{ \Carbon\Carbon::parse($request->date)->format('F j, Y') }} | 
                <i class="bi bi-clock me-2"></i> {{ \Carbon\Carbon::parse($request->time)->format('g:i A') }}
            </p>
        </div>
    </div>

    <div class="container pb-5" style="position: relative; z-index: 2;">
        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('kundli.index') }}" class="btn btn-warning shadow-sm fw-bold px-4 rounded-pill" style="background-color: rgba(253, 219, 24, 1); border: none;">
                <i class="bi bi-arrow-left me-2"></i> Generate Another
            </a>
        </div>

        @if($kundli && isset($kundli['data']))
            <div class="row g-4">
                {{-- Nakshatra & Rasi Highlight Panels --}}
                @if(isset($kundli['data']['nakshatra_details']))
                    @php $n = $kundli['data']['nakshatra_details']; @endphp
                    <div class="col-md-4">
                        <div class="stats-card card-primary h-100">
                            <div class="stats-icon"><i class="bi bi-moon-stars"></i></div>
                            <div class="stats-label">Nakshatra</div>
                            <div class="stats-value" style="font-size: 1.8rem;">{{ $n['nakshatra']['name'] }}</div>
                            <div class="stats-meta">Lord: {{ $n['nakshatra']['lord']['name'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card card-success h-100">
                            <div class="stats-icon"><i class="bi bi-brightness-high"></i></div>
                            <div class="stats-label">Moon Sign (Rasi)</div>
                            <div class="stats-value" style="font-size: 1.8rem;">{{ $n['chandra_rasi']['name'] }}</div>
                            <div class="stats-meta">Lord: {{ $n['chandra_rasi']['lord']['name'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card card-warning h-100">
                            <div class="stats-icon"><i class="bi bi-sun"></i></div>
                            <div class="stats-label">Sun Sign</div>
                            <div class="stats-value" style="font-size: 1.8rem;">{{ $n['soorya_rasi']['name'] }}</div>
                            <div class="stats-meta">Lord: {{ $n['soorya_rasi']['lord']['name'] }}</div>
                        </div>
                    </div>

                    {{-- Additional Technical Info --}}
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 px-4">
                                <h5 class="fw-bold mb-0">Detailed Attributes</h5>
                            </div>
                            <div class="card-body px-4 pb-4">
                                @php $info = $n['additional_info']; @endphp
                                <div class="row row-cols-2 row-cols-md-4 g-4 mt-1">
                                    <div class="col text-center">
                                        <div class="small text-muted mb-1 uppercase">Deity</div>
                                        <div class="fw-bold text-dark">{{ $info['deity'] }}</div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="small text-muted mb-1 uppercase">Ganam</div>
                                        <div class="fw-bold text-dark">{{ $info['ganam'] }}</div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="small text-muted mb-1 uppercase">Animal</div>
                                        <div class="fw-bold text-dark">{{ $info['animal_sign'] }}</div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="small text-muted mb-1 uppercase">Nadi</div>
                                        <div class="fw-bold text-dark">{{ $info['nadi'] }}</div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="small text-muted mb-1 uppercase">Color</div>
                                        <div class="fw-bold text-dark">{{ $info['color'] }}</div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="small text-muted mb-1 uppercase">Direction</div>
                                        <div class="fw-bold text-dark">{{ $info['best_direction'] }}</div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="small text-muted mb-1 uppercase">Stone</div>
                                        <div class="fw-bold text-dark">{{ $info['birth_stone'] }}</div>
                                    </div>
                                    <div class="col text-center">
                                        <div class="small text-muted mb-1 uppercase">Zodiac</div>
                                        <div class="fw-bold text-dark">{{ $n['zodiac']['name'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Mangal Dosha --}}
                    @if(isset($kundli['data']['mangal_dosha']))
                        @php $md = $kundli['data']['mangal_dosha']; @endphp
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100 dosha-card" style="border-left-color: {{ $md['has_dosha'] ? '#ef4444' : '#10b981' }};">
                                <div class="card-body p-4 d-flex flex-column align-items-center text-center justify-content-center">
                                    <div class="mb-3">
                                        @if($md['has_dosha'])
                                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 d-inline-block">
                                                <i class="bi bi-exclamation-triangle fs-1"></i>
                                            </div>
                                        @else
                                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 d-inline-block">
                                                <i class="bi bi-check-circle fs-1"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h5 class="fw-bold mb-2">{{ $md['has_dosha'] ? 'Manglik Dosha Present' : 'No Manglik Dosha' }}</h5>
                                    <p class="text-muted small mb-0">{{ Str::limit($md['description'], 120) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Yoga Details --}}
                @if(isset($kundli['data']['yoga_details']))
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white border-0 pt-4 px-4">
                                <h5 class="fw-bold mb-0">Astrological Yogas</h5>
                                <p class="text-muted small mb-0">Special planetary combinations in your chart</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    @foreach($kundli['data']['yoga_details'] as $yoga)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="p-4 rounded-4 h-100" style="background-color: #f9fafb; border: 1px solid #f3f4f6 transition: all 0.3s ease;">
                                                <h6 class="fw-bold text-indigo mb-2 d-flex align-items-center">
                                                    <i class="bi bi-stars text-warning me-2"></i>
                                                    {{ $yoga['name'] }}
                                                </h6>
                                                <p class="text-muted small mb-0">{{ $yoga['description'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="card border-0 shadow rounded-4 text-center p-5">
                <i class="bi bi-exclamation-octagon text-danger display-1 mb-4"></i>
                <h2 class="fw-bold">Report Generation Failed</h2>
                <p class="text-muted">We couldn't generate your Kundli at this time. Please ensure your birth details are accurate.</p>
                <a href="{{ route('kundli.index') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold mt-3">Try Again</a>
            </div>
        @endif
    </div>
@endsection
        @else
            <div class="alert alert-danger">
                Unable to generate Kundli. Please check your inputs and try again.
            </div>
        @endif
    </div>
@endsection