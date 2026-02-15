@extends('admin.layouts.app')

@section('title', 'Revenue Report')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="font-size-18">Financial Revenue Report</h4>
                <p class="text-muted">Overview of platform earnings and astrologer payouts.</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50">Total Revenue (User Payments)</h6>
                                <h2 class="mb-0">₹{{ number_format($totalRevenue, 2) }}</h2>
                            </div>
                            <div class="ms-3">
                                <i class="bi bi-wallet2 fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50">Platform Profit (Commission)</h6>
                                <h2 class="mb-0">₹{{ number_format($totalCommission, 2) }}</h2>
                            </div>
                            <div class="ms-3">
                                <i class="bi bi-graph-up-arrow fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50">Total Astrologer Earnings</h6>
                                <h2 class="mb-0">₹{{ number_format($totalEarnings, 2) }}</h2>
                            </div>
                            <div class="ms-3">
                                <i class="bi bi-people fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Transaction Breakdown</h5>
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-calls-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-calls" type="button" role="tab">Voice Calls</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-chats-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-chats" type="button" role="tab">Chats</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <!-- Calls Tab -->
                            <div class="tab-pane fade show active" id="pills-calls" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Astrologer</th>
                                                <th>User</th>
                                                <th>Duration</th>
                                                <th>Total Paid</th>
                                                <th>Platform Comm.</th>
                                                <th>Astrol. Net</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($calls as $call)
                                                <tr>
                                                    <td>{{ $call->created_at->format('d M Y, h:i A') }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-2">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                    {{ substr($call->astrologer->display_name ?? 'U', 0, 1) }}
                                                                </span>
                                                            </div>
                                                            {{ $call->astrologer->display_name ?? 'Unknown' }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $call->user->name ?? 'Unknown' }}</td>
                                                    <td>{{ ceil($call->call_duration / 60) }} min</td>
                                                    <td class="fw-bold text-dark">₹{{ $call->call_cost }}</td>
                                                    <td class="text-danger">₹{{ $call->commission_amount }}</td>
                                                    <td class="text-success fw-bold">₹{{ $call->astrologer_earnings }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">No call records found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Chats Tab -->
                            <div class="tab-pane fade" id="pills-chats" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Astrologer</th>
                                                <th>User</th>
                                                <th>Duration</th>
                                                <th>Total Paid</th>
                                                <th>Platform Comm.</th>
                                                <th>Astrol. Net</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($chats as $chat)
                                                <tr>
                                                    <td>{{ $chat->created_at->format('d M Y, h:i A') }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-2">
                                                                <span
                                                                    class="avatar-title rounded-circle bg-info-subtle text-info">
                                                                    {{ substr($chat->astrologer->display_name ?? 'U', 0, 1) }}
                                                                </span>
                                                            </div>
                                                            {{ $chat->astrologer->display_name ?? 'Unknown' }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $chat->user->name ?? 'Unknown' }}</td>
                                                    <td>{{ $chat->chat_duration }} min</td>
                                                    <td class="fw-bold text-dark">₹{{ $chat->chat_cost }}</td>
                                                    <td class="text-danger">₹{{ $chat->commission_amount }}</td>
                                                    <td class="text-success fw-bold">₹{{ $chat->astrologer_earnings }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">No chat records found
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
        </div>
    </div>
@endsection