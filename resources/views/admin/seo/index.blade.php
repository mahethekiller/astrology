@extends('admin.layouts.app')

@section('title', 'Global SEO Management')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-size-18 fw-bold text-primary">Global SEO Hub</h4>
                    <p class="text-muted">Manage Meta Tags and Open Graph settings for non-dynamic routes (Home, Blog, etc.)
                    </p>
                </div>
                <a href="{{ route('admin.seo.create') }}" class="btn btn-primary bg-gradient shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Add New SEO Path
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted uppercase font-size-11 tracking-wider">
                            <tr>
                                <th class="ps-4">Target URL/Path</th>
                                <th>Meta Title</th>
                                <th>OG Image</th>
                                <th>Last Updated</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($seoMetas as $meta)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 text-info p-2 rounded me-3">
                                                <i class="bi bi-globe2 fs-5"></i>
                                            </div>
                                            <div>
                                                <code class="fw-bold text-primary">{{ $meta->url_path }}</code>
                                                <div class="small text-muted">Global Route</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark small">{{ Str::limit($meta->title, 40) }}</span>
                                    </td>
                                    <td>
                                        @if($meta->og_image)
                                            <img src="{{ asset('storage/' . $meta->og_image) }}" class="rounded shadow-xs"
                                                style="height: 30px; width: 50px; object-fit: cover;">
                                        @else
                                            <span class="text-muted small">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $meta->updated_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.seo.edit', $meta->id) }}"
                                                class="btn btn-primary btn-sm px-3 shadow-none">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.seo.destroy', $meta->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Delete this SEO record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-3 shadow-none">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-search fs-1 mb-3 d-block"></i>
                                            <p>No custom SEO paths defined yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .uppercase {
            text-transform: uppercase;
        }

        .tracking-wider {
            letter-spacing: 0.05em;
        }

        .shadow-xs {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .bg-gradient {
            background: linear-gradient(45deg, #0d6efd 0%, #0db9fd 100%) !important;
        }
    </style>
@endsection