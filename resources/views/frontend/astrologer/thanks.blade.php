@extends('frontend.layouts.app')

@section('title', 'Thank You - Astrologer Registration')

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/astrologer-pages.css') }}">
@endpush

@section('content')
    <div class="thanks-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="thanks-card">
                        <div class="success-icon">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <h1 class="thanks-title">Application Received!</h1>
                        <p class="thanks-message">
                            Thank you for applying to join our expert panel. We've received your application and our team is
                            already reviewing your profile.
                        </p>

                        <div class="next-steps shadow-sm">
                            <h5><i class="bi bi-info-circle me-2"></i>What's Next?</h5>
                            <div class="step-item">
                                <div class="step-number">1</div>
                                <p class="mb-0 text-muted"><strong>Profile Review:</strong> Our experts will verify your
                                    credentials and experience.</p>
                            </div>
                            <div class="step-item">
                                <div class="step-number">2</div>
                                <p class="mb-0 text-muted"><strong>Email Notification:</strong> You'll receive an email once
                                    your profile is approved (usually within 24-48 hours).</p>
                            </div>
                            <div class="step-item mb-0">
                                <div class="step-number">3</div>
                                <p class="mb-0 text-muted"><strong>Start Consultations:</strong> Once approved, you can set
                                    your pricing and start taking chat and call requests.</p>
                            </div>
                        </div>

                        <a href="{{ route('home') }}" class="btn-home">Return to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection