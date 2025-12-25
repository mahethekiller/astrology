@extends('admin.layouts.app')

@section('title', 'Astrologer Profiles')
@section('page-title', 'Astrologer Profiles')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <h3>Astrologer Profiles</h3>
                    <a href="{{ route('admin.astrologer-profiles.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Add Astrologer Profile
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
                    <table class="table table-hover" id="astrologersTable">
                        <thead>
                            <tr>
                                <th>Profile</th>
                                <th>Display Name</th>
                                <th>User Email</th>
                                <th>Verification</th>
                                <th>Status</th>
                                <th>Rating</th>
                                <th>Consultations</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($profiles as $profile)
                                <tr>
                                    <td>
                                        <img src="{{ $profile->profile_image_url }}" alt="{{ $profile->display_name }}"
                                            class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $profile->display_name }}</span>
                                            <small class="text-muted">{{ $profile->slug }}</small>
                                            <div class="mt-1">
                                                @if($profile->is_featured)
                                                    <span class="badge bg-warning text-dark me-1">
                                                        <i class="bi bi-star-fill"></i> Featured
                                                    </span>
                                                @endif
                                                @if($profile->is_online)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-circle-fill"></i> Online
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $profile->user->name }}</span>
                                            <small class="text-muted">{{ $profile->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($profile->verification_status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Approved
                                            </span>
                                        @elseif($profile->verification_status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle"></i> Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($profile->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($profile->status === 'inactive')
                                            <span class="badge bg-secondary">Inactive</span>
                                        @else
                                            <span class="badge bg-danger">Suspended</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-star-fill text-warning me-1"></i>
                                            <span>{{ number_format($profile->rating, 1) }}</span>
                                            <small class="text-muted ms-1">({{ $profile->total_reviews }})</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $profile->total_consultations }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.astrologer-profiles.edit', $profile) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.astrologer-profiles.destroy', $profile) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger ms-1"
                                                    onclick="return confirm('Are you sure? This will delete both the profile and the user account!')">
                                                    <i class="bi bi-trash"></i> Delete
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

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#astrologersTable').DataTable({
                order: [[1, 'asc']], // Sort by display name
                pageLength: 10,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search astrologers..."
                }
            });
        });
    </script>
@endpush