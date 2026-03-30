@extends('layouts.app')

@section('title', $listing->name . ' - Gridspace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li>
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </li>
            <li>
                <span class="text-gray-500">/</span>
            </li>
            <li>
                <span class="text-gray-900 font-medium">{{ $listing->name }}</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Image Gallery -->
            @if($listing->images->count() > 0)
                <div class="mb-6">
                    <div class="relative h-96 bg-gray-200 rounded-lg overflow-hidden">
                        @foreach($listing->images as $index => $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                 alt="{{ $listing->name }} - Image {{ $index + 1 }}"
                                 class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                                 data-index="{{ $index }}">
                        @endforeach
                    </div>

                    <!-- Image Thumbnails -->
                    @if($listing->images->count() > 1)
                        <div class="flex space-x-2 mt-4">
                            @foreach($listing->images as $index => $image)
                                <button onclick="showImage({{ $index }})"
                                        class="w-20 h-20 rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-blue-500' : 'border-gray-300 hover:border-blue-500' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         alt="{{ $listing->name }} - Thumbnail {{ $index + 1 }}"
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- Listing Details -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $listing->name }}</h1>
                        @if($listing->featured)
                            <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i>Featured
                            </span>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-700 rounded-full px-6 py-3 shadow-lg">
                            <div class="flex items-center">
                                <span class="text-white text-3xl font-bold">₦</span>
                                <span class="text-white text-3xl font-bold">{{ number_format($listing->price, 0) }}</span>
                                <span class="text-blue-100 text-sm font-medium ml-1">/{{ $listing->price_period ?? 'night' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category and Location -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-tag mr-2"></i>
                        <span>{{ $listing->category->name }}</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span>{{ $listing->address }}</span>
                        @if($listing->city)
                            <span>, {{ $listing->city->name }}</span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">About This Space</h3>
                    <div class="prose prose-gray max-w-none">
                        {!! nl2br($listing->description) !!}
                    </div>
                </div>

                <!-- Detailed Amenities Section -->
                @if($listing->amenities->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>Available Amenities
                        </h3>
                        <div class="bg-gradient-to-r from-blue-50 to-green-50 border border-blue-200 rounded-xl p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($listing->amenities as $amenity)
                                    <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                                        <div class="flex items-start">
                                            @if($amenity->icon)
                                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-{{ $amenity->icon }} text-blue-600"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 mb-1">{{ $amenity->name }}</div>
                                                @if($amenity->description)
                                                    <div class="text-sm text-gray-600">{{ $amenity->description }}</div>
                                                @endif
                                                <div class="text-xs text-green-600 mt-1">
                                                    <i class="fas fa-check-circle mr-1"></i>Available
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                                    All amenities are maintained to the highest standards
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Space Features -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-home text-blue-600 mr-2"></i>Space Features
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($listing->capacity)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center text-blue-700">
                                    <i class="fas fa-users text-2xl mr-3"></i>
                                    <div>
                                        <div class="font-semibold">Capacity</div>
                                        <div class="text-2xl font-bold">{{ $listing->capacity }} people</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center text-green-700">
                                <i class="fas fa-wifi text-2xl mr-3"></i>
                                <div>
                                    <div class="font-semibold">Internet</div>
                                    <div class="text-sm">High-speed WiFi included</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-center text-purple-700">
                                <i class="fas fa-clock text-2xl mr-3"></i>
                                <div>
                                    <div class="font-semibold">Access</div>
                                    <div class="text-sm">24/7 Access Available</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center text-yellow-700">
                                <i class="fas fa-parking text-2xl mr-3"></i>
                                <div>
                                    <div class="font-semibold">Parking</div>
                                    <div class="text-sm">Free parking available</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Actions -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('track', ['listing' => $listing->id, 'type' => 'phone']) }}"
                       class="flex-1 bg-green-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-green-700 text-center">
                        <i class="fas fa-phone mr-2"></i>Call Now
                    </a>
                    <a href="{{ route('track', ['listing' => $listing->id, 'type' => 'whatsapp']) }}"
                       class="flex-1 bg-green-500 text-white px-4 py-3 rounded-lg font-medium hover:bg-green-600 text-center">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                    </a>
                    @if($listing->website)
                        <a href="{{ $listing->website }}"
                           target="_blank"
                           class="flex-1 bg-gray-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-gray-700 text-center">
                            <i class="fas fa-globe mr-2"></i>Visit Website
                        </a>
                    @endif
                </div>
            </div>

            <!-- Inquiry Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Make an Inquiry</h3>
                <p class="text-gray-600 mb-6">Interested in this space? Send a message and the workspace provider will get back to you.</p>

                <form method="POST" action="{{ route('inquiries.store') }}">
                    @csrf
                    <input type="hidden" name="listing_id" value="{{ $listing->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Name *</label>
                            <input type="text"
                                   name="name"
                                   value="{{ auth()->check() ? auth()->user()->full_name : old('name') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email"
                                   name="email"
                                   value="{{ auth()->check() ? auth()->user()->email : old('email') }}"
                                   {{ auth()->check() ? 'readonly' : '' }}
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 {{ auth()->check() ? 'bg-gray-100' : '' }}">
                            @if(auth()->check())
                                <p class="text-xs text-gray-500 mt-1">Using your registered email</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                        <input type="tel"
                               name="phone"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea name="message"
                                  rows="4"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Tell us about your requirements..."></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="newsletter_opt_in" value="1" class="mr-2">
                            <span class="text-sm text-gray-700">Send me updates about similar spaces</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-blue-700">
                        Send Inquiry
                    </button>
                </form>
            </div>
        </div>

        <!-- Booking Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-calendar-check mr-2"></i>Book This Space
            </h3>

            @if($listing->available)
                <a href="{{ route('bookings.create', $listing->slug) }}"
                   class="group relative w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-xl font-semibold hover:from-green-700 hover:to-green-800 text-center block transition-all duration-300 transform hover:scale-105 shadow-xl">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-calendar-check mr-3 text-lg"></i>
                        <span class="text-lg">Book Now</span>
                        <div class="absolute -top-2 -right-2 bg-yellow-400 text-gray-900 text-xs font-bold px-2 py-1 rounded-full">
                            ₦{{ number_format($listing->price, 0) }}/{{ $listing->price_period ?? 'night' }}
                        </div>
                    </div>
                </a>
            @else
                <div class="w-full bg-gray-400 text-gray-200 px-4 py-3 rounded-lg text-center block">
                    <i class="fas fa-calendar-times mr-2"></i>
                    Currently Unavailable
                </div>
            @endif

            <!-- Feature Request Section (only for listing owner) -->
            @if(auth()->check() && auth()->id() === $listing->user_id && !$listing->featured)
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h4 class="text-md font-semibold text-gray-900 mb-3">
                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                        Make Your Listing Featured
                    </h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Get more visibility and bookings by featuring your listing at the top of search results.
                    </p>
                    <a href="{{ route('feature-requests.create', $listing) }}"
                       class="w-full bg-yellow-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-yellow-700 text-center block transition-colors">
                        <i class="fas fa-star mr-2"></i>
                        Request Featured Listing
                    </a>
                </div>
            @endif

            <!-- Host Information -->
            <div class="mt-6 border-t pt-6">
                <h4 class="text-md font-semibold text-gray-900 mb-4">Host Information</h4>
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-xl text-gray-500"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-gray-900">{{ $listing->user->display_name }}</h5>
                        <p class="text-sm text-gray-600">{{ $listing->user->role }} • {{ $listing->user->residence }}</p>
                    </div>
                </div>
            </div>

            <!-- Map/Location -->
            <div class="mt-6 border-t pt-6">
                <h4 class="text-md font-semibold text-gray-900 mb-4">Location</h4>
                <div class="bg-gray-200 rounded-lg h-64 overflow-hidden">
                    <div id="map" class="w-full h-full"></div>
                </div>
                <div class="mt-4">
                    <p class="text-gray-700 font-medium">{{ $listing->address }}</p>
                    <p class="text-sm text-gray-600">{{ $listing->city->name }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Sidebar can be used for future features -->
        </div>
    </div>
</div>

<script>
function showImage(index) {
    const images = document.querySelectorAll('[data-index]');
    images.forEach((img, i) => {
        img.style.opacity = i === index ? '1' : '0';
    });
}

// Initialize Google Maps
function initMap() {
    const address = "{{ $listing->address }} @if($listing->city) {{ $listing->city->name }} @endif";
    const geocoder = new google.maps.Geocoder();
    const mapDiv = document.getElementById('map');

    geocoder.geocode({ address: address }, (results, status) => {
        if (status === 'OK') {
            const map = new google.maps.Map(mapDiv, {
                zoom: 15,
                center: results[0].geometry.location,
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    }
                ]
            });

            new google.maps.Marker({
                position: results[0].geometry.location,
                map: map,
                title: "{{ $listing->name }}"
            });
        } else {
            mapDiv.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500"><i class="fas fa-map-marker-alt mr-2"></i>Map unavailable</div>';
        }
    });
}

// Load Google Maps
window.initMap = initMap;
</script>

<!-- Google Maps API -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap">
</script>
@endsection
