@extends('admin.layouts.app')

@section('title', 'Menu Management')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-size-18">Menu Management</h4>
                    <p class="text-muted">Manage your website's navigation menus.</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMenuModal">
                    <i class="bi bi-plus-lg"></i> Create New Menu
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            @foreach ($menus as $menu)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100 overflow-hidden">
                        <div class="card-header bg-primary bg-gradient py-3 text-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-25 rounded p-2 me-3">
                                        <i class="bi bi-list-nested fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-white font-size-16">{{ $menu->name }}</h5>
                                        <small class="text-white-50">#{{ $menu->slug }}</small>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-white p-0 opacity-75 hover-opacity-100" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                        <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                                                data-bs-target="#editMenuModal{{ $menu->id }}"><i
                                                    class="bi bi-pencil me-2 text-primary"></i> Edit Name</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST"
                                                onsubmit="return confirm('Deleting this menu will delete all its items! Continue?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 text-danger"><i
                                                        class="bi bi-trash me-2"></i> Delete Menu</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div class="row text-center mb-4">
                                <div class="col-6 border-end">
                                    <h3 class="fw-bold mb-0 text-primary">{{ $menu->items_count }}</h3>
                                    <p class="text-muted small mb-0 font-size-12 uppercase tracking-wider">Total Items</p>
                                </div>
                                <div class="col-6">
                                    <h3 class="fw-bold mb-0 text-success">{{ $menu->items->where('status', 1)->count() }}</h3>
                                    <p class="text-muted small mb-0 font-size-12 uppercase tracking-wider">Active Links</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.menus.items.index', $menu->id) }}"
                                class="btn btn-primary bg-gradient w-100 py-2 fw-semibold shadow-sm">
                                <i class="bi bi-gear-fill me-2"></i> Manage Navigation Architecture
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editMenuModal{{ $menu->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content border-0 shadow-lg">
                            <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header bg-primary py-3 text-white border-0">
                                    <h5 class="modal-title fw-bold">Edit Navigation Menu</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Menu Name</label>
                                        <input type="text" name="name" class="form-control shadow-sm" value="{{ $menu->name }}"
                                            required placeholder="e.g. Primary Header">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Identifier (Slug)</label>
                                        <input type="text" name="slug" class="form-control shadow-sm" value="{{ $menu->slug }}"
                                            required placeholder="e.g. main-menu">
                                        <small class="text-muted">Used in code to retrieve this menu.</small>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light border-0">
                                    <button type="button" class="btn btn-outline-secondary px-4 font-size-13"
                                        data-bs-toggle="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Update Menu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createMenuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('admin.menus.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary py-3 text-white border-0">
                        <h5 class="modal-title fw-bold">Create New Navigation Menu</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Menu Name</label>
                            <input type="text" name="name" class="form-control shadow-sm" placeholder="e.g. Header Menu"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Identifier (Slug)</label>
                            <input type="text" name="slug" class="form-control shadow-sm" placeholder="e.g. header"
                                required>
                            <small class="text-muted">Unique identifier for this menu.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-outline-secondary px-4 font-size-13"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Save Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection