@extends('admin.layouts.app')

@section('title', 'Manage Menu: ' . $menu->name)

@push('css')
<style>
    .bg-soft-primary { background-color: rgba(9, 120, 240, 0.1); }
    .table-light-soft { background-color: #f8fafc; }
    .uppercase { text-transform: uppercase; }
    .tracking-wider { letter-spacing: 0.05em; }
    .dot {
        height: 8px;
        width: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    .hover-opacity-100:hover { opacity: 1 !important; }
    .bg-gradient { background: linear-gradient(45deg, #0d6efd 0%, #0db9fd 100%) !important; }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-size-18">Manage Items: {{ $menu->name }}</h4>
                    <p class="text-muted">Add and organize links for this menu.</p>
                </div>
                <a href="{{ route('admin.menus.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back to Menus
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 border-top border-4 border-primary">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle-fill text-primary me-2"></i> Add New Link</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.menus.items.store', $menu->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Label</label>
                                <input type="text" name="title" class="form-control" required placeholder="e.g. Services">
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Type</label>
                                    <select name="type" class="form-select" id="linkTypeSelect">
                                        <option value="route">Route Name</option>
                                        <option value="url">Direct URL</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Opens In</label>
                                    <select name="target" class="form-select">
                                        <option value="_self">Same Tab</option>
                                        <option value="_blank">New Tab</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3" id="routeInput">
                                <label class="form-label fw-semibold">Route Name</label>
                                <input type="text" name="route" class="form-control" placeholder="astrologer.index">
                                <small class="text-muted">Requires a valid Laravel route name.</small>
                            </div>
                            <div class="mb-3 d-none" id="urlInput">
                                <label class="form-label fw-semibold">URL Path</label>
                                <input type="text" name="url" class="form-control" placeholder="/services or https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Parent Link (Optional)</label>
                                <select name="parent_id" class="form-select">
                                    <option value="">None (Top Level)</option>
                                    @foreach ($parentItems as $pItem)
                                        <option value="{{ $pItem->id }}">{{ $pItem->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Display Order</label>
                                <input type="number" name="order" class="form-control" value="0">
                            </div>
                            <button type="submit" class="btn btn-primary bg-gradient w-100 py-2 fw-bold shadow-sm">
                                <i class="bi bi-plus-lg me-1"></i> Add to Structure
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-stack text-primary me-2"></i> Navigation Architecture</h5>
                        <span class="badge bg-soft-primary text-primary px-3 rounded-pill">Total: {{ $items->count() + $items->sum(fn($i) => $i->children->count()) }} Items</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 80px;" class="ps-4">Sort</th>
                                        <th>Label & Location</th>
                                        <th>Target</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr class="table-light-soft bg-opacity-10 border-start border-4 border-primary">
                                            <td class="ps-4 font-monospace text-muted">{{ $item->order }}</td>
                                            <td>
                                                <div class="fw-bold text-dark fs-6">{{ $item->title }}</div>
                                                <div class="small text-muted">
                                                    @if($item->type == 'route')
                                                        <i class="bi bi-link-45deg"></i> route: <code>{{ $item->route }}</code>
                                                    @else
                                                        <i class="bi bi-globe"></i> url: <code>{{ $item->url }}</code>
                                                    @endif
                                                </div>
                                            </td>
                                            <td><span class="badge bg-light text-dark shadow-none border">{{ str_replace('_', ' ', $item->target) }}</span></td>
                                            <td>
                                                @if($item->status)
                                                    <span class="badge rounded-pill bg-success px-3">Active</span>
                                                @else
                                                    <span class="badge rounded-pill bg-danger px-3">Hidden</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group shadow-sm">
                                                    <button type="button" class="btn btn-sm btn-white border" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                                                        <i class="bi bi-pencil text-primary"></i>
                                                    </button>
                                                    <form action="{{ route('admin.menu-items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this link and all its sub-links?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-white border">
                                                            <i class="bi bi-trash text-danger"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        @foreach($item->children as $child)
                                            <tr class="bg-white border-start border-4 border-light">
                                                <td class="ps-4 font-monospace text-muted opacity-50">{{ $child->order }}</td>
                                                <td>
                                                    <div class="ps-4 d-flex align-items-center">
                                                        <i class="bi bi-arrow-return-right text-muted me-3"></i>
                                                        <div>
                                                            <div class="fw-semibold text-dark">{{ $child->title }}</div>
                                                            <div class="small text-muted font-monospace">
                                                                {{ $child->type == 'route' ? $child->route : $child->url }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="text-muted small">{{ $child->target }}</span></td>
                                                <td>
                                                    <span class="dot {{ $child->status ? 'bg-success' : 'bg-danger' }} me-1"></span>
                                                    <span class="small text-muted">{{ $child->status ? 'On' : 'Off' }}</span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-light border-0" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $child->id }}">
                                                            <i class="bi bi-pencil text-primary"></i>
                                                        </button>
                                                        <form action="{{ route('admin.menu-items.destroy', $child->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-light border-0">
                                                                <i class="bi bi-trash text-danger"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            @include('admin.menus.partials.edit_modal', ['item' => $child, 'parentItems' => $parentItems])
                                        @endforeach

                                        @include('admin.menus.partials.edit_modal', ['item' => $item, 'parentItems' => $parentItems])
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                                This menu is empty. Start adding some links!
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('linkTypeSelect').addEventListener('change', function () {
                if (this.value === 'url') {
                    document.getElementById('urlInput').classList.remove('d-none');
                    document.getElementById('routeInput').classList.add('d-none');
                } else {
                    document.getElementById('urlInput').classList.add('d-none');
                    document.getElementById('routeInput').classList.remove('d-none');
                }
            });
        </script>
    @endpush
@endsection