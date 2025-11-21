@extends('layouts.app')

@section('title', 'Create Role')
@section('page-title', 'Create Role')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="component-card">
            <h4>Create New Role</h4>
            <form method="POST" action="{{ route('admin.roles.store') }}">
                @csrf

                <div class="form-group-custom">
                    <label class="form-label-custom" for="name">Role Name *</label>
                    <input type="text" class="form-control-custom @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Enter role name (e.g., admin, manager)">
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom mb-3">Permissions</label>
                    <div class="permissions-container">
                        @foreach($permissions as $module => $modulePermissions)
                        <div class="permission-module mb-4">
                            <h6 class="text-muted mb-3 border-bottom pb-2">
                                <i class="bi bi-folder me-2"></i>{{ ucfirst($module) }} Permissions
                            </h6>
                            <div class="row">
                                @foreach($modulePermissions as $permission)
                                <div class="col-md-4 mb-3">
                                    <div class="form-check permission-checkbox">
                                        <input class="form-check-input" type="checkbox"
                                               name="permissions[]" value="{{ $permission->id }}"
                                               id="permission_{{ $permission->id }}"
                                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                                            <div class="fw-medium">{{ $permission->name }}</div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-custom btn-primary-custom">
                        <i class="bi bi-check-lg me-2"></i>Create Role
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn-custom btn-outline-secondary-custom">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
