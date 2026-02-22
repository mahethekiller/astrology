@extends('frontend.layouts.app')

@section('content')
    <!-- Premium Banner Section -->
    <div class="page-banner position-relative overflow-hidden text-white d-flex align-items-center"
        style="min-height: 500px;">
        <div class="banner-overlay position-absolute w-100 h-100"
            style="background: linear-gradient(135deg, rgba(76, 29, 149, 0.9) 0%, rgba(124, 58, 237, 0.7) 100%); z-index: 1;">
        </div>

        @if($page->image)
            <img src="{{ asset('storage/' . $page->image) }}" class="position-absolute w-100 h-100 object-fit-cover shadow-lg"
                alt="{{ $page->title }}" style="top: 0; left: 0; z-index: 0;">
        @else
            <div class="position-absolute w-100 h-100 bg-dark" style="z-index: 0;"></div>
        @endif

        <div class="container position-relative py-5" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-lg-8 animate-fade-in-up">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="/"
                                    class="text-white opacity-75 text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item active text-white fw-bold" aria-current="page">About Our Journey</li>
                        </ol>
                    </nav>
                    <h1 class="display-3 fw-bold mb-3 tracking-tight">{{ $page->title }}</h1>
                    <p class="lead text-white-50 mb-0 max-w-600">Discover the cosmic intersection of ancient wisdom and
                        modern technology. We bring the stars closer to your daily life.</p>
                </div>
            </div>
        </div>

        <!-- Subtle Star Decoration -->
        <div class="position-absolute bottom-0 end-0 p-5 opacity-25 d-none d-lg-block animate-pulse" style="z-index: 2;">
            <i class="bi bi-stars display-1"></i>
        </div>
    </div>

    <!-- Main Content Section -->
    <section class="py-5 bg-white">
        <div class="container py-lg-5">
            <div class="row g-5 align-items-start">
                <div class="col-lg-7">
                    <div class="page-content line-height-xl fs-5 text-secondary pe-lg-5">
                        {!! $page->content !!}
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="sticky-top" style="top: 100px;">
                        <div class="card border-0 shadow-premium rounded-4 overflow-hidden mb-5">
                            <div class="card-header bg-gradient-purple p-4 text-white border-0">
                                <h4 class="mb-0 fw-bold">Our Cosmic Promise</h4>
                            </div>
                            <div class="card-body p-4 bg-light">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex mb-4">
                                        <div
                                            class="icon-box bg-white shadow-sm rounded-circle me-3 flex-shrink-0 animate-hover">
                                            <i class="bi bi-patch-check text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Authentic Insights</h6>
                                            <p class="small text-muted mb-0">Verified ancient practices meets modern
                                                accuracy.</p>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4">
                                        <div
                                            class="icon-box bg-white shadow-sm rounded-circle me-3 flex-shrink-0 animate-hover">
                                            <i class="bi bi-shield-lock text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Privacy Guaranteed</h6>
                                            <p class="small text-muted mb-0">Your spiritual journey is entirely
                                                confidential.</p>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-0">
                                        <div
                                            class="icon-box bg-white shadow-sm rounded-circle me-3 flex-shrink-0 animate-hover">
                                            <i class="bi bi-lightning text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Instant Access</h6>
                                            <p class="small text-muted mb-0">Connect with top astrologers anytime, anywhere.
                                            </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div
                            class="cta-card bg-dark rounded-4 p-5 text-white position-relative overflow-hidden shadow-lg animate-fade-in">
                            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                                <i class="bi bi-moon-stars fs-1"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Ready to explore your future?</h3>
                            <p class="text-white-50 mb-4">Join thousands of seekers finding clarity through the stars today.
                            </p>
                            <a href="/register"
                                class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm bg-gradient-premium">Start Your
                                Journey</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection