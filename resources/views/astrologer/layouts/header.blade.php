<div class="header">
    <div class="header-left">
        <button class="mobile-menu-btn">
            <i class="bi bi-list"></i>
        </button>
        <h1>@yield('page-title', 'Dashboard')</h1>
    </div>
    <div class="header-right">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search...">
        </div>
        <button class="theme-toggle" id="themeToggle">
            <i class="bi bi-moon"></i>
        </button>
        <div class="user-profile dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4361ee&color=fff" alt="User">
                <div>
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <small class="text-muted">
                        @auth
                            {{ auth()->user()->getRoleNames()->first() ?? 'User' }}
                        @else
                            Guest
                        @endauth
                    </small>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
