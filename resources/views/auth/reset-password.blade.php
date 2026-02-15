@extends('layouts.auth')

@section('title', 'Reset Password')

@section('auth-header')
    <div class="auth-logo">
        <img src="{{ asset('images/loginLogo.png') }}" alt="Logo">
    </div>
    <div class="mt-3">
        <h4 class="text-center">{{ __('Reset Password') }}</h4>
        <p class="text-muted text-center" style="font-size: 14px;">
            {{ __('Please enter your new password below.') }}
        </p>
    </div>
@endsection

@section('auth-content')
    <form class="auth-form" method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-group">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ old('email', $request->email) }}" placeholder="Enter your email" required autofocus
                    autocomplete="username">
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

        <!-- Password -->
        <div class="form-group">
            <label class="form-label" for="password">New Password</label>
            <div class="input-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Enter new password" required autocomplete="new-password">
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

        <!-- Confirm Password -->
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm new password" required autocomplete="new-password">
                <div class="input-icon">
                    <i class="bi bi-lock-fill"></i>
                </div>
            </div>
            @error('password_confirmation')
                <div class="text-danger mt-1" style="font-size: 12px;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="auth-btn mt-3">
            <i class="bi bi-shield-lock me-2"></i>
            {{ __('RESET PASSWORD') }}
        </button>
    </form>
@endsection

@section('auth-footer')
    <div class="mt-4" style="text-align: center;">
        <small class="text-muted">
            &copy; {{ date('Y') }} Astroauraa All rights reserved.
        </small>
    </div>
@endsection