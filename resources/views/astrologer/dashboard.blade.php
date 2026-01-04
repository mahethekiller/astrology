@extends('astrologer.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Astrologer Dashboard</h1>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Rating</h5>
                        <p class="card-text display-4">{{ number_format($stats['rating'], 1) }} <i
                                class="fas fa-star text-warning"></i></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Today's Earnings</h5>
                        <p class="card-text display-4">₹{{ number_format($stats['today_earnings'], 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Chats</h5>
                        <p class="card-text display-4">{{ $stats['total_chats'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests Section -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Incoming Chat Requests</h5>
                        <a href="{{ route('astrologer.chat.index') }}" class="btn btn-sm btn-light">View All</a>
                    </div>
                    <div class="card-body">
                        @if($incomingRequests->isEmpty())
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-coffee fa-3x mb-3"></i>
                                <p>No new requests at the moment.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Requested</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($incomingRequests as $request)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar me-2 bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $request->user->name }}</h6>
                                                            <small class="text-muted">{{ $request->user->phone_number }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $request->created_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ route('astrologer.chat.accept', $request->id) }}"
                                                        class="btn btn-success btn-sm"><i class="fas fa-check"></i> Accept</a>
                                                    <a href="{{ route('astrologer.chat.reject', $request->id) }}"
                                                        class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Reject</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions / Status -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input status-toggle" type="checkbox" id="chatStatus" data-type="chat"
                                {{ Auth::user()->astrologerProfile->is_chat_online ? 'checked' : '' }}>
                            <label class="form-check-label" for="chatStatus">Available for Chat</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input status-toggle" type="checkbox" id="callStatus" data-type="call"
                                {{ Auth::user()->astrologerProfile->is_call_online ? 'checked' : '' }}>
                            <label class="form-check-label" for="callStatus">Available for Call</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Auto-refresh requests every 10 seconds --}}
    @push('scripts')
        <script>
            // Toggle Status
            document.querySelectorAll('.status-toggle').forEach(item => {
                item.addEventListener('change', async function () {
                    const type = this.dataset.type;
                    const status = this.checked ? 1 : 0;

                    try {
                        await axios.post("{{ route('astrologer.status.toggle') }}", {
                            type: type,
                            status: status
                        });
                        // Optional: Toast notification
                    } catch (error) {
                        console.error(error);
                        alert('Failed to update status');
                        this.checked = !status; // Revert
                    }
                });
            });

            setTimeout(function () {
                // window.location.reload(1);
            }, 10000);
        </script>
    @endpush
@endsection