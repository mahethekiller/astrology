@extends('frontend.layouts.app')

@section('title', 'Your Kundli')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-4 px-4 text-center">
                        <h1 class="h3 mb-0">Generate Your Kundli</h1>
                        <p class="text-muted small">Enter your birth details below</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('kundli.generate') }}" method="POST">
                            @csrf

                            {{-- Personal Details --}}
                            <h5 class="mb-3 text-secondary">Personal Details</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                                        placeholder="Enter Name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Birth Details --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" id="date"
                                        name="date" value="{{ old('date') }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="time" class="form-label">Time of Birth</label>
                                    <input type="time" class="form-control @error('time') is-invalid @enderror" id="time"
                                        name="time" value="{{ old('time') }}" required>
                                    @error('time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Location Search --}}
                            <h5 class="mb-3 mt-4 text-secondary">Birth Location</h5>
                            <div class="mb-3 position-relative">
                                <label for="location_search" class="form-label">City, State, Country</label>
                                <input type="text" class="form-control" id="location_search"
                                    placeholder="Type to search location..." autocomplete="off">
                                <div id="location_suggestions" class="list-group position-absolute w-100 shadow-sm"
                                    style="z-index: 1000; display: none;"></div>
                                <small class="text-muted" id="selected_location_display"></small>
                            </div>

                            {{-- Hidden Coordinates --}}
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                            <input type="hidden" name="timezone" id="timezone" value="{{ old('timezone') }}">

                            @if ($errors->has('latitude') || $errors->has('longitude'))
                                <div class="alert alert-danger">
                                    Please search and select a valid location from the list.
                                </div>
                            @endif

                            {{-- Chart Configuration --}}
                            <h5 class="mb-3 mt-4 text-secondary">Configuration</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ayanamsa" class="form-label">Ayanamsa</label>
                                    <select class="form-select" id="ayanamsa" name="ayanamsa">
                                        <option value="1" {{ old('ayanamsa') == '1' ? 'selected' : '' }}>Lahiri (Chitrapaksha)
                                        </option>
                                        <option value="3" {{ old('ayanamsa') == '3' ? 'selected' : '' }}>Raman</option>
                                        <option value="5" {{ old('ayanamsa') == '5' ? 'selected' : '' }}>KP</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="language" class="form-label">Report Language</label>
                                    <select class="form-select" id="language" name="language">
                                        <option value="en">English</option>
                                        <option value="hi">Hindi</option>
                                        <option value="ta">Tamil</option>
                                        <option value="ml">Malayalam</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="result_type" class="form-label">Result Type</label>
                                    <select class="form-select" id="result_type" name="result_type">
                                        <option value="basic" {{ old('result_type') == 'basic' ? 'selected' : '' }}>Basic
                                        </option>
                                        <option value="advanced" {{ old('result_type') == 'advanced' ? 'selected' : '' }}>
                                            Advanced</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Generate Kundli</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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