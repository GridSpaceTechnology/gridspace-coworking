@php
    $user = auth()->user();
    $roleLabel = $user->isAdmin() ? 'Admin' : ($user->isHost() ? 'Host' : 'Guest');
@endphp

<header class="bg-black sticky top-0 z-50 w-full">
    <div class="flex items-center justify-between gap-3 md:gap-6 px-4 md:px-margin-desktop w-full max-w-container-max mx-auto h-16 md:h-[72px]">
        {{-- Brand --}}
        <a href="{{ auth()->user()->defaultHomeUrl() }}" class="flex items-center gap-2.5 shrink-0">
            <div class="w-9 h-9 bg-white rounded-md flex items-center justify-center p-1 shrink-0">
                <img src="{{ asset('logo.jpeg') }}" alt="" class="w-full h-full object-contain">
            </div>
            <span class="font-manrope text-xl md:text-2xl font-extrabold text-primary-container tracking-tight">GridSpace</span>
        </a>

        {{-- Search (center) --}}
        <form action="{{ route('listings.index') }}" method="GET" class="hidden sm:flex flex-1 max-w-xl mx-auto relative items-center">
            <span class="material-symbols-outlined absolute left-4 text-gray-400 text-xl pointer-events-none">search</span>
            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="search..."
                class="w-full bg-white text-gray-800 placeholder:text-gray-400 placeholder:lowercase rounded-full pl-11 pr-11 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/50 transition-shadow"
            >
            <a href="{{ route('listings.index') }}" class="absolute right-4 text-gray-400 hover:text-gray-600 transition-colors" title="Filters">
                <span class="material-symbols-outlined text-xl">tune</span>
            </a>
        </form>

        {{-- Right: notifications + profile --}}
        <div class="flex items-center gap-2 md:gap-4 shrink-0">
            <button type="button" class="hidden sm:flex w-10 h-10 items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors" aria-label="Notifications">
                <span class="material-symbols-outlined text-gray-900 dark:text-gray-100 text-[22px]">notifications</span>
            </button>

            <div class="relative" id="profile-menu-wrap">
                <button type="button"
                        id="profile-menu-btn"
                        class="flex items-center gap-2.5 md:gap-3 rounded-full md:rounded-lg md:pr-2 md:py-1 hover:bg-white/10 transition-colors"
                        aria-expanded="false"
                        aria-haspopup="true"
                        aria-controls="profile-menu-dropdown">
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-full overflow-hidden ring-2 ring-white/20 bg-primary-container flex items-center justify-center shrink-0">
                        @if($user->profile_photo_url)
                            <img class="w-full h-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->display_name }}">
                        @else
                            <span class="text-white font-manrope font-bold text-sm">{{ strtoupper(substr($user->firstname, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="hidden md:block text-left leading-tight">
                        <p class="font-manrope text-sm font-bold text-white">{{ $user->display_name }}</p>
                        <p class="font-inter text-xs text-gray-400">{{ $roleLabel }}</p>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 text-[20px] hidden md:block" id="profile-menu-chevron">expand_more</span>
                </button>

                <div id="profile-menu-dropdown"
                     class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-xl shadow-xl border border-outline-variant/40 py-1.5 z-[60]"
                     role="menu"
                     aria-labelledby="profile-menu-btn">
                    <div class="px-4 py-2.5 border-b border-outline-variant/30 md:hidden">
                        <p class="font-manrope text-sm font-bold text-[#1c2c40]">{{ $user->display_name }}</p>
                        <p class="font-inter text-xs text-on-surface-variant">{{ $roleLabel }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-2.5 px-4 py-2.5 font-inter text-sm text-on-surface hover:bg-surface-container-low transition-colors"
                       role="menuitem">
                        <span class="material-symbols-outlined text-[20px] text-on-surface-variant">person</span>
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" role="none">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2.5 px-4 py-2.5 font-inter text-sm text-red-600 hover:bg-red-50 transition-colors text-left"
                                role="menuitem">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <button type="button" onclick="toggleMainNav()" class="sm:hidden w-10 h-10 flex items-center justify-center rounded-full bg-gray-800 text-white" aria-label="Menu">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </div>

    {{-- Mobile search --}}
    <form action="{{ route('listings.index') }}" method="GET" class="sm:hidden px-4 pb-3">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="search..."
                class="w-full bg-white rounded-full pl-11 pr-4 py-2.5 font-inter text-sm outline-none"
            >
        </div>
    </form>

    {{-- Mobile / overflow nav --}}
    <div id="main-mobile-menu" class="hidden border-t border-white/10 bg-black px-4 py-4">
        <nav class="flex flex-col gap-1">
            <a href="{{ auth()->user()->defaultHomeUrl() }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Overview</a>
            <a href="{{ route('listings.index') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Find Space</a>

            @if($user->isHost())
                <a href="{{ route('dashboard') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">My Listings</a>
                <a href="{{ route('host.calendar') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Calendar</a>
                <a href="{{ route('host.earnings') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Earnings</a>
                <a href="{{ route('dashboard', ['add_listing' => 1]) }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Add Listing</a>
            @else
                <a href="{{ route('bookings.index') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">My Bookings</a>
                <a href="{{ route('wallet.index') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Wallet</a>
            @endif

            <a href="{{ route('inquiries.index') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Messages</a>
            <a href="{{ route('profile.edit') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">My Profile</a>

            @if($user->isAdmin())
                <a href="{{ route('admin.index') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Admin Dashboard</a>
                <a href="{{ route('admin.listings.index') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Listings</a>
                <a href="{{ route('admin.users.index') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Users</a>
                <a href="{{ route('admin.bookings.index') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Bookings</a>
                <a href="{{ route('admin.blog.index') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Blog</a>
                <a href="{{ route('analytics.index') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Analytics</a>
            @endif

            <a href="{{ route('home') }}" class="font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">View Site</a>

            <form method="POST" action="{{ route('logout') }}" class="pt-2 mt-2 border-t border-white/10">
                @csrf
                <button type="submit" class="w-full text-left font-inter text-sm font-medium text-gray-300 py-2.5 px-3 rounded-lg hover:bg-white/10 hover:text-white transition-colors">Sign Out</button>
            </form>
        </nav>
    </div>
</header>

<script>
(function () {
    const wrap = document.getElementById('profile-menu-wrap');
    const btn = document.getElementById('profile-menu-btn');
    const menu = document.getElementById('profile-menu-dropdown');
    const chevron = document.getElementById('profile-menu-chevron');
    if (!wrap || !btn || !menu) return;

    const setOpen = (open) => {
        menu.classList.toggle('hidden', !open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (chevron) chevron.textContent = open ? 'expand_less' : 'expand_more';
    };

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        setOpen(menu.classList.contains('hidden'));
    });

    document.addEventListener('click', (e) => {
        if (!wrap.contains(e.target)) setOpen(false);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') setOpen(false);
    });
})();
</script>
