@extends('frontend.layouts.app')

@section('title', 'Advanced Kundli Report')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/kundli-report.css') }}">
@endpush

@section('content')
    @php
        $tabs = [
            'birth-details' => ['label' => 'Birth Details', 'icon' => 'bi-info-circle'],
            'kundli' => ['label' => 'Kundli', 'icon' => 'bi-grid-3x3'],
            'kundli-advanced' => ['label' => 'Detailed Kundli', 'icon' => 'bi-card-list'],
            'mangal-dosha' => ['label' => 'Mangal Dosha', 'icon' => 'bi-shield-exclamation'],
            'mangal-dosha-advanced' => ['label' => 'Dosha Analysis', 'icon' => 'bi-search'],
            'kaal-sarp-dosha' => ['label' => 'Kaal Sarp', 'icon' => 'bi-activity'],
            'sade-sati' => ['label' => 'Sade Sati', 'icon' => 'bi-hourglass-split'],
            'sade-sati-advanced' => ['label' => 'Saturn Cycle', 'icon' => 'bi-clock-history'],
            'chart' => ['label' => 'Visual Charts', 'icon' => 'bi-map'],
            'planet-position' => ['label' => 'Planets', 'icon' => 'bi-globe'],
            'upagraha-position' => ['label' => 'Upagrahas', 'icon' => 'bi-stars'],
            'papasamyam' => ['label' => 'Papasamyam', 'icon' => 'bi-calculator'],
            'yoga' => ['label' => 'Yogas', 'icon' => 'bi-lightning-charge'],
            'dasha-periods' => ['label' => 'Dasha Periods', 'icon' => 'bi-calendar-range'],
            'planet-relationship' => ['label' => 'Relationships', 'icon' => 'bi-people'],
            'ashtakavarga' => ['label' => 'Ashtakavarga', 'icon' => 'bi-pentagon'],
            'sarvashtakavarga' => ['label' => 'Sarva-Ashta', 'icon' => 'bi-hexagon'],
            'divisional-planet-position' => ['label' => 'Divisional', 'icon' => 'bi-diagram-3'],
            'chandrashtama-periods' => ['label' => 'Chandrashtama', 'icon' => 'bi-moon'],
        ];
        $currentParams = $request->except('tab');
        $activeTabData = $tabs[(string)($activeTab ?? '')] ?? ['label' => 'Report', 'icon' => 'bi-file-text'];
    @endphp

    <div class="report-header">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-1">Advanced Kundli Report</h1>
                    <p class="mb-0 opacity-75 small">Comprehensive Vedic Astrological Analysis</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('kundli.index') }}" class="btn btn-light btn-sm fw-bold px-3 rounded-pill text-primary">
                        <i class="bi bi-arrow-left me-1"></i> New Chart
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 pb-5">
        <div class="row">
            {{-- Sidebar Navigation --}}
            <div class="col-md-3 col-lg-2 mb-4">
                <div class="sidebar-nav sticky-top" style="top: 20px; z-index: 10;">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 px-3 small letter-spacing-1">Report Sections</h6>
                    <div class="list-group list-group-flush border-0" id="kundliTabs">
                        @foreach($tabs as $key => $tab)
                            <a href="{{ route('kundli.detailed', array_merge($currentParams, ['tab' => $key])) }}"
                                class="list-group-item list-group-item-action {{ $activeTab === $key ? 'active' : '' }}">
                                <i class="bi {{ $tab['icon'] }}"></i>
                                {{ $tab['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Main Content Area --}}
            <div class="col-md-9 col-lg-10">
                <div class="card main-card overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 fw-bold text-dark">{{ $activeTabData['label'] }}</h4>
                        <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                            <i class="bi {{ $activeTabData['icon'] }} me-1"></i>
                            Analysis
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        @if($apiData)
                            {{-- Load specific view based on active tab --}}
                            @php 
                                $viewPath = 'frontend.kundli.tabs.' . (
                                    (in_array($activeTab, ['kundli', 'kundli-advanced'])) ? 'kundli' : (
                                        (in_array($activeTab, ['mangal-dosha', 'mangal-dosha-advanced'])) ? 'mangal-dosha' : (
                                            (in_array($activeTab, ['sade-sati', 'sade-sati-advanced'])) ? 'sade-sati' : $activeTab
                                        )
                                    )
                                );
                            @endphp
                            
                            @if(view()->exists($viewPath))
                                @include($viewPath, ['data' => $apiData])
                            @else
                                <div class="alert alert-info border-0 shadow-sm rounded-4 text-center py-5">
                                    <i class="bi bi-code-square display-4 mb-3 d-block"></i>
                                    <h5>Section Work in Progress</h5>
                                    <p class="text-muted mb-0">Detailed view for <strong>{{ $activeTabData['label'] }}</strong> is being optimized.</p>
                                </div>
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-3 text-secondary">Raw Technical Data</h6>
                                    <pre class="bg-light p-4 rounded-4 border-0 small" style="max-height: 400px; overflow: auto;">{{ json_encode($apiData, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="text-muted">Fetching your astrological data...</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
