<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GridSpace | Dashboard')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'on-surface-variant': '#5b4038',
                        'surface-container-high': '#e6e8ea',
                        'surface-container-highest': '#e0e3e5',
                        'primary-container': '#ff5a1f',
                        secondary: '#49607e',
                        'surface-variant': '#e0e3e5',
                        'outline-variant': '#e4beb3',
                        'on-surface': '#191c1e',
                        'surface-container-lowest': '#ffffff',
                        surface: '#f7f9fb',
                        'surface-container': '#eceef0',
                        'surface-container-low': '#f2f4f6',
                        primary: '#ae3200',
                        outline: '#8f7067',
                        'primary-fixed': '#ffdbd0',
                    },
                    maxWidth: {
                        'container-max': '1280px',
                    },
                    spacing: {
                        'stack-sm': '12px',
                        'stack-lg': '48px',
                        'stack-md': '24px',
                        'margin-desktop': '48px',
                        'margin-mobile': '16px',
                        gutter: '24px',
                    },
                    fontFamily: {
                        manrope: ['Manrope', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                },
            },
        }
    </script>
    <style>
        body { background-color: #F8FAFC; font-family: Inter, sans-serif; }
        .card-lift {
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(10, 37, 64, 0.05);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    @stack('head')
</head>
<body class="text-on-surface selection:bg-primary-fixed min-h-screen flex flex-col">
    @php
        $user = auth()->user();
        $roleLabel = match (true) {
            $user->isAdmin() => 'Admin',
            $user->isHost() => 'Host',
            default => 'Guest',
        };
    @endphp

    <header class="bg-surface shadow-sm sticky top-0 z-50 w-full">
        <div class="flex justify-between items-center px-4 md:px-margin-desktop w-full max-w-container-max mx-auto h-20">
            <div class="flex items-center gap-6 md:gap-12 flex-1 min-w-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                    <div class="w-8 h-8 bg-primary-container rounded-sm flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-xl" style="font-variation-settings: 'FILL' 1;">grid_view</span>
                    </div>
                    <span class="font-manrope text-xl font-bold text-on-surface hidden sm:inline">GridSpace</span>
                </a>
                <form action="{{ route('listings.index') }}" method="GET" class="hidden md:flex flex-1 max-w-md relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline pointer-events-none">search</span>
                    <input
                        name="search"
                        value="{{ request('search') }}"
                        class="w-full bg-white border border-outline-variant rounded-xl pl-12 pr-12 py-2.5 focus:ring-2 focus:ring-primary-container focus:border-transparent outline-none transition-all font-inter text-sm"
                        placeholder="Search workspaces..."
                        type="search"
                    />
                </form>
            </div>

            <div class="flex items-center gap-4 md:gap-6 shrink-0">
                <button type="button" class="relative p-2 rounded-full bg-surface-container hover:bg-surface-variant transition-colors" aria-label="Notifications">
                    <span class="material-symbols-outlined text-on-surface-variant">notifications</span>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-primary rounded-full"></span>
                </button>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                    <div class="text-right hidden sm:block">
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
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-container-max mx-auto px-4 md:px-margin-desktop py-stack-lg w-full">
        @yield('content')
    </main>

    <footer class="bg-surface-container-highest border-t border-outline-variant mt-auto">
        <div class="flex flex-col md:flex-row justify-between items-center px-4 md:px-margin-desktop py-stack-md w-full max-w-container-max mx-auto">
            <div class="flex items-center gap-2 mb-4 md:mb-0">
                <span class="font-manrope text-xl font-extrabold text-on-surface">GridSpace</span>
                <span class="font-mono text-xs text-on-surface-variant ml-2">&copy; {{ date('Y') }} GridSpace. All rights reserved.</span>
            </div>
            <nav class="flex flex-wrap justify-center gap-6 md:gap-8">
                <a class="font-mono text-xs text-on-surface-variant hover:text-primary hover:underline transition-all" href="#">Privacy Policy</a>
                <a class="font-mono text-xs text-on-surface-variant hover:text-primary hover:underline transition-all" href="#">Terms of Service</a>
                <a class="font-mono text-xs text-on-surface-variant hover:text-primary hover:underline transition-all" href="{{ route('home') }}">Help Center</a>
                <a class="font-mono text-xs text-on-surface-variant hover:text-primary hover:underline transition-all" href="{{ route('home') }}">Contact Us</a>
            </nav>
        </div>
    </footer>

    @stack('scripts')
    <script>
        document.querySelectorAll('.card-lift').forEach(card => {
            card.addEventListener('mouseenter', () => {
                const icon = card.querySelector('.material-symbols-outlined');
                if (icon) icon.style.fontVariationSettings = "'FILL' 1";
            });
            card.addEventListener('mouseleave', () => {
                const icon = card.querySelector('.material-symbols-outlined');
                if (icon) icon.style.fontVariationSettings = "'FILL' 0";
            });
        });
    </script>
</body>
</html>
