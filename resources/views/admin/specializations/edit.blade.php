@extends('admin.layouts.app')

@section('title', 'Edit Specialization')
@section('page-title', 'Edit Specialization')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="component-card">
            <h4>Edit Specialization</h4>
            
            @if($astrologerCount > 0)
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    This specialization is currently used by <strong>{{ $astrologerCount }}</strong> astrologer(s).
                </div>
            @endif

            <form method="POST" action="{{ route('admin.specializations.update', $specialization) }}">
                @csrf
                @method('PUT')

                <div class="form-group-custom">
                    <label class="form-label-custom" for="name">Name *</label>
                    <input type="text" class="form-control-custom @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $specialization->name) }}" required
                           placeholder="Enter specialization name">
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">Slug (Auto-generated)</label>
                    <input type="text" class="form-control-custom" value="{{ $specialization->slug }}" disabled>
                    <small class="text-muted">This is automatically generated from the name</small>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom" for="description">Description</label>
                    <textarea class="form-control-custom @error('description') is-invalid @enderror"
                              id="description" name="description" rows="4"
                              placeholder="Enter description (optional)">{{ old('description', $specialization->description) }}</textarea>
                    @error('description')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-custom">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" 
                               id="is_active" value="1" {{ old('is_active', $specialization->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <i class="bi bi-check-circle text-success"></i> Active
                        </label>
                        <small class="text-muted d-block">Active specializations will be available for selection</small>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-custom btn-primary-custom">
                        <i class="bi bi-check-lg me-2"></i>Update Specialization
                    </button>
                    <a href="{{ route('admin.specializations.index') }}" class="btn-custom btn-outline-secondary-custom">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
