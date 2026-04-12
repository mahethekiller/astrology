<div class="sidebar">
    <div class="sidebar-header">
        <h3>
            <a href="{{ url('/') }}" class="text-decoration-none">
                <img src="{{ asset('frontend/images/logo.png') }}" alt="{{ config('app.name', 'Astroauraa') }}"
                    style="max-height: 45px; object-fit: contain;">
            </a>
        </h3>
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
                        <a href="{{ route('manager.dashboard') }}"
                            class="{{ request()->is('manager/dashboard') ? 'active' : '' }}">
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




                @endif

                <!-- Admin Only Menu -->
                @if(auth()->user()->isAdmin())


                    <li>
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.sliders.index') : route('manager.sliders.index') }}"
                            class="{{ request()->is('*/sliders*') ? 'active' : '' }}">
                            <i class="bi bi-sliders"></i> <span>Sliders</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.newsletters.index') }}"
                            class="{{ request()->is('admin/newsletters*') ? 'active' : '' }}">
                            <i class="bi bi-envelope"></i> <span>Newsletters</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.testimonials.index') }}"
                            class="{{ request()->is('admin/testimonials*') ? 'active' : '' }}">
                            <i class="bi bi-chat-quote"></i> <span>Testimonials</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.zodiac-signs.index') }}"
                            class="{{ request()->is('admin/zodiac-signs*') ? 'active' : '' }}">
                            <i class="bi bi-moon-stars"></i> <span>Zodiac Signs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pages.standard') }}"
                            class="{{ request()->routeIs('admin.pages.standard') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-lock"></i> <span>Standard Pages</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pages.index') }}"
                            class="{{ request()->routeIs('admin.pages.index') || (request()->routeIs('admin.pages.*') && !request()->routeIs('admin.pages.standard')) ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-plus"></i> <span>Custom Pages</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.menus.index') }}" class="{{ request()->is('admin/menus*') ? 'active' : '' }}">
                            <i class="bi bi-list-columns-reverse"></i> <span>Menu Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.seo.index') }}" class="{{ request()->is('admin/seo*') ? 'active' : '' }}">
                            <i class="bi bi-search"></i> <span>SEO Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.blogs.index') }}" class="{{ request()->is('admin/blogs*') ? 'active' : '' }}">
                            <i class="bi bi-journal-text"></i> <span>Blogs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.blog-categories.index') }}"
                            class="{{ request()->is('admin/blog-categories*') ? 'active' : '' }}">
                            <i class="bi bi-tags"></i> <span>Blog Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i> <span>User Management</span>
                        </a>
                    </li>




                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center dropdown-toggle
                                                                                                                                            {{ request()->is('admin/astrologer-profiles*') || request()->is('admin/languages*') || request()->is('admin/specializations*') ? '' : 'collapsed' }}"
                            href="#astrologerMenu" data-bs-toggle="collapse" data-bs-target="#astrologerMenu"
                            aria-expanded="{{ request()->is('admin/astrologer-profiles*') || request()->is('admin/languages*') || request()->is('admin/specializations*') ? 'true' : 'false' }}"
                            aria-controls="astrologerMenu">
                            <i class="bi bi-stars me-2"></i>
                            <span>Astrologer Management</span>
                        </a>

                        <ul id="astrologerMenu"
                            class="collapse list-unstyled ps-3
                                                                                                                                            {{ request()->is('admin/astrologer-profiles*') || request()->is('admin/languages*') || request()->is('admin/specializations*') ? 'show' : '' }}">
                            <li class="nav-item">
                                <a href="{{ route('admin.astrologer-profiles.index') }}"
                                    class="nav-link {{ request()->is('admin/astrologer-profiles*') ? 'active' : '' }}">
                                    <i class="bi bi-stars me-2"></i>
                                    Astrologers
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.languages.index') }}"
                                    class="nav-link {{ request()->is('admin/languages*') ? 'active' : '' }}">
                                    <i class="bi bi-translate me-2"></i>
                                    Languages
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.specializations.index') }}"
                                    class="nav-link {{ request()->is('admin/specializations*') ? 'active' : '' }}">
                                    <i class="bi bi-star me-2"></i>
                                    Specializations
                                </a>
                            </li>
                        </ul>
                    </li>


                    <li>
                        <a href="{{ route('admin.roles.index') }}" class="{{ request()->is('admin/roles*') ? 'active' : '' }}">
                            <i class="bi bi-shield-check"></i> <span>Role Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.permissions.index') }}"
                            class="{{ request()->is('admin/permissions*') ? 'active' : '' }}">
                            <i class="bi bi-key"></i> <span>Permission Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.index') }}"
                            class="{{ request()->is('admin/settings*') ? 'active' : '' }}">
                            <i class="bi bi-gear"></i> <span>Global Settings</span>
                        </a>
                    </li>



                    <li>
                        <a href="{{ route('admin.history.index') }}"
                            class="{{ request()->is('admin/history*') ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i> <span>Service History</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.revenue.index') }}"
                            class="{{ request()->is('admin/revenue*') ? 'active' : '' }}">
                            <i class="bi bi-currency-dollar"></i> <span>Revenue Report</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center dropdown-toggle
                                                                                                                                            {{ request()->is('admin/api-*') ? '' : 'collapsed' }}"
                            href="#apiMenu" data-bs-toggle="collapse" data-bs-target="#apiMenu"
                            aria-expanded="{{ request()->is('admin/api-*') ? 'true' : 'false' }}" aria-controls="apiMenu">
                            <i class="bi bi-code-slash me-2"></i>
                            <span>API Management</span>
                        </a>

                        <ul id="apiMenu"
                            class="collapse list-unstyled ps-3
                                                                                                                                            {{ request()->is('admin/api-*') ? 'show' : '' }}">
                            <li class="nav-item">
                                <a href="{{ route('admin.api-tokens.index') }}"
                                    class="nav-link {{ request()->is('admin/api-tokens*') ? 'active' : '' }}">
                                    <i class="bi bi-shield-lock me-2"></i>
                                    API Tokens
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.api-docs') }}"
                                    class="nav-link {{ request()->is('admin/api-docs*') ? 'active' : '' }}">
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    API Docs
                                </a>
                            </li>
                        </ul>
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