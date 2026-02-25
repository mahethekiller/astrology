@extends('frontend.layouts.app')

@section('title', ($astrologer->display_name ?? 'Astrologer Profile'))

@section('content')
    <style>
        :root {
            --primary-gold: #c5a059;
            --dark-bg: #1a1a1a;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --hero-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }

        .profile-hero {
            background: var(--hero-gradient);
            padding: 5rem 0 3rem 0;
            position: relative;
            overflow: hidden;
            color: white;
        }

        .profile-hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, #f8f9fa, transparent);
        }

        .astrologer-pp-container {
            position: relative;
            margin-top: -80px;
            z-index: 10;
        }

        .astrologer-pp {
            width: 180px;
            height: 180px;
            border: 5px solid white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            object-fit: cover;
            background: white;
        }

        .sticky-sidebar {
            position: sticky;
            top: 20px;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        }

        .verified-badge {
            background: #00d2ff;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .nav-pills-custom .nav-link {
            color: #4a5568;
            font-weight: 600;
            border-radius: 1rem;
            padding: 12px 24px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .nav-pills-custom .nav-link.active {
            background: var(--hero-gradient);
            color: white;
            box-shadow: 0 8px 15px rgba(30, 60, 114, 0.2);
        }

        .btn-premium-call {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 15px;
            border-radius: 1rem;
            font-weight: 700;
            transition: transform 0.2s;
        }

        .btn-premium-chat {
            background: linear-gradient(135deg, #2af598 0%, #009efd 100%);
            border: none;
            color: white;
            padding: 15px;
            border-radius: 1rem;
            font-weight: 700;
            transition: transform 0.2s;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            color: white;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .online {
            background: #2af598;
            box-shadow: 0 0 10px #2af598;
        }

        .offline {
            background: #cbd5e0;
        }

        .spec-badge {
            background: rgba(235, 244, 255, 1);
            color: #2b6cb0;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 500;
            margin-right: 8px;
            margin-bottom: 8px;
            display: inline-block;
        }
    </style>

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