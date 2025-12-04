@extends('frontend.layouts.app')

@section('title', $blog->meta_title ?? $blog->title)
@section('meta_description', $blog->meta_description ?? Str::limit(strip_tags($blog->content), 160))

@section('content')
    <div class="section1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="title1 text-center">{{ $blog->title }}</h1>
                    <div class="headingDeign text-center"><img src="{{ asset('frontend/images/headingDesign.png') }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section7">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="blog-details">
                        <div class="blog-image mb-4">
                            @if($blog->image)
                                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}"
                                    class="img-fluid w-100" style="border-radius: 20px;">
                            @else
                                <img src="{{ asset('frontend/images/blog1.jpg') }}" alt="" class="img-fluid w-100"
                                    style="border-radius: 20px;">
                            @endif
                        </div>
                        <div class="blog-meta d-flex justify-content-between mb-4">
                            <div class="date"><img src="{{ asset('frontend/images/date.png') }}" />
                                {{ $blog->published_at ? $blog->published_at->format('D, M d, Y') : '' }}</div>
                            <div class="author"><img src="{{ asset('frontend/images/astroicon.png') }}" />
                                {{ $blog->author }}</div>
                        </div>
                        <div class="blog-content">
                            {!! nl2br(e($blog->content)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection