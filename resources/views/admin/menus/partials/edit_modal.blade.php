<div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('admin.menu-items.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold">Edit menu link: <span class="text-primary">{{ $item->title }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Label</label>
                        <input type="text" name="title" class="form-control shadow-sm" value="{{ $item->title }}"
                            required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Type</label>
                            <select name="type" class="form-select shadow-sm"
                                onchange="toggleEditInput(this, '{{ $item->id }}')">
                                <option value="route" {{ $item->type == 'route' ? 'selected' : '' }}>Route Name</option>
                                <option value="url" {{ $item->type == 'url' ? 'selected' : '' }}>Direct URL</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Target</label>
                            <select name="target" class="form-select shadow-sm">
                                <option value="_self" {{ $item->target == '_self' ? 'selected' : '' }}>Same Tab</option>
                                <option value="_blank" {{ $item->target == '_blank' ? 'selected' : '' }}>New Tab</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 {{ $item->type == 'url' ? 'd-none' : '' }}" id="editRouteInput{{ $item->id }}">
                        <label class="form-label fw-semibold">Route Name</label>
                        <input type="text" name="route" class="form-control shadow-sm" value="{{ $item->route }}"
                            placeholder="astrologer.index">
                    </div>

                    <div class="mb-3 {{ $item->type == 'route' ? 'd-none' : '' }}" id="editUrlInput{{ $item->id }}">
                        <label class="form-label fw-semibold">URL Path</label>
                        <input type="text" name="url" class="form-control shadow-sm" value="{{ $item->url }}"
                            placeholder="/home">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Parent Item</label>
                        <select name="parent_id" class="form-select shadow-sm">
                            <option value="">None (Top Level)</option>
                            @foreach ($parentItems as $pItem)
                                @if ($pItem->id != $item->id)
                                    <option value="{{ $pItem->id }}" {{ $item->parent_id == $pItem->id ? 'selected' : '' }}>
                                        {{ $pItem->title }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Display Order</label>
                            <input type="number" name="order" class="form-control shadow-sm" value="{{ $item->order }}">
                        </div>
                        <div class="col-md-6 pt-4">
                            <div class="form-check form-switch card p-2 border-0 bg-light">
                                <input class="form-check-input ms-1" type="checkbox" name="status" value="1"
                                    id="status{{ $item->id }}" {{ $item->status ? 'checked' : '' }}>
                                <label class="form-check-label ms-2 fw-semibold" for="status{{ $item->id }}">
                                    Visible
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary bg-gradient px-4 fw-bold shadow-sm">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleEditInput(select, id) {
        const routeInput = document.getElementById('editRouteInput' + id);
        const urlInput = document.getElementById('editUrlInput' + id);
        if (select.value === 'url') {
            urlInput.classList.remove('d-none');
            routeInput.classList.add('d-none');
        } else {
            urlInput.classList.add('d-none');
            routeInput.classList.remove('d-none');
        }
    }
</script>