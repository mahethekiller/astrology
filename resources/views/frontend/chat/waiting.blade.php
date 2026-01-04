@extends('frontend.layouts.app')

@section('title', 'Waiting for Astrologer')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card shadow p-5">
                    <h3 class="mb-4">Waiting for {{ $chatRequest->astrologer->display_name }}</h3>
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="lead">Please wait while the astrologer accepts your request...</p>
                    <p class="text-muted small">Do not close this window.</p>

                    <div id="status-message" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const requestId = {{ $chatRequest->id }};
        const statusEndpoint = "{{ route('chat.request.status', $chatRequest->id) }}";

        const pollInterval = setInterval(checkStatus, 3000); // Check every 3 seconds

        async function checkStatus() {
            try {
                const response = await axios.get(statusEndpoint);
                const status = response.data.status;

                if (status === 'accepted') {
                    clearInterval(pollInterval);
                    document.getElementById('status-message').innerHTML = '<span class="text-success fw-bold">Request Accepted! Redirecting...</span>';
                    window.location.href = "{{ route('chat.room', 'SID_PLACEHOLDER') }}".replace('SID_PLACEHOLDER', response.data.sid);
                } else if (status === 'rejected') {
                    clearInterval(pollInterval);
                    document.getElementById('status-message').innerHTML = '<span class="text-danger fw-bold">Request Rejected.</span>';
                    setTimeout(() => window.location.href = "{{ route('astrologer.index') }}", 3000);
                }
            } catch (error) {
                console.error("Error checking status", error);
            }
        }
    </script>
@endpush