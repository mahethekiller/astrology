@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-blog.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="card blog-management-card">
                    <div class="card-header bg-white py-3">
                        <h3 class="card-title fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Category:
                            {{ $blogCategory->name }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.blog-categories.update', $blogCategory->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label for="name" class="form-label admin-form-label">Category Name</label>
                                <input type="text"
                                    class="form-control admin-form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $blogCategory->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="slug" class="form-label admin-form-label">Slug</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-link-45deg"></i></span>
                                    <input type="text"
                                        class="form-control admin-form-control border-start-0 @error('slug') is-invalid @enderror"
                                        id="slug" name="slug" value="{{ old('slug', $blogCategory->slug) }}">
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label admin-form-label">Status</label>
                                <select class="form-select admin-form-select @error('status') is-invalid @enderror"
                                    id="status" name="status">
                                    <option value="1" {{ old('status', $blogCategory->status) == '1' ? 'selected' : '' }}>
                                        Active - Visible to users</option>
                                    <option value="0" {{ old('status', $blogCategory->status) == '0' ? 'selected' : '' }}>
                                        Inactive - Hidden</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                <a href="{{ route('admin.blog-categories.index') }}"
                                    class="btn btn-light px-4 me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary px-5 btn-add-new">Update Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection