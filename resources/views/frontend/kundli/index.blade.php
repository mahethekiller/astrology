@extends('frontend.layouts.app')

@section('title', 'Your Kundli')

@section('content')
    <section class="py-5" style="background: #f8f9fa; min-height: 80vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="card-header border-0 p-5 text-center"
                            style="background: linear-gradient(135deg, #1a2a6c 0%, #b21f1f 50%, #fdbb2d 100%);">
                            <h1 class="h2 text-white fw-bold mb-2">Free Janma Kundli</h1>
                            <p class="text-white-50 mb-0">Discover your destiny with our precise Vedic Astrology charts</p>
                        </div>
                        <div class="card-body p-4 p-md-5 bg-white">
                            <form action="{{ route('kundli.generate') }}" method="POST">
                                @csrf

                                {{-- Personal Details --}}
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-4 d-flex align-items-center">
                                        <i class="bi bi-person-circle text-primary me-2 fs-4"></i>
                                        Personal Details
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label fw-semibold">Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i
                                                        class="bi bi-person"></i></span>
                                                <input type="text" class="form-control bg-light border-start-0 ps-0"
                                                    id="name" name="name" value="{{ old('name') }}"
                                                    placeholder="Enter your name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="gender" class="form-label fw-semibold">Gender</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i
                                                        class="bi bi-gender-ambiguous"></i></span>
                                                <select class="form-select bg-light border-start-0 ps-0" id="gender"
                                                    name="gender">
                                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male
                                                    </option>
                                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                        Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Birth Details --}}
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-4 d-flex align-items-center">
                                        <i class="bi bi-calendar-event text-danger me-2 fs-4"></i>
                                        Birth Details
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="date" class="form-label fw-semibold">Date of Birth</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i
                                                        class="bi bi-calendar3"></i></span>
                                                <input type="date"
                                                    class="form-control bg-light border-start-0 ps-0 @error('date') is-invalid @enderror"
                                                    id="date" name="date" value="{{ old('date') }}" required>
                                            </div>
                                            @error('date')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="time" class="form-label fw-semibold">Time of Birth</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i
                                                        class="bi bi-clock"></i></span>
                                                <input type="time"
                                                    class="form-control bg-light border-start-0 ps-0 @error('time') is-invalid @enderror"
                                                    id="time" name="time" value="{{ old('time') }}" required>
                                            </div>
                                            @error('time')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Location Search --}}
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-4 d-flex align-items-center">
                                        <i class="bi bi-geo-alt text-success me-2 fs-4"></i>
                                        Birth Location
                                    </h5>
                                    <div class="position-relative">
                                        <label for="location_search" class="form-label fw-semibold">City, State,
                                            Country</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i
                                                    class="bi bi-search"></i></span>
                                            <input type="text" class="form-control bg-light border-start-0 ps-0"
                                                id="location_search" placeholder="Type birth city name..."
                                                autocomplete="off">
                                        </div>
                                        <div id="location_suggestions"
                                            class="list-group position-absolute w-100 shadow-lg mt-1"
                                            style="z-index: 1050; display: none;"></div>
                                        <div id="selected_location_display" class="form-text text-success mt-2 fw-medium">
                                        </div>
                                    </div>
                                    @if ($errors->has('latitude') || $errors->has('longitude'))
                                        <div class="alert alert-soft-danger py-2 mt-3 mb-0">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            Please select a valid location from the suggestions.
                                        </div>
                                    @endif
                                </div>

                                {{-- Hidden Coordinates --}}
                                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                                <input type="hidden" name="timezone" id="timezone" value="{{ old('timezone') }}">

                                {{-- Chart Configuration --}}
                                <div class="mb-5">
                                    <h5 class="fw-bold mb-4 d-flex align-items-center">
                                        <i class="bi bi-gear text-secondary me-2 fs-4"></i>
                                        Advanced Options
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="ayanamsa" class="form-label fw-semibold small">Ayanamsa</label>
                                            <select class="form-select bg-light" id="ayanamsa" name="ayanamsa">
                                                <option value="1" {{ old('ayanamsa') == '1' ? 'selected' : '' }}>Lahiri
                                                </option>
                                                <option value="3" {{ old('ayanamsa') == '3' ? 'selected' : '' }}>Raman
                                                </option>
                                                <option value="5" {{ old('ayanamsa') == '5' ? 'selected' : '' }}>KP</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="language" class="form-label fw-semibold small">Language</label>
                                            <select class="form-select bg-light" id="language" name="language">
                                                <option value="en">English</option>
                                                <option value="hi">Hindi</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="result_type" class="form-label fw-semibold small">Chart Type</label>
                                            <select class="form-select bg-light" id="result_type" name="result_type">
                                                <option value="basic" {{ old('result_type') == 'basic' ? 'selected' : '' }}>
                                                    Basic</option>
                                                <option value="advanced" {{ old('result_type') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn py-3 fw-bold shadow-sm rounded-3"
                                        style="background-color: rgba(253, 219, 24, 1); color: #111827; border: none; font-size: 1.1rem; transition: background-color 0.2s;">
                                        <i class="bi bi-stars me-2"></i>
                                        GENERATE KUNDLI
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted small">Your birth details are used only for calculating your astrological
                            chart.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .input-group-text {
            color: #6366f1;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #fff !important;
            border-color: #fdbb2d;
            box-shadow: 0 0 0 0.25rem rgba(253, 187, 45, 0.1);
        }

        .list-group-item-action:hover {
            background-color: #fffbeb;
            color: #b45309;
        }

        .alert-soft-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fee2e2;
        }
    </style>

    @push('scripts')
        <script>
            $(document).ready(function () {
                const searchInput = $('#location_search');
                const suggestionsBox = $('#location_suggestions');
                const latInput = $('#latitude');
                const longInput = $('#longitude');
                const tzInput = $('#timezone');
                const displaySelected = $('#selected_location_display');
                let timeout = null;

                searchInput.on('input', function () {
                    const query = $(this).val();

                    if (query.length < 3) {
                        suggestionsBox.hide();
                        return;
                    }

                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        $.ajax({
                            url: "{{ route('api.location.search') }}",
                            data: { q: query },
                            success: function (response) {
                                suggestionsBox.empty();

                                // Response structure: { status: "ok", data: [ [id, name, region, country, code, timezone, lat, long], ... ] }
                                if (response && response.status === 'ok' && Array.isArray(response.data)) {
                                    const locations = response.data;

                                    if (locations.length > 0) {
                                        locations.forEach(loc => {
                                            // loc is an array: [id, name, region, country, code, timezone, lat, long]
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
                                console.error('Location search failed', err);
                            }
                        });
                    }, 500);
                });

                // Hide suggestions when clicking outside
                $(document).on('click', function (e) {
                    if (!$(e.target).closest('#location_search, #location_suggestions').length) {
                        suggestionsBox.hide();
                    }
                });
            });
        </script>
    @endpush
@endsection