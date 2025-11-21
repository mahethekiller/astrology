@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Admin Dashboard</div>

                <div class="card-body">
                    <h4>Welcome, {{ auth()->user()->name }}!</h4>
                    <p>You have administrator privileges.</p>
                    <ul>
                        <li>Manage Users</li>
                        <li>System Configuration</li>
                        <li>All Access</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
