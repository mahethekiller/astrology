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
                @if(auth()->user()->isAstrologer())
                    <li>
                        <a href="{{ route('astrologer.dashboard') }}"
                            class="{{ request()->is('astrologer/dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i> <span>My Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('astrologer.revenue.index') }}"
                            class="{{ request()->is('astrologer/revenue*') ? 'active' : '' }}">
                            <i class="bi bi-briefcase"></i> <span>My Earnings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('astrologer.call.dashboard') }}"
                            class="{{ request()->is('astrologer/call/dashboard') ? 'active' : '' }}">
                            <i class="bi bi-telephone-fill"></i> <span>Call Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('astrologer.wallet.index') }}"
                            class="{{ request()->is('astrologer/wallet*') ? 'active' : '' }}">
                            <i class="bi bi-wallet2"></i> <span>My Wallet</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('astrologer.profile.edit') }}"
                            class="{{ request()->is('astrologer/profile/edit') ? 'active' : '' }}">
                            <i class="bi bi-person-gear"></i> <span>Profile Settings</span>
                        </a>
                    </li>
                @endif
            @endauth

            <!-- Common features accessible based on role -->
            @auth


                <!-- Admin Only Menu -->
                @if(auth()->user()->hasRole('astrologer'))



                @endif




            @endauth
        </ul>
    </div>
</div>