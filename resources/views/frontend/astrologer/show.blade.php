@extends('frontend.layouts.app')

@section('title', $astrologer->display_name . ' - Astrologer')

@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Profile Image and Actions -->
            <div class="col-md-4 text-center">
                <div class="card shadow border-0 rounded-3">
                    <div class="card-body p-4">
                        <img src="{{ $astrologer->profile_image ? asset('uploads/astrologers/' . $astrologer->profile_image) : asset('images/default-user.png') }}"
                            alt="{{ $astrologer->display_name }}"
                            class="rounded-circle img-fluid border border-3 border-warning mb-3"
                            style="width: 150px; height: 150px; object-fit: cover;">

                        <h3 class="fw-bold text-dark">{{ $astrologer->display_name }}</h3>
                        <p class="text-muted">{{ $astrologer->specializations->pluck('name')->join(', ') }}</p>

                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('call.initiate', $astrologer->id) }}"
                                class="btn btn-primary rounded-pill btn-lg">
                                <i class="fas fa-phone-alt me-2"></i> Call
                                ({{ $astrologer->call_price > 0 ? '₹' . $astrologer->call_price . '/min' : 'Free' }})
                            </a>
                            <a href="{{ route('chat.initiate', $astrologer->id) }}"
                                class="btn btn-outline-success rounded-pill btn-lg">
                                <i class="fas fa-comments me-2"></i> Chat
                                ({{ $astrologer->chat_price > 0 ? '₹' . $astrologer->chat_price . '/min' : 'Free' }})
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="col-md-8">
                <div class="card shadow border-0 rounded-3 h-100">
                    <div class="card-body p-5">
                        <h4 class="mb-4 text-uppercase border-bottom pb-2">About Me</h4>
                        <p class="text-secondary lh-lg mb-5">
                            {{ $astrologer->about ?? 'No description available.' }}
                        </p>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="text-warning fw-bold text-uppercase">Experience</h6>
                                <p class="fs-5">{{ $astrologer->experience_years }} Years</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning fw-bold text-uppercase">Languages</h6>
                                <p class="fs-5">{{ $astrologer->languages->pluck('name')->join(', ') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning fw-bold text-uppercase">Consultations</h6>
                                <p class="fs-5">{{ number_format($astrologer->total_consultations) }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning fw-bold text-uppercase">Rating</h6>
                                <p class="fs-5">
                                    <i class="fas fa-star text-warning"></i>
                                    {{ number_format($astrologer->rating, 1) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection