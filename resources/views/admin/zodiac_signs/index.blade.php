@extends('admin.layouts.app')

@section('title', 'Zodiac Signs Management')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-size-20 fw-bold text-primary mb-1">Zodiac Signs</h4>
                    <p class="text-muted mb-0">Manage the daily astrology prediction zodiac signs.</p>
                </div>
                <a href="{{ route('admin.zodiac-signs.create') }}"
                    class="btn btn-primary px-4 py-2 bg-gradient shadow-sm border-0 animate-hover">
                    <i class="bi bi-plus-lg me-1"></i> Add New Sign
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
                                <th class="ps-4 py-3">Sign Image</th>
                                <th class="py-3">Name</th>
                                <th class="py-3">Slug</th>
                                <th class="py-3">Order</th>
                                <th class="py-3">Status</th>
                                <th class="text-end pe-4 py-3">Management</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($zodiacSigns as $sign)
                                <tr class="transition-all">
                                    <td class="ps-4 py-3">
                                        @if($sign->icon)
                                            <img src="{{ str_contains($sign->icon, 'frontend/') ? asset($sign->icon) : asset($sign->icon) }}" style="width: 40px; height: 40px; object-fit: contain;">
                                        @else
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><div class="fw-bold text-dark fs-6">{{ $sign->name }}</div></td>
                                    <td><span class="badge bg-light text-dark border">{{ $sign->slug }}</span></td>
                                    <td><div class="font-monospace text-muted">{{ $sign->sort_order }}</div></td>
                                    <td>
                                        @if($sign->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-xs rounded-3 overflow-hidden border">
                                            <a href="{{ route('admin.zodiac-signs.edit', $sign) }}"
                                                class="btn btn-sm btn-white px-3 py-2 border-end" title="Edit Design">
                                                <i class="bi bi-pencil-square text-primary"></i>
                                            </a>
                                            <form action="{{ route('admin.zodiac-signs.destroy', $sign) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Remove this sign permanentely?')">
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
