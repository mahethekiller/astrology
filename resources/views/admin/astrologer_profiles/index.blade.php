@extends('admin.layouts.app')

@section('title', 'Astrologer Profiles')
@section('page-title', 'Astrologer Profiles')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-astrologer-index.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="index-card animate__animated animate__fadeIn">
                <div class="index-header">
                    <div class="index-title-group">
                        <h3>Astrologer Directory</h3>
                        <p>Manage and monitor all spiritual expert profiles</p>
                    </div>
                    <a href="{{ route('admin.astrologer-profiles.create') }}"
                        class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i>Add New Profile
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-3 fade show mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-premium" id="astrologersTable">
                        <thead>
                            <tr>
                                <th>Expert</th>
                                <th>Identity</th>
                                <th>Contact Details</th>
                                <th>Verification</th>
                                <th>Availability</th>
                                <th>Performance</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($profiles as $profile)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $profile->profile_image_url }}" alt="{{ $profile->display_name }}"
                                                class="avatar-premium me-3">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $profile->display_name }}</div>
                                                <div class="x-small text-muted" style="font-size: 0.75rem;">{{ $profile->slug }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">{{ $profile->user->name }}</span>
                                            <div class="mt-1 d-flex gap-1">
                                                @if($profile->is_featured)
                                                    <span class="vibrant-tag tag-featured">Featured</span>
                                                @endif
                                                @if($profile->is_online)
                                                    <span class="vibrant-tag tag-online">Online</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="text-dark small"><i class="bi bi-envelope me-1 text-muted"></i>
                                                {{ $profile->user->email }}</div>
                                            <div class="text-muted small mt-1"><i class="bi bi-phone me-1 text-muted"></i>
                                                {{ $profile->user->phone_number ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($profile->verification_status === 'approved')
                                            <span class="badge-premium badge-approved">
                                                <i class="bi bi-patch-check-fill"></i> Approved
                                            </span>
                                        @elseif($profile->verification_status === 'pending')
                                            <span class="badge-premium badge-pending">
                                                <i class="bi bi-hourglass-split"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge-premium badge-rejected">
                                                <i class="bi bi-patch-exclamation-fill"></i> Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($profile->status === 'active')
                                            <span class="status-pills status-active">Active</span>
                                        @elseif($profile->status === 'inactive')
                                            <span class="status-pills status-inactive">Inactive</span>
                                        @else
                                            <span class="status-pills status-suspended">Suspended</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="bi bi-star-fill text-warning me-1" style="font-size: 0.8rem;"></i>
                                            <span class="fw-bold text-dark">{{ number_format($profile->rating, 1) }}</span>
                                            <span class="rating-count">({{ $profile->total_reviews }})</span>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            <i class="bi bi-chat-left-text me-1"></i> {{ $profile->total_consultations }}
                                            sessions
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.astrologer-profiles.edit', $profile) }}"
                                                class="btn-action-premium btn-edit-premium" title="Edit Profile">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.astrologer-profiles.destroy', $profile) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-premium btn-delete-premium"
                                                    title="Delete Account"
                                                    onclick="return confirm('Are you sure? This will delete both the profile and the user account!')">
                                                    <i class="bi bi-trash3-fill"></i>
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
                order: [[0, 'asc']],
                pageLength: 10,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search experts..."
                },
                drawCallback: function () {
                    $('.dataTables_paginate > .pagination').addClass('pagination-sm justify-content-end');
                }
            });
        });
    </script>
@endpush