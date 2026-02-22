<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $currentPath = '/' . request()->path();
        if ($currentPath == '//')
            $currentPath = '/';

        // Try to get SEO from global SEO hub first
        $seo = \App\Models\SeoMeta::where('url_path', $currentPath)->first();

        // If not found and we are on a dynamic page, use page's SEO fields
        if (!$seo && isset($page) && $page instanceof \App\Models\Page) {
            $seo = (object) [
                'title' => $page->meta_title ?? $page->title,
                'description' => $page->meta_description,
                'keywords' => $page->keywords,
                'og_title' => $page->og_title ?? $page->title,
                'og_description' => $page->og_description ?? $page->meta_description,
                'og_image' => $page->og_image ?? $page->image,
                'canonical_url' => $page->canonical_url
            ];
        }
    @endphp

    <title>{{ $seo->title ?? ($page->title ?? 'Astrologer') }}</title>
    @if(isset($seo->description))
    <meta name="description" content="{{ $seo->description }}"> @endif
    @if(isset($seo->keywords))
    <meta name="keywords" content="{{ $seo->keywords }}"> @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $seo->canonical_url ?? url()->current() }}">
    <meta property="og:title" content="{{ $seo->og_title ?? ($seo->title ?? ($page->title ?? 'Astrologer')) }}">
    <meta property="og:description" content="{{ $seo->og_description ?? ($seo->description ?? '') }}">
    @if(isset($seo->og_image))
        <meta property="og:image" content="{{ asset('storage/' . $seo->og_image) }}">
    @endif

    @if(isset($seo->canonical_url))
        <link rel="canonical" href="{{ $seo->canonical_url }}">
    @endif

    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('frontend/css/all.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('frontend/css/pages.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --border-color: #e9ecef;
            --text-color: #212529;
            --text-muted: #6c757d;
            --card-bg: #ffffff;
            --success-color: #4cc9a7;
            --danger-color: #f94144;
        }
    </style>
    @stack('styles')
</head>

<body>
    @include('frontend.partials.header')

    <main>
        @yield('content')
    </main>

    @include('frontend.partials.footer')
    @include('frontend.partials.scripts')
</body>

</html>