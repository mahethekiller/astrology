@extends('layouts.app')

@section('title', 'Create Permission')
@section('page-title', 'Create Permission')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="component-card">
            <h4>Create New Permission</h4>
            <form method="POST" action="{{ route('admin.permissions.store') }}">
                @csrf

                <div class="form-group-custom">
                    <label class="form-label-custom" for="name">Permission Name *</label>
                    <input type="text" class="form-control-custom @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Enter permission name (e.g., create-users, edit-content)">
                    <small class="text-muted">Use lowercase with hyphens (e.g., create-users)</small>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-custom btn-primary-custom">
                        <i class="bi bi-check-lg me-2"></i>Create Permission
                    </button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn-custom btn-outline-secondary-custom">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
