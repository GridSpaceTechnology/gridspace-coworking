@extends('layouts.app')

@section('title', 'Dashboard - Gridspace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->display_name }}!</h1>
        <p class="text-gray-600 mt-2">Discover your perfect workspace and book your next coworking space.</p>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <a href="{{ route('listings.index') }}" class="bg-white rounded-lg shadow p-4 md:p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-center mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-search text-white text-lg md:text-xl"></i>
                </div>
            </div>
            <h3 class="text-base md:text-lg font-semibold text-gray-900">Browse Spaces</h3>
            <p class="text-sm md:text-base text-gray-600 mt-2">Search for available coworking spaces in your area</p>
        </a>

        <a href="{{ route('bookings.index') }}" class="bg-white rounded-lg shadow p-4 md:p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-center mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-green-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar text-white text-lg md:text-xl"></i>
                </div>
            </div>
            <h3 class="text-base md:text-lg font-semibold text-gray-900">My Bookings</h3>
            <p class="text-sm md:text-base text-gray-600 mt-2">View and manage your current bookings</p>
        </a>

        <a href="{{ route('inquiries.index') }}" class="bg-white rounded-lg shadow p-4 md:p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-center mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-envelope text-white text-lg md:text-xl"></i>
                </div>
            </div>
            <h3 class="text-base md:text-lg font-semibold text-gray-900">My Inquiries</h3>
            <p class="text-sm md:text-base text-gray-600 mt-2">Track your workspace inquiries</p>
        </a>

        <a href="{{ route('profile.edit') }}" class="bg-white rounded-lg shadow p-4 md:p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-center mb-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-lg md:text-xl"></i>
                </div>
            </div>
            <h3 class="text-base md:text-lg font-semibold text-gray-900">My Profile</h3>
            <p class="text-sm md:text-base text-gray-600 mt-2">Update your personal information</p>
        </a>
    </div>

    <!-- Featured Spaces -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            <i class="fas fa-star text-yellow-500 mr-2"></i>
            Featured Spaces
        </h2>
        <p class="text-gray-600 mb-6 text-sm md:text-base">Discover premium coworking spaces with enhanced amenities and features.</p>

        @if($featuredListings->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                @foreach($featuredListings as $listing)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden">
                        @if($listing->images->count() > 0)
                            <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}"
                                 alt="{{ $listing->name }}"
                                 class="w-full h-32 md:h-48 object-cover">
                        @endif

                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-base md:text-lg font-semibold text-gray-900">{{ $listing->name }}</h3>
                                @if($listing->featured)
                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>Featured
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-600 text-sm md:text-base mb-2">{{ Str::limit($listing->description, 100) }}</p>

                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                                <div class="text-gray-500 text-sm md:text-base">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <span class="text-xs md:text-sm">{{ $listing->address }}</span>
                                </div>
                                <div class="text-blue-600 font-semibold text-sm md:text-base">
                                    {{ $listing->price_range }}
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-3 bg-gray-50">
                            <a href="{{ route('listings.show', $listing->slug) }}"
                               class="w-full bg-blue-600 text-white text-center py-2 md:py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm md:text-base">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 md:py-12 px-4 md:px-6">
                <i class="fas fa-star text-gray-300 text-3xl md:text-4xl mb-4"></i>
                <p class="text-gray-600 text-base md:text-lg">No featured spaces available at the moment.</p>
                <a href="{{ route('listings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-base md:text-lg">
                    Browse All Spaces
                </a>
            </div>
        @endif
    </div>

    <!-- How It Works -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 md:p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6 text-center">How Gridspace Works</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8">
            <div class="text-center">
                <div class="w-12 h-12 md:w-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl md:text-2xl font-bold text-blue-600">1</span>
                </div>
                <h3 class="text-base md:text-lg font-semibold text-gray-900">Browse</h3>
                <p class="text-sm md:text-base text-gray-600">Search for available coworking spaces in your area</p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 md:w-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl md:text-2xl font-bold text-green-600">2</span>
                </div>
                <h3 class="text-base md:text-lg font-semibold text-gray-900">Book</h3>
                <p class="text-sm md:text-base text-gray-600">Reserve your perfect workspace with easy booking</p>
            </div>

            <div class="text-center">
                <div class="w-12 h-12 md:w-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl md:text-2xl font-bold text-purple-600">3</span>
                </div>
                <h3 class="text-base md:text-lg font-semibold text-gray-900">Enjoy</h3>
                <p class="text-sm md:text-base text-gray-600">Experience your booked workspace</p>
            </div>
        </div>
    </div>
</div>
@endsection
