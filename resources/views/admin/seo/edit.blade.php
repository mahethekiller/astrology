@extends('admin.layouts.app')

@section('title', 'Edit SEO Path')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('admin.seo.index') }}" class="btn btn-link link-secondary ps-0 mb-2">
                    <i class="bi bi-arrow-left"></i> Back to SEO Hub
                </a>
                <h4 class="font-size-18 fw-bold">Update SEO Definition</h4>
                <p class="text-muted">Master the search engine appearance for specific URL paths on your website.</p>
            </div>
        </div>

        <form action="{{ route('admin.seo.update', $seo->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 fw-bold text-primary">Target & Core Meta</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">URL Path / Route Identifier</label>
                                <input type="text" name="url_path" class="form-control form-control-lg shadow-sm"
                                    value="{{ $seo->url_path ?? old('url_path') }}" required
                                    placeholder="e.g. /, /blog, /contact-us">
                                <small class="text-muted italic">Use <code>/</code> for the homepage. For pages with
                                    parameters, use the exact route path.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Meta Title</label>
                                <input type="text" name="title" class="form-control shadow-sm"
                                    value="{{ $seo->title ?? old('title') }}" placeholder="Optimized browser title">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Meta Description</label>
                                <textarea name="description" class="form-control shadow-sm" rows="5"
                                    placeholder="Brief summary for search engine results">{{ $seo->description ?? old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 fw-bold text-success"><i class="bi bi-share me-1"></i> Social Media (OG) Tags
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">OG Title</label>
                                    <input type="text" name="og_title" class="form-control shadow-sm"
                                        value="{{ $seo->og_title ?? old('og_title') }}"
                                        placeholder="Custom title for social shares">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">OG Image</label>
                                    @if(isset($seo) && $seo->og_image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $seo->og_image) }}" class="rounded shadow-sm"
                                                style="height: 60px; width: 100px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <input type="file" name="og_image" class="form-control shadow-sm" accept="image/*">
                                </div>
                                <div class="col-12 mb-0">
                                    <label class="form-label fw-semibold">OG Description</label>
                                    <textarea name="og_description" class="form-control shadow-sm" rows="3"
                                        placeholder="Customize how the description looks on social media">{{ $seo->og_description ?? old('og_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 20px;">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 fw-bold">Advanced Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Meta Keywords</label>
                                <textarea name="keywords" class="form-control shadow-sm" rows="3"
                                    placeholder="keyword1, keyword2, keyword3">{{ $seo->keywords ?? old('keywords') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Canonical URL</label>
                                <input type="url" name="canonical_url" class="form-control shadow-sm"
                                    value="{{ $seo->canonical_url ?? old('canonical_url') }}"
                                    placeholder="https://{{ request()->getHost() }}/path">
                                <small class="text-muted font-size-11">Override the default canonical tag if needed.</small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm bg-gradient">
                                <i class="bi bi-cloud-check me-1"></i>
                                Update SEO Definition
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .bg-gradient {
            background: linear-gradient(45deg, #0d6efd 0%, #0db9fd 100%) !important;
        }

        .italic {
            font-style: italic;
        }
    </style>
@endsection