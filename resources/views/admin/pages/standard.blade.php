@extends('admin.layouts.app')

@section('title', 'Standard Pages Management')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="font-size-20 fw-bold text-primary mb-1">Standard Navigation Pages</h4>
                <p class="text-muted mb-0">These are the 7 core pillars of your website. Manage their content and cosmic appeal here.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle p-1 me-3 d-flex shadow-sm">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="fw-bold text-success">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-premium border-0 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-soft text-muted uppercase font-size-11 tracking-wider">
                            <tr>
                                <th class="ps-4 py-3">Core Page Name</th>
                                <th class="py-3">Fixed System Slug</th>
                                <th class="py-3">Display Status</th>
                                <th class="py-3">Last Content Update</th>
                                <th class="text-end pe-4 py-3">Content Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $page)
                                <tr class="transition-all">
                                    <td class="ps-4 py-4">
                                        <div class="d-flex align-items-center">
                                            <div class="page-icon bg-indigo-soft text-indigo me-3">
                                                <i class="bi bi-file-earmark-check fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6">{{ $page->title }}</div>
                                                <span class="badge bg-indigo-subtle text-indigo px-2 font-size-11 border border-indigo-subtle">System Lock</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <code class="path-badge text-indigo">/{{ $page->slug }}</code>
                                            <a href="/{{ $page->slug }}" target="_blank" class="ms-2 text-link animate-hover">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        @if($page->status)
                                            <div class="d-flex align-items-center text-success fw-semibold font-size-13">
                                                <span class="pulse-dot bg-success me-2"></span> Visible on Frontend
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center text-muted fw-semibold font-size-13 opacity-75">
                                                <span class="dot bg-secondary me-2"></span> Hidden (Internal)
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-dark fw-medium font-size-13">{{ $page->updated_at->format('M d, Y') }}</div>
                                        <div class="text-muted font-size-11">{{ $page->updated_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm border-0 bg-indigo-gradient animate-hover">
                                            <i class="bi bi-pencil-square me-1"></i> Edit Blueprint
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .uppercase { text-transform: uppercase; }
        .tracking-wider { letter-spacing: 0.12em; }
        .bg-light-soft { background-color: #f8fafc; }
        .bg-indigo-soft { background-color: rgba(79, 70, 229, 0.08); }
        .bg-indigo-subtle { background-color: rgba(79, 70, 229, 0.05); }
        .text-indigo { color: #4f46e5; }
        .border-indigo-subtle { border-color: rgba(79, 70, 229, 0.2) !important; }
        .font-size-11 { font-size: 11px; }
        .font-size-13 { font-size: 13px; }
        .bg-indigo-gradient { background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%) !important; }
        .shadow-premium { box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
        
        .page-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .path-badge {
            background: #f1f5f9;
            padding: 4px 12px;
            border-radius: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            border: 1px solid #e2e8f0;
        }

        .text-link { color: #cbd5e1; transition: 0.2s; }
        .text-link:hover { color: #4f46e5; }

        .transition-all { transition: all 0.3s ease; }
        .table-hover tbody tr:hover { background-color: #fcfdfe; }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .animate-hover { transition: transform 0.2s; }
        .animate-hover:hover { transform: scale(1.05); }
    </style>
@endsection
