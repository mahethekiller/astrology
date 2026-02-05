@extends('frontend.layouts.app')

@section('title', 'Daily Horoscope - ' . ($sign ? ucfirst($sign) : 'Select Sign'))

@section('content')
    <div class="section5">
        <div class="container">
            <h2 class="title2">Today's Astrology Prediction</h2>
            <div class="headingDeign"><img src="{{ asset('frontend/images/headingDesign.png') }}" /></div>

            <div class="row">
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'aries' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'aries') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/aries.png') }}" /></span>
                            <span class="sec5text">Aries</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'taurus' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'taurus') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/taurus.png') }}" /></span>
                            <span class="sec5text">Taurus</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'gemini' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'gemini') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/gemini.png') }}" /></span>
                            <span class="sec5text">Gemini</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'cancer' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'cancer') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/cancer.png') }}" /></span>
                            <span class="sec5text">Cancer</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'leo' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'leo') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/leo.png') }}" /></span>
                            <span class="sec5text">Leo</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'virgo' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'virgo') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/virgo.png') }}" /></span>
                            <span class="sec5text">Virgo</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'libra' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'libra') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/libra.png') }}" /></span>
                            <span class="sec5text">Libra</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'scorpio' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'scorpio') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/scorpio.png') }}" /></span>
                            <span class="sec5text">Scorpio</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'sagittarius' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'sagittarius') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/sagittarius.png') }}" /></span>
                            <span class="sec5text">Sagittarius</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'capricorn' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'capricorn') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/capricorn.png') }}" /></span>
                            <span class="sec5text">Capricorn</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'aquarius' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'aquarius') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/aquarius.png') }}" /></span>
                            <span class="sec5text">Aquarius</span>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="sec5Box {{ $sign === 'pisces' ? 'active' : '' }}">
                        <a href="{{ route('horoscope.daily', 'pisces') }}">
                            <span class="sec5icon"><img src="{{ asset('frontend/images/pisces.png') }}" /></span>
                            <span class="sec5text">Pisces</span>
                        </a>
                    </div>
                </div>
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
                                                        <span class="aspect-type 
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

    <style>
        .horoscope-sign-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .horoscope-date {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .sign-info-badges .badge {
            margin: 0 0.25rem;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .horoscope-tabs {
            border-bottom: 2px solid #e9ecef;
        }

        .horoscope-tabs .nav-link {
            color: #6c757d;
            font-weight: 500;
            padding: 1rem 1.5rem;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .horoscope-tabs .nav-link:hover {
            color: #495057;
            border-bottom-color: #dee2e6;
        }

        .horoscope-tabs .nav-link.active {
            color: #007bff;
            border-bottom-color: #007bff;
            background-color: transparent;
        }

        .prediction-content {
            min-height: 200px;
        }

        .prediction-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #495057;
            text-align: justify;
        }

        .insight-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .insight-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .seek-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        }

        .challenge-card {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
        }

        .insight-card-main {
            background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
        }

        .insight-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #495057;
        }

        .insight-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        .insight-text {
            color: #495057;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .aspect-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            border-left: 4px solid #007bff;
        }

        .aspect-planets {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .planet-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .aspect-type {
            font-weight: bold;
            font-size: 0.9rem;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #0093E9 0%, #80D0C7 100%);
        }

        .sec5Box.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: scale(1.05);
        }

        .sec5Box.active a {
            color: white;
        }

        .sec5Box.active .sec5text {
            color: white;
        }

        @media (max-width: 768px) {
            .horoscope-tabs .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .horoscope-sign-title {
                font-size: 2rem;
            }

            .prediction-text {
                font-size: 1rem;
            }
        }
    </style>
@endsection