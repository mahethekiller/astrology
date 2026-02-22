@extends('astrologer.layouts.app')

@section('title', 'My Earnings')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/astrologer-panel.css') }}">
@endpush

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between my-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">My Earnings</h1>
                <p class="text-muted small mb-0">Track your performance and revenue across all sessions.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('astrologer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Revenue</li>
                </ol>
            </nav>
        </div>

        <!-- Summary Statistics -->
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="stats-card card-primary">
                    <div class="stats-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="stats-label">Total Earnings (Net)</div>
                    <h2 class="stats-value">₹{{ number_format($totalEarnings, 2) }}</h2>
                    <div class="mt-2">
                        <small class="opacity-75">{{ $totalSessions }} Total Sessions</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stats-card card-success">
                    <div class="stats-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stats-label">Today's Earnings</div>
                    <h2 class="stats-value">₹{{ number_format($todayEarnings, 2) }}</h2>
                    <div class="mt-2">
                        <small class="opacity-75">Active Today</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stats-card card-warning">
                    <div class="stats-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="stats-label">This Week</div>
                    <h2 class="stats-value">₹{{ number_format($weeklyEarnings, 2) }}</h2>
                    <div class="mt-2">
                        <small class="opacity-75">Last 7 days</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stats-card card-info">
                    <div class="stats-icon">
                        <i class="bi bi-calendar-month"></i>
                    </div>
                    <div class="stats-label">This Month</div>
                    <h2 class="stats-value">₹{{ number_format($monthlyEarnings, 2) }}</h2>
                    <div class="mt-2">
                        <small class="opacity-75">February 2026</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Breakdown -->
        <div class="breakdown-card">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold">Earnings Breakdown</h5>
                <ul class="nav nav-pills" id="earningsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="calls-tab" data-bs-toggle="pill" data-bs-target="#calls-pane"
                            type="button" role="tab">Voice Calls</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="chats-tab" data-bs-toggle="pill" data-bs-target="#chats-pane"
                            type="button" role="tab">Chats</button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content" id="earningsTabContent">
                    <!-- Calls Pane -->
                    <div class="tab-pane fade show active" id="calls-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-3">Date</th>
                                        <th class="border-0">User</th>
                                        <th class="border-0 text-center">Duration</th>
                                        <th class="border-0 text-end">Charged</th>
                                        <th class="border-0 text-end">Commission</th>
                                        <th class="border-0 text-end px-3">Net Earnings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($calls as $call)
                                        <tr>
                                            <td class="px-3">
                                                <div class="fw-bold">{{ $call->created_at->format('d M Y') }}</div>
                                                <small class="text-muted">{{ $call->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($call->user->name ?? 'U') }}&background=random"
                                                        class="table-user-img" alt="">
                                                    <span>{{ $call->user->name ?? 'Unknown' }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ ceil($call->call_duration / 60) }} min</td>
                                            <td class="text-end">₹{{ number_format($call->call_cost, 2) }}</td>
                                            <td class="text-end text-danger">-₹{{ number_format($call->commission_amount, 2) }}
                                            </td>
                                            <td class="text-end px-3 fw-bold text-success">
                                                ₹{{ number_format($call->astrologer_earnings, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-telephone-x fs-1 d-block mb-3 opacity-25"></i>
                                                No call records found for completed sessions.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Chats Pane -->
                    <div class="tab-pane fade" id="chats-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-3">Date</th>
                                        <th class="border-0">User</th>
                                        <th class="border-0 text-center">Duration</th>
                                        <th class="border-0 text-end">Charged</th>
                                        <th class="border-0 text-end">Commission</th>
                                        <th class="border-0 text-end px-3">Net Earnings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($chats as $chat)
                                        <tr>
                                            <td class="px-3">
                                                <div class="fw-bold">{{ $chat->created_at->format('d M Y') }}</div>
                                                <small class="text-muted">{{ $chat->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($chat->user->name ?? 'U') }}&background=random"
                                                        class="table-user-img" alt="">
                                                    <span>{{ $chat->user->name ?? 'Unknown' }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $chat->chat_duration }} min</td>
                                            <td class="text-end">₹{{ number_format($chat->chat_cost, 2) }}</td>
                                            <td class="text-end text-danger">-₹{{ number_format($chat->commission_amount, 2) }}
                                            </td>
                                            <td class="text-end px-3 fw-bold text-success">
                                                ₹{{ number_format($chat->astrologer_earnings, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="bi bi-chat-left-x fs-1 d-block mb-3 opacity-25"></i>
                                                No chat records found for completed sessions.
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
@endsection