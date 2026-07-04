<header class="bg-white md:bg-surface dark:bg-gray-900 shadow-sm sticky top-0 z-50 w-full border-b border-outline-variant/40 dark:border-gray-800">
    {{-- Mobile top bar --}}
    <div class="md:hidden flex items-center justify-between px-4 h-14">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <img src="{{ asset('logo.jpeg') }}" alt="GridSpace" class="w-8 h-8 rounded-md object-contain">
            <span class="font-manrope text-lg font-bold text-on-surface">GridSpace</span>
        </a>
        @auth
            <a href="{{ route('dashboard') }}" class="font-inter text-xs font-semibold text-primary-container">Dashboard</a>
        @else
            <a href="{{ route('register') }}" class="font-inter text-xs font-semibold bg-primary-container text-white px-3 py-1.5 rounded-full">Sign Up</a>
        @endauth
    </div>

    {{-- Desktop top bar --}}
    <div class="hidden md:flex justify-between items-center px-4 md:px-margin-desktop w-full max-w-container-max mx-auto h-20">
        <div class="flex items-center gap-4 md:gap-8 flex-1 min-w-0">
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                <img src="{{ asset('logo.jpeg') }}" alt="GridSpace" class="w-8 h-8 rounded-md object-contain">
                <span class="font-manrope text-xl font-bold text-on-surface dark:text-gray-100">GridSpace</span>
            </a>

            <nav class="hidden xl:flex items-center gap-5 shrink-0">
                <a href="{{ route('listings.index') }}" class="font-inter text-sm font-semibold whitespace-nowrap {{ request()->routeIs('listings.index', 'listings.show') ? 'text-primary-container border-b-2 border-primary-container pb-0.5' : 'text-secondary hover:text-primary' }} transition-colors">Find Space</a>
                <a href="{{ route('home') }}#how-it-works" class="font-inter text-sm font-semibold whitespace-nowrap text-secondary hover:text-primary transition-colors">How it Works</a>
                <a href="{{ route('blog.index') }}" class="font-inter text-sm font-semibold whitespace-nowrap {{ request()->routeIs('blog.*') ? 'text-primary-container border-b-2 border-primary-container pb-0.5' : 'text-secondary hover:text-primary' }} transition-colors">Blog</a>
                <a href="{{ route('invest.index') }}" class="font-inter text-sm font-semibold whitespace-nowrap {{ request()->routeIs('invest.*') ? 'text-primary-container border-b-2 border-primary-container pb-0.5' : 'text-secondary hover:text-primary' }} transition-colors">Investors</a>
            </nav>

            <form action="{{ route('listings.index') }}" method="GET" class="hidden lg:flex flex-1 max-w-sm relative min-w-0">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xl">search</span>
                <input name="search" value="{{ request('search') }}" class="w-full bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 border border-outline-variant rounded-xl pl-12 pr-4 py-2.5 focus:ring-2 focus:ring-primary-container focus:border-transparent outline-none transition-all font-inter text-sm" placeholder="Search workspaces..." type="search">
            </form>
        </div>

        <div class="flex items-center gap-2 md:gap-4 shrink-0">
            @auth
                <a href="{{ route('dashboard') }}" class="font-inter text-sm font-semibold text-secondary hover:text-primary transition-colors">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 pl-3 border-l border-outline-variant">
                    @php
                        $user = auth()->user();
                        $roleLabel = $user->isAdmin() ? 'Admin' : ($user->isHost() ? 'Host' : 'Guest');
                    @endphp
                    <div class="text-right hidden lg:block">
                        <p class="font-manrope text-sm font-bold leading-tight">{{ $user->display_name }}</p>
                        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide">{{ $roleLabel }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary-container ring-2 ring-white bg-primary-container flex items-center justify-center shrink-0">
                        @if($user->profile_photo_url)
                            <img class="w-full h-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->display_name }}">
                        @else
                            <span class="text-white font-manrope font-bold text-sm">{{ strtoupper(substr($user->firstname, 0, 1)) }}</span>
                        @endif
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="font-inter text-sm font-semibold text-secondary hover:text-primary transition-colors">Sign Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="font-inter text-sm font-semibold text-secondary hover:text-primary transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="font-inter text-sm font-semibold bg-primary-container text-white px-4 py-2 rounded-lg hover:bg-primary transition-colors">Sign Up</a>
            @endauth

            <button type="button" onclick="toggleMainNav()" class="xl:hidden p-2 rounded-lg text-on-surface hover:bg-surface-container transition-colors" aria-label="Menu">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </div>

    <div id="main-mobile-menu" class="hidden xl:hidden border-t border-outline-variant bg-surface px-4 py-4">
        <nav class="flex flex-col gap-1">
            <a href="{{ route('listings.index') }}" class="font-inter text-sm font-semibold text-on-surface py-2.5 px-2 rounded-lg hover:bg-surface-container">Find Space</a>
            <a href="{{ route('home') }}#how-it-works" class="font-inter text-sm font-semibold text-on-surface py-2.5 px-2 rounded-lg hover:bg-surface-container">How it Works</a>
            <a href="{{ route('blog.index') }}" class="font-inter text-sm font-semibold text-on-surface py-2.5 px-2 rounded-lg hover:bg-surface-container">Blog</a>
            <a href="{{ route('invest.index') }}" class="font-inter text-sm font-semibold text-on-surface py-2.5 px-2 rounded-lg hover:bg-surface-container">Investors</a>
            <a href="{{ route('featured') }}" class="font-inter text-sm font-semibold text-on-surface py-2.5 px-2 rounded-lg hover:bg-surface-container">Featured</a>
        </nav>
    </div>
</header>
