@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="component-card">
            <h4>Edit User: {{ $user->name }}</h4>
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="form-group-custom">
                    <label class="form-label-custom" for="name">Name *</label>
                    <input type="text" class="form-control-custom @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $user->name) }}" required
                           placeholder="Enter user's full name">
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom" for="email">Email *</label>
                    <input type="email" class="form-control-custom @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email', $user->email) }}" required
                           placeholder="Enter user's email address">
                    @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom" for="password">Password</label>
                    <input type="password" class="form-control-custom @error('password') is-invalid @enderror"
                           id="password" name="password"
                           placeholder="Leave blank to keep current password">
                    <small class="text-muted">Only fill this if you want to change the password</small>
                    @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom" for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control-custom"
                           id="password_confirmation" name="password_confirmation"
                           placeholder="Re-enter new password if changing">
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom mb-3">Roles</label>
                    <div class="roles-container">
                        @if($roles->count() > 0)
                            <div class="row">
                                @foreach($roles as $role)
                                <div class="col-md-4 mb-3">
                                    <div class="form-check role-checkbox">
                                        <input class="form-check-input" type="checkbox"
                                               name="roles[]" value="{{ $role->id }}"
                                               id="role_{{ $role->id }}"
                                               {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            <div class="fw-medium">
                                                <i class="bi bi-shield-check me-1"></i>{{ ucfirst($role->name) }}
                                            </div>
                                            @if($role->permissions->count() > 0)
                                                <small class="text-muted">{{ $role->permissions->count() }} permissions</small>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                No roles available. Please create roles first.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-custom btn-primary-custom">
                        <i class="bi bi-check-lg me-2"></i>Update User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-custom btn-outline-secondary-custom">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
