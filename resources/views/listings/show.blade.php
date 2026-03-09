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
                        <span class="text-2xl font-bold text-blue-600">{{ $listing->price_range }}</span>
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

                <!-- Capacity -->
                @if($listing->capacity)
                    <div class="flex items-center text-gray-600 mb-6">
                        <i class="fas fa-users mr-2"></i>
                        <span>Capacity: {{ $listing->capacity }} people</span>
                    </div>
                @endif

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                    <div class="prose prose-gray max-w-none">
                        {!! nl2br($listing->description) !!}
                    </div>
                </div>

                <!-- Amenities -->
                @if($listing->amenities->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Amenities</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($listing->amenities as $amenity)
                                <div class="flex items-center text-gray-600">
                                    @if($amenity->icon)
                                        <i class="fas fa-{{ $amenity->icon }} mr-2"></i>
                                    @endif
                                    <span>{{ $amenity->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

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
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email"
                                   name="email"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                   class="w-full bg-green-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-green-700 text-center block">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Book Now - ₦{{ number_format($listing->price, 0) }}/night
                </a>
            @else
                <div class="w-full bg-gray-400 text-gray-200 px-4 py-3 rounded-lg text-center block">
                    <i class="fas fa-calendar-times mr-2"></i>
                    Currently Unavailable
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Host Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Host Information</h3>
                <div class="text-center">
                    <div class="w-20 h-20 bg-gray-300 rounded-full mx-auto mb-3 flex items-center justify-center">
                        <i class="fas fa-user text-3xl text-gray-500"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">{{ $listing->user->name }}</h4>
                    <p class="text-sm text-gray-600">Member since {{ $listing->user->created_at->format('M Y') }}</p>
                </div>
            </div>

            <!-- Map -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Location</h3>
                <div class="aspect-square bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-map-marked-alt text-4xl text-gray-400"></i>
                </div>
                <p class="text-sm text-gray-600 mt-3 text-center">
                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $listing->address }}
                    @if($listing->city)
                        , {{ $listing->city->name }}
                    @endif
                </p>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Space Details</h3>
                <dl class="space-y-3">
                    @if($listing->capacity)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Capacity</dt>
                            <dd class="text-sm text-gray-900">{{ $listing->capacity }} people</dd>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-600">Category</dt>
                        <dd class="text-sm text-gray-900">{{ $listing->category->name }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-600">Price Range</dt>
                        <dd class="text-sm text-gray-900">{{ $listing->price_range }}</dd>
                    </div>

                    @if($listing->website)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Website</dt>
                            <dd class="text-sm text-blue-600 truncate">
                                <a href="{{ $listing->website }}" target="_blank">{{ parse_url($listing->website)['host'] }}</a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
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
</script>
@endsection
