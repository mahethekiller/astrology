@extends('frontend.layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Chat History</h2>
                <div class="card shadow">
                    <div class="card-body">
                        @if($chats->isEmpty())
                            <p class="text-center text-muted">No past chats found.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Astrologer</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($chats as $chat)
                                            <tr>
                                                <td>{{ $chat->astrologer->display_name }}</td>
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
        </div>
    </div>
@endsection