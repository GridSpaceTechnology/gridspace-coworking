@extends('layouts.minimal')

@section('title', 'Create Account - Gridspace')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ __('There were some problems with your input:') }}
                        </p>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center items-center mb-6">
                <div class="flex items-center">
                    <img src="{{ asset('logo.jpeg') }}" alt="Gridspace Cowork" class="h-16 w-auto rounded-lg mr-3">
                    <span class="text-2xl font-bold text-gray-900">Gridspace Cowork</span>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Side - Welcome Message -->
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-8 lg:p-12 text-white">
                    <div class="space-y-6">
                        <div class="flex items-center mb-6">
                            <i class="fas fa-cube text-4xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold mb-2">Welcome to Gridspace</h2>
                            <p class="text-blue-100">Join thousands of professionals who've already found their perfect workspace</p>
                        </div>

                        <!-- Benefits -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-300 mr-3"></i>
                                <span class="text-sm">Verified workspace providers</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-green-300 mr-3"></i>
                                <span class="text-sm">Secure and trusted platform</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-green-300 mr-3"></i>
                                <span class="text-sm">24/7 customer support</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Registration Form -->
                <div class="p-8 lg:p-12">
                    <div class="space-y-6">
                        <!-- Form Header -->
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Create Your Account</h2>
                            <p class="text-gray-600">Join our community of workspace professionals</p>
                        </div>

                        <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
                            @csrf

                            <!-- Name Section -->
                            <div class="bg-white p-6 rounded-lg shadow space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    <i class="fas fa-user mr-2 text-blue-600"></i>
                                    Personal Information
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                                        <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required autofocus autocomplete="given-name"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                               placeholder="John">
                                        @error('firstname')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                                        <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required autocomplete="family-name"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                               placeholder="Doe">
                                        @error('lastname')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                               placeholder="john@example.com">
                                        @error('email')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                               placeholder="+2348000000000">
                                        @error('phone')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                        <input type="text" id="location" name="location" value="{{ old('location') }}" required
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                               placeholder="Lagos, Nigeria">
                                        @error('location')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="role" class="block text-sm font-medium text-gray-700">Account Type</label>
                                        <select id="role" name="role" required
                                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <option value="">Select Account Type</option>
                                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>I'm looking for a workspace</option>
                                            <option value="host" {{ old('role') == 'host' ? 'selected' : '' }}>I want to list my workspace</option>
                                        </select>
                                        @error('role')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                            <!-- Password Section -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    <i class="fas fa-lock mr-2 text-blue-600"></i>
                                    Security
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                        <input type="password" id="password" name="password" required autocomplete="new-password"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                               placeholder="••••••••">
                                        @error('password')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                               placeholder="•••••••">
                                        @error('password_confirmation')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Space Before Submit Button -->
                            <div class="h-6"></div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-between">
                                <div class="space-x-4">
                                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-500 underline">
                                        {{ __('Already have an account?') }}
                                    </a>
                                    <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:text-blue-500 underline">
                                        <i class="fas fa-home mr-1"></i>{{ __('Back to Home') }}
                                    </a>
                                </div>
                                <button type="submit" class="w-full md:w-auto bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    {{ __('Create Account') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Add some interactivity
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus first empty field
        const firstEmpty = document.querySelector('input:placeholder');
        if (firstEmpty) {
            firstEmpty.focus();
        }

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    });
</script>
@endsection
