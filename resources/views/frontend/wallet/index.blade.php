@extends('frontend.layouts.app')

@section('title', 'My Wallet')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">My Wallet</h4>
                    </div>
                    <div class="card-body text-center">

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <h5 class="text-muted">Current Balance</h5>
                        <h1 class="display-4 fw-bold text-success">₹{{ number_format($user->wallet->balance, 2) }}</h1>

                        <hr class="my-4">

                        <h5>Add Money to Wallet</h5>
                        <form action="{{ route('wallet.add') }}" method="POST" class="mt-3">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" name="amount" class="form-control" placeholder="Enter Amount"
                                            min="1" required>
                                        <button class="btn btn-success" type="submit">Add Money</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="mt-4 text-start">
                            <h6>Recent Transactions</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->wallet->transactions()->latest()->take(10)->get() as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format('d M Y, h:i A') }}</td>
                                                <td>{{ $transaction->description }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $transaction->type == 'credit' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($transaction->type) }}
                                                    </span>
                                                </td>
                                                <td>₹{{ number_format($transaction->amount, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No transactions yet.</td>
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