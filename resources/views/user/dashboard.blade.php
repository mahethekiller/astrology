@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">User Dashboard</div>

                <div class="card-body">
                    <h4>Welcome, {{ auth()->user()->name }}!</h4>
                    <p>You have regular user privileges.</p>
                    <ul>
                        <li>View Profile</li>
                        <li>Basic Access</li>
                        <li>Limited Features</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
