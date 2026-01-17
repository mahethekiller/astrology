<div class="sidebar">
    <div class="sidebar-header">
        <h3><i class="bi bi-layout-text-window-reverse"></i> <span>{{ config('app.name', 'Laravel') }}</span></h3>
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
                        <a href="{{ route('astrologer.chat.history') }}"
                            class="{{ request()->is('astrologer/chat/history') ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i> <span>Chat History</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('astrologer.call.dashboard') }}"
                            class="{{ request()->is('astrologer/call/dashboard') ? 'active' : '' }}">
                            <i class="bi bi-telephone"></i> <span>Call Dashboard</span>
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