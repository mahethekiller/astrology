@extends('layouts.app')

@section('title', 'Edit Permission')
@section('page-title', 'Edit Permission')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="component-card">
            <h4>Edit Permission: {{ $permission->name }}</h4>
            <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
                @csrf
                @method('PUT')

                <div class="form-group-custom">
                    <label class="form-label-custom" for="name">Permission Name *</label>
                    <input type="text" class="form-control-custom @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $permission->name) }}" required
                           placeholder="Enter permission name">
                    <small class="text-muted">Use lowercase with hyphens (e.g., create-users)</small>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-custom btn-primary-custom">
                        <i class="bi bi-check-lg me-2"></i>Update Permission
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
