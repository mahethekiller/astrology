@extends('admin.layouts.app')

@section('title', 'Edit Zodiac Sign')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-size-20 fw-bold text-primary mb-1">Edit Zodiac Sign</h4>
                </div>
                <a href="{{ route('admin.zodiac-signs.index') }}"
                    class="btn btn-secondary px-4 py-2 bg-gradient shadow-sm border-0 animate-hover">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card shadow-premium border-0 rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <form action="{{ route('admin.zodiac-signs.update', $zodiacSign->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $zodiacSign->name) }}"
                                required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug', $zodiacSign->slug) }}"
                                required readonly>
                            <small class="text-muted">Slug is read-only because modifying it breaks API queries.</small>
                            @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold">Icon Image</label>
                            @if($zodiacSign->icon)
                                <div class="mb-2">
                                    <img src="{{ str_contains($zodiacSign->icon, 'frontend/') ? asset($zodiacSign->icon) : asset($zodiacSign->icon) }}"
                                        style="width: 50px; height: 50px; object-fit: contain;">
                                </div>
                            @endif
                            <input type="file" name="icon" class="form-control" accept="image/*">
                            @error('icon') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="{{ old('sort_order', $zodiacSign->sort_order) }}">
                            @error('sort_order') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch mt-3 d-flex align-items-center h-100">
                                <input class="form-check-input mt-0 me-2" type="checkbox" name="is_active" id="is_active"
                                    value="1" {{ $zodiacSign->is_active ? 'checked' : '' }}
                                    style="width: 40px; height: 20px;">
                                <label class="form-check-label fw-bold ms-2" for="is_active">Active Status</label>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold">Update Sign</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection