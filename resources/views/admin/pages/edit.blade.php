@extends('admin.layouts.app')

@section('title', 'Refine Page Design')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ $isStandard ? route('admin.pages.standard') : route('admin.pages.index') }}"
                    class="btn btn-link link-secondary ps-0 mb-2">
                    <i class="bi bi-arrow-left"></i> Back to {{ $isStandard ? 'Standard' : 'Custom' }} Pages
                </a>
                <h4 class="font-size-20 fw-bold text-primary">Evolution of Content</h4>
                <p class="text-muted">
                    @if($isStandard)
                        Refining the narrative and visuals for the system's core informational hub:
                        <strong>{{ $page->title }}</strong>.
                    @else
                        Enhancing the experience for your custom landing page: <strong>{{ $page->title }}</strong>.
                    @endif
                </p>
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

        <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-premium border-0 mb-4">
                        <div
                            class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Content &
                                Narrative</h5>
                            <span class="badge bg-light text-dark font-monospace shadow-none border py-2">Slug:
                                /{{ $page->slug }}</span>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Page Heading</label>
                                <input type="text" name="title"
                                    class="form-control form-control-lg shadow-sm @error('title') is-invalid @enderror"
                                    value="{{ old('title', $page->title) }}" required {{ $isStandard ? 'readonly' : '' }}>
                                @if(!$isStandard)
                                    <small class="text-muted">Descriptive title for internal and external navigation.</small>
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @else
                                    <small class="text-info"><i class="bi bi-info-circle me-1"></i> System titles are preserved
                                        for stability.</small>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-primary">URL Access Path</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-light border-end-0">/</span>
                                    <input type="text" name="slug"
                                        class="form-control border-start-0 @error('slug') is-invalid @enderror"
                                        value="{{ old('slug', $page->slug) }}" {{ $isStandard ? 'readonly' : '' }}
                                        placeholder="custom-url-path">
                                    @error('slug') <div class="invalid-feedback ps-2">{{ $message }}</div> @enderror
                                </div>
                                <small class="text-muted">Unique identifier in the web address.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-primary">Hero Banner Asset</label>
                                <div class="preview-container">
                                    <div class="row align-items-center">
                                        @if($page->image)
                                            <div class="col-md-5 mb-3 mb-md-0 text-center">
                                                <img src="{{ asset('storage/' . $page->image) }}" alt="Banner Preview"
                                                    class="img-fluid rounded-3 shadow-sm border"
                                                    style="max-height: 150px; width: 100%; object-fit: cover;">
                                                <div class="small text-muted mt-1 font-monospace">Current Asset</div>
                                            </div>
                                        @endif
                                        <div class="col">
                                            <input type="file" name="image"
                                                class="form-control shadow-sm mb-2 @error('image') is-invalid @enderror"
                                                accept="image/*">
                                            @error('image') <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="font-size-11 text-muted">
                                                <i class="bi bi-info-circle me-1"></i> Recommended: 1920x600px. Max 2MB.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rich Content Editor</label>
                                <textarea name="content" id="editor"
                                    class="form-control shadow-sm @error('content') is-invalid @enderror"
                                    rows="15">{{ old('content', $page->content) }}</textarea>
                                @error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-premium border-0 mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-share me-2 text-primary"></i>Social Media Connectivity
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">OG Title</label>
                                    <input type="text" name="og_title" class="form-control shadow-sm"
                                        value="{{ old('og_title', $page->og_title) }}" placeholder="Social Media Title">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Canonical Endpoint</label>
                                    <input type="url" name="canonical_url" class="form-control shadow-sm"
                                        value="{{ old('canonical_url', $page->canonical_url) }}"
                                        placeholder="https://{{ request()->getHost() }}/{{ $page->slug }}">
                                </div>
                                <div class="col-12 mb-4">
                                    <label class="form-label fw-semibold">OG Description</label>
                                    <textarea name="og_description" class="form-control shadow-sm" rows="3"
                                        placeholder="Engagement text for social previews">{{ old('og_description', $page->og_description) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Social Preview Asset (OG Image)</label>
                                    <div class="preview-container">
                                        <div class="row align-items-center">
                                            @if($page->og_image)
                                                <div class="col-md-4 mb-3 mb-md-0 text-center">
                                                    <img src="{{ asset('storage/' . $page->og_image) }}" alt="OG Preview"
                                                        class="img-fluid rounded-3 shadow-sm border" style="max-height: 100px;">
                                                </div>
                                            @endif
                                            <div class="col">
                                                <input type="file" name="og_image"
                                                    class="form-control shadow-sm @error('og_image') is-invalid @enderror"
                                                    accept="image/*">
                                                @error('og_image') <div class="invalid-feedback d-block">{{ $message }}
                                                </div> @enderror
                                                <small class="text-muted d-block mt-1 font-size-11">Recommended:
                                                    1200x630px.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-premium border-0 mb-4 sticky-top" style="top: 20px;">
                        <div class="card-header bg-white py-3 border-bottom text-center">
                            <h5 class="mb-0 fw-bold">Search Optimization</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label
                                    class="form-label fw-semibold text-primary font-size-13 uppercase tracking-wider">Browser
                                    Title</label>
                                <input type="text" name="meta_title" class="form-control shadow-sm"
                                    value="{{ old('meta_title', $page->meta_title) }}" placeholder="Max 60 chars">
                            </div>
                            <div class="mb-4">
                                <label
                                    class="form-label fw-semibold text-primary font-size-13 uppercase tracking-wider">Search
                                    Snippet</label>
                                <textarea name="meta_description" class="form-control shadow-sm" rows="4"
                                    placeholder="Brief summary (Max 160 chars)">{{ old('meta_description', $page->meta_description) }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label
                                    class="form-label fw-semibold text-primary font-size-13 uppercase tracking-wider">Keywords</label>
                                <textarea name="keywords" class="form-control shadow-sm" rows="2"
                                    placeholder="astrology, zodiac, horoscope">{{ old('keywords', $page->keywords) }}</textarea>
                            </div>
                            <hr>
                            <div class="mb-4">
                                <label class="form-label fw-semibold d-block">Public Status</label>
                                <div class="btn-group w-100 shadow-xs" role="group">
                                    <input type="radio" class="btn-check" name="status" id="pub_active" value="1" {{ $page->status ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success py-2 font-size-13 fw-bold" for="pub_active">
                                        <i class="bi bi-journal-check me-1"></i> Published
                                    </label>

                                    <input type="radio" class="btn-check" name="status" id="pub_hidden" value="0" {{ !$page->status ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger py-2 font-size-13 fw-bold" for="pub_hidden">
                                        <i class="bi bi-journal-x me-1"></i> Draft
                                    </label>
                                </div>
                            </div>

                            <div class="alert bg-light-soft border-0 shadow-none font-size-11 mb-4">
                                <div class="mb-1"><i class="bi bi-calendar-event me-2"></i><strong>Born:</strong>
                                    {{ $page->created_at->format('M d, Y') }}</div>
                                <div><i class="bi bi-arrow-repeat me-2"></i><strong>Last Refined:</strong>
                                    {{ $page->updated_at->diffForHumans() }}</div>
                            </div>

                            <button type="submit"
                                class="btn btn-primary bg-gradient w-100 py-3 fw-bold shadow-sm animate-hover">
                                <i class="bi bi-cloud-arrow-up me-2"></i> Update Page & SEO
                            </button>
                            <a href="{{ $isStandard ? route('admin.pages.standard') : route('admin.pages.index') }}"
                                class="btn btn-light w-100 mt-2 py-2 text-muted small">Revert Changes</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error(error);
                });
        </script>
    @endpush
@endsection