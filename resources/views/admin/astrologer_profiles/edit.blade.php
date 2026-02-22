@extends('admin.layouts.app')

@section('title', 'Edit Astrologer Profile')
@section('page-title', 'Edit Astrologer Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-astrologer-profiles.css') }}">
    <style>
        .current-image-preview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 1rem;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .current-cover-preview {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 1rem;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .image-status-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            background: #f1f5f9;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: inline-block;
        }
    </style>
@endpush

@section('content')
    <div class="redesign-container">
        <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
            <div>
                <h2 class="fw-bold text-dark mb-1">Edit Astrologer</h2>
                <p class="text-muted small mb-0">Updating profile for
                    <strong>{{ $astrologerProfile->display_name }}</strong></p>
            </div>
            <a href="{{ route('admin.astrologer-profiles.index') }}"
                class="btn btn-light rounded-pill px-4 shadow-sm border-0">
                <i class="bi bi-arrow-left me-2"></i> Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 p-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                    <h5 class="mb-0 fw-bold">Please correct the following errors:</h5>
                </div>
                <ul class="mb-0 ms-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.astrologer-profiles.update', $astrologerProfile) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Section 1: User Account -->
            <div class="form-section-card animate__animated animate__fadeInUp">
                <div class="section-header">
                    <div class="section-icon"><i class="bi bi-person-badge"></i></div>
                    <h5 class="section-title">Account Information</h5>
                </div>
                <div class="section-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label-premium">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-premium w-100"
                                value="{{ old('name', $astrologerProfile->user->name) }}" required
                                placeholder="e.g. Rahul Sharma">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-premium w-100"
                                value="{{ old('email', $astrologerProfile->user->email) }}" required
                                placeholder="rahul@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Password <small
                                    class="text-muted text-lowercase fw-normal">(Leave blank to keep
                                    current)</small></label>
                            <input type="password" name="password" class="form-control form-control-premium w-100"
                                placeholder="Min. 8 characters">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="form-control form-control-premium w-100" placeholder="Repeat password">
                        </div>
                        <div class="col-12">
                            <label class="form-label-premium">Phone Number</label>
                            <input type="text" name="phone" class="form-control form-control-premium w-100"
                                value="{{ old('phone', $astrologerProfile->user->phone_number) }}"
                                placeholder="+91 98765 43210">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Professional Profile -->
            <div class="form-section-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <div class="section-header">
                    <div class="section-icon bg-info"><i class="bi bi-briefcase"></i></div>
                    <h5 class="section-title">Professional Profile</h5>
                </div>
                <div class="section-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label-premium">Display Name <span class="text-danger">*</span></label>
                            <input type="text" name="display_name" class="form-control form-control-premium w-100"
                                value="{{ old('display_name', $astrologerProfile->display_name) }}" required
                                placeholder="e.g. Acharya Rahul">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Gender <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3 pt-2">
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender', $astrologerProfile->gender) === 'male' ? 'checked' : '' }} required>
                                    <label class="form-check-label fw-semibold" for="male">Male</label>
                                </div>
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender', $astrologerProfile->gender) === 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="female">Female</label>
                                </div>
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="gender" id="other" value="other" {{ old('gender', $astrologerProfile->gender) === 'other' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="other">Other</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" class="form-control form-control-premium w-100"
                                value="{{ old('date_of_birth', $astrologerProfile->date_of_birth->format('Y-m-d')) }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Experience (Years) <span class="text-danger">*</span></label>
                            <input type="number" name="experience_years" class="form-control form-control-premium w-100"
                                value="{{ old('experience_years', $astrologerProfile->experience_years) }}" min="0" max="70"
                                required placeholder="e.g. 10">
                        </div>
                        <div class="col-12">
                            <label class="form-label-premium">About Bio <span class="text-danger">*</span></label>
                            <textarea name="about" class="form-control form-control-premium w-100" rows="5" required
                                placeholder="Describe the astrologer's background and expertise...">{{ old('about', $astrologerProfile->about) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Pricing & Commissions -->
            <div class="form-section-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <div class="section-header">
                    <div class="section-icon bg-success"><i class="bi bi-currency-dollar"></i></div>
                    <h5 class="section-title">Pricing & Commissions</h5>
                </div>
                <div class="section-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label-premium">Chat Price (per min)</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-premium"><i
                                        class="bi bi-chat-dots-fill"></i></span>
                                <input type="number" step="0.01" name="chat_price"
                                    class="form-control form-control-premium input-premium-with-icon"
                                    value="{{ old('chat_price', $astrologerProfile->chat_price) }}" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Call Price (per min)</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-premium"><i
                                        class="bi bi-telephone-fill"></i></span>
                                <input type="number" step="0.01" name="call_price"
                                    class="form-control form-control-premium input-premium-with-icon"
                                    value="{{ old('call_price', $astrologerProfile->call_price) }}" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Chat Commission (%)</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-premium"><i class="bi bi-percent"></i></span>
                                <input type="number" step="0.01" name="chat_commission_percentage"
                                    class="form-control form-control-premium input-premium-with-icon"
                                    value="{{ old('chat_commission_percentage', $astrologerProfile->chat_commission_percentage) }}"
                                    placeholder="Leave for global">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Call Commission (%)</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-premium"><i class="bi bi-percent"></i></span>
                                <input type="number" step="0.01" name="call_commission_percentage"
                                    class="form-control form-control-premium input-premium-with-icon"
                                    value="{{ old('call_commission_percentage', $astrologerProfile->call_commission_percentage) }}"
                                    placeholder="Leave for global">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Media Assets -->
            <div class="form-section-card animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <div class="section-header">
                    <div class="section-icon bg-warning text-dark"><i class="bi bi-images"></i></div>
                    <h5 class="section-title">Media Assets</h5>
                </div>
                <div class="section-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label-premium">Profile Image</label>
                            <div class="text-center mb-3">
                                <div class="image-status-badge">Current Profile</div><br>
                                <img src="{{ $astrologerProfile->profile_image_url }}" class="current-image-preview">
                            </div>
                            <div class="image-upload-wrapper" onclick="document.getElementById('profile_image').click()">
                                <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/*"
                                    onchange="handlePreview(this, 'profile_preview')">
                                <div class="upload-placeholder">
                                    <i class="bi bi-cloud-arrow-up-fill"></i>
                                    <span class="fw-bold">Upload New Profile</span>
                                    <p class="small mb-0">Drag & drop or click to replace</p>
                                </div>
                                <img id="profile_preview" class="preview-img-premium">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Cover Image</label>
                            <div class="text-center mb-3">
                                <div class="image-status-badge">Current Cover</div><br>
                                <img src="{{ $astrologerProfile->cover_image_url }}" class="current-cover-preview">
                            </div>
                            <div class="image-upload-wrapper" onclick="document.getElementById('cover_image').click()">
                                <input type="file" name="cover_image" id="cover_image" class="d-none" accept="image/*"
                                    onchange="handlePreview(this, 'cover_preview')">
                                <div class="upload-placeholder">
                                    <i class="bi bi-image-fill"></i>
                                    <span class="fw-bold">Upload New Cover</span>
                                    <p class="small mb-0">Recommended size: 1200x400px</p>
                                </div>
                                <img id="cover_preview" class="preview-img-premium">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Expertise & Settings -->
            <div class="form-section-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                <div class="section-header">
                    <div class="section-icon bg-dark text-white"><i class="bi bi-gear-fill"></i></div>
                    <h5 class="section-title">Expertise & Settings</h5>
                </div>
                <div class="section-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label-premium">Languages <span class="text-danger">*</span></label>
                            <select name="languages[]" multiple
                                class="form-control form-control-premium multi-select-premium w-100" required>
                                @php $selectedLanguages = old('languages', $astrologerProfile->languages->pluck('id')->toArray()) @endphp
                                @foreach($languages as $language)
                                    <option value="{{ $language->id }}" {{ in_array($language->id, $selectedLanguages) ? 'selected' : '' }}>
                                        {{ $language->name }} ({{ strtoupper($language->code) }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i> Hold Ctrl/Cmd to
                                select multiple</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Specializations <span class="text-danger">*</span></label>
                            <select name="specializations[]" multiple
                                class="form-control form-control-premium multi-select-premium w-100" required>
                                @php $selectedSpecializations = old('specializations', $astrologerProfile->specializations->pluck('id')->toArray()) @endphp
                                @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->id }}" {{ in_array($specialization->id, $selectedSpecializations) ? 'selected' : '' }}>
                                        {{ $specialization->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i> Hold Ctrl/Cmd to
                                select multiple</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium">Verification Status <span class="text-danger">*</span></label>
                            <select name="verification_status" class="form-control form-control-premium w-100" required>
                                <option value="pending" {{ old('verification_status', $astrologerProfile->verification_status) === 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ old('verification_status', $astrologerProfile->verification_status) === 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ old('verification_status', $astrologerProfile->verification_status) === 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium">Profile Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control form-control-premium w-100" required>
                                <option value="active" {{ old('status', $astrologerProfile->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $astrologerProfile->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status', $astrologerProfile->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium">Promotion Options</label>
                            <div class="d-flex flex-column gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="feat" value="1"
                                        {{ old('is_featured', $astrologerProfile->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="feat">
                                        <i class="bi bi-star-fill text-warning me-1"></i> Featured
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_online" id="online" value="1"
                                        {{ old('is_online', $astrologerProfile->is_online) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="online">
                                        <i class="bi bi-circle-fill text-success me-1"></i> Force Online
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 py-5">
                <button type="submit" class="btn-submit-premium">
                    <i class="bi bi-check-all"></i> Update Expert Profile
                </button>
                <a href="{{ route('admin.astrologer-profiles.index') }}" class="btn-cancel-premium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function handlePreview(input, previewId) {
            const preview = document.getElementById(previewId);
            const placeholder = input.parentElement.querySelector('.upload-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'inline-block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush