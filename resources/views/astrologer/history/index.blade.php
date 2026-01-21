@extends('astrologer.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Title -->
        <div class="row mb-3">
            <div class="col-12">
                <h4 class="font-size-18">Service History</h4>
            </div>
        </div>

        <div class="row">
            <!-- LEFT SIDE MENU -->
            <div class="col-md-3">
                <div class="card">
                    <div class="list-group list-group-flush">
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action active" id="menu-calls"
                            onclick="showSection('calls')">
                            📞 Voice Calls
                        </a>

                        <a href="javascript:void(0)" class="list-group-item list-group-item-action" id="menu-chats"
                            onclick="showSection('chats')">
                            💬 Chats
                        </a>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE CONTENT -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">

                        <!-- CALLS TABLE -->
                        <div id="section-calls">
                            <h5 class="mb-3">Voice Call History</h5>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>User</th>
                                            <th>Status</th>
                                            <th>Duration (min)</th>
                                            <th>Earnings</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($calls as $call)
                                            <tr>
                                                <td>{{ optional($call->user)->name ?? 'Unknown' }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $call->call_status === 'completed' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($call->call_status) }}
                                                    </span>
                                                </td>
                                                <td>{{ ceil($call->call_duration / 60) }}</td>
                                                <td>₹{{ $call->astrologer_earnings }}</td>
                                                <td>{{ $call->created_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    No call records found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- CHATS TABLE -->
                        <div id="section-chats" class="d-none">
                            <h5 class="mb-3">Chat History</h5>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>User</th>
                                            <th>Status</th>
                                            <th>Duration (min)</th>
                                            <th>Earnings</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($chats as $chat)
                                            <tr>
                                                <td>{{ optional($chat->user)->name ?? 'Unknown' }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $chat->status === 'completed' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($chat->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $chat->chat_duration }}</td>
                                                <td>₹{{ $chat->astrologer_earnings }}</td>
                                                <td>{{ $chat->created_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    No chat records found
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
@endsection

@push('scripts')
    <script>
        function showSection(type) {

            // Hide all sections
            document.getElementById('section-calls').classList.add('d-none');
            document.getElementById('section-chats').classList.add('d-none');

            // Remove active class from menu
            document.getElementById('menu-calls').classList.remove('active');
            document.getElementById('menu-chats').classList.remove('active');

            // Show selected section
            document.getElementById('section-' + type).classList.remove('d-none');
            document.getElementById('menu-' + type).classList.add('active');
        }
    </script>
@endpush