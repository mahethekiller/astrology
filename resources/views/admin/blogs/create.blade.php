@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-blog.css') }}">
    <style>
        .ck-editor__editable_inline {
            min-height: 400px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card blog-management-card">
                    <div class="card-header bg-white py-3">
                        <h3 class="card-title fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Create New Blog
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-4">
                                        <label for="title" class="form-label admin-form-label">Blog Title</label>
                                        <input type="text"
                                            class="form-control admin-form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}"
                                            placeholder="Enter a compelling title" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="category_id" class="form-label admin-form-label">Category</label>
                                        <select
                                            class="form-select admin-form-select @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="slug" class="form-label admin-form-label">Slug (URL)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i
                                                    class="bi bi-link-45deg"></i></span>
                                            <input type="text"
                                                class="form-control admin-form-control border-start-0 @error('slug') is-invalid @enderror"
                                                id="slug" name="slug" value="{{ old('slug') }}"
                                                placeholder="auto-generated-from-title">
                                        </div>
                                        <small class="text-muted mt-2 d-block">Leave empty to auto-generate based on the
                                            title.</small>
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="content" class="form-label admin-form-label">Content</label>
                                        <textarea
                                            class="form-control admin-form-control @error('content') is-invalid @enderror"
                                            id="content" name="content">{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label for="image" class="form-label admin-form-label">Featured Image</label>
                                        <div class="image-preview-container mb-3" id="imagePreview">
                                            <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                        </div>
                                        <input type="file"
                                            class="form-control admin-form-control @error('image') is-invalid @enderror"
                                            id="image" name="image" onchange="previewImage(this)">
                                        <small class="text-muted mt-2 d-block">Recommended size: 1200x800px.</small>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="image_alt" class="form-label admin-form-label">Image Alt Text
                                            (SEO)</label>
                                        <input type="text"
                                            class="form-control admin-form-control @error('image_alt') is-invalid @enderror"
                                            id="image_alt" name="image_alt" value="{{ old('image_alt') }}"
                                            placeholder="Describe the image">
                                        @error('image_alt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="card bg-light border-0 rounded-4">
                                        <div class="card-body p-4">
                                            <h6 class="fw-bold mb-3"><i class="bi bi-search me-2"></i>SEO Settings</h6>
                                            <div class="mb-3">
                                                <label for="meta_title" class="form-label admin-form-label small">Meta
                                                    Title</label>
                                                <input type="text"
                                                    class="form-control admin-form-control form-control-sm @error('meta_title') is-invalid @enderror"
                                                    id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                                                    placeholder="SEO title">
                                            </div>
                                            <div class="mb-0">
                                                <label for="meta_description" class="form-label admin-form-label small">Meta
                                                    Description</label>
                                                <textarea
                                                    class="form-control admin-form-control form-control-sm @error('meta_description') is-invalid @enderror"
                                                    id="meta_description" name="meta_description" rows="3"
                                                    placeholder="SEO description">{{ old('meta_description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                <a href="{{ route('admin.blogs.index') }}" class="btn btn-light px-4 me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary px-5 btn-add-new">Publish Blog</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => {
                console.error(error);
            });

        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    preview.style.border = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush