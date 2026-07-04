@php
    $user = auth()->user();
    $hideBottomNav = request()->routeIs([
        'admin.*',
        'analytics.*',
        'host.*',
        'dashboard',
        'listings.create',
        'listings.edit',
        'feature-requests.*',
        'onboarding.*',
        'wallet.*',
    ]);

    // Hosts use the host dashboard for their home; keep bottom nav off there.
    if ($hideBottomNav || ($user && $user->isAdmin())) {
        return;
    }

    $exploreActive = request()->routeIs('home');
    $searchActive = request()->routeIs('listings.index', 'listings.show', 'featured');
    $bookingsActive = request()->routeIs('bookings.*');
    $messagesActive = request()->routeIs('inquiries.*');
    $profileActive = request()->routeIs('profile.*', 'login', 'register');

    $bookingsUrl = $user ? route('bookings.index') : route('login');
    $messagesUrl = $user ? route('inquiries.index') : route('login');
    $profileUrl = $user ? route('profile.edit') : route('login');
@endphp

<nav class="md:hidden fixed bottom-0 inset-x-0 z-[70] bg-white border-t border-outline-variant/60 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] pb-[env(safe-area-inset-bottom)]"
     aria-label="Mobile navigation">
    <div class="grid grid-cols-5 h-16">
        <a href="{{ route('home') }}"
           class="flex flex-col items-center justify-center gap-0.5 {{ $exploreActive ? 'text-primary-container' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[24px] {{ $exploreActive ? 'filled' : '' }}" style="{{ $exploreActive ? 'font-variation-settings: \'FILL\' 1;' : '' }}">travel_explore</span>
            <span class="font-inter text-[10px] font-semibold">Explore</span>
        </a>

        <a href="{{ route('listings.index') }}"
           class="flex flex-col items-center justify-center gap-0.5 {{ $searchActive ? 'text-primary-container' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[24px]" style="{{ $searchActive ? 'font-variation-settings: \'FILL\' 1;' : '' }}">search</span>
            <span class="font-inter text-[10px] font-semibold">Search</span>
        </a>

        <a href="{{ $bookingsUrl }}"
           class="flex flex-col items-center justify-center gap-0.5 {{ $bookingsActive ? 'text-primary-container' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[24px]" style="{{ $bookingsActive ? 'font-variation-settings: \'FILL\' 1;' : '' }}">event_available</span>
            <span class="font-inter text-[10px] font-semibold">Bookings</span>
        </a>

        <a href="{{ $messagesUrl }}"
           class="flex flex-col items-center justify-center gap-0.5 {{ $messagesActive ? 'text-primary-container' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-[24px]" style="{{ $messagesActive ? 'font-variation-settings: \'FILL\' 1;' : '' }}">chat_bubble</span>
            <span class="font-inter text-[10px] font-semibold">Messages</span>
        </a>

        <a href="{{ $profileUrl }}"
           class="flex flex-col items-center justify-center gap-0.5 {{ $profileActive ? 'text-primary-container' : 'text-on-surface-variant' }}">
            @if($user?->profile_photo_url)
                <img src="{{ $user->profile_photo_url }}" alt=""
                     class="w-6 h-6 rounded-full object-cover {{ $profileActive ? 'ring-2 ring-primary-container' : '' }}">
            @elseif($user)
                <span class="w-6 h-6 rounded-full bg-primary-container text-white text-[11px] font-bold flex items-center justify-center">
                    {{ strtoupper(substr($user->firstname, 0, 1)) }}
                </span>
            @else
                <span class="material-symbols-outlined text-[24px]" style="{{ $profileActive ? 'font-variation-settings: \'FILL\' 1;' : '' }}">person</span>
            @endif
            <span class="font-inter text-[10px] font-semibold">Profile</span>
        </a>
    </div>
</nav>
