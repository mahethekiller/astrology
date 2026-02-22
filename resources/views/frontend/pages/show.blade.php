@extends('frontend.layouts.app')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description)

@section('content')
    <!-- Premium Banner Section -->
    <div class="page-banner position-relative overflow-hidden text-white d-flex align-items-center"
        style="min-height: 400px;">
        <div class="banner-overlay position-absolute w-100 h-100"
            style="background: linear-gradient(135deg, rgba(30, 27, 75, 0.9) 0%, rgba(67, 56, 202, 0.7) 100%); z-index: 1;">
        </div>

        @if($page->image)
            <img src="{{ asset('storage/' . $page->image) }}" class="position-absolute w-100 h-100 object-fit-cover"
                alt="{{ $page->title }}" style="top: 0; left: 0; z-index: 0;">
        @else
            <div class="position-absolute w-100 h-100" style="background: #1e1b4b; z-index: 0;"></div>
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
                    <h1 class="display-3 fw-bold mb-0 tracking-tight text-white">{{ $page->title }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-n5 pb-5 position-relative" style="z-index: 3;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-premium border-0 rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5 bg-white">
                        <div class="page-content line-height-lg fs-5 text-secondary">
                            {!! $page->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection