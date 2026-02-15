@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('auth-header')
    <div class="auth-logo">
        <img src="{{ asset('images/loginLogo.png') }}" alt="Logo">
    </div>
    <div class="mt-3">
        <h4 class="text-center">{{ __('Forgot Password?') }}</h4>
        <p class="text-muted text-center" style="font-size: 14px;">
            {{ __('Enter your email address and we will send you a password reset link.') }}
        </p>
    </div>
@endsection

@section('auth-content')
    <!-- Session Status -->
    @if(session('status'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
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

        <button type="submit" class="auth-btn mt-3">
            <i class="bi bi-send me-2"></i>
            {{ __('SEND RESET LINK') }}
        </button>
    </form>
@endsection

@section('auth-footer')
    <div class="auth-links">
        <a href="{{ route('login') }}" class="auth-link">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Login
        </a>
    </div>

    <div class="mt-4" style="text-align: center;">
        <small class="text-muted">
            &copy; {{ date('Y') }} Astroauraa All rights reserved.
        </small>
    </div>
@endsection