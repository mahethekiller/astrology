@extends('astrologer.layouts.app')

@section('title', 'Call Dashboard')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Call Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Receive Calls</li>
        </ol>

        <div class="row justify-content-center mt-5">
            <div class="col-md-6 text-center">
                <div class="card shadow-sm border-0 rounded-4 p-5">
                    <div class="card-body">
                        <i class="bi bi-telephone-inbound display-1 text-primary mb-4"></i>
                        <h3>Calls Multi-Tasking</h3>
                        <p class="text-muted mb-4">You can now receive calls from any page in your dashboard! Look for the phone icon in the top header to manage your status.</p>
                        
                        <button class="btn btn-primary btn-lg rounded-pill px-5" data-bs-toggle="modal" data-bs-target="#astrologerCallManagementModal">
                            Manage Availability
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection