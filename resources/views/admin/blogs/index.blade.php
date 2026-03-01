@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-blog.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card blog-management-card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                        <h3 class="card-title"><i class="bi bi-journal-text me-2 text-primary"></i>Blogs</h3>
                        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-add-new">
                            <i class="bi bi-plus-lg me-1"></i> Add New Blog
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if (session('success'))
                            <div class="alert alert-success mx-4 mt-3 alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table admin-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Published At</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($blogs as $blog)
                                        <tr>
                                            <td class="ps-4 text-muted small">#{{ $blog->id }}</td>
                                            <td>
                                                @if ($blog->image)
                                                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}"
                                                        class="blog-thumb">
                                                @else
                                                    <div class="blog-thumb bg-light d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="blog-title-cell text-truncate-2">{{ $blog->title }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                        {{ strtoupper(substr($blog->author, 0, 1)) }}
                                                    </div>
                                                    <span class="small fw-semibold text-dark">{{ $blog->author }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="small text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $blog->published_at ? $blog->published_at->format('d M, Y') : 'Draft' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a href="{{ route('blog.show', $blog->slug) }}" class="btn action-btn bg-info bg-opacity-10 text-info"
                                                        target="_blank" title="View Blog">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.blogs.edit', $blog->id) }}"
                                                        class="btn action-btn bg-primary bg-opacity-10 text-primary" title="Edit Blog">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST"
                                                        class="d-inline" onsubmit="return confirm('Are you sure you want to delete this blog?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn action-btn bg-danger bg-opacity-10 text-danger" title="Delete Blog">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                    <p class="mb-0">No blogs found. Start by creating your first blog post!</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2 px-2">
                    <div class="text-muted small">
                        Showing {{ $blogs->firstItem() }} to {{ $blogs->lastItem() }} of {{ $blogs->total() }} blogs
                    </div>
                    <div>
                        {{ $blogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
