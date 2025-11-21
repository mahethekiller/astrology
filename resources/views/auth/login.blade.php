@extends('layouts.auth')

@section('title', 'Login')

@section('auth-header')
    <div class="auth-logo">
        <i class="bi bi-layout-text-window-reverse"></i>
        <h1>Laravel Admin</h1>
    </div>
    <div>
        <h2 class="auth-title">Welcome Back</h2>
        <p class="auth-subtitle">Please sign in to your account</p>
    </div>
@endsection

@section('auth-content')
    <!-- Display Success Message -->
    @if(session('status'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <!-- Display Error Message -->
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-group">
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Enter your email"
                       required
                       autofocus>
                <div class="input-icon">
                    <i class="bi bi-envelope"></i>
                </div>
            </div>
            @error('email')
                <div class="text-danger mt-1" style="font-size: 12px;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-group">
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       placeholder="Enter your password"
                       required>
                <div class="input-icon">
                    <i class="bi bi-lock"></i>
                </div>
            </div>
            @error('password')
                <div class="text-danger mt-1" style="font-size: 12px;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label" for="remember">
                Remember me
            </label>
        </div>

        <button type="submit" class="auth-btn">
            <i class="bi bi-box-arrow-in-right me-2"></i>
            Sign In
        </button>
    </form>

    <div class="auth-divider">
        <span>Or continue with</span>
    </div>

    <div class="social-auth">
        <button type="button" class="social-btn google">
            <i class="bi bi-google"></i>
            Google
        </button>
        <button type="button" class="social-btn github">
            <i class="bi bi-github"></i>
            GitHub
        </button>
    </div>
@endsection

@section('auth-footer')
    <div class="auth-links">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="auth-link">
                <i class="bi bi-key me-1"></i>
                Forgot password?
            </a>
        @endif

        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="auth-link">
                <i class="bi bi-person-plus me-1"></i>
                Create account
            </a>
        @endif
    </div>

    <div style="text-align: center;">
        <small class="text-muted">
            &copy; {{ date('Y') }} Laravel Admin. All rights reserved.
        </small>
    </div>
@endsection
