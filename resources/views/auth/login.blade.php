@extends('layouts.auth')

@section('title', 'Login')

@section('auth-header')
    <div class="auth-logo">
        <img src="{{ asset('images/loginLogo.png') }}" alt="Logo">
    </div>
    <div class="text-center mt-3">
        <h4 class="fw-bold text-primary">User Login</h4>
        <p class="text-muted small">Sign in to consult with top astrologers</p>
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
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
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
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Enter your password" required>
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

        <button type="submit" class="auth-btn w-100 py-2 fw-bold"
            style="background-color: rgba(253, 219, 24, 1); border: none; color: #111827;">
            <i class="bi bi-box-arrow-in-right me-2"></i>
            LOGIN
        </button>
    </form>
@endsection

@section('auth-footer')
    <div class="auth-links text-center mt-3">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="auth-link small text-decoration-none">
                <i class="bi bi-key me-1"></i>
                Forgot password?
            </a>
        @endif

        <div class="mt-2">
            <a href="{{ route('register') }}" class="small text-muted text-decoration-none">
                New to Astroaura? <span class="text-primary fw-bold">Sign Up Free</span>
            </a>
        </div>
    </div>

    <div class="text-center mt-4">
        <small class="text-muted">
            &copy; {{ date('Y') }} Astroaura. All rights reserved.
        </small>
    </div>
@endsection