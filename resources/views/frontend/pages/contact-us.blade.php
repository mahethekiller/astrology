@extends('frontend.layouts.app')

@section('content')
    <!-- Premium Banner Section -->
    <div class="page-banner position-relative overflow-hidden text-white d-flex align-items-center"
        style="min-height: 400px;">
        <div class="banner-overlay position-absolute w-100 h-100"
            style="background: linear-gradient(135deg, rgba(30, 27, 75, 0.9) 0%, rgba(67, 56, 202, 0.7) 100%); z-index: 1;">
        </div>

        @if($page->image)
            <img src="{{ asset('storage/' . $page->image) }}" class="position-absolute w-100 h-100 object-fit-cover shadow-lg"
                alt="{{ $page->title }}" style="top: 0; left: 0; z-index: 0;">
        @else
            <div class="position-absolute w-100 h-100 bg-dark" style="z-index: 0;"></div>
        @endif

        <div class="container position-relative py-5" style="z-index: 2;">
            <div class="row text-center">
                <div class="col-12 animate-fade-in-up">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-4">
                            <li class="breadcrumb-item"><a href="/"
                                    class="text-white opacity-75 text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item active text-white fw-bold" aria-current="page">{{ $page->title }}
                            </li>
                        </ol>
                    </nav>
                    <h1 class="display-3 fw-bold mb-3 tracking-tight">{{ $page->title }}</h1>
                    <p class="lead text-white-50 mx-auto" style="max-width: 600px;">Have questions about your spiritual
                        path? Our team is here to guide you with cosmic clarity and tech-driven care.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Info Cards -->
    <section class="py-5 bg-light mt-n5 position-relative" style="z-index: 3;">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="card border-0 shadow-premium rounded-4 text-center p-4 h-100 animate-hover">
                        <div class="icon-circle bg-primary-soft text-primary mx-auto mb-3">
                            <i class="bi bi-geo-alt fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Our Sanctuary</h5>
                        <p class="text-muted small mb-0">123 Cosmic Way, Star City<br>Zodiac District, 90210</p>
                    </div>
                </div>
                <div class="col-md-4 animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="card border-0 shadow-premium rounded-4 text-center p-4 h-100 animate-hover">
                        <div class="icon-circle bg-success-soft text-success mx-auto mb-3">
                            <i class="bi bi-chat-dots fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Direct Connection</h5>
                        <p class="text-muted small mb-0">support@astrology.com<br>+1 (888) COSMIC-8</p>
                    </div>
                </div>
                <div class="col-md-4 animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="card border-0 shadow-premium rounded-4 text-center p-4 h-100 animate-hover">
                        <div class="icon-circle bg-warning-soft text-warning mx-auto mb-3">
                            <i class="bi bi-clock fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Cosmic Hours</h5>
                        <p class="text-muted small mb-0">Mon - Sun: 24/7<br>Stars Never Sleep</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Form Section -->
    <section class="py-5 bg-white">
        <div class="container py-lg-5">
            <div class="row g-5">
                <div class="col-lg-12">
                    <div class="page-content line-height-xl fs-5 text-secondary">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection