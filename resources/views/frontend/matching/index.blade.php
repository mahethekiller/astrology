@extends('frontend.layouts.app')

@section('title', 'Kundli Matching - Gun Milan')

@section('content')
    <div class="section5">
        <div class="container">
            <h2 class="title2">Kundli Matching</h2>
            <div class="headingDeign"><img src="{{ asset('frontend/images/headingDesign.png') }}" /></div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('kundli.matching.calculate') }}" method="POST" class="mt-5">
                @csrf
                <div class="row">
                    <!-- Boy's Details -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-male me-2"></i>Boy's Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="boy_name"
                                        class="form-control @error('boy_name') is-invalid @enderror"
                                        value="{{ old('boy_name', $formData['boy_name'] ?? '') }}" required>
                                    @error('boy_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" name="boy_date"
                                            class="form-control @error('boy_date') is-invalid @enderror"
                                            value="{{ old('boy_date', $formData['boy_date'] ?? '') }}" required>
                                        @error('boy_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Time of Birth <span class="text-danger">*</span></label>
                                        <input type="time" name="boy_time"
                                            class="form-control @error('boy_time') is-invalid @enderror"
                                            value="{{ old('boy_time', $formData['boy_time'] ?? '') }}" required>
                                        @error('boy_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text" id="boy_location" class="form-control"
                                            placeholder="Type to search location..." autocomplete="off">
                                        <div id="boy_location_suggestions"
                                            class="list-group position-absolute w-100 shadow-sm"
                                            style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto;">
                                        </div>
                                        <div id="boy_loader" class="position-absolute top-50 end-0 translate-middle-y me-3"
                                            style="display: none;">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted" id="boy_selected_location"></small>
                                    <input type="hidden" name="boy_latitude" id="boy_latitude"
                                        value="{{ old('boy_latitude', $formData['boy_latitude'] ?? '') }}" required>
                                    <input type="hidden" name="boy_longitude" id="boy_longitude"
                                        value="{{ old('boy_longitude', $formData['boy_longitude'] ?? '') }}" required>
                                    <input type="hidden" name="boy_timezone" id="boy_timezone"
                                        value="{{ old('boy_timezone', $formData['boy_timezone'] ?? '') }}" required>
                                    @error('boy_latitude')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Girl's Details -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0"><i class="fas fa-female me-2"></i>Girl's Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="girl_name"
                                        class="form-control @error('girl_name') is-invalid @enderror"
                                        value="{{ old('girl_name', $formData['girl_name'] ?? '') }}" required>
                                    @error('girl_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" name="girl_date"
                                            class="form-control @error('girl_date') is-invalid @enderror"
                                            value="{{ old('girl_date', $formData['girl_date'] ?? '') }}" required>
                                        @error('girl_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Time of Birth <span class="text-danger">*</span></label>
                                        <input type="time" name="girl_time"
                                            class="form-control @error('girl_time') is-invalid @enderror"
                                            value="{{ old('girl_time', $formData['girl_time'] ?? '') }}" required>
                                        @error('girl_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Place of Birth <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text" id="girl_location" class="form-control"
                                            placeholder="Type to search location..." autocomplete="off">
                                        <div id="girl_location_suggestions"
                                            class="list-group position-absolute w-100 shadow-sm"
                                            style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto;">
                                        </div>
                                        <div id="girl_loader" class="position-absolute top-50 end-0 translate-middle-y me-3"
                                            style="display: none;">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted" id="girl_selected_location"></small>
                                    <input type="hidden" name="girl_latitude" id="girl_latitude"
                                        value="{{ old('girl_latitude', $formData['girl_latitude'] ?? '') }}" required>
                                    <input type="hidden" name="girl_longitude" id="girl_longitude"
                                        value="{{ old('girl_longitude', $formData['girl_longitude'] ?? '') }}" required>
                                    <input type="hidden" name="girl_timezone" id="girl_timezone"
                                        value="{{ old('girl_timezone', $formData['girl_timezone'] ?? '') }}" required>
                                    @error('girl_latitude')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ayanamsa <span class="text-danger">*</span></label>
                                        <select name="ayanamsa" class="form-select @error('ayanamsa') is-invalid @enderror"
                                            required>
                                            <option value="1" {{ old('ayanamsa', $formData['ayanamsa'] ?? 1) == 1 ? 'selected' : '' }}>Lahiri</option>
                                            <option value="3" {{ old('ayanamsa', $formData['ayanamsa'] ?? '') == 3 ? 'selected' : '' }}>Raman</option>
                                            <option value="5" {{ old('ayanamsa', $formData['ayanamsa'] ?? '') == 5 ? 'selected' : '' }}>KP</option>
                                        </select>
                                        @error('ayanamsa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button type="submit" class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-heart me-2"></i>Calculate Compatibility
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


            @if(isset($result) && isset($result['data']))
                @php
                    $data = $result['data'];
                    $gunaMilan = $data['guna_milan'] ?? null;
                    $message = $data['message'] ?? null;
                @endphp

                <div class="results-section mt-5">
                    <!-- Overall Compatibility -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div
                            class="card-header {{ $message && $message['type'] === 'good' ? 'bg-success' : ($message && $message['type'] === 'bad' ? 'bg-danger' : 'bg-warning') }} text-white">
                            <h4 class="mb-0 text-center">
                                <i class="fas fa-heart me-2"></i>
                                Compatibility Result: {{ $boyName ?? 'Boy' }} & {{ $girlName ?? 'Girl' }}
                            </h4>
                        </div>
                        <div class="card-body">
                            @if($gunaMilan)
                                <div class="text-center mb-4">
                                    <h2 class="display-4 mb-3">
                                        <span
                                            class="badge {{ $gunaMilan['total_points'] >= 24 ? 'bg-success' : ($gunaMilan['total_points'] >= 18 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $gunaMilan['total_points'] }}/{{ $gunaMilan['maximum_points'] }}
                                        </span>
                                    </h2>
                                    <div class="progress" style="height: 30px;">
                                        <div class="progress-bar {{ $gunaMilan['total_points'] >= 24 ? 'bg-success' : ($gunaMilan['total_points'] >= 18 ? 'bg-warning' : 'bg-danger') }}"
                                            role="progressbar"
                                            style="width: {{ ($gunaMilan['total_points'] / $gunaMilan['maximum_points']) * 100 }}%">
                                            {{ number_format(($gunaMilan['total_points'] / $gunaMilan['maximum_points']) * 100, 1) }}%
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($message)
                                <div
                                    class="alert alert-{{ $message['type'] === 'good' ? 'success' : ($message['type'] === 'bad' ? 'danger' : 'warning') }}">
                                    <h5><i class="fas fa-info-circle me-2"></i>Analysis</h5>
                                    <p class="mb-0">
                                        @if(is_array($message['description']))
                                            @foreach($message['description'] as $desc)
                                                {{ $desc }}<br>
                                            @endforeach
                                        @else
                                            {{ $message['description'] }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Birth Details Analysis -->
                    <div class="row">
                        @foreach(['boy', 'girl'] as $person)
                            @php
                                $info = $data[$person . '_info'] ?? null;
                                $name = $person === 'boy' ? ($boyName ?? 'Boy') : ($girlName ?? 'Girl');
                                $color = $person === 'boy' ? 'primary' : 'danger';
                                $icon = $person === 'boy' ? 'fa-male' : 'fa-female';
                            @endphp
                            @if($info)
                                <div class="col-md-6 mb-4">
                                    <div class="card shadow-sm border-0 h-100">
                                        <div class="card-header bg-{{ $color }} text-white">
                                            <h5 class="mb-0"><i class="fas {{ $icon }} me-2"></i>{{ $name }} - Birth Details</h5>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($info['nakshatra']))
                                                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                                    <strong>Nakshatra:</strong>
                                                    <span>{{ $info['nakshatra']['name'] }} (Pada {{ $info['nakshatra']['pada'] }})</span>
                                                </div>
                                                @if(isset($info['nakshatra']['lord']))
                                                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                                        <strong>Nakshatra Lord:</strong>
                                                        <span>{{ $info['nakshatra']['lord']['vedic_name'] }}
                                                            ({{ $info['nakshatra']['lord']['name'] }})</span>
                                                    </div>
                                                @endif
                                            @endif
                                            @if(isset($info['rasi']))
                                                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                                    <strong>Rasi (Moon Sign):</strong>
                                                    <span>{{ $info['rasi']['name'] }}</span>
                                                </div>
                                                @if(isset($info['rasi']['lord']))
                                                    <div class="d-flex justify-content-between">
                                                        <strong>Rasi Lord:</strong>
                                                        <span>{{ $info['rasi']['lord']['vedic_name'] }}
                                                            ({{ $info['rasi']['lord']['name'] }})</span>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Guna Milan Details -->
                    @if($gunaMilan && isset($gunaMilan['guna']))
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-gradient-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-list-check me-2"></i>Guna Milan (Ashtakoot) Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($gunaMilan['guna'] as $koot)
                                        <div class="col-md-6 mb-4">
                                            <div class="koot-card h-100">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="mb-0">
                                                        @if(is_array($koot['name']))
                                                            {{ implode(', ', $koot['name']) }}
                                                        @else
                                                            {{ $koot['name'] }}
                                                        @endif
                                                    </h6>
                                                    <span
                                                        class="badge {{ $koot['obtained_points'] == $koot['maximum_points'] ? 'bg-success' : ($koot['obtained_points'] > 0 ? 'bg-warning' : 'bg-danger') }}">
                                                        {{ $koot['obtained_points'] }}/{{ $koot['maximum_points'] }}
                                                    </span>
                                                </div>
                                                <div class="progress mb-3" style="height: 8px;">
                                                    <div class="progress-bar {{ $koot['obtained_points'] == $koot['maximum_points'] ? 'bg-success' : ($koot['obtained_points'] > 0 ? 'bg-warning' : 'bg-danger') }}"
                                                        style="width: {{ ($koot['obtained_points'] / $koot['maximum_points']) * 100 }}%">
                                                    </div>
                                                </div>
                                                <div class="koot-values mb-2">
                                                    <small class="text-muted">
                                                        <strong>Boy:</strong> @if(is_array($koot['boy_koot']))
                                                            {{ implode(', ', $koot['boy_koot']) }}
                                                        @else
                                                            {{ $koot['boy_koot'] }}
                                                        @endif
                                                        |
                                                        <strong>Girl:</strong>
                                                        @if(is_array($koot['girl_koot']))
                                                            {{ implode(', ', $koot['girl_koot']) }}
                                                        @else
                                                            {{ $koot['girl_koot'] }}
                                                        @endif
                                                    </small>
                                                </div>
                                                <p class="small text-muted mb-0">
                                                    @if(is_array($koot['description']))
                                                        @foreach($koot['description'] as $desc)
                                                            {{ $desc }}<br>
                                                        @endforeach
                                                    @else
                                                        {{ $koot['description'] }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Mangal Dosha Details -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-male me-2"></i>{{ $boyName ?? 'Boy' }} - Mangal Dosha</h6>
                                </div>
                                <div class="card-body">
                                    @if(isset($data['boy_mangal_dosha_details']))
                                        @php $boyDosha = $data['boy_mangal_dosha_details']; @endphp
                                        <div class="text-center mb-3">
                                            <span class="badge {{ $boyDosha['has_dosha'] ? 'bg-danger' : 'bg-success' }} p-3">
                                                <i
                                                    class="fas {{ $boyDosha['has_dosha'] ? 'fa-exclamation-triangle' : 'fa-check-circle' }} me-2"></i>
                                                {{ $boyDosha['has_dosha'] ? 'Manglik' : 'Not Manglik' }}
                                                @if($boyDosha['has_dosha'] && !empty($boyDosha['dosha_type']))
                                                    ({{ ucfirst($boyDosha['dosha_type']) }})
                                                @endif
                                            </span>
                                        </div>
                                        <p class="text-center mb-0">
                                            @if(is_array($boyDosha['description']))
                                                @foreach($boyDosha['description'] as $desc)
                                                    {{ $desc }}<br>
                                                @endforeach
                                            @else
                                                {{ $boyDosha['description'] }}
                                            @endif
                                        </p>
                                        @if($boyDosha['has_exception'])
                                            <div class="alert alert-info mt-3 mb-0">
                                                <small><i class="fas fa-info-circle me-1"></i>Exception Present</small>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0"><i class="fas fa-female me-2"></i>{{ $girlName ?? 'Girl' }} - Mangal Dosha
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if(isset($data['girl_mangal_dosha_details']))
                                        @php $girlDosha = $data['girl_mangal_dosha_details']; @endphp
                                        <div class="text-center mb-3">
                                            <span class="badge {{ $girlDosha['has_dosha'] ? 'bg-danger' : 'bg-success' }} p-3">
                                                <i
                                                    class="fas {{ $girlDosha['has_dosha'] ? 'fa-exclamation-triangle' : 'fa-check-circle' }} me-2"></i>
                                                {{ $girlDosha['has_dosha'] ? 'Manglik' : 'Not Manglik' }}
                                                @if($girlDosha['has_dosha'] && !empty($girlDosha['dosha_type']))
                                                    ({{ ucfirst($girlDosha['dosha_type']) }})
                                                @endif
                                            </span>
                                        </div>
                                        <p class="text-center mb-0">
                                            @if(is_array($girlDosha['description']))
                                                @foreach($girlDosha['description'] as $desc)
                                                    {{ $desc }}<br>
                                                @endforeach
                                            @else
                                                {{ $girlDosha['description'] }}
                                            @endif
                                        </p>
                                        @if($girlDosha['has_exception'])
                                            <div class="alert alert-info mt-3 mb-0">
                                                <small><i class="fas fa-info-circle me-1"></i>Exception Present</small>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!empty($data['exceptions']))
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-circle-exclamation me-2"></i>Important Notes & Exceptions</h5>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    @foreach($data['exceptions'] as $exception)
                                        @if(!empty($exception))
                                            <li>
                                                @if(is_array($exception))
                                                    @foreach($exception as $e) {{ $e }}<br> @endforeach
                                                @else
                                                    {{ $exception }}
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
        .koot-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        @media (max-width: 768px) {
            .display-4 {
                font-size: 2rem;
            }
        }
    </style>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Location search for Boy
                setupLocationSearch('boy');
                // Location search for Girl
                setupLocationSearch('girl');

                function setupLocationSearch(prefix) {
                    const searchInput = $(`#${prefix}_location`);
                    const suggestionsBox = $(`#${prefix}_location_suggestions`);
                    const loader = $(`#${prefix}_loader`);
                    const latInput = $(`#${prefix}_latitude`);
                    const longInput = $(`#${prefix}_longitude`);
                    const tzInput = $(`#${prefix}_timezone`);
                    const displaySelected = $(`#${prefix}_selected_location`);
                    let timeout = null;

                    searchInput.on('input', function () {
                        const query = $(this).val();

                        if (query.length < 3) {
                            suggestionsBox.hide();
                            loader.hide();
                            return;
                        }

                        clearTimeout(timeout);
                        loader.show();

                        timeout = setTimeout(() => {
                            $.ajax({
                                url: "{{ route('api.location.search') }}",
                                data: { q: query },
                                success: function (response) {
                                    loader.hide();
                                    suggestionsBox.empty();

                                    if (response && response.status === 'ok' && Array.isArray(response.data)) {
                                        const locations = response.data;

                                        if (locations.length > 0) {
                                            locations.forEach(loc => {
                                                const locName = loc[1];
                                                const locRegion = loc[2];
                                                const locCountry = loc[3];
                                                const locTimezone = loc[5];
                                                const locLat = loc[6];
                                                const locLong = loc[7];

                                                const item = $(`
                                                                                                                                    <button type="button" class="list-group-item list-group-item-action text-start">
                                                                                                                                        <strong>${locName}</strong><br>
                                                                                                                                        <small class="text-muted">${locRegion}, ${locCountry}</small>
                                                                                                                                    </button>
                                                                                                                                `);

                                                item.on('click', function () {
                                                    latInput.val(locLat);
                                                    longInput.val(locLong);
                                                    tzInput.val(locTimezone);
                                                    searchInput.val(`${locName}, ${locRegion}, ${locCountry}`);
                                                    suggestionsBox.hide();
                                                    displaySelected.text(`Selected: ${locName} (${locTimezone})`);
                                                });

                                                suggestionsBox.append(item);
                                            });
                                            suggestionsBox.show();
                                        } else {
                                            suggestionsBox.hide();
                                        }
                                    } else {
                                        console.error('Unexpected response format', response);
                                        suggestionsBox.hide();
                                    }
                                },
                                error: function (err) {
                                    loader.hide();
                                    console.error('Location search failed', err);
                                }
                            });
                        }, 500);
                    });
                }

                // Hide suggestions when clicking outside
                $(document).on('click', function (e) {
                    if (!$(e.target).closest('#boy_location, #boy_location_suggestions, #girl_location, #girl_location_suggestions').length) {
                        $('#boy_location_suggestions, #girl_location_suggestions').hide();
                    }
                });
            });
        </script>
    @endpush
@endsection