@extends('admin.layouts.app')

@section('title', 'Edit Language')
@section('page-title', 'Edit Language')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="component-card">
            <h4>Edit Language</h4>
            
            @if($astrologerCount > 0)
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    This language is currently used by <strong>{{ $astrologerCount }}</strong> astrologer(s).
                </div>
            @endif

            <form method="POST" action="{{ route('admin.languages.update', $language) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group-custom">
                            <label class="form-label-custom" for="name">Language Name *</label>
                            <input type="text" class="form-control-custom @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $language->name) }}" required
                                   placeholder="Enter language name">
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group-custom">
                            <label class="form-label-custom" for="code">Language Code *</label>
                            <input type="text" class="form-control-custom @error('code') is-invalid @enderror"
                                   id="code" name="code" value="{{ old('code', $language->code) }}" required maxlength="10"
                                   placeholder="e.g., hi, en, es">
                            <small class="text-muted">ISO 639-1 code (2 letters)</small>
                            @error('code')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group-custom">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" 
                               id="is_active" value="1" {{ old('is_active', $language->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <i class="bi bi-check-circle text-success"></i> Active
                        </label>
                        <small class="text-muted d-block">Active languages will be available for selection</small>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-custom btn-primary-custom">
                        <i class="bi bi-check-lg me-2"></i>Update Language
                    </button>
                    <a href="{{ route('admin.languages.index') }}" class="btn-custom btn-outline-secondary-custom">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
