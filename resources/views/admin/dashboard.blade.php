@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            --success-gradient: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            --info-gradient: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
            --warning-gradient: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
            --glass-bg: rgba(255, 255, 255, 0.9);
        }

        .dashboard-container {
            padding: 1.5rem;
            background: #f8f9fc;
        }

        .stat-card {
            border: none;
            border-radius: 1rem;
            padding: 1.5rem;
            color: white;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.primary {
            background: var(--primary-gradient);
        }

        .stat-card.success {
            background: var(--success-gradient);
        }

        .stat-card.info {
            background: var(--info-gradient);
        }

        .stat-card.warning {
            background: var(--warning-gradient);
        }

        .stat-card i.icon-bg {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 4.5rem;
            opacity: 0.2;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .chart-container {
            position: relative;
            height: 350px;
            width: 100%;
        }

        .section-title {
            font-weight: 700;
            color: #4e73df;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .activity-table thead th {
            background-color: #f8f9fc;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            font-weight: 700;
        }
    </style>

    <div class="dashboard-container">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Executive Overview</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm rounded-pill px-3">
                <i class="fas fa-download fa-sm text-white-50 me-2"></i> Generate Report
            </a>
        </div>

        <!-- Stats Row -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card primary">
                    <div class="text-xs font-weight-bold text-uppercase mb-1 opacity-75">Platform Revenue</div>
                    <div class="h3 mb-0 fw-bold">₹{{ number_format($stats['total_revenue'], 2) }}</div>
                    <i class="fas fa-coins icon-bg"></i>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card success">
                    <div class="text-xs font-weight-bold text-uppercase mb-1 opacity-75">Active Customers</div>
                    <div class="h3 mb-0 fw-bold">{{ number_format($stats['total_users']) }}</div>
                    <i class="fas fa-users icon-bg"></i>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card info">
                    <div class="text-xs font-weight-bold text-uppercase mb-1 opacity-75">Verified Astrologers</div>
                    <div class="h3 mb-0 fw-bold">{{ number_format($stats['total_astrologers']) }}</div>
                    <i class="fas fa-user-tie icon-bg"></i>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card warning">
                    <div class="text-xs font-weight-bold text-uppercase mb-1 opacity-75">Pending Reviews</div>
                    <div class="h3 mb-0 fw-bold">{{ $stats['pending_verifications'] }}</div>
                    <i class="fas fa-clock icon-bg"></i>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Revenue Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card glass-card shadow h-100">
                    <div
                        class="card-header bg-transparent border-0 py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold section-title"><i class="fas fa-chart-line"></i> Revenue Trends (6
                            Months)</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Summary Pie? (Users vs Astrologers ratio) -->
            <div class="col-xl-4 col-lg-5">
                <div class="card glass-card shadow h-100">
                    <div
                        class="card-header bg-transparent border-0 py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold section-title"><i class="fas fa-chart-pie"></i> Platform Mix</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="mixChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-12">
                <div class="card glass-card shadow">
                    <div
                        class="card-header bg-transparent border-0 py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold section-title"><i class="fas fa-history"></i> Recent Consultations
                        </h6>
                        <a href="#" class="btn btn-sm btn-link text-primary text-decoration-none fw-bold">View Audit Log</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table activity-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">User</th>
                                        <th>Astrologer</th>
                                        <th>Status</th>
                                        <th>Revenue</th>
                                        <th class="text-end pe-4">Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recent_consultations as $session)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle text-white p-2 me-2 d-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px; font-size: 10px;">
                                                        {{ strtoupper(substr($session->user->name, 0, 1)) }}
                                                    </div>
                                                    <span class="fw-bold">{{ $session->user->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $session->astrologer->display_name }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $session->status == 'completed' ? 'success' : ($session->status == 'pending' ? 'warning' : 'danger') }} rounded-pill px-3">
                                                    {{ ucfirst($session->status) }}
                                                </span>
                                            </td>
                                            <td class="fw-bold text-dark">₹{{ number_format($session->commission_amount, 2) }}
                                            </td>
                                            <td class="text-end pe-4 text-muted small">
                                                {{ $session->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 opacity-50">No recent activity detected</td>
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Revenue Area Chart
            const revCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Revenue (₹)',
                        data: @json($revenueData),
                        fill: true,
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        lineTension: 0.3,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: 'rgba(255, 255, 255, 1)',
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointHoverBorderColor: 'rgba(255, 255, 255, 1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Mix Pie Chart
            const mixCtx = document.getElementById('mixChart').getContext('2d');
            const mixChart = new Chart(mixCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Users', 'Astrologers'],
                    datasets: [{
                        data: [{{ $stats['total_users'] }}, {{ $stats['total_astrologers'] }}],
                        backgroundColor: ['#4e73df', '#1cc88a'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673'],
                        hoverBorderColor: 'rgba(234, 236, 244, 1)',
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: 'rgb(255,255,255)',
                        bodyFontColor: '#858796',
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    cutout: '70%',
                },
            });
        </script>
    @endpush
@endsection