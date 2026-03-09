@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">
                <i class="fas fa-star text-yellow-500 mr-3"></i>
                Featured Spaces
            </h1>
            <p class="mt-2 text-gray-600">
                Premium workspaces with enhanced visibility and top amenities
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featuredListings as $listing)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Listing Image -->
                    <div class="relative h-48 bg-gray-200">
                        @if($listing->images->isNotEmpty())
                            <img src="{{ $listing->images->first()->image_path }}" 
                                 alt="{{ $listing->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                <i class="fas fa-building text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <!-- Featured Badge -->
                        <div class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                            <i class="fas fa-star mr-1"></i>Featured
                        </div>
                    </div>

                    <!-- Listing Details -->
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $listing->title }}</h3>
                            <span class="ml-auto text-lg font-bold text-blue-600">
                                ₦{{ number_format($listing->price, 0) }}
                                <span class="text-sm text-gray-500">/month</span>
                            </span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $listing->city->name }}, {{ $listing->city->country }}
                        </div>

                        <div class="flex items-center text-sm text-gray-600 mb-4">
                            <i class="fas fa-users mr-2"></i>
                            Capacity: {{ $listing->capacity }} people
                        </div>

                        <div class="flex items-center text-sm text-gray-600 mb-4">
                            <i class="fas fa-tag mr-2"></i>
                            {{ $listing->category->name }}
                        </div>

                        <!-- Contact Buttons -->
                        <div class="flex gap-3 mt-6">
                            <a href="{{ route('listings.show', $listing->slug) }}" 
                               class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                View Details
                            </a>
                            
                            <a href="tel:{{ $listing->phone }}" 
                               class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg text-center hover:bg-green-700 transition-colors">
                                <i class="fas fa-phone mr-2"></i>
                                Call Now
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-star text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Featured Spaces Yet</h3>
                    <p class="text-gray-500 mb-6">
                        Check back soon for premium workspaces in your area
                    </p>
                    <a href="{{ route('listings.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Browse All Spaces
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
