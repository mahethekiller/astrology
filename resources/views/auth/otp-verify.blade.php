@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verify OTP') }}</div>

                    <div class="card-body">
                        <p class="mb-4">Please enter the 6-digit code sent to your email to complete registration.</p>

                        <form method="POST" action="{{ route('register.verify-otp') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="otp" class="col-md-4 col-form-label text-md-end">{{ __('OTP Code') }}</label>

                                <div class="col-md-6">
                                    <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror"
                                        name="otp" required autofocus maxlength="6" pattern="\d{6}">

                                    @error('otp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Verify & Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-4 text-center">
                            <p class="mb-2 text-muted small">OTP is valid for 10 minutes.</p>
                            <p class="mb-0">Didn't receive the code?
                                <button type="button" id="resend-otp-btn" class="btn btn-link p-0 align-baseline"
                                    onclick="resendOtp()">Resend OTP</button>
                                <span id="resend-timer" class="d-none text-muted">Resend in <span
                                        id="timer-seconds">60</span>s</span>
                            </p>
                            <div id="resend-message" class="mt-2 small d-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let timerOn = false;
            function startTimer(remaining) {
                timerOn = true;
                const btn = document.getElementById('resend-otp-btn');
                const timerSpan = document.getElementById('resend-timer');
                const secondsSpan = document.getElementById('timer-seconds');

                btn.classList.add('d-none');
                timerSpan.classList.remove('d-none');

                secondsSpan.innerHTML = remaining;

                let interval = setInterval(function () {
                    remaining -= 1;
                    secondsSpan.innerHTML = remaining;

                    if (remaining <= 0) {
                        clearInterval(interval);
                        btn.classList.remove('d-none');
                        timerSpan.classList.add('d-none');
                        timerOn = false;
                    }
                }, 1000);
            }

            function resendOtp() {
                if (timerOn) return;

                const btn = document.getElementById('resend-otp-btn');
                const messageDiv = document.getElementById('resend-message');

                btn.disabled = true;
                btn.innerHTML = 'Sending...';
                messageDiv.classList.add('d-none');

                fetch('{{ route("register.resend-otp") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            messageDiv.textContent = data.message;
                            messageDiv.className = 'mt-2 small text-success';
                            messageDiv.classList.remove('d-none');
                            startTimer(60);
                        } else {
                            messageDiv.textContent = data.message || 'Failed to resend OTP.';
                            messageDiv.className = 'mt-2 small text-danger';
                            messageDiv.classList.remove('d-none');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        messageDiv.textContent = 'An error occurred. Please try again later.';
                        messageDiv.className = 'mt-2 small text-danger';
                        messageDiv.classList.remove('d-none');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = 'Resend OTP';
                    });
            }
        </script>
    @endpush
@endsection