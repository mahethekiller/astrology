@extends('layouts.app')

@section('title', 'Data Tables')
@section('page-title', 'Data Tables')

@section('content')
<div class="table-container mb-4">
    <div class="table-header">
        <h3>Users Table</h3>
        <button class="btn btn-primary btn-sm">Export</button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user['id'] }}</td>
                    <td>{{ $user['name'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>
                        <span class="badge bg-{{ $user['role'] == 'Admin' ? 'primary' : ($user['role'] == 'Manager' ? 'warning' : 'secondary') }}">
                            {{ $user['role'] }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $user['status'] == 'active' ? 'success' : 'warning' }}">
                            {{ ucfirst($user['status']) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
