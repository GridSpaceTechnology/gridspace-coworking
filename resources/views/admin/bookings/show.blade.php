@extends('layouts.app')

@section('title', 'Booking Details - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Booking Details</h1>
        <p class="text-gray-600 mt-2">View and manage booking information.</p>
    </div>

    <!-- Booking Overview -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Booking #{{ $booking->id }}</h2>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex px-3 py-1 text-sm leading-5 font-semibold rounded-full
                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                          ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                    <span class="text-white text-sm">
                        {{ $booking->created_at->format('M j, Y \a\t g:i A') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Guest Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Guest Information
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Name:</span>
                            <p class="text-gray-900">{{ $booking->guest_name }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Email:</span>
                            <p class="text-gray-900">
                                <a href="mailto:{{ $booking->guest_email }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $booking->guest_email }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Phone:</span>
                            <p class="text-gray-900">
                                <a href="tel:{{ $booking->guest_phone }}" class="text-green-600 hover:text-green-900">
                                    {{ $booking->guest_phone }}
                                </a>
                            </p>
                        </div>
                        @if($booking->notes)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Notes:</span>
                            <p class="text-gray-900">{{ $booking->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Booking Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-calendar mr-2 text-blue-600"></i>
                        Booking Information
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Check-in:</span>
                            <p class="text-gray-900">{{ $booking->check_in_date->format('F j, Y') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Check-out:</span>
                            <p class="text-gray-900">{{ $booking->check_out_date->format('F j, Y') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Duration:</span>
                            <p class="text-gray-900">{{ $booking->check_in_date->diffInDays($booking->check_out_date) }} nights</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Total Price:</span>
                            <p class="text-2xl font-bold text-green-600">₦{{ number_format($booking->total_price) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Listing Information -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-building mr-2 text-blue-600"></i>
                Listing Information
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Listing Images -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-3">
                        <i class="fas fa-images mr-2 text-blue-600"></i>
                        Listing Images
                    </h4>
                    @if($booking->listing->images->count() > 0)
                        <div class="space-y-3">
                            @foreach($booking->listing->images as $image)
                                <div class="relative rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         alt="{{ $booking->listing->name }} - Image {{ $loop->iteration }}"
                                         class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300 cursor-pointer"
                                         onclick="window.open('{{ asset('storage/' . $image->image_path) }}', '_blank')">
                                    <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                        {{ $loop->iteration }} / {{ $booking->listing->images->count() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-500 text-sm">No images available</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Listing Details -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-3">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Property Details
                    </h4>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Listing Name:</span>
                            <p class="text-gray-900 font-medium">{{ $booking->listing->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Category:</span>
                            <p class="text-gray-900">{{ $booking->listing->category->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Address:</span>
                            <p class="text-gray-900">{{ $booking->listing->address }}</p>
                            @if($booking->listing->city)
                                <p class="text-gray-900">{{ $booking->listing->city->name }}</p>
                            @endif
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Capacity:</span>
                            <p class="text-gray-900">{{ $booking->listing->capacity }} people</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Price per Night:</span>
                            <p class="text-gray-900">₦{{ number_format($booking->listing->price) }}</p>
                        </div>
                        @if($booking->listing->description)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Description:</span>
                            <p class="text-gray-900 text-sm">{{ Str::limit($booking->listing->description, 150) }}</p>
                        </div>
                        @endif
                        <div class="pt-3 border-t border-gray-200">
                            <a href="{{ route('listings.show', $booking->listing->slug) }}"
                               target="_blank"
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                View Full Listing
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Management -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-cog mr-2 text-blue-600"></i>
                Booking Management
            </h3>
        </div>
        <div class="p-6">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Manage booking status and communicate with guests and hosts.
                </p>
            </div>

            <!-- Status Update Form -->
            <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}" class="mb-6">
                @csrf
                @method('PATCH')
                <div class="flex items-center space-x-4">
                    <label for="status" class="text-sm font-medium text-gray-700">Update Status:</label>
                    <select name="status" id="status" class="block px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Status
                    </button>
                </div>
            </form>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4">
                <a href="mailto:{{ $booking->guest_email }}"
                   class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-envelope mr-2"></i>
                    Email Guest
                </a>
                <a href="tel:{{ $booking->guest_phone }}"
                   class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-phone mr-2"></i>
                    Call Guest
                </a>
                <a href="mailto:{{ $booking->listing->user->email }}"
                   class="inline-flex items-center px-4 py-2 border border-purple-300 rounded-md shadow-sm text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <i class="fas fa-envelope mr-2"></i>
                    Email Host
                </a>
                <a href="tel:{{ $booking->listing->user->phone }}"
                   class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-phone mr-2"></i>
                    Call Host
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="mt-8">
        <a href="{{ route('admin.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Admin Dashboard
        </a>
    </div>
</div>
@endsection
