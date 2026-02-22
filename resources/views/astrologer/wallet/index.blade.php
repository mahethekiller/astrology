@extends('astrologer.layouts.app')

@section('title', 'My Wallet')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/astrologer-panel.css') }}">
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">My Wallet</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('astrologer.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">My Wallet</li>
        </ol>

        <div class="row">
            <div class="col-lg-6">
                <div class="wallet-card">
                    <div class="balance-label">Current Earnings Balance</div>
                    <h2 class="balance-amount">₹{{ number_format($wallet->balance, 2) }}</h2>
                    <div class="mt-4">
                        <small>Earnings are automatically added after each successful session.</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="transaction-card h-100 d-flex flex-column justify-content-center">
                    <h5>Wallet Summary</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Total Transactions
                            <span class="badge bg-primary rounded-pill">{{ $transactions->total() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Last Transaction
                            <span
                                class="text-muted">{{ $transactions->first() ? $transactions->first()->created_at->diffForHumans() : 'No transactions' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="transaction-card mt-4">
            <h5 class="mb-4">Transaction History</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    <span
                                        class="type-badge {{ $transaction->type == 'credit' ? 'type-credit' : 'type-debit' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="fw-bold {{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type == 'credit' ? '+' : '-' }}₹{{ number_format($transaction->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection