@extends('astrologer.layouts.app')

@section('title', 'My Earnings')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">My Earnings</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 text-muted">
                            <li class="breadcrumb-item"><a href="{{ route('astrologer.dashboard') }}"
                                    class="text-reset">Dashboard</a></li>
                            <li class="breadcrumb-item active">Revenue</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-6 col-xl-4">
                <div class="card bg-success text-white shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50">Total Earnings (Net)</h6>
                                <h2 class="mb-0">₹{{ number_format($totalEarnings, 2) }}</h2>
                            </div>
                            <div class="ms-3">
                                <i class="bi bi-wallet2 fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card bg-info text-white shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50">Total Consultations</h6>
                                <h2 class="mb-0">{{ $totalSessions }}</h2>
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
                            <h5 class="mb-0">Earnings Breakdown</h5>
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-calls-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-calls" type="button" role="tab" aria-controls="pills-calls"
                                        aria-selected="true">Voice Calls</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-chats-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-chats" type="button" role="tab" aria-controls="pills-chats"
                                        aria-selected="false">Chats</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            <!-- Calls Tab -->
                            <div class="tab-pane fade show active" id="pills-calls" role="tabpanel"
                                aria-labelledby="pills-calls-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>User</th>
                                                <th>Duration</th>
                                                <th>Charged Amount</th>
                                                <th>Commission</th>
                                                <th>My Earnings</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($calls as $call)
                                                <tr>
                                                    <td>{{ $call->created_at->format('d M Y, h:i A') }}</td>
                                                    <td>{{ $call->user->name ?? 'Unknown' }}</td>
                                                    <td>{{ ceil($call->call_duration / 60) }} min</td>
                                                    <td>₹{{ $call->call_cost }}</td>
                                                    <td class="text-danger">- ₹{{ $call->commission_amount }}</td>
                                                    <td class="text-success fw-bold">₹{{ $call->astrologer_earnings }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">No call records found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Chats Tab -->
                            <div class="tab-pane fade" id="pills-chats" role="tabpanel" aria-labelledby="pills-chats-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>User</th>
                                                <th>Duration</th>
                                                <th>Charged Amount</th>
                                                <th>Commission</th>
                                                <th>My Earnings</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($chats as $chat)
                                                <tr>
                                                    <td>{{ $chat->created_at->format('d M Y, h:i A') }}</td>
                                                    <td>{{ $chat->user->name ?? 'Unknown' }}</td>
                                                    <td>{{ $chat->chat_duration }} min</td>
                                                    <td>₹{{ $chat->chat_cost }}</td>
                                                    <td class="text-danger">- ₹{{ $chat->commission_amount }}</td>
                                                    <td class="text-success fw-bold">₹{{ $chat->astrologer_earnings }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">No chat records found
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