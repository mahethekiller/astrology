@extends('frontend.layouts.app')

@section('title', 'Advanced Kundli Report')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            {{-- Sidebar Navigation --}}
            <div class="col-md-3 col-lg-2 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">Kundli Report</h6>
                    </div>
                    <div class="list-group list-group-flush small" id="kundliTabs">
                        @php
                            $tabs = [
                                'birth-details' => 'Birth Details',
                                'kundli' => 'Kundli',
                                'kundli-advanced' => 'Detailed Kundli',
                                'mangal-dosha' => 'Mangal Dosha',
                                'mangal-dosha-advanced' => 'Detailed Mangal Dosha',
                                'kaal-sarp-dosha' => 'Kaal Sarp Dosha',
                                'sade-sati' => 'Sade Sati',
                                'sade-sati-advanced' => 'Detailed Sade Sati',
                                'chart' => 'Chart',
                                'planet-position' => 'Planet Position',
                                'upagraha-position' => 'Upagraha Position',
                                'papasamyam' => 'Papasamyam',
                                'yoga' => 'Yoga',
                                'dasha-periods' => 'Dasha Periods',
                                'planet-relationship' => 'Planet Relationship',
                                'ashtakavarga' => 'Ashtakavarga',
                                'sarvashtakavarga' => 'Sarvashtakavarga',
                                'divisional-planet-position' => 'Divisional Planet Position',
                                'chandrashtama-periods' => 'Chandrashtama Periods',
                            ];
                            $currentParams = $request->except('tab');
                        @endphp

                        @foreach($tabs as $key => $label)
                            <a href="{{ route('kundli.detailed', array_merge($currentParams, ['tab' => $key])) }}"
                                class="list-group-item list-group-item-action {{ $activeTab === $key ? 'active' : '' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Main Content Area --}}
            <div class="col-md-9 col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">{{ $tabs[$activeTab] ?? 'Report' }}</h4>
                    </div>
                    <div class="card-body">
                        @if($apiData)
                            {{-- Load specific view based on active tab --}}
                            @if ($activeTab == 'birth-details')
                                @include('frontend.kundli.tabs.birth-details', ['data' => $apiData])
                            @elseif (in_array($activeTab, ['kundli', 'kundli-advanced']))
                                @include('frontend.kundli.tabs.kundli', ['data' => $apiData])
                            @elseif (in_array($activeTab, ['mangal-dosha', 'mangal-dosha-advanced']))
                                @include('frontend.kundli.tabs.mangal-dosha', ['data' => $apiData])
                            @elseif ($activeTab == 'kaal-sarp-dosha')
                                @include('frontend.kundli.tabs.kaal-sarp-dosha', ['data' => $apiData])
                            @elseif (in_array($activeTab, ['sade-sati', 'sade-sati-advanced']))
                                @include('frontend.kundli.tabs.sade-sati', ['data' => $apiData])
                            @elseif ($activeTab == 'chart')
                                @include('frontend.kundli.tabs.chart', ['data' => $apiData, 'request' => $request, 'currentParams' => $currentParams])
                            @elseif ($activeTab == 'planet-position')
                                @include('frontend.kundli.tabs.planet-position', ['data' => $apiData])
                            @elseif ($activeTab == 'upagraha-position')
                                @include('frontend.kundli.tabs.upagraha-position', ['data' => $apiData])
                            @elseif ($activeTab == 'papasamyam')
                                @include('frontend.kundli.tabs.papasamyam', ['data' => $apiData])
                            @elseif ($activeTab == 'dasha-periods')
                                @include('frontend.kundli.tabs.dasha-periods', ['data' => $apiData])
                            @elseif ($activeTab == 'planet-relationship')
                                @include('frontend.kundli.tabs.planet-relationship', ['data' => $apiData])
                            @elseif ($activeTab == 'ashtakavarga')
                                @include('frontend.kundli.tabs.ashtakavarga', ['data' => $apiData])
                            @elseif ($activeTab == 'sarvashtakavarga')
                                @include('frontend.kundli.tabs.sarvashtakavarga', ['data' => $apiData])
                            @elseif ($activeTab == 'divisional-planet-position')
                                @include('frontend.kundli.tabs.divisional-planet-position', ['data' => $apiData])
                            @elseif ($activeTab == 'chandrashtama-periods')
                                @include('frontend.kundli.tabs.chandrashtama-periods', ['data' => $apiData])
                            @elseif ($activeTab == 'yoga')
                                @include('frontend.kundli.tabs.yoga', ['data' => $apiData])

                            @else
                                {{-- Generic Dump for un-implemented tabs for now --}}
                                <div class="alert alert-info">
                                    Displaying raw data for {{ $tabs[$activeTab] ?? $activeTab }}.
                                </div>
                                <pre class="bg-light p-3 rounded"
                                    style="max-height: 500px; overflow: auto;">{{ json_encode($apiData, JSON_PRETTY_PRINT) }}</pre>
                            @endif
                        @else
                            <div class="alert alert-danger">
                                Failed to fetch data for this section. Please try again.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection