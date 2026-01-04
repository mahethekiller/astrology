@extends('astrologer.layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Chat History</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                @if($chats->isEmpty())
                    <p class="text-center text-muted">No past chats found.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chats as $chat)
                                    <tr>
                                        <td>{{ $chat->user->name }}</td>
                                        <td>{{ $chat->created_at->format('d M Y, h:i A') }}</td>
                                        <td><span class="badge bg-secondary">{{ ucfirst($chat->status) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection