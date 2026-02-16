@extends('astrologer.layouts.app')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #2af598 0%, #009efd 100%);
        --info-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        --dark-gradient: linear-gradient(135deg, #232526 0%, #414345 100%);
    }

    .dashboard-container {
        padding: 2rem;
        background: #f8f9fa;
        min-height: calc(100vh - 60px);
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 1.25rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
    }

    .stat-card {
        padding: 1.5rem;
        border-radius: 1.25rem;
        color: white;
        position: relative;
        overflow: hidden;
        border: none;
    }

    .stat-card.primary { background: var(--primary-gradient); }
    .stat-card.success { background: var(--success-gradient); }
    .stat-card.info { background: var(--info-gradient); }
    .stat-card.warning { background: var(--warning-gradient); }

    .stat-card .icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.15;
        transform: rotate(-15deg);
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 800;
        margin-bottom: 0;
    }

    .stat-label {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
    }

    .section-title {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-badge {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }

    .status-online { background-color: #2af598; box-shadow: 0 0 10px #2af598; }
    .status-offline { background-color: #f5576c; }

    .avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .table thead th {
        border-top: none;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #a0aec0;
        font-weight: 600;
    }

    .btn-action {
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    .custom-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
        cursor: pointer;
    }

    .welcome-banner {
        background: var(--dark-gradient);
        border-radius: 1.25rem;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="dashboard-container">
    <div class="welcome-banner glass-card" style="background: var(--dark-gradient);">
        <div>
            <h1 class="mb-0 fw-bold">Welcome back, {{ Auth::user()->astrologerProfile->display_name ?? Auth::user()->name }}!</h1>
            <p class="opacity-75 mb-0">Here's what's happening with your consultations today.</p>
        </div>
        <div class="text-end">
            <div class="d-flex align-items-center gap-3">
                <div class="text-end me-2">
                    <span class="d-block small opacity-75">Your Rating</span>
                    <span class="fw-bold h4 mb-0">{{ number_format($stats['rating'], 1) }} <i class="fas fa-star text-warning"></i></span>
                </div>
                <img src="{{ Auth::user()->astrologerProfile->profile_image_url ?? asset('assets/images/default-avatar.png') }}" 
                     class="rounded-circle border border-3 border-light shadow" width="60" height="60" alt="Profile">
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card primary glass-card">
                <div class="stat-label">Today's Earnings</div>
                <div class="stat-value">₹{{ number_format($stats['today_earnings'], 2) }}</div>
                <i class="fas fa-wallet icon-bg"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card success glass-card">
                <div class="stat-label">Total Consultations</div>
                <div class="stat-value">{{ $stats['total_consultations'] }}</div>
                <i class="fas fa-user-friends icon-bg"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card info glass-card">
                <div class="stat-label">Lifetime Revenue</div>
                <div class="stat-value">₹{{ number_format($stats['total_earnings'], 0) }}</div>
                <i class="fas fa-chart-line icon-bg"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning glass-card">
                <div class="stat-label">Experience</div>
                <div class="stat-value">{{ Auth::user()->astrologerProfile->experience_years ?? 0 }} Years</div>
                <i class="fas fa-award icon-bg"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content Area -->
        <div class="col-lg-8">
            <!-- Active Requests -->
            <div class="card glass-card mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="section-title mb-0"><i class="fas fa-bell text-primary"></i> Pending Requests</h5>
                    <span class="badge bg-primary rounded-pill" id="requestCountBadge">{{ $incomingRequests->count() }} New</span>
                </div>
                <div class="card-body p-0">
                    <div id="requestsContainer">
                        @if($incomingRequests->isEmpty())
                            <div class="text-center py-5" id="emptyRequestsState">
                                <div class="mb-3">
                                    <i class="fas fa-inbox fa-3x text-muted opacity-25"></i>
                                </div>
                                <h6 class="text-muted">Stay active to receive new requests</h6>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="incomingRequestsTable">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">User Details</th>
                                            <th>Type</th>
                                            <th>Time Remaining</th>
                                            <th class="text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($incomingRequests as $request)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-3 text-white" style="background: var(--primary-gradient)">
                                                            {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-bold">{{ $request->user->name }}</h6>
                                                            <small class="text-muted">{{ $request->user->phone_number }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill">
                                                        <i class="fas fa-comments me-1"></i> Chat
                                                    </span>
                                                </td>
                                                <td><span class="text-success"><i class="far fa-clock me-1"></i> Just now</span></td>
                                                <td class="text-end pe-4">
                                                    <div class="btn-group gap-2">
                                                        <a href="{{ route('astrologer.chat.accept', $request->id) }}" 
                                                           class="btn btn-success btn-action shadow-sm">
                                                            <i class="fas fa-check me-1"></i> Accept
                                                        </a>
                                                        <a href="{{ route('astrologer.chat.reject', $request->id) }}" 
                                                           class="btn btn-outline-danger btn-action">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </div>
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

            <!-- Inbound Calls (Dynamically filled) -->
            <div class="card glass-card mb-4 d-none" id="callRequestsCard">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="section-title mb-0"><i class="fas fa-phone-alt text-success"></i> Inbound Calls</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="incomingCallRequestsTable">
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Performance Overview Placeholder -->
            <div class="card glass-card">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="section-title mb-0"><i class="fas fa-chart-area text-info"></i> Consultation Trends</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container d-flex align-items-center justify-content-center">
                        <div class="text-center opacity-50">
                            <i class="fas fa-chart-bar fa-4x mb-3"></i>
                            <p>Monthly analytics will appear here after enough sessions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <!-- Availability Settings -->
            <div class="card glass-card mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="section-title mb-0"><i class="fas fa-toggle-on text-warning"></i> Availability</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-4 bg-light">
                            <div>
                                <h6 class="mb-0 fw-bold">Live Chat</h6>
                                <small class="text-muted">Ready to accept chat requests</small>
                            </div>
                            <div class="form-check form-switch custom-switch">
                                <input class="form-check-input status-toggle" type="checkbox" id="chatStatus" data-type="chat"
                                    {{ (Auth::user()->astrologerProfile && Auth::user()->astrologerProfile->is_chat_online) ? 'checked' : '' }}
                                    {{ !Auth::user()->astrologerProfile ? 'disabled' : '' }}>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center p-3 rounded-4 bg-light">
                            <div>
                                <h6 class="mb-0 fw-bold">Voice Call</h6>
                                <small class="text-muted">Available for incoming calls</small>
                            </div>
                            <div class="form-check form-switch custom-switch">
                                <input class="form-check-input status-toggle" type="checkbox" id="callStatus" data-type="call"
                                    {{ (Auth::user()->astrologerProfile && Auth::user()->astrologerProfile->is_call_online) ? 'checked' : '' }}
                                    {{ !Auth::user()->astrologerProfile ? 'disabled' : '' }}>
                            </div>
                        </div>
                    </div>

                    @if(!Auth::user()->astrologerProfile)
                        <div class="alert alert-soft-danger mt-3 mb-0" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i> Profile data missing!
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profile Overview Widget -->
            <div class="card glass-card">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="section-title mb-0"><i class="fas fa-user-circle text-primary"></i> Profile Brief</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3 position-relative d-inline-block">
                        <img src="{{ Auth::user()->astrologerProfile->profile_image_url ?? asset('assets/images/default-avatar.png') }}" 
                             class="rounded-circle border border-4 border-white shadow" width="100" height="100" alt="Profile">
                        <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-white border-2 rounded-circle shadow-sm"></span>
                    </div>
                    <h5 class="fw-bold mb-1">{{ Auth::user()->astrologerProfile->display_name ?? Auth::user()->name }}</h5>
                    <p class="text-muted small mb-3">{{ Auth::user()->astrologerProfile->about ? Str::limit(strip_tags(Auth::user()->astrologerProfile->about), 80) : 'Share your wisdom with seekers.' }}</p>
                    
                    <div class="list-group list-group-flush text-start small border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Chat Price:</span>
                            <span class="fw-bold text-success font-monospace">₹{{ number_format(Auth::user()->astrologerProfile->chat_price ?? 0, 2) }}/min</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Call Price:</span>
                            <span class="fw-bold text-success font-monospace">₹{{ number_format(Auth::user()->astrologerProfile->call_price ?? 0, 2) }}/min</span>
                        </div>
                    </div>
                    
                    <a href="#" class="btn btn-primary w-100 mt-3 rounded-pill">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle Status with feedback
    document.querySelectorAll('.status-toggle').forEach(item => {
        item.addEventListener('change', async function () {
            const type = this.dataset.type;
            const status = this.checked ? 1 : 0;
            const label = this.closest('.p-3').querySelector('h6').textContent;

            try {
                await axios.post("{{ route('astrologer.status.toggle') }}", {
                    type: type,
                    status: status
                });
                // Optional: You could add a small toast here
            } catch (error) {
                console.error(error);
                alert('Failed to update status for ' + label);
                this.checked = !status; // Revert
            }
        });
    });

    // Polling for Pending Requests with Enhanced UI
    async function fetchPendingRequests() {
        try {
            const response = await axios.get("{{ route('astrologer.requests.pending') }}");
            const chatRequests = response.data.chatRequests;
            const callRequests = response.data.callRequests;
            
            const tableBody = document.querySelector('#incomingRequestsTable tbody');
            const requestsContainer = document.querySelector('#requestsContainer');
            const badge = document.querySelector('#requestCountBadge');

            badge.textContent = `${chatRequests.length} New`;
            
            if (chatRequests.length === 0) {
                requestsContainer.innerHTML = `
                    <div class="text-center py-5" id="emptyRequestsState">
                        <div class="mb-3">
                            <i class="fas fa-inbox fa-3x text-muted opacity-25"></i>
                        </div>
                        <h6 class="text-muted">Stay active to receive new requests</h6>
                    </div>
                `;
            } else {
                let html = `
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="incomingRequestsTable">
                            <thead>
                                <tr>
                                    <th class="ps-4">User Details</th>
                                    <th>Type</th>
                                    <th>Time Remaining</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                chatRequests.forEach(req => {
                    html += `
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3 text-white" style="background: var(--primary-gradient)">
                                        ${req.user_initial}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">${req.user_name}</h6>
                                        <small class="text-muted">${req.user_phone || 'Private'}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill">
                                    <i class="fas fa-comments me-1"></i> Chat
                                </span>
                            </td>
                            <td><span class="text-success"><i class="far fa-clock me-1"></i> ${req.created_at_human}</span></td>
                            <td class="text-end pe-4">
                                <div class="btn-group gap-2">
                                    <a href="${req.accept_url}" class="btn btn-success btn-action shadow-sm">
                                        <i class="fas fa-check me-1"></i> Accept
                                    </a>
                                    <a href="${req.reject_url}" class="btn btn-outline-danger btn-action">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                html += `</tbody></table></div>`;
                requestsContainer.innerHTML = html;
            }

            // Handle Call Requests
            const callCard = document.querySelector('#callRequestsCard');
            const callTableBody = document.querySelector('#incomingCallRequestsTable tbody');

            if (callRequests.length > 0) {
                callCard.classList.remove('d-none');
                let callHtml = '';
                callRequests.forEach(req => {
                    callHtml += `
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3 text-white" style="background: var(--success-gradient)">
                                        ${req.user_initial}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">${req.user_name}</h6>
                                        <small class="text-muted">Inbound Call</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <span class="badge bg-success pulse-animation px-3 py-2 rounded-pill">
                                    <i class="fas fa-phone-alt me-1"></i> Rining...
                                </span>
                            </td>
                        </tr>
                    `;
                });
                callTableBody.innerHTML = callHtml;
            } else {
                callCard.classList.add('d-none');
            }

        } catch (error) {
            console.error('Error fetching pending requests:', error);
        }
    }

    // Start polling every 5 seconds
    setInterval(fetchPendingRequests, 5000);
    fetchPendingRequests();
</script>

<style>
    .bg-soft-primary { background-color: rgba(102, 126, 234, 0.1); }
    .bg-soft-success { background-color: rgba(42, 245, 152, 0.1); }
    .font-monospace { font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace; }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
@endpush
@endsection
