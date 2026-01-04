@extends('admin.layouts.app')

@section('title', 'Permission Management')
@section('page-title', 'Permission Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <h3>Permissions</h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" id="toggleAllModules">
                            <i class="bi bi-arrows-expand me-1"></i>Toggle All
                        </button>
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Add Permission
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Quick Stats -->
                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <div class="card-icon primary">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="card-value">{{ $stats['totalPermissions'] }}</div>
                            <div class="card-title">Total Permissions</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <div class="card-icon success">
                                <i class="bi bi-folder"></i>
                            </div>
                            <div class="card-value">{{ count($permissions) }}</div>
                            <div class="card-title">Modules</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <div class="card-icon warning">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="card-value">{{ $stats['totalRoles'] }}</div>
                            <div class="card-title">Roles</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card">
                            <div class="card-icon info">
                                <i class="bi bi-link"></i>
                            </div>
                            <div class="card-value">{{ $stats['assignedPermissions'] }}</div>
                            <div class="card-title">Assigned</div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" id="permissionSearch" placeholder="Search permissions...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-end">
                            <select class="form-select-custom" id="moduleFilter" style="width: auto;">
                                <option value="">All Modules</option>
                                @foreach($permissions as $module => $modulePermissions)
                                    <option value="{{ $module }}">{{ ucfirst($module) }}</option>
                                @endforeach
                            </select>
                            <select class="form-select-custom" id="roleFilter" style="width: auto;">
                                <option value="">All Roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Permissions Accordion -->
                <div class="accordion" id="permissionsAccordion">
                    @foreach($permissions as $module => $modulePermissions)
                        <div class="accordion-item module-{{ $module }}" data-module="{{ $module }}">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#module{{ ucfirst($module) }}" aria-expanded="false">
                                    <div class="d-flex align-items-center w-100">
                                        <i class="bi bi-folder-fill me-3 text-primary"></i>
                                        <div class="flex-grow-1">
                                            <span class="fw-bold">{{ ucfirst($module) }} Permissions</span>
                                            <span class="badge bg-primary ms-2">{{ count($modulePermissions) }}</span>
                                        </div>
                                        <div class="text-muted small">
                                            {{ $modulePermissions->pluck('name')->implode(', ') }}
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="module{{ ucfirst($module) }}" class="accordion-collapse collapse"
                                data-bs-parent="#permissionsAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        @foreach($modulePermissions as $permission)
                                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3 permission-item"
                                                data-permission="{{ $permission->name }}"
                                                data-roles="{{ $permission->roles->pluck('name')->implode(',') }}">
                                                <div class="permission-card">
                                                    <div class="permission-header">
                                                        <h6 class="fw-bold mb-2 text-truncate" title="{{ $permission->name }}">
                                                            {{ $permission->name }}
                                                        </h6>
                                                        <div class="permission-actions">
                                                            <a href="{{ route('admin.permissions.edit', $permission) }}"
                                                                class="btn btn-sm btn-outline-primary" title="Edit Permission">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            @if($permission->roles->count() === 0)
                                                                <form action="{{ route('admin.permissions.destroy', $permission) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                        onclick="return confirm('Are you sure?')"
                                                                        title="Delete Permission">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Role Assignments -->
                                                    <div class="permission-roles mt-2">
                                                        @if($permission->roles->count() > 0)
                                                            <div class="assigned-roles">
                                                                <small class="text-muted d-block mb-1">Assigned to:</small>
                                                                <div class="role-badges">
                                                                    @foreach($permission->roles as $role)
                                                                        <span class="badge role-badge
                                                                                    @if($role->name == 'admin') bg-danger
                                                                                    @elseif($role->name == 'manager') bg-warning text-dark
                                                                                    @else bg-secondary @endif">
                                                                            {{ $role->name }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-muted small">
                                                                <i class="bi bi-info-circle me-1"></i>
                                                                Not assigned to any roles
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Quick Actions -->
                                                    <div class="permission-footer mt-3 pt-2 border-top">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">
                                                                {{ $permission->created_at->diffForHumans() }}
                                                            </small>
                                                            <div class="quick-actions">
                                                                <button class="btn btn-sm btn-outline-info quick-assign"
                                                                    data-bs-toggle="tooltip" title="Quick Assign"
                                                                    data-permission-id="{{ $permission->id }}"
                                                                    data-permission-name="{{ $permission->name }}">
                                                                    <i class="bi bi-shield-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Empty State -->
                @if(empty($permissions))
                    <div class="text-center py-5">
                        <i class="bi bi-shield-x display-1 text-muted"></i>
                        <h4 class="text-muted mt-3">No Permissions Found</h4>
                        <p class="text-muted">Get started by creating your first permission.</p>
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Create Permission
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Assign Modal -->
    <div class="modal fade" id="quickAssignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Quick Assign Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Assign "<span id="permissionName"></span>" to roles:</p>
                    <form id="quickAssignForm">
                        @csrf
                        <input type="hidden" id="assignPermissionId" name="permission_id">
                        <div class="role-checkboxes">
                            @foreach($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input role-checkbox" type="checkbox" name="roles[]"
                                        value="{{ $role->id }}" id="role{{ $role->id }}">
                                    <label class="form-check-label" for="role{{ $role->id }}">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveRoleAssignment">Save Assignment</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .permission-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 15px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .permission-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .permission-header {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .permission-header h6 {
            flex: 1;
            margin-right: 10px;
        }

        .permission-actions {
            display: flex;
            gap: 5px;
        }

        .role-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .role-badge {
            font-size: 0.7em;
            padding: 3px 6px;
        }

        .assigned-roles {
            margin-top: 8px;
        }

        .permission-footer {
            font-size: 0.8em;
        }

        .quick-actions .btn {
            padding: 2px 6px;
            font-size: 0.8em;
        }

        .accordion-button {
            padding: 1rem 1.25rem;
        }

        .accordion-button:not(.collapsed) {
            background-color: rgba(var(--primary-color-rgb), 0.1);
            color: var(--primary-color);
        }

        .module-stats {
            font-size: 0.9em;
        }

        /* Search and Filter Styles */
        #permissionSearch {
            width: 100%;
            padding: 8px 15px 8px 35px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            color: var(--text-color);
        }

        .form-select-custom {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            color: var(--text-color);
        }

        /* Empty state */
        .text-center.py-5 {
            padding: 3rem 0;
        }

        /* Dark mode support */
        [data-theme="dark"] .permission-card {
            background: rgba(255, 255, 255, 0.05);
        }

        [data-theme="dark"] .accordion-button:not(.collapsed) {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle All Modules
            const toggleAllBtn = document.getElementById('toggleAllModules');
            const accordionItems = document.querySelectorAll('.accordion-button');

            let allExpanded = false;

            toggleAllBtn.addEventListener('click', function () {
                allExpanded = !allExpanded;

                accordionItems.forEach(button => {
                    const target = document.querySelector(button.getAttribute('data-bs-target'));

                    if (allExpanded) {
                        button.classList.remove('collapsed');
                        target.classList.add('show');
                    } else {
                        button.classList.add('collapsed');
                        target.classList.remove('show');
                    }
                });

                toggleAllBtn.innerHTML = allExpanded ?
                    '<i class="bi bi-arrows-collapse me-1"></i>Collapse All' :
                    '<i class="bi bi-arrows-expand me-1"></i>Expand All';
            });

            // Search Functionality
            const searchInput = document.getElementById('permissionSearch');
            const permissionItems = document.querySelectorAll('.permission-item');

            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();

                permissionItems.forEach(item => {
                    const permissionName = item.getAttribute('data-permission').toLowerCase();
                    const matchesSearch = permissionName.includes(searchTerm);

                    if (matchesSearch) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Module Filter
            const moduleFilter = document.getElementById('moduleFilter');

            moduleFilter.addEventListener('change', function () {
                const selectedModule = this.value;

                document.querySelectorAll('.accordion-item').forEach(item => {
                    const module = item.getAttribute('data-module');

                    if (!selectedModule || module === selectedModule) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Role Filter
            const roleFilter = document.getElementById('roleFilter');

            roleFilter.addEventListener('change', function () {
                const selectedRole = this.value.toLowerCase();

                permissionItems.forEach(item => {
                    const roles = item.getAttribute('data-roles').toLowerCase();
                    const matchesRole = !selectedRole || roles.includes(selectedRole);

                    if (matchesRole) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Quick Assign Modal
            const quickAssignModal = new bootstrap.Modal(document.getElementById('quickAssignModal'));
            const quickAssignButtons = document.querySelectorAll('.quick-assign');

            quickAssignButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const permissionId = this.getAttribute('data-permission-id');
                    const permissionName = this.getAttribute('data-permission-name');

                    document.getElementById('assignPermissionId').value = permissionId;
                    document.getElementById('permissionName').textContent = permissionName;

                    quickAssignModal.show();
                });
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush