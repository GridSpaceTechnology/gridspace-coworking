<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gridspace Cowork - Workspace Directory')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Mobile Menu Toggle -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>

    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo with Brand Name -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="/logo.jpeg" alt="Gridspace Cowork" class="h-8 w-auto rounded mr-2 sm:mr-3">
                        <span class="text-lg sm:text-xl font-bold text-gray-900">Gridspace</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-4 lg:space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        Home
                    </a>

                    <!-- Search for all users -->
                    <a href="{{ route('listings.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-search mr-2"></i>Find Spaces
                    </a>

                    <!-- Featured Spaces -->
                    <a href="{{ route('featured') }}" class="text-gray-700 hover:text-yellow-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-star mr-2"></i>Featured
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>

                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.index') }}" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-cog mr-2"></i>Admin
                            </a>
                            <a href="{{ route('analytics.index') }}" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-chart-bar mr-2"></i>Analytics
                            </a>
                        @endif
                    @else
                        <!-- Login/Register for guests -->
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                            <i class="fas fa-user-plus mr-2"></i>Sign Up
                        </a>
                    @endauth

                    <!-- User Info Dropdown (Desktop) -->
                    @auth
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium whitespace-nowrap">
                            <i class="fas fa-user-circle mr-2"></i>
                            {{ auth()->user()->display_name }}
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->display_name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                <p class="text-xs text-blue-600 font-medium mt-1">
                                    @if(auth()->user()->isAdmin())
                                        Administrator
                                    @elseif(auth()->user()->isHost())
                                        Host
                                    @else
                                        User
                                    @endif
                                </p>

                                <div class="border-t border-gray-100 mt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-blue-600 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path id="hamburger-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu panel -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <!-- Mobile Navigation Links -->
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                    <i class="fas fa-home mr-3"></i>Home
                </a>

                <a href="{{ route('listings.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                    <i class="fas fa-search mr-3"></i>Find Spaces
                </a>

                <a href="{{ route('featured') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-yellow-600 hover:bg-gray-50">
                    <i class="fas fa-star mr-3"></i>Featured
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-gray-50">
                            <i class="fas fa-cog mr-3"></i>Admin
                        </a>
                        <a href="{{ route('analytics.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-purple-600 hover:bg-gray-50">
                            <i class="fas fa-chart-bar mr-3"></i>Analytics
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                        <i class="fas fa-sign-in-alt mr-3"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-blue-600 text-white hover:bg-blue-700">
                        <i class="fas fa-user-plus mr-3"></i>Sign Up
                    </a>
                @endauth

                <!-- Mobile User Section -->
                @auth
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="px-3 py-2">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-circle text-2xl text-gray-400"></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800">{{ auth()->user()->display_name }}</div>
                                <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                                <div class="text-xs text-blue-600 font-medium mt-1">
                                    @if(auth()->user()->isAdmin())
                                        Administrator
                                    @elseif(auth()->user()->isHost())
                                        Host
                                    @else
                                        User
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 px-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-3"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Gridspace. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
