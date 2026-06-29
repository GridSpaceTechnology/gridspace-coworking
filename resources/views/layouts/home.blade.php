<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'GridSpace - Find a flexible workspace near you')</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ff5a1f',
                        navy: '#0A2540',
                        surface: '#f7f9fb',
                        'surface-container': '#f2f4f6',
                    },
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                    },
                    borderRadius: {
                        'grid': '4px',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            color: #1a1a1a;
        }
        .gradient-overlay {
            background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%);
        }
    </style>
    @stack('head')
</head>
<body class="bg-white">
    <header class="sticky top-0 z-50 bg-white border-b border-gray-100">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary rounded-sm flex items-center justify-center">
                        <div class="w-4 h-4 bg-white rounded-full"></div>
                    </div>
                    <span class="text-2xl font-extrabold text-navy tracking-tight">GridSpace</span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="#how-it-works" class="text-sm font-semibold text-gray-700 hover:text-primary transition">How it works</a>
                <a href="#featured-workspaces" class="text-sm font-semibold text-gray-700 hover:text-primary transition">Workspaces</a>
                <a href="#testimonials" class="text-sm font-semibold text-gray-700 hover:text-primary transition">Stories</a>
            </div>

            <div class="flex items-center space-x-2 sm:space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden sm:inline text-sm font-bold text-navy px-4 py-2 hover:bg-gray-50 rounded-grid border border-navy">Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="hidden sm:inline text-sm font-bold text-navy px-4 py-2 hover:bg-gray-50 rounded-grid border border-navy">Become a Host</a>
                @endauth
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-bold bg-primary text-white px-4 sm:px-6 py-2 rounded-grid hover:bg-orange-600 transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="text-sm font-bold bg-primary text-white px-4 sm:px-6 py-2 rounded-grid hover:bg-orange-600 transition">Sign Up</a>
                @endauth
                <button type="button" onclick="toggleMobileMenu()" class="md:hidden p-2 text-navy" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </nav>

        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white px-4 py-4 space-y-2">
            <a href="#how-it-works" class="block text-sm font-semibold text-gray-700 py-2">How it works</a>
            <a href="#featured-workspaces" class="block text-sm font-semibold text-gray-700 py-2">Workspaces</a>
            <a href="#testimonials" class="block text-sm font-semibold text-gray-700 py-2">Stories</a>
            @guest
                <a href="{{ route('login') }}" class="block text-sm font-semibold text-gray-700 py-2">Login</a>
                <a href="{{ route('register') }}" class="block text-sm font-semibold text-primary py-2">Become a Host</a>
            @endguest
            @auth
                <a href="{{ route('dashboard') }}" class="block text-sm font-semibold text-gray-700 py-2">Dashboard</a>
            @endauth
        </div>
    </header>

    @if(session('success'))
        <div class="bg-green-50 border-b border-green-200 text-green-800 px-4 py-3 text-sm text-center">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-b border-red-200 text-red-800 px-4 py-3 text-sm text-center">{{ session('error') }}</div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="bg-navy text-white pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12 mb-16">
                <div class="col-span-2 lg:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 bg-primary rounded-sm flex items-center justify-center">
                            <div class="w-4 h-4 bg-white rounded-full"></div>
                        </div>
                        <span class="text-2xl font-extrabold tracking-tight">GridSpace</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-sm">
                        Providing professionals with flexible, vetted workspaces across Nigeria.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold mb-6">Company</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white">About Us</a></li>
                        <li><a href="#testimonials" class="hover:text-white">Read stories</a></li>
                        <li><a href="#how-it-works" class="hover:text-white">How it works</a></li>
                        <li><a href="{{ route('featured') }}" class="hover:text-white">Featured</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">Support</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white">Help Centre</a></li>
                        <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white">Safety</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6">Partners &amp; Opportunities</h4>
                    <p class="text-sm text-gray-400 mb-6 leading-relaxed">Want to host or collaborate? We're open to opportunities.</p>
                    <a href="{{ route('register') }}" class="inline-flex bg-primary text-white font-bold px-6 py-2 rounded-grid text-sm hover:bg-orange-600 transition items-center gap-2">
                        Contact us
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </a>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500">
                <p>Copyright &copy; {{ date('Y') }} GridSpace. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white">Privacy Policy</a>
                    <a href="#" class="hover:text-white">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
