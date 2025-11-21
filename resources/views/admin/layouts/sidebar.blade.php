<div class="sidebar">
    <div class="sidebar-header">
        <h3><i class="bi bi-layout-text-window-reverse"></i> <span>{{ config('app.name', 'Laravel') }}</span></h3>
    </div>
    <div class="sidebar-menu">
        <ul>
            <!-- Role-specific dashboard links -->
            @auth
                @if(auth()->user()->isAdmin())
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> <span>Admin Dashboard</span>
                    </a>
                </li>
                @elseif(auth()->user()->isManager())
                <li>
                    <a href="{{ route('manager.dashboard') }}" class="{{ request()->is('manager/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> <span>Manager Dashboard</span>
                    </a>
                </li>
                @else
                <li>
                    <a href="{{ route('user.dashboard') }}" class="{{ request()->is('user/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> <span>My Dashboard</span>
                    </a>
                </li>
                @endif
            @endauth

            <!-- Common features accessible based on role -->
            @auth
                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <li>
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.components') : route('manager.components') }}"
                       class="{{ request()->is('*/components') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i> <span>Components</span>
                    </a>
                </li>
                <li>
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.tables') : route('manager.tables') }}"
                       class="{{ request()->is('*/tables') ? 'active' : '' }}">
                        <i class="bi bi-table"></i> <span>Tables</span>
                    </a>
                </li>



                @endif

                <!-- Admin Only Menu -->
                @if(auth()->user()->isAdmin())
                <li class="menu-section">
                    <small class="text-uppercase text-muted">Administration</small>
                </li>
                <li>
                    <a href="{{ route('admin.roles.index') }}" class="{{ request()->is('admin/roles*') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i> <span>Role Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.permissions.index') }}" class="{{ request()->is('admin/permissions*') ? 'active' : '' }}">
                        <i class="bi bi-key"></i> <span>Permission Management</span>
                    </a>
                </li>
                @endif

                <!-- Manager Only Menu -->
                @if(auth()->user()->isManager())
                <li class="menu-section">
                    <small class="text-uppercase text-muted">Management</small>
                </li>
                <li>
                    <a href="{{ route('manager.reports') }}" class="{{ request()->is('manager/reports') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i> <span>Reports</span>
                    </a>
                </li>
                @endif

                <!-- User Menu -->
                @if(auth()->user()->isUser())
                <li class="menu-section">
                    <small class="text-uppercase text-muted">Personal</small>
                </li>
                <li>
                    <a href="{{ route('user.settings') }}" class="{{ request()->is('user/settings') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i> <span>Settings</span>
                    </a>
                </li>
                @endif
            @endauth
        </ul>
    </div>
</div>
