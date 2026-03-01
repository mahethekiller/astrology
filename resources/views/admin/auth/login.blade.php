@extends('layouts.auth')

@section('title', 'Admin Login')

@section('auth-header')
    <div class="auth-logo">
        <img src="{{ asset('images/loginLogo.png') }}" alt="Logo">
    </div>
    <div class="text-center mt-3">
        <h4 class="fw-bold" style="color: rgba(253, 219, 24, 1) !important;">Admin Portal</h4>
        <p class="text-muted small">Secure Access for Administrators</p>
    </div>
@endsection

@section('auth-content')
    @if(session('status'))
        <div class="alert alert-success small">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger small">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group mb-3">
            <label class="form-label small fw-bold" for="email">Admin Email</label>
            <div class="input-group">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ old('email') }}" placeholder="admin@astroaura.com" required autofocus>
                <div class="input-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
            @error('email')
                <div class="text-danger mt-1 small">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label class="form-label small fw-bold" for="password">Password</label>
            <div class="input-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="••••••••" required>
                <div class="input-icon">
                    <i class="bi bi-key"></i>
                </div>
            </div>
            @error('password')
                <div class="text-danger mt-1 small">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label small" for="remember">
                Keep me logged in
            </label>
        </div>

        <button type="submit" class="auth-btn btn-warning w-100 py-2 fw-bold"
            style="background-color: rgba(253, 219, 24, 1); border: none; color: #111827;">
            <i class="bi bi-box-arrow-in-right me-2"></i>
            ADMIN ACCESS
        </button>
    </form>
@endsection

@section('auth-footer')
    <div class="auth-links text-center mt-3">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="auth-link small text-decoration-none">
                <i class="bi bi-question-circle me-1"></i>
                Recovery Access
            </a>
        @endif
    </div>

    <div class="text-center mt-4">
        <small class="text-muted">
            &copy; {{ date('Y') }} Astroaura Admin Control. All rights reserved.
        </small>
    </div>
@endsection