@extends('frontend.layouts.app')

@section('title', 'Daily Horoscope - ' . ($sign ? ucfirst($sign) : 'Select Sign'))

@section('content')
    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="mb-3">Daily Horoscope</h1>
                <p class="lead">Select your zodiac sign to get your daily prediction</p>
            </div>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    @foreach($signs as $key => $label)
                        <a href="{{ route('horoscope.daily', $key) }}"
                            class="btn {{ $sign === $key ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        @if($prediction)
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h2 class="card-title text-center mb-4">{{ ucfirst($sign) }} Daily Horoscope</h2>
                            <div class="text-muted text-center mb-4">
                                {{ \Carbon\Carbon::parse(now())->format('l, F j, Y') }}
                            </div>

                            @if(isset($prediction['data']['daily_prediction']['prediction']))
                                <div class="prediction-content">
                                    <p class="lead">{{ $prediction['data']['daily_prediction']['prediction'] }}</p>
                                </div>
                            @elseif(isset($prediction['data']))
                                {{-- Fallback for structure variation --}}
                                @foreach($prediction['data'] as $key => $value)
                                    @if(is_string($value))
                                        <h5 class="mt-3">{{ ucfirst(str_replace('_', ' ', $key)) }}</h5>
                                        <p>{{ $value }}</p>
                                    @endif
                                @endforeach
                            @else
                                <div class="alert alert-info">
                                    Prediction data format is unexpected.
                                    @if(app()->environment('local'))
                                        <pre>{{ json_encode($prediction, JSON_PRETTY_PRINT) }}</pre>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @elseif($sign)
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="alert alert-warning text-center">
                        Unable to fetch horoscope for <strong>{{ ucfirst($sign) }}</strong> at this time. Please try again
                        later.
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection