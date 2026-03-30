<x-guest-layout>
    <!-- Login Form Container -->
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md mx-4">
            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <!-- Logo on Left -->
                        <img src="{{ asset('logo.jpeg') }}"
                             alt="Gridspace Cowork"
                             class="h-12 w-40">

                        <!-- Welcome Text -->
                        <div class="text-right">
                            <h2 class="text-2xl font-bold text-white">
                                Welcome Back
                            </h2>
                            <p class="text-blue-100 mt-1">
                                Sign in to your Gridspace account
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="px-8 py-6">
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 4 4 0 00-8zm0 0v8m-4-4h8"/>
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    :value="old('email')"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Enter your email"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                >
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                >
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input
                                id="remember"
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Remember me
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-2">
                            @if (Route::has('password.request'))
                                <a
                                    class="text-sm text-blue-600 hover:text-blue-500 font-medium transition-colors"
                                    href="{{ route('password.request') }}">
                                    Forgot your password?
                                </a>
                            @endif

                            <button
                                type="submit"
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium py-3 px-6 rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105"
                            >
                                Sign In
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
                    <p class="text-center text-sm text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium transition-colors">
                            Create an account
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
