@extends('frontend.layouts.app')

@section('title', 'Astrologer Registration')

@push('styles')
    <style>
        .registration-section {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .registration-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-bottom: 30px;
        }

        .section-title {
            border-bottom: 2px solid #fbd91a;
            padding-bottom: 10px;
            margin-bottom: 30px;
            font-weight: 700;
            color: #333;
        }

        .form-label {
            font-weight: 600;
            color: #555;
        }

        .form-control:focus {
            border-color: #fbd91a;
            box-shadow: 0 0 0 0.2rem rgba(251, 217, 26, 0.25);
        }

        .btn-register {
            background: #fbd91a;
            color: #000;
            border: none;
            padding: 12px 30px;
            font-weight: 700;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .btn-register:hover {
            background: #000;
            color: #fff;
            transform: translateY(-2px);
        }

        .image-preview {
            max-width: 150px;
            max-height: 150px;
            margin-top: 10px;
            border-radius: 10px;
            display: none;
            border: 2px dashed #ddd;
            padding: 5px;
        }

        .select-multiple {
            min-height: 150px;
        }
    </style>
@endpush

@section('content')
    <div class="registration-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center mb-5">
                        <h2 class="title2">Join Our Expert Panel</h2>
                        <p class="lead">Register as an Astrologer and start helping people with your wisdom.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('astrologer.register.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Account Information -->
                        <div class="registration-card">
                            <h4 class="section-title"><i class="bi bi-person-circle me-2"></i>Account Information</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" value="{{ old('name') }}" required placeholder="Your real name">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                        name="email" value="{{ old('email') }}" required placeholder="email@example.com">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required placeholder="Minimum 8 characters">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required placeholder="Repeat password">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                    name="phone" value="{{ old('phone') }}"
                                    placeholder="Contact number (with country code)">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Profile Information -->
                        <div class="registration-card">
                            <h4 class="section-title"><i class="bi bi-star-fill me-2"></i>Professional Profile</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="display_name" class="form-label">Display Name *</label>
                                    <input type="text" class="form-control @error('display_name') is-invalid @enderror"
                                        id="display_name" name="display_name" value="{{ old('display_name') }}" required
                                        placeholder="Professional name (e.g. Acharya John)">
                                    @error('display_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gender *</label>
                                    <div class="d-flex gap-4 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_male"
                                                value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="gender_male">Male</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_female"
                                                value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender_female">Female</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_other"
                                                value="other" {{ old('gender') == 'other' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender_other">Other</label>
                                        </div>
                                    </div>
                                    @error('gender') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                        id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                    @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="experience_years" class="form-label">Years of Experience *</label>
                                    <input type="number"
                                        class="form-control @error('experience_years') is-invalid @enderror"
                                        id="experience_years" name="experience_years" value="{{ old('experience_years') }}"
                                        min="0" required placeholder="Total years in astrology">
                                    @error('experience_years') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="about" class="form-label">About You *</label>
                                <textarea class="form-control @error('about') is-invalid @enderror" id="about" name="about"
                                    rows="6" required
                                    placeholder="Tell us about your background, expertise and how you help your clients...">{{ old('about') }}</textarea>
                                @error('about') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="profile_image" class="form-label">Profile Photo *</label>
                                    <input type="file" class="form-control @error('profile_image') is-invalid @enderror"
                                        id="profile_image" name="profile_image" accept="image/*" required
                                        onchange="previewImage(this, 'profile_preview')">
                                    <img id="profile_preview" class="image-preview" alt="Profile Preview">
                                    @error('profile_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cover_image" class="form-label">Cover Photo (Optional)</label>
                                    <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                                        id="cover_image" name="cover_image" accept="image/*"
                                        onchange="previewImage(this, 'cover_preview')">
                                    <img id="cover_preview" class="image-preview" alt="Cover Preview">
                                    @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Languages Known *</label>
                                    <select class="form-select select-multiple @error('languages') is-invalid @enderror"
                                        name="languages[]" multiple required>
                                        @foreach($languages as $language)
                                            <option value="{{ $language->id }}" {{ in_array($language->id, old('languages', [])) ? 'selected' : '' }}>{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                                    @error('languages') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Specializations *</label>
                                    <select
                                        class="form-select select-multiple @error('specializations') is-invalid @enderror"
                                        name="specializations[]" multiple required>
                                        @foreach($specializations as $specialization)
                                            <option value="{{ $specialization->id }}" {{ in_array($specialization->id, old('specializations', [])) ? 'selected' : '' }}>{{ $specialization->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                                    @error('specializations') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-center mb-5">
                            <button type="submit" class="btn-register">Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush