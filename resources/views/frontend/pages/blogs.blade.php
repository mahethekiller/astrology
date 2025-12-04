@extends('frontend.layouts.app')

@section('title', 'Astrology Blogs')

@section('content')
    <div class="section1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="title1 text-center">Latest Astrology Blogs</h1>
                    <div class="headingDeign text-center"><img src="{{ asset('frontend/images/headingDesign.png') }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section7">
        <div class="container">
            <div class="row">
                @forelse($blogs as $blog)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="blogBox">
                            <div class="blogImg">
                                <a href="{{ route('blog.show', $blog->slug) }}">
                                    @if($blog->image)
                                        <img src="{{ asset('storage/' . $blog->image) }}"
                                            alt="{{ $blog->image_alt ?? $blog->title }}"
                                            style="width: 100%; height: 250px; object-fit: cover; border-radius: 20px;" />
                                    @else
                                        <img src="{{ asset('frontend/images/blog1.jpg') }}" alt="Blog"
                                            style="width: 100%; height: 250px; object-fit: cover; border-radius: 20px;" />
                                    @endif
                                </a>
                            </div>
                            <div class="dateandAstroaura">
                                <div class="blogDate"><img src="{{ asset('frontend/images/date.png') }}" />
                                    {{ $blog->published_at ? $blog->published_at->format('D, M d, Y') : '' }}</div>
                                <div class="astroaura"><img src="{{ asset('frontend/images/astroicon.png') }}" />
                                    {{ $blog->author }}</div>
                            </div>
                            <div class="blogTitle">
                                <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>
                            </div>
                            <div class="blogreadmorebtn">
                                <a href="{{ route('blog.show', $blog->slug) }}"><img
                                        src="{{ asset('frontend/images/readmorearrow.png') }}" /></a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No blogs found.</p>
                    </div>
                @endforelse
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    {{ $blogs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection