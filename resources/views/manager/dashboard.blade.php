@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">Manager Dashboard</div>

                <div class="card-body">
                    <h4>Welcome, {{ auth()->user()->name }}!</h4>
                    <p>You have manager privileges.</p>
                    <ul>
                        <li>Manage Team</li>
                        <li>View Reports</li>
                        <li>Limited Admin Access</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
