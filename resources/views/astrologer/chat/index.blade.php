@extends('astrologer.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Chat Requests</h2>
                <div class="card">
                    <div class="card-body">
                        @if($requests->isEmpty())
                            <p class="text-center text-muted">No pending requests.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requests as $request)
                                            <tr>
                                                <td>{{ $request->user->name }} ({{ $request->user->phone_number }})</td>
                                                <td>{{ $request->created_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ route('astrologer.chat.accept', $request->id) }}"
                                                        class="btn btn-success btn-sm">Accept</a>
                                                    <a href="{{ route('astrologer.chat.reject', $request->id) }}"
                                                        class="btn btn-danger btn-sm">Reject</a>
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
        </div>
    </div>
@endsection