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
                        <h3 class="card-title"><i class="bi bi-tags me-2 text-primary"></i>Blog Categories</h3>
                        <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary btn-add-new">
                            <i class="bi bi-plus-lg me-1"></i> Add New Category
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if (session('success'))
                            <div class="alert alert-success mx-4 mt-3 alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger mx-4 mt-3 alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table admin-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Blogs Count</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                        <tr>
                                            <td class="ps-4 text-muted small">#{{ $category->id }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $category->name }}</div>
                                            </td>
                                            <td>
                                                <code class="small text-muted">{{ $category->slug }}</code>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill bg-light text-dark px-3 py-2">
                                                    <i class="bi bi-journal-text me-1"></i>
                                                    {{ $category->blogs_count ?? $category->blogs()->count() }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-status {{ $category->status ? 'badge-status-active' : 'badge-status-inactive' }}">
                                                    <i
                                                        class="bi {{ $category->status ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a href="{{ route('admin.blog-categories.edit', $category->id) }}"
                                                        class="btn action-btn bg-primary bg-opacity-10 text-primary"
                                                        title="Edit Category">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.blog-categories.destroy', $category->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this category? This will only work if there are no associated blogs.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn action-btn bg-danger bg-opacity-10 text-danger"
                                                            title="Delete Category">
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
                                                    <i class="bi bi-tag fs-1 d-block mb-3"></i>
                                                    <p class="mb-0">No categories found. Organize your blogs by creating
                                                        categories.</p>
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
                        Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of
                        {{ $categories->total() }} categories
                    </div>
                    <div>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection