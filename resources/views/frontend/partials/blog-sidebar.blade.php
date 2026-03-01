<div class="blog-sidebar">
    <!-- Categories Widget -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap text-primary me-2"></i>Categories</h5>
        </div>
        <div class="card-body">
            <ul class="list-unstyled mb-0">
                @foreach($categories as $category)
                    <li class="mb-2">
                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                            class="text-decoration-none d-flex justify-content-between align-items-center p-2 rounded hover-bg-light {{ request()->category == $category->slug ? 'bg-light text-primary fw-bold' : 'text-dark' }}">
                            <span>{{ $category->name }}</span>
                            <span class="badge bg-light text-muted rounded-pill">{{ $category->blogs_count }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Recent Posts Widget -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history text-primary me-2"></i>Recent Posts</h5>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($recentPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}"
                        class="list-group-item list-group-item-action border-0 py-3">
                        <div class="d-flex align-items-center">
                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="rounded me-3"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1 text-truncate-2" style="font-size: 0.9rem; line-height: 1.4;">
                                    {{ $post->title }}
                                </h6>
                                <small
                                    class="text-muted">{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}</small>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('frontend/css/blog.css') }}">