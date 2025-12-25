@extends('admin.layouts.app')

@section('title', 'Create Astrologer Profile')
@section('page-title', 'Create Astrologer Profile')

@push('styles')
<style>
    .image-preview {
        max-width: 200px;
        max-height: 200px;
        margin-top: 10px;
        border-radius: 8px;
        display: none;
    }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="component-card">
                <h4>Create New Astrologer Profile</h4>
                <form method="POST" action="{{ route('admin.astrologer-profiles.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- User Account Section -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">User Account Information</h5>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="name">Full Name *</label>
                                    <input type="text" class="form-control-custom @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required
                                        placeholder="Enter full name">
                                    @error('name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="email">Email Address *</label>
                                    <input type="email" class="form-control-custom @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required
                                        placeholder="Enter email address">
                                    @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="password">Password *</label>
                                    <input type="password"
                                        class="form-control-custom @error('password') is-invalid @enderror" id="password"
                                        name="password" required placeholder="Enter password (minimum 8 characters)">
                                    @error('password')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="password_confirmation">Confirm Password *</label>
                                    <input type="password" class="form-control-custom" id="password_confirmation"
                                        name="password_confirmation" required placeholder="Re-enter password">
                                </div>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-custom" for="phone">Phone Number</label>
                            <input type="text" class="form-control-custom @error('phone') is-invalid @enderror" id="phone"
                                name="phone" value="{{ old('phone') }}" placeholder="Enter phone number (optional)">
                            @error('phone')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Astrologer Profile Section -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">Astrologer Profile Information</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="display_name">Display Name *</label>
                                    <input type="text"
                                        class="form-control-custom @error('display_name') is-invalid @enderror"
                                        id="display_name" name="display_name" value="{{ old('display_name') }}" required
                                        placeholder="Enter display name for profile">
                                    @error('display_name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">Gender *</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_male"
                                                value="male" {{ old('gender') === 'male' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="gender_male">Male</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_female"
                                                value="female" {{ old('gender') === 'female' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender_female">Female</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_other"
                                                value="other" {{ old('gender') === 'other' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender_other">Other</label>
                                        </div>
                                    </div>
                                    @error('gender')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="date_of_birth">Date of Birth *</label>
                                    <input type="date"
                                        class="form-control-custom @error('date_of_birth') is-invalid @enderror"
                                        id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                    @error('date_of_birth')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="experience_years">Years of Experience *</label>
                                    <input type="number"
                                        class="form-control-custom @error('experience_years') is-invalid @enderror"
                                        id="experience_years" name="experience_years" value="{{ old('experience_years') }}"
                                        min="0" max="70" required placeholder="Enter years of experience">
                                    @error('experience_years')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="chat_price">Chat Price Using (per min)</label>
                                    <input type="number" step="0.01" min="0" class="form-control-custom @error('chat_price') is-invalid @enderror"
                                        id="chat_price" name="chat_price" value="{{ old('chat_price') }}" placeholder="Enter chat price">
                                    @error('chat_price')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="call_price">Call Price Using (per min)</label>
                                    <input type="number" step="0.01" min="0" class="form-control-custom @error('call_price') is-invalid @enderror"
                                        id="call_price" name="call_price" value="{{ old('call_price') }}" placeholder="Enter call price">
                                    @error('call_price')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-custom" for="about">About *</label>
                            <textarea class="form-control-custom @error('about') is-invalid @enderror" id="about"
                                name="about" rows="5" required
                                placeholder="Enter detailed description about the astrologer">{{ old('about') }}</textarea>
                            @error('about')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="profile_image">Profile Image * (Max 2MB)</label>
                                    <input type="file"
                                        class="form-control-custom @error('profile_image') is-invalid @enderror"
                                        id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/jpg"
                                        required onchange="previewImage(this, 'profile_preview')">
                                    <img id="profile_preview" class="image-preview" alt="Profile Preview">
                                    @error('profile_image')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="cover_image">Cover Image (Max 2MB)</label>
                                    <input type="file"
                                        class="form-control-custom @error('cover_image') is-invalid @enderror"
                                        id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/jpg"
                                        onchange="previewImage(this, 'cover_preview')">
                                    <img id="cover_preview" class="image-preview" alt="Cover Preview">
                                    @error('cover_image')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">Languages * (Hold Ctrl/Cmd to select multiple)</label>
                                    <select class="form-control-custom @error('languages') is-invalid @enderror" 
                                            name="languages[]" multiple size="5" required>
                                        @foreach($languages as $language)
                                            <option value="{{ $language->id }}" 
                                                {{ in_array($language->id, old('languages', [])) ? 'selected' : '' }}>
                                                {{ $language->name }} ({{ strtoupper($language->code) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple languages</small>
                                    @error('languages')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">Specializations * (Hold Ctrl/Cmd to select multiple)</label>
                                    <select class="form-control-custom @error('specializations') is-invalid @enderror" 
                                            name="specializations[]" multiple size="5" required>
                                        @foreach($specializations as $specialization)
                                            <option value="{{ $specialization->id }}" 
                                                {{ in_array($specialization->id, old('specializations', [])) ? 'selected' : '' }}>
                                                {{ $specialization->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple specializations</small>
                                    @error('specializations')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="verification_status">Verification Status *</label>
                                    <select class="form-control-custom @error('verification_status') is-invalid @enderror"
                                        id="verification_status" name="verification_status" required>
                                        <option value="pending" {{ old('verification_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('verification_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ old('verification_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    @error('verification_status')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group-custom">
                                    <label class="form-label-custom" for="status">Status *</label>
                                    <select class="form-control-custom @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>
                                            Suspended</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">Options</label>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_featured"
                                                id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                <i class="bi bi-star-fill text-warning"></i> Featured Astrologer
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_online" id="is_online"
                                                value="1" {{ old('is_online') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_online">
                                                <i class="bi bi-circle-fill text-success"></i> Currently Online
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn-custom btn-primary-custom">
                            <i class="bi bi-check-lg me-2"></i>Create Astrologer Profile
                        </button>
                        <a href="{{ route('admin.astrologer-profiles.index') }}"
                            class="btn-custom btn-outline-secondary-custom">
                            <i class="bi bi-arrow-left me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Image preview function
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