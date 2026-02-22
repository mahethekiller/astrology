@extends('admin.layouts.app')

@section('title', 'Pages Management')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-size-20 fw-bold text-primary mb-1">Custom Dynamic Pages</h4>
                    <p class="text-muted mb-0">Design and deploy unique landing pages and content sections.</p>
                </div>
                <a href="{{ route('admin.pages.create') }}"
                    class="btn btn-primary px-4 py-2 bg-gradient shadow-sm border-0 animate-hover">
                    <i class="bi bi-plus-lg me-1"></i> Create New Page
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle p-1 me-3 d-flex shadow-sm">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="fw-bold text-success">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-premium border-0 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-soft text-muted uppercase font-size-11 tracking-wider">
                            <tr>
                                <th class="ps-4 py-3">Page Details</th>
                                <th class="py-3">Live URL</th>
                                <th class="py-3">Visibility</th>
                                <th class="py-3">Engagement Meta</th>
                                <th class="text-end pe-4 py-3">Management</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $page)
                                <tr class="transition-all">
                                    <td class="ps-4 py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="page-icon bg-primary-soft text-primary me-3">
                                                <i class="bi bi-file-earmark-code fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6">{{ $page->title }}</div>
                                                <div class="text-muted font-size-12 mt-1">
                                                    <span class="me-2"><i class="bi bi-clock me-1"></i>
                                                        {{ $page->updated_at->diffForHumans() }}</span>
                                                    @if($page->meta_title)
                                                        <span class="badge bg-light text-dark fw-normal border">SEO Active</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center mb-1">
                                            <code class="path-badge text-primary">/{{ $page->slug }}</code>
                                            <a href="/{{ $page->slug }}" target="_blank" class="ms-2 text-link animate-hover">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        </div>
                                        <small class="text-muted font-size-11">Public path identifier</small>
                                    </td>
                                    <td>
                                        @if($page->status)
                                            <div class="d-flex align-items-center text-success fw-semibold">
                                                <span class="pulse-dot bg-success me-2"></span> Published
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center text-danger fw-semibold opacity-75">
                                                <span class="dot bg-danger me-2"></span> Draft Mode
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-dark fw-medium font-size-13">
                                            {{ Str::limit($page->meta_title ?? 'Title not defined', 30) }}</div>
                                        <div class="text-muted font-size-11">{{ $page->keywords ?? 'No keywords set' }}</div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-xs rounded-3 overflow-hidden border">
                                            <a href="{{ route('admin.pages.edit', $page->id) }}"
                                                class="btn btn-sm btn-white px-3 py-2 border-end" title="Edit Content">
                                                <i class="bi bi-pencil-square text-primary"></i>
                                            </a>
                                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Permanently remove this custom page?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-white px-3 py-2"
                                                    title="Delete Page">
                                                    <i class="bi bi-trash-fill text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-5">
                                            <div class="bg-light-soft rounded-circle d-inline-flex p-4 mb-3">
                                                <i class="bi bi-journal-plus display-4 text-muted"></i>
                                            </div>
                                            <h5 class="fw-bold text-dark">No custom pages yet</h5>
                                            <p class="text-muted mx-auto" style="max-width: 300px;">Begin by creating your first
                                                dynamic page to host unique content or campaigns.</p>
                                            <a href="{{ route('admin.pages.create') }}"
                                                class="btn btn-primary mt-3 px-4 rounded-pill">
                                                Get Started
                                            </a>
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

@endsection