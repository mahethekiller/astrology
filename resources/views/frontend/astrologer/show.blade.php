@extends('frontend.layouts.app')

@section('title', ($astrologer->display_name ?? 'Astrologer Profile'))

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('frontend/css/astrologer-pages.css') }}">
    @endpush

    <div class="profile-hero">
        <div class="container text-center">
            <div class="verified-badge mb-3">
                <i class="fas fa-check-circle"></i> VERIFIED ASTROLOGER
            </div>
            <h1 class="display-4 fw-bold mb-2">{{ $astrologer->display_name }}</h1>
            <p class="lead opacity-75">{{ $astrologer->specializations->pluck('name')->take(3)->join(' • ') }}</p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-4 astrologer-pp-container">
                <div class="sticky-sidebar">
                    <div class="card glass-card text-center mb-4">
                        <div class="card-body p-4">
                            <img src="{{ $astrologer->profile_image ? asset('uploads/astrologers/' . $astrologer->profile_image) : asset('images/default-user.png') }}"
                                alt="{{ $astrologer->display_name }}" class="rounded-circle astrologer-pp mb-4">

                            <div class="d-flex justify-content-center gap-3 mb-4">
                                <div class="text-center">
                                    <span class="d-block fw-bold h4 mb-0">{{ number_format($astrologer->rating, 1) }}</span>
                                    <small class="text-muted">Rating</small>
                                </div>
                                <div class="vr"></div>
                                <div class="text-center">
                                    <span class="d-block fw-bold h4 mb-0">{{ $astrologer->experience_years }}+</span>
                                    <small class="text-muted">Years Exp</small>
                                </div>
                                <div class="vr"></div>
                                <div class="text-center">
                                    <span
                                        class="d-block fw-bold h4 mb-0">{{ number_format($astrologer->total_consultations) }}</span>
                                    <small class="text-muted">Sessions</small>
                                </div>
                            </div>

                            <div class="d-grid gap-3">
                                <div class="text-start mb-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small text-muted"><span
                                                class="status-indicator {{ $astrologer->is_call_online ? 'online' : 'offline' }}"></span>
                                            Voice Call</span>
                                        <span class="fw-bold text-dark">₹{{ $astrologer->call_price }}/min</span>
                                    </div>
                                    <a href="{{ route('call.initiate', $astrologer->id) }}"
                                        class="btn btn-premium-call w-100">
                                        <i class="fas fa-phone-alt me-2"></i> Start Call Now
                                    </a>
                                </div>

                                <div class="text-start">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small text-muted"><span
                                                class="status-indicator {{ $astrologer->is_chat_online ? 'online' : 'offline' }}"></span>
                                            Online Chat</span>
                                        <span class="fw-bold text-dark">₹{{ $astrologer->chat_price }}/min</span>
                                    </div>
                                    <a href="{{ route('chat.initiate', $astrologer->id) }}"
                                        class="btn btn-premium-chat w-100">
                                        <i class="fas fa-comments me-2"></i> Chat with Me
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card glass-card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Languages</h6>
                            <div>
                                @foreach($astrologer->languages as $lang)
                                    <span class="badge bg-light text-dark border p-2 mb-1">{{ $lang->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-8 ps-lg-5 pt-5">
                <ul class="nav nav-pills nav-pills-custom mb-4" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-about-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-about" type="button" role="tab">About Me</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-spec-tab" data-bs-toggle="pill" data-bs-target="#pills-spec"
                            type="button" role="tab">Specializations</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-reviews-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-reviews" type="button" role="tab">Reviews</button>
                    </li>
                </ul>

                <div class="tab-content glass-card p-4 p-md-5" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-about" role="tabpanel">
                        <h3 class="fw-bold mb-4">Welcome to my space</h3>
                        <div class="text-secondary lh-lg fs-5">
                            {!! nl2br(e($astrologer->about ?? 'Highly experienced astrologer ready to guide you through life\'s most complex problems with vedic wisdom and modern insights.')) !!}
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-spec" role="tabpanel">
                        <h3 class="fw-bold mb-4">Core Expertise</h3>
                        <div class="row">
                            @foreach($astrologer->specializations as $spec)
                                <div class="col-md-6 mb-3">
                                    <div class="spec-badge w-100">
                                        <i class="fas fa-star text-warning me-2"></i> {{ $spec->name }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-reviews" role="tabpanel">
                        <div class="mb-4">
                            <h3 class="fw-bold">Client Reviews</h3>
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <h2 class="mb-0 fw-bold">{{ number_format($astrologer->rating, 1) }}</h2>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= round($astrologer->rating) ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                </div>
                                <span class="text-muted">({{ $astrologer->total_reviews }} reviews)</span>
                            </div>
                        </div>

                        <div class="review-list">
                            @forelse($astrologer->ratings()->where('status', 'approved')->with('user')->latest()->get() as $review)
                                <div class="review-item mb-4 pb-4 border-bottom">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random"
                                                class="rounded-circle" width="40" height="40">
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $review->user->name }}</h6>
                                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                        <div class="text-warning small">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-secondary mb-0">{{ $review->comment }}</p>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-star fa-3x text-light mb-3"></i>
                                    <h5 class="text-muted">No reviews yet</h5>
                                    <p class="text-muted">Start a consultation to be the first to leave a review!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection