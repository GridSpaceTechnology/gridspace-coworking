<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gridspace - Workspace Directory')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 hover:text-blue-800">
                        Gridspace
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        Home
                    </a>

                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}">
                                <img src="/logo.jpeg" alt="Gridspace Cowork" class="h-8 w-auto rounded">
                            </a>
                        </div>

                        <div class="hidden md:flex space-x-8">
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
                            @endauth

                            <!-- User Info Dropdown -->
                            @auth
                            <div class="relative group">
                                <button class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
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
                                    </div>

                                    <div class="border-t border-gray-100">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @else
                            <!-- Login/Register for guests -->
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                <i class="fas fa-user-plus mr-2"></i>Sign Up
                            </a>
                            @endauth
                        </div>
                    @endguest
                </div>
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
