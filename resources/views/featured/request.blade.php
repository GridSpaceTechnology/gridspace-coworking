@extends('layouts.app')

@section('title', 'Request Featured Listing - Gridspace')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Feature Your Listing</h1>
        <p class="text-gray-600 mt-2">Get 10x more visibility and inquiries with featured placement</p>
    </div>

    <!-- Benefits Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 mb-8 text-white">
        <h2 class="text-xl font-bold mb-4">Why Feature Your Listing?</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-start">
                <i class="fas fa-star text-yellow-300 text-xl mr-3 mt-1"></i>
                <div>
                    <h3 class="font-semibold mb-1">Priority Placement</h3>
                    <p class="text-blue-100 text-sm">Appear at the top of search results</p>
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-home text-yellow-300 text-xl mr-3 mt-1"></i>
                <div>
                    <h3 class="font-semibold mb-1">Homepage Spotlight</h3>
                    <p class="text-blue-100 text-sm">Featured on homepage for maximum exposure</p>
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-chart-line text-yellow-300 text-xl mr-3 mt-1"></i>
                <div>
                    <h3 class="font-semibold mb-1">10x More Views</h3>
                    <p class="text-blue-100 text-sm">Proven increase in inquiries and bookings</p>
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-trophy text-yellow-300 text-xl mr-3 mt-1"></i>
                <div>
                    <h3 class="font-semibold mb-1">Premium Badge</h3>
                    <p class="text-blue-100 text-sm">Stand out with featured badge</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Plans -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Choose Your Plan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Basic -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Basic</h3>
                <p class="text-gray-600 mb-4">Standard listing</p>
                <div class="text-3xl font-bold text-gray-900 mb-4">Free</div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Standard listing placement
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Basic search visibility
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-times text-gray-400 mr-2"></i>
                        No homepage placement
                    </li>
                </ul>
            </div>

            <!-- Featured -->
            <div class="border-2 border-blue-500 rounded-lg p-6 relative">
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium">
                    MOST POPULAR
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Featured</h3>
                <p class="text-gray-600 mb-4">30 days of premium placement</p>
                <div class="text-3xl font-bold text-blue-600 mb-4">₦5,000<span class="text-lg text-gray-600">/month</span></div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Priority search placement
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Homepage featured section
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Featured badge on listing
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        10x more inquiries
                    </li>
                </ul>
            </div>

            <!-- Premium -->
            <div class="border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Premium</h3>
                <p class="text-gray-600 mb-4">90 days of maximum exposure</p>
                <div class="text-3xl font-bold text-gray-900 mb-4">₦12,000<span class="text-lg text-gray-600">/3 months</span></div>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Top priority placement
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Homepage hero section
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Premium featured badge
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Analytics dashboard
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Request Form -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Request Featured Status</h2>

        <form method="POST" action="{{ route('featured-requests.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Select Listing -->
                <div>
                    <label for="listing_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Listing to Feature
                    </label>
                    @if($listings->count() > 0)
                        <select name="listing_id" id="listing_id" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Choose a listing...</option>
                            @foreach($listings as $listing)
                                <option value="{{ $listing->id }}"
                                    {{ $listing->featured == 1 ? 'disabled' : '' }}
                                    {{ $listing->featured_request_status === 'pending' ? 'disabled' : '' }}>
                                    {{ $listing->name }} - {{ $listing->address }}
                                    @if($listing->featured == 1)
                                        (Already Featured)
                                    @elseif($listing->featured_request_status === 'pending')
                                        (Request Pending)
                                    @elseif($listing->status !== 'published')
                                        (Status: {{ $listing->status }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">No Published Listings</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>You don't have any published listings yet. You can only request featured status for published listings.</p>
                                        <div class="mt-3">
                                            <a href="{{ route('dashboard') }}" class="text-yellow-800 underline font-medium">
                                                Go to Dashboard →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Plan Selection -->
                <div>
                    <label for="plan" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Plan
                    </label>
                    <select name="plan" id="plan" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Choose a plan...</option>
                        <option value="featured">Featured - ₦5,000/month</option>
                        <option value="premium">Premium - ₦12,000/3 months</option>
                    </select>
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                        Duration (months)
                    </label>
                    <input type="number" name="duration" id="duration" min="1" max="12" value="1" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Contact Email -->
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Email
                    </label>
                    <input type="email" name="contact_email" id="contact_email"
                           value="{{ auth()->user()->email }}" required
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Additional Notes (Optional)
                </label>
                <textarea name="notes" id="notes" rows="4"
                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Any special requirements or questions..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-md font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-star mr-2"></i>
                    Submit Featured Request
                </button>
            </div>
        </form>
    </div>

    <!-- Payment Info -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-8">
        <h3 class="text-lg font-semibold text-yellow-800 mb-3">
            <i class="fas fa-info-circle mr-2"></i>
            Payment Information
        </h3>
        <div class="text-sm text-yellow-700 space-y-2">
            <p>• After submitting your request, our team will review and contact you within 24 hours.</p>
            <p>• Payment can be made via bank transfer, Paystack, or card payment.</p>
            <p>• Your listing will be featured immediately after payment confirmation.</p>
            <p>• You'll receive analytics data to track your listing performance.</p>
        </div>
    </div>
</div>
@endsection
