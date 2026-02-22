@extends('admin.layouts.app')

@section('title', 'Sliders Management')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-size-20 fw-bold text-primary mb-1">Visual Sliders</h4>
                    <p class="text-muted mb-0">Manage the hero section and promotional banners across your site.</p>
                </div>
                <a href="{{ route('admin.sliders.create') }}"
                    class="btn btn-primary px-4 py-2 bg-gradient shadow-sm border-0 animate-hover">
                    <i class="bi bi-plus-lg me-1"></i> Add New Slider
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
                                <th class="ps-4 py-3">Visual Content</th>
                                <th class="py-3">Details</th>
                                <th class="py-3">Placement</th>
                                <th class="py-3">Display Order</th>
                                <th class="py-3">Status</th>
                                <th class="text-end pe-4 py-3">Management</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sliders as $slider)
                                <tr class="transition-all">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="position-relative me-3">
                                                <img src="{{ Storage::url($slider->image) }}" class="slider-thumb"
                                                    alt="Desktop">
                                                @if($slider->mobile_image)
                                                    <img src="{{ Storage::url($slider->mobile_image) }}"
                                                        class="slider-thumb-mobile position-absolute border border-white border-2"
                                                        style="bottom: -10px; right: -10px; z-index: 2;" alt="Mobile">
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6">{{ $slider->title ?? 'Untitled' }}</div>
                                                <div class="text-muted font-size-12">{{ Str::limit($slider->description, 30) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($slider->button_text)
                                            <span class="badge bg-soft-primary text-primary border border-primary-soft">
                                                <i class="bi bi-link-45deg"></i> {{ $slider->button_text }}
                                            </span>
                                        @else
                                            <span class="text-muted small">No CTAs</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-dark fw-normal border uppercase tracking-wider font-size-11">
                                            {{ $slider->group }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-monospace text-muted">{{ sprintf('%02d', $slider->order) }}</div>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.sliders.toggle-status', $slider) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-check form-switch p-0 m-0">
                                                <button type="submit" class="border-0 bg-transparent p-0">
                                                    @if($slider->is_active)
                                                        <div class="d-flex align-items-center text-success fw-semibold">
                                                            <span class="pulse-dot bg-success me-2"></span> Active
                                                        </div>
                                                    @else
                                                        <div class="d-flex align-items-center text-danger fw-semibold opacity-75">
                                                            <span class="dot bg-danger me-2"></span> Inactive
                                                        </div>
                                                    @endif
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-xs rounded-3 overflow-hidden border">
                                            <a href="{{ route('admin.sliders.edit', $slider) }}"
                                                class="btn btn-sm btn-white px-3 py-2 border-end" title="Edit Design">
                                                <i class="bi bi-pencil-square text-primary"></i>
                                            </a>
                                            <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Remove this slider permanentely?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-white px-3 py-2"
                                                    title="Delete">
                                                    <i class="bi bi-trash-fill text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-5 text-muted">
                                            <i class="bi bi-images display-3 d-block mb-3 opacity-25"></i>
                                            <h5>No Sliders Available</h5>
                                            <p>Start by adding high-quality banners for your users.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $sliders->links() }}
        </div>
    </div>
@endsection
