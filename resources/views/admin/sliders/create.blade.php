@extends('admin.layouts.app')

@section('title', 'Design New Slider')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-link link-secondary ps-0 mb-2">
                    <i class="bi bi-arrow-left"></i> Back to Sliders
                </a>
                <h4 class="font-size-20 fw-bold text-primary">Capture New Experience</h4>
                <p class="text-muted">Create a high-impact banner or hero section for your website.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                <div class="d-flex align-items-center">
                    <div class="bg-danger text-white rounded-circle p-1 me-3 d-flex shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-danger">Please fix the following issues:</div>
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-premium border-0 mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 fw-bold text-primary">Content & Assets</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Banner Heading</label>
                                    <input type="text" name="title" class="form-control shadow-sm"
                                        value="{{ old('title') }}" placeholder="e.g. Discover Your Destiny">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Display Group</label>
                                    <select name="group" class="form-select shadow-sm" required>
                                        <option value="">Select Target Placement</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group }}" {{ old('group') == $group ? 'selected' : '' }}>
                                                {{ ucfirst($group) }} Section</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Sub-heading / Description</label>
                                <textarea name="description" class="form-control shadow-sm" rows="3"
                                    placeholder="Brief text to engage your visitors...">{{ old('description') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="preview-container h-100">
                                        <label class="form-label fw-bold text-primary mb-1">
                                            <i class="bi bi-laptop me-1"></i> Desktop Visual
                                        </label>
                                        <input type="file" name="image" class="form-control shadow-sm mb-2" required
                                            accept="image/*">
                                        <div class="font-size-11 text-muted">
                                            <i class="bi bi-info-circle"></i> Recommended: <strong>1920x600 px</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="preview-container h-100">
                                        <label class="form-label fw-bold text-info mb-1">
                                            <i class="bi bi-phone me-1"></i> Mobile Visual (Optional)
                                        </label>
                                        <input type="file" name="mobile_image" class="form-control shadow-sm mb-2"
                                            accept="image/*">
                                        <div class="font-size-11 text-muted">
                                            <i class="bi bi-info-circle"></i> Recommended: <strong>600x800 px</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-premium border-0 mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 fw-bold text-primary">Interaction (Call to Action)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Button Label</label>
                                    <input type="text" name="button_text" class="form-control shadow-sm"
                                        value="{{ old('button_text') }}" placeholder="e.g. Consult Now">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Direct Link / URL</label>
                                    <input type="text" name="button_link" class="form-control shadow-sm"
                                        value="{{ old('button_link') }}" placeholder="e.g. /astrologers">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-premium border-0 sticky-top" style="top: 20px;">
                        <div class="card-header bg-white py-3 border-bottom text-center">
                            <h5 class="mb-0 fw-bold">Publishing</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Sequence Weight (Order)</label>
                                <input type="number" name="order" class="form-control shadow-sm"
                                    value="{{ old('order', 0) }}">
                                <small class="text-muted">Lowest number appears first.</small>
                            </div>

                            <hr>

                            <button type="submit" class="btn btn-primary bg-gradient w-100 py-3 fw-bold shadow-sm animate-hover">
                                <i class="bi bi-cloud-upload me-2"></i> Launch Slider
                            </button>
                            <a href="{{ route('admin.sliders.index') }}"
                                class="btn btn-light w-100 mt-2 py-2 text-muted">Disgard Changes</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection