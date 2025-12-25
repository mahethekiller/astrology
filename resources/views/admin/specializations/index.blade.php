@extends('admin.layouts.app')

@section('title', 'Specializations')
@section('page-title', 'Specializations')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <h3>Specializations</h3>
                    <a href="{{ route('admin.specializations.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Add Specialization
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover" id="specializationsTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th>Astrologers</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($specializations as $specialization)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $specialization->name }}</span>
                                    </td>
                                    <td>
                                        <code>{{ $specialization->slug }}</code>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $specialization->description ? Str::limit($specialization->description, 50) : 'No description' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $specialization->astrologer_profiles_count }}</span>
                                    </td>
                                    <td>
                                        @if($specialization->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $specialization->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.specializations.edit', $specialization) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            @if($specialization->astrologer_profiles_count == 0)
                                                <form action="{{ route('admin.specializations.destroy', $specialization) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger ms-1"
                                                        onclick="return confirm('Are you sure you want to delete this specialization?')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
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

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#specializationsTable').DataTable({
                order: [[0, 'asc']], // Sort by name
                pageLength: 10,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search specializations..."
                }
            });
        });
    </script>
@endpush