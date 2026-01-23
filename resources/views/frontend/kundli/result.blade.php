@extends('frontend.layouts.app')

@section('title', 'Your Kundli')

@section('content')
    <div class="container py-5">
        <div class="row align-items-center mb-5">
            <div class="col-md-8">
                <h1 class="mb-0">Your Kundli</h1>
                <p class="text-muted mb-0">Generated for {{ \Carbon\Carbon::parse($request->date)->format('F j, Y') }} at
                    {{ \Carbon\Carbon::parse($request->time)->format('g:i A') }}</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('kundli.index') }}" class="btn btn-outline-primary">Generate Another</a>
            </div>
        </div>

        @if($kundli && isset($kundli['data']))
            <div class="row">
                {{-- Nakshatra Details --}}
                @if(isset($kundli['data']['nakshatra_details']))
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Nakshatra Details</h5>
                            </div>
                            <div class="card-body">
                                @php $n = $kundli['data']['nakshatra_details']; @endphp
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th class="text-muted">Nakshatra</th>
                                            <td>{{ $n['nakshatra']['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Nakshatra Lord</th>
                                            <td>{{ $n['nakshatra']['lord']['name'] }} ({{ $n['nakshatra']['lord']['vedic_name'] }})</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Chandra Rasi</th>
                                            <td>{{ $n['chandra_rasi']['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Chandra Rasi Lord</th>
                                            <td>{{ $n['chandra_rasi']['lord']['name'] }} ({{ $n['chandra_rasi']['lord']['vedic_name'] }})</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Soorya Rasi</th>
                                            <td>{{ $n['soorya_rasi']['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Soorya Rasi Lord</th>
                                            <td>{{ $n['soorya_rasi']['lord']['name'] }} ({{ $n['soorya_rasi']['lord']['vedic_name'] }})</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Zodiac</th>
                                            <td>{{ $n['zodiac']['name'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Info --}}
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">Additional Info</h5>
                            </div>
                            <div class="card-body">
                                @php $info = $kundli['data']['nakshatra_details']['additional_info']; @endphp
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <tr><th>Deity</th><td>{{ $info['deity'] }}</td></tr>
                                        <tr><th>Ganam</th><td>{{ $info['ganam'] }}</td></tr>
                                        <tr><th>Symbol</th><td>{{ $info['symbol'] }}</td></tr>
                                        <tr><th>Animal Sign</th><td>{{ $info['animal_sign'] }}</td></tr>
                                        <tr><th>Nadi</th><td>{{ $info['nadi'] }}</td></tr>
                                        <tr><th>Color</th><td>{{ $info['color'] }}</td></tr>
                                        <tr><th>Best Direction</th><td>{{ $info['best_direction'] }}</td></tr>
                                        <tr><th>Birth Stone</th><td>{{ $info['birth_stone'] }}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Mangal Dosha --}}
                @if(isset($kundli['data']['mangal_dosha']))
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm border-0 {{ $kundli['data']['mangal_dosha']['has_dosha'] ? 'border-danger' : 'border-success' }}">
                            <div class="card-header {{ $kundli['data']['mangal_dosha']['has_dosha'] ? 'bg-danger' : 'bg-success' }} text-white">
                                <h5 class="mb-0">Mangal Dosha</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        @if($kundli['data']['mangal_dosha']['has_dosha'])
                                            <span class="fs-1 text-danger">⚠️</span>
                                        @else
                                            <span class="fs-1 text-success">✅</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="card-title">{{ $kundli['data']['mangal_dosha']['has_dosha'] ? 'Manglik Dosha Present' : 'No Manglik Dosha' }}</h5>
                                        <p class="card-text">{{ $kundli['data']['mangal_dosha']['description'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Yoga Details --}}
                @if(isset($kundli['data']['yoga_details']))
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">Yoga Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($kundli['data']['yoga_details'] as $yoga)
                                        <div class="col-md-6 mb-3">
                                            <div class="p-3 border rounded h-100">
                                                <h6 class="fw-bold">{{ $yoga['name'] }}</h6>
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
            <div class="alert alert-danger">
                Unable to generate Kundli. Please check your inputs and try again.
            </div>
        @endif
    </div>
@endsection