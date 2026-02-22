@extends('astrologer.layouts.app')

@section('title', 'Profile Settings')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/astrologer-panel.css') }}">
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Profile Settings</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('astrologer.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Profile Settings</li>
        </ol>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('astrologer.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Left Column: Photos & Prices -->
                <div class="col-lg-4">
                    <div class="profile-card text-center">
                        <h5 class="section-title">Profile Photos</h5>

                        <div class="mb-4">
                            <label class="form-label d-block text-start">Profile Image</label>
                            <div class="d-flex justify-content-center">
                                <div class="image-preview-container">
                                    <img id="profile_image_preview" src="{{ $profile->profile_image_url }}"
                                        class="image-preview" alt="Profile">
                                </div>
                            </div>
                            <input type="file" name="profile_image" class="form-control"
                                onchange="previewImage(this, 'profile_image_preview')">
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-block text-start">Cover Image</label>
                            <img id="cover_image_preview"
                                src="{{ $profile->cover_image_url ?? 'https://via.placeholder.com/800x200?text=No+Cover+Image' }}"
                                class="cover-preview" alt="Cover">
                            <input type="file" name="cover_image" class="form-control"
                                onchange="previewImage(this, 'cover_image_preview')">
                        </div>
                    </div>

                    <div class="profile-card">
                        <h5 class="section-title">Pricing Management</h5>

                        <div class="mb-4">
                            <label for="chat_price" class="form-label">Chat Price (per min)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" class="form-control" id="chat_price" name="chat_price"
                                    value="{{ old('chat_price', $profile->chat_price) }}" required
                                    oninput="calculateEarnings()">
                            </div>
                            <div class="earning-info">
                                <small class="text-muted">You will get:</small>
                                <div id="chat_earnings" class="earning-value">₹0.00</div>
                                <small class="text-muted d-block">After {{ $chatCommission }}% commission</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="call_price" class="form-label">Call Price (per min)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" class="form-control" id="call_price" name="call_price"
                                    value="{{ old('call_price', $profile->call_price) }}" required
                                    oninput="calculateEarnings()">
                            </div>
                            <div class="earning-info">
                                <small class="text-muted">You will get:</small>
                                <div id="call_earnings" class="earning-value">₹0.00</div>
                                <small class="text-muted d-block">After {{ $callCommission }}% commission</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Info & Expertise -->
                <div class="col-lg-8">
                    <div class="profile-card">
                        <h5 class="section-title">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="display_name" class="form-label">Display Name</label>
                                <input type="text" class="form-control" id="display_name" name="display_name"
                                    value="{{ old('display_name', $profile->display_name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="male" {{ $profile->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $profile->gender == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                    <option value="other" {{ $profile->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                    value="{{ old('date_of_birth', $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : '') }}"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="experience_years" class="form-label">Years of Experience</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years"
                                    value="{{ old('experience_years', $profile->experience_years) }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="about" class="form-label">About Bio</label>
                            <textarea class="form-control" id="about" name="about" rows="6"
                                required>{{ old('about', $profile->about) }}</textarea>
                        </div>
                    </div>

                    <div class="profile-card">
                        <h5 class="section-title">Expertise & Languages</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Languages</label>
                                <select class="form-select" name="languages[]" multiple style="height: 200px;" required>
                                    @foreach($languages as $language)
                                        <option value="{{ $language->id }}" {{ $profile->languages->contains($language->id) ? 'selected' : '' }}>
                                            {{ $language->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted mt-2 d-block">Hold Ctrl/Cmd to select multiple</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Specializations</label>
                                <select class="form-select" name="specializations[]" multiple style="height: 200px;"
                                    required>
                                    @foreach($specializations as $specialization)
                                        <option value="{{ $specialization->id }}" {{ $profile->specializations->contains($specialization->id) ? 'selected' : '' }}>
                                            {{ $specialization->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted mt-2 d-block">Hold Ctrl/Cmd to select multiple</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5 text-end">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Save Profile Changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const chatComm = {{ $chatCommission }};
        const callComm = {{ $callCommission }};

        function calculateEarnings() {
            const chatPrice = parseFloat(document.getElementById('chat_price').value) || 0;
            const callPrice = parseFloat(document.getElementById('call_price').value) || 0;

            const chatEarnings = chatPrice * (1 - (chatComm / 100));
            const callEarnings = callPrice * (1 - (callComm / 100));

            document.getElementById('chat_earnings').textContent = '₹' + chatEarnings.toFixed(2);
            document.getElementById('call_earnings').textContent = '₹' + callEarnings.toFixed(2);
        }

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById(previewId).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', calculateEarnings);
    </script>
@endpush