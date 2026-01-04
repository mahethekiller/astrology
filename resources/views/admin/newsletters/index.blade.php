@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Newsletter Subscriptions</h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Email</th>
                                        <th>Subscribed At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($newsletters as $newsletter)
                                        <tr>
                                            <td>{{ $newsletter->id }}</td>
                                            <td>{{ $newsletter->email }}</td>
                                            <td>{{ $newsletter->created_at->format('d M Y, h:i A') }}</td>
                                            <td>
                                                <form action="{{ route('admin.newsletters.destroy', $newsletter->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this subscription?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No subscriptions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $newsletters->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection