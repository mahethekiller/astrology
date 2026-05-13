@extends('admin.layouts.app')

@section('content')
@php
    $activeTab = request()->has('chats_page') ? 'chats' : 'calls';
@endphp
    <div class="container-fluid">

        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <h4 class="font-size-18">Service History (Global)</h4>
            </div>
        </div>

        <div class="row">
            <!-- LEFT MENU -->
            <div class="col-md-3">
                <div class="card">
                    <div class="list-group list-group-flush">
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ $activeTab == 'calls' ? 'active' : '' }}" id="menu-calls"
                            onclick="showAdminSection('calls')">
                            📞 Voice Calls
                        </a>

                        <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ $activeTab == 'chats' ? 'active' : '' }}" id="menu-chats"
                            onclick="showAdminSection('chats')">
                            💬 Chats
                        </a>
                    </div>
                </div>
            </div>

            <!-- RIGHT CONTENT -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">

                        <!-- CALLS -->
                        <div id="section-calls" class="{{ $activeTab == 'calls' ? '' : 'd-none' }}">
                            <h5 class="mb-3">Voice Call History</h5>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Astrologer</th>
                                            <th>User</th>
                                            <th>Status</th>
                                            <th>Duration</th>
                                            <th>Total Cost</th>
                                            <th>Commission</th>
                                            <th>Earnings</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($calls as $call)
                                            <tr>
                                                <td>{{ optional($call->astrologer)->display_name ?? 'Unknown' }}</td>
                                                <td>{{ optional($call->user)->name ?? 'Unknown' }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $call->call_status === 'completed' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($call->call_status) }}
                                                    </span>
                                                </td>
                                                <td>{{ ceil($call->call_duration / 60) }} min</td>
                                                <td>₹{{ $call->call_cost }}</td>
                                                <td class="text-danger">- ₹{{ $call->commission_amount }}</td>
                                                <td class="text-success">₹{{ $call->astrologer_earnings }}</td>
                                                <td>{{ $call->created_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">
                                                    No call records found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $calls->appends(request()->except('calls_page'))->links('pagination::bootstrap-5') }}
                            </div>
                        </div>

                        <!-- CHATS -->
                        <div id="section-chats" class="{{ $activeTab == 'chats' ? '' : 'd-none' }}">
                            <h5 class="mb-3">Chat History</h5>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Astrologer</th>
                                            <th>User</th>
                                            <th>Status</th>
                                            <th>Duration</th>
                                            <th>Total Cost</th>
                                            <th>Commission</th>
                                            <th>Earnings</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($chats as $chat)
                                            <tr>
                                                <td>{{ optional($chat->astrologer)->display_name ?? 'Unknown' }}</td>
                                                <td>{{ optional($chat->user)->name ?? 'Unknown' }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $chat->status === 'completed' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($chat->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $chat->chat_duration }} min</td>
                                                <td>₹{{ $chat->chat_cost }}</td>
                                                <td class="text-danger">- ₹{{ $chat->commission_amount }}</td>
                                                <td class="text-success">₹{{ $chat->astrologer_earnings }}</td>
                                                <td>{{ $chat->created_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">
                                                    No chat records found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $chats->appends(request()->except('chats_page'))->links('pagination::bootstrap-5') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showAdminSection(type) {

            // Hide sections
            document.getElementById('section-calls').classList.add('d-none');
            document.getElementById('section-chats').classList.add('d-none');

            // Remove active menu
            document.getElementById('menu-calls').classList.remove('active');
            document.getElementById('menu-chats').classList.remove('active');

            // Show selected
            document.getElementById('section-' + type).classList.remove('d-none');
            document.getElementById('menu-' + type).classList.add('active');
        }
    </script>
@endpush