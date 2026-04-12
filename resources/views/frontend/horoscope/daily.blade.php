@extends('frontend.layouts.app')

@section('title', 'Daily Horoscope - ' . ($sign ? ucfirst($sign) : 'Select Sign'))

@section('content')
    <div class="section5">
        <div class="container">
            <h2 class="title2">Today's Astrology Prediction</h2>
            <div class="headingDeign"><img src="{{ asset('frontend/images/headingDesign.png') }}" /></div>

            <div class="row">
                @foreach($zodiacSigns as $zSign)
                    <div class="col-sm-6 col-lg-2">
                        <a href="{{ route('horoscope.daily', $zSign->slug) }}"
                            class="sec5Box {{ $sign === $zSign->slug ? 'active' : '' }}">
                            <span class="sec5icon"><img
                                    src="{{ str_contains($zSign->icon, 'frontend/') ? asset($zSign->icon) : asset($zSign->icon) }}" /></span>
                            <span class="sec5text">{{ $zSign->name }}</span>
                        </a>
                    </div>
                @endforeach
            </div>



            @if($prediction && isset($prediction['data']['daily_predictions'][0]))
                @php
                    $dailyData = $prediction['data']['daily_predictions'][0];
                @endphp
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="horoscope-header text-center mb-4">
                            <h3 class="horoscope-sign-title">{{ $dailyData['sign']['name'] ?? ucfirst($sign) }}</h3>
                            <p class="horoscope-date">{{ \Carbon\Carbon::parse(now())->format('l, F j, Y') }}</p>
                            @if(isset($dailyData['sign_info']))
                                <div class="sign-info-badges mt-3">
                                    <span class="badge bg-primary">{{ $dailyData['sign_info']['modality'] ?? '' }}</span>
                                    <span class="badge bg-danger">{{ $dailyData['sign_info']['triplicity'] ?? '' }}</span>
                                    <span class="badge bg-success">{{ $dailyData['sign_info']['quadruplicity'] ?? '' }}</span>
                                    @if(isset($dailyData['sign']['lord']['name']))
                                        <span class="badge bg-warning text-dark">Ruled by
                                            {{ $dailyData['sign']['lord']['name'] }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @if(isset($dailyData['predictions']) && count($dailyData['predictions']) > 0)
                            <!-- Predictions Tabs -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body p-0">
                                    <ul class="nav nav-tabs horoscope-tabs" id="predictionTabs" role="tablist">
                                        @foreach($dailyData['predictions'] as $index => $pred)
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                                    id="tab-{{ strtolower($pred['type']) }}" data-bs-toggle="tab"
                                                    data-bs-target="#content-{{ strtolower($pred['type']) }}" type="button" role="tab">
                                                    @if($pred['type'] === 'General')
                                                        <i class="fas fa-star me-2"></i>
                                                    @elseif($pred['type'] === 'Health')
                                                        <i class="fas fa-heartbeat me-2"></i>
                                                    @elseif($pred['type'] === 'Career')
                                                        <i class="fas fa-briefcase me-2"></i>
                                                    @elseif($pred['type'] === 'Love')
                                                        <i class="fas fa-heart me-2"></i>
                                                    @endif
                                                    {{ $pred['type'] }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content p-4" id="predictionTabContent">
                                        @foreach($dailyData['predictions'] as $index => $pred)
                                            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                                id="content-{{ strtolower($pred['type']) }}" role="tabpanel">
                                                <div class="prediction-content">
                                                    <p class="prediction-text">{{ $pred['prediction'] }}</p>

                                                    <div class="row mt-4">
                                                        @if(isset($pred['seek']))
                                                            <div class="col-md-4 mb-3">
                                                                <div class="insight-card seek-card">
                                                                    <div class="insight-icon">
                                                                        <i class="fas fa-search"></i>
                                                                    </div>
                                                                    <h6 class="insight-title">Seek</h6>
                                                                    <p class="insight-text">{{ str_replace('Seek: ', '', $pred['seek']) }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if(isset($pred['challenge']))
                                                            <div class="col-md-4 mb-3">
                                                                <div class="insight-card challenge-card">
                                                                    <div class="insight-icon">
                                                                        <i class="fas fa-exclamation-triangle"></i>
                                                                    </div>
                                                                    <h6 class="insight-title">Challenge</h6>
                                                                    <p class="insight-text">
                                                                        {{ str_replace('Challenge: ', '', $pred['challenge']) }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if(isset($pred['insight']))
                                                            <div class="col-md-4 mb-3">
                                                                <div class="insight-card insight-card-main">
                                                                    <div class="insight-icon">
                                                                        <i class="fas fa-lightbulb"></i>
                                                                    </div>
                                                                    <h6 class="insight-title">Insight</h6>
                                                                    <p class="insight-text">
                                                                        {{ str_replace('Insight: ', '', $pred['insight']) }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Planetary Aspects -->

                        @if(isset($dailyData['aspects']) && count($dailyData['aspects']) > 0)
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-gradient-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-project-diagram me-2"></i>Planetary Aspects</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($dailyData['aspects'] as $aspect)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="aspect-card">
                                                    <div class="aspect-planets">
                                                        <span class="planet-name">{{ $aspect['planet_one']['name'] ?? 'N/A' }}</span>
                                                        <span
                                                            class="aspect-type 
                                                                                                                                                                                            @if(isset($aspect['aspect']['name']) && ($aspect['aspect']['name'] === 'Trine' || $aspect['aspect']['name'] === 'Sextile')) text-success
                                                                                                                                                                                            @elseif(isset($aspect['aspect']['name']) && ($aspect['aspect']['name'] === 'Square' || $aspect['aspect']['name'] === 'Opposition')) text-danger
                                                                                                                                                                                            @else text-warning
                                                                                                                                                                                            @endif">
                                                            {{ $aspect['aspect']['name'] ?? 'N/A' }}
                                                        </span>
                                                        <span
                                                            class="planet-name">{{ $aspect['planet_Two']['name'] ?? $aspect['planet_two']['name'] ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Planetary Transits -->
                        @if(isset($dailyData['transits']) && count($dailyData['transits']) > 0)
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-gradient-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-globe me-2"></i>Planetary Transits</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Planet</th>
                                                    <th>Zodiac Sign</th>
                                                    <th>House</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dailyData['transits'] as $transit)
                                                    <tr>
                                                        <td><strong>{{ $transit['name'] }}</strong></td>
                                                        <td>{{ $transit['zodiac']['name'] ?? 'N/A' }}</td>
                                                        <td>{{ $transit['house_number'] ?? 'N/A' }}</td>
                                                        <td>
                                                            @if(isset($transit['is_retrograde']) && $transit['is_retrograde'])
                                                                <span class="badge bg-warning text-dark">
                                                                    <i class="fas fa-undo me-1"></i>Retrograde
                                                                </span>
                                                            @else
                                                                <span class="badge bg-success">Direct</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($sign)
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-8">
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Unable to fetch horoscope for <strong>{{ ucfirst($sign) }}</strong> at this time. Please try again
                            later.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('frontend/css/horoscope.css') }}">
    @endpush

@endsection