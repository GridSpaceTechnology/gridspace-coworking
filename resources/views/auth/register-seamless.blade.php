@extends('layouts.guest')

@section('title', 'Create Account - Gridspace')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto w-full space-y-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center">
                <img src="{{ asset('logo.jpeg') }}" alt="Gridspace Cowork" class="h-16 w-auto rounded-lg mr-3">
                <span class="text-2xl font-bold text-gray-900">Gridspace Cowork</span>
            </div>
            <p class="text-gray-600 mt-2">Join thousands of professionals finding their perfect workspace</p>
        </div>

        <!-- Progress Steps -->
        <div class="flex justify-center mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div id="step1" class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">1</div>
                    <div class="w-16 h-1 bg-gray-300"></div>
                </div>
                <div class="flex items-center">
                    <div id="step2" class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-semibold text-sm">2</div>
                    <div class="w-16 h-1 bg-gray-300"></div>
                </div>
                <div class="flex items-center">
                    <div id="step3" class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-semibold text-sm">3</div>
                    <div class="w-16 h-1 bg-gray-300"></div>
                </div>
                <div class="flex items-center">
                    <div id="step4" class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-semibold text-sm">4</div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="bg-white rounded-2xl shadow-xl p-8 space-y-6">
            @csrf

            <!-- Step 1: Account Type Selection -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <span class="inline-flex items-center">
                        <span class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-2">1</span>
                        Choose Your Account Type
                    </span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                        <input type="radio" id="role_guest" name="role" value="user" class="peer sr-only" {{ old('role') == 'user' ? 'checked' : '' }}>
                        <label for="role_guest" class="block p-6 border-2 rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:bg-blue-50 peer-checked:border-blue-500 transition-all">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-search text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">I'm looking for a workspace</div>
                                    <div class="text-sm text-gray-600">Search and book spaces for my needs</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="relative">
                        <input type="radio" id="role_host" name="role" value="host" class="peer sr-only" {{ old('role') == 'host' ? 'checked' : '' }}>
                        <label for="role_host" class="block p-6 border-2 rounded-lg cursor-pointer hover:bg-green-50 peer-checked:bg-green-50 peer-checked:border-green-500 transition-all">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-building text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">I want to list my workspace</div>
                                    <div class="text-sm text-gray-600">Share my space with others</div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Step 2: Basic Information (Always Required) -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <span class="inline-flex items-center">
                        <span class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-2" id="step2-indicator">2</span>
                        Basic Information
                    </span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="John">
                        @error('firstname')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Doe">
                        @error('lastname')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="john@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="+2348000000000">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Create a strong password">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Confirm your password">
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Step 3: Location Information (Conditional) -->
            <div id="location-section" class="space-y-4 hidden">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <span class="inline-flex items-center">
                        <span class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-2" id="step3-indicator">3</span>
                        Location Information
                    </span>
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="residence" class="block text-sm font-medium text-gray-700">Residence</label>
                        <select id="residence" name="residence" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select State</option>
                            <option value="lagos" {{ old('residence') == 'lagos' ? 'selected' : '' }}>Lagos</option>
                            <option value="abuja" {{ old('residence') == 'abuja' ? 'selected' : '' }}>Abuja</option>
                            <option value="port-harcourt" {{ old('residence') == 'port-harcourt' ? 'selected' : '' }}>Port Harcourt</option>
                            <option value="kano" {{ old('residence') == 'kano' ? 'selected' : '' }}>Kano</option>
                            <option value="ibadan" {{ old('residence') == 'ibadan' ? 'selected' : '' }}>Ibadan</option>
                        </select>
                        @error('residence')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="lga-section" class="hidden">
                        <label for="local_government_area" class="block text-sm font-medium text-gray-700">Local Government Area</label>
                        <input type="text" id="local_government_area" name="local_government_area" value="{{ old('local_government_area') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., Ikeja">
                        @error('local_government_area')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 4: Host Information (Conditional for Hosts) -->
            <div id="host-section" class="space-y-4 hidden">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <span class="inline-flex items-center">
                        <span class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-2" id="step4-indicator">4</span>
                        Host Information
                    </span>
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name (Optional)</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Your company name">
                        @error('company_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="business_description" class="block text-sm font-medium text-gray-700">Business Description</label>
                        <textarea id="business_description" name="business_description" rows="3" value="{{ old('business_description') }}"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Tell us about your coworking space business...">{{ old('business_description') }}</textarea>
                        @error('business_description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Terms and Submit -->
            <div class="space-y-4">
                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        I agree to the <a href="#" class="text-blue-600 hover:text-blue-700">Terms and Conditions</a> and 
                        <a href="#" class="text-blue-600 hover:text-blue-700">Privacy Policy</a>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                        {{ __('Already have an account?') }}
                    </a>
                    <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:text-blue-700 underline">
                        <i class="fas fa-home mr-1"></i>{{ __('Back to Home') }}
                    </a>
                    
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>
                        {{ __('Create Account') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleGuest = document.getElementById('role_guest');
    const roleHost = document.getElementById('role_host');
    const locationSection = document.getElementById('location-section');
    const hostSection = document.getElementById('host-section');
    const step2Indicator = document.getElementById('step2-indicator');
    const step3Indicator = document.getElementById('step3-indicator');
    const step4Indicator = document.getElementById('step4-indicator');
    const lgaSection = document.getElementById('lga-section');

    function updateFormDisplay() {
        if (roleHost.checked) {
            // Host selected - show all sections
            locationSection.classList.remove('hidden');
            hostSection.classList.remove('hidden');
            lgaSection.classList.remove('hidden');
            step2Indicator.classList.remove('bg-blue-600');
            step2Indicator.classList.add('bg-green-600');
            step3Indicator.classList.remove('bg-blue-600');
            step3Indicator.classList.add('bg-green-600');
            step4Indicator.classList.remove('bg-gray-300');
            step4Indicator.classList.add('bg-green-600');
        } else {
            // Guest selected - show only basic info, hide advanced sections
            locationSection.classList.add('hidden');
            hostSection.classList.add('hidden');
            lgaSection.classList.add('hidden');
            step2Indicator.classList.add('bg-blue-600');
            step2Indicator.classList.remove('bg-green-600');
            step3Indicator.classList.remove('bg-blue-600');
            step3Indicator.classList.remove('bg-green-600');
            step4Indicator.classList.add('bg-gray-300');
        }
    }

    roleGuest.addEventListener('change', updateFormDisplay);
    roleHost.addEventListener('change', updateFormDisplay);

    // Initialize display based on default selection
    updateFormDisplay();
});
</script>
@endsection
