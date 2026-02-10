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
                    <div class="card-body" id="requestsContainer">
                        @if($incomingRequests->isEmpty())
                            <div class="text-center py-4 text-muted" id="emptyRequestsState">
                                <i class="fas fa-coffee fa-3x mb-3"></i>
                                <p>No new requests at the moment.</p>
                            </div>
                            <div class="table-responsive d-none">
                                <table class="table align-middle" id="incomingRequestsTable">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Requested</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle" id="incomingRequestsTable">
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
                            <div class="text-center py-4 text-muted d-none" id="emptyRequestsState">
                                <i class="fas fa-coffee fa-3x mb-3"></i>
                                <p>No new requests at the moment.</p>
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
                                {{ (Auth::user()->astrologerProfile && Auth::user()->astrologerProfile->is_chat_online) ? 'checked' : '' }}
                                    {{ !Auth::user()->astrologerProfile ? 'disabled' : '' }}>
                            <label class="form-check-label" for="chatStatus">Available for Chat</label>
                        @if(!Auth::user()->astrologerProfile)
                            <small class="text-danger d-block">Profile missing</small>
                        @endif
                            </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input status-toggle" type="checkbox" id="callStatus" data-type="call"
                                {{ (Auth::user()->astrologerProfile && Auth::user()->astrologerProfile->is_call_online) ? 'checked' : '' }}
                                    {{ !Auth::user()->astrologerProfile ? 'disabled' : '' }}>
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

            // Polling for Pending Requests
            async function fetchPendingRequests() {
                try {
                    const response = await axios.get("{{ route('astrologer.requests.pending') }}");
                    const chatRequests = response.data.chatRequests;
                    const callRequests = response.data.callRequests;
                    
                    const tableBody = document.querySelector('#incomingRequestsTable tbody');
                    const emptyState = document.querySelector('#emptyRequestsState');

                    if (chatRequests.length === 0) {
                        if (tableBody) tableBody.closest('.table-responsive').classList.add('d-none');
                        if (emptyState) emptyState.classList.remove('d-none');
                    } else {
                        if (emptyState) emptyState.classList.add('d-none');
                        
                        let html = '';
                        chatRequests.forEach(req => {
                            html += `
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2 bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                ${req.user_initial}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">${req.user_name}</h6>
                                                <small class="text-muted">${req.user_phone || ''}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${req.created_at_human}</td>
                                    <td>
                                        <a href="${req.accept_url}" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Accept</a>
                                        <a href="${req.reject_url}" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Reject</a>
                                    </td>
                                </tr>
                            `;
                        });

                        if (tableBody) {
                            tableBody.innerHTML = html;
                            tableBody.closest('.table-responsive').classList.remove('d-none');
                        }
                    }

                    // Handle Call Requests
                    const callTableBody = document.querySelector('#incomingCallRequestsTable tbody');
                    const emptyCallState = document.querySelector('#emptyCallRequestsState');

                    if (callRequests.length === 0) {
                        if (callTableBody) callTableBody.closest('.table-responsive').classList.add('d-none');
                        if (emptyCallState) emptyCallState.classList.remove('d-none');
                    } else {
                        if (emptyCallState) emptyCallState.classList.add('d-none');
                        
                        let callHtml = '';
                        callRequests.forEach(req => {
                            callHtml += `
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2 bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                ${req.user_initial}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">${req.user_name}</h6>
                                                <small class="text-muted">${req.user_phone || ''}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${req.created_at_human}</td>
                                    <td>
                                        <a href="#" class="btn btn-success btn-sm disabled"><i class="fas fa-phone"></i> Inbound</a>
                                    </td>
                                </tr>
                            `;
                        });

                        if (callTableBody) {
                            callTableBody.innerHTML = callHtml;
                            callTableBody.closest('.table-responsive').classList.remove('d-none');
                        }
                    }

                    if (chatRequests.length > 0 || callRequests.length > 0) {
                        // Optional: Play sound if new request (logic to detect NEW can be added)
                    }

                } catch (error) {
                    console.error('Error fetching pending requests:', error);
                }
            }

            // Start polling every 5 seconds
            setInterval(fetchPendingRequests, 5000);
            
            // Fetch immediately on load
            fetchPendingRequests();
        </script>
    @endpush
@endsection