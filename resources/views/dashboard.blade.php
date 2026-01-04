@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="card-icon primary">
                    <i class="bi bi-people"></i>
                </div>
                <div class="card-value">{{ number_format($stats['total_users']) }}</div>
                <div class="card-title">Total Users</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="card-icon success">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="card-value">{{ number_format($stats['total_orders']) }}</div>
                <div class="card-title">Total Orders</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="card-icon warning">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="card-value">${{ number_format($stats['total_revenue']) }}</div>
                <div class="card-title">Total Revenue</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="card-icon danger">
                    <i class="bi bi-eye"></i>
                </div>
                <div class="card-value">{{ number_format($stats['page_views']) }}</div>
                <div class="card-title">Page Views</div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="row">
        <div class="col-lg-8">
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Revenue Overview</h3>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-secondary active">Weekly</button>
                        <button class="btn btn-sm btn-outline-secondary">Monthly</button>
                        <button class="btn btn-sm btn-outline-secondary">Yearly</button>
                    </div>
                </div>
                <div style="height: 300px; background-color: var(--border-color); border-radius: 5px; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                    Chart Visualization Area
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Traffic Sources</h3>
                </div>
                <div style="height: 300px; background-color: var(--border-color); border-radius: 5px; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                    Pie Chart Visualization
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <h3>Recent Orders</h3>
                    <button class="btn btn-primary btn-sm">View All</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_orders as $order)
                            <tr>
                                <td>{{ $order['id'] }}</td>
                                <td>{{ $order['customer'] }}</td>
                                <td>{{ $order['date'] }}</td>
                                <td>${{ number_format($order['amount'], 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order['status'] == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
