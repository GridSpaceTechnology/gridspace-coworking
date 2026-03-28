@extends('layouts.app')

@section('title', 'Dashboard - Gridspace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->display_name }}!</h1>
        <p class="text-gray-600 mt-2">Manage your workspace listings and track their performance.</p>
    </div>

    <!-- Approval Status Alert -->
    @if(auth()->user()->isHost() && !auth()->user()->isApproved())
        <div class="bg-white rounded-lg shadow border-l-4 border-yellow-400 p-6 mb-8 h-32 flex items-center">
            <div class="flex items-center w-full">
                <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-medium text-gray-900">Account Pending Approval</h3>
                    <div class="mt-2 text-sm text-gray-600">
                        <p>Your host account is currently pending admin approval. You can view your dashboard, but you won't be able to create or manage listings until your account is approved.</p>
                        <p class="mt-1">We'll notify you once your account is approved. This typically takes 24-48 hours.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 h-32 flex flex-col justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Listings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->listings()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 h-32 flex flex-col justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->listings()->sum(function($listing) { return $listing->analytics()->where('event_type', 'view')->count(); }) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 h-32 flex flex-col justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                    <i class="fas fa-phone text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Contact Clicks</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->listings()->sum(function($listing) { return $listing->analytics()->whereIn('event_type', ['phone_click', 'whatsapp_click'])->count(); }) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 h-32 flex flex-col justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3">
                    <i class="fas fa-envelope text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Inquiries</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->listings()->sum(function($listing) { return $listing->analytics()->where('event_type', 'inquiry')->count(); }) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 h-32 flex flex-col justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-500 rounded-lg p-3">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">₦{{ number_format(auth()->user()->listings()->sum(function($listing) {
                        return $listing->analytics()->whereIn('event_type', ['booking_payment', 'booking_confirmed'])->sum(function($listing) {
                            return $listing->price * 30; // Assuming 30 days average booking
                        });
                    }, 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('listings.create') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Create New Listing</h3>
                <p class="text-sm text-gray-600 mt-2">Add a new workspace to your portfolio</p>
            </a>

            <a href="{{ route('featured-requests.create') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Feature Your Listing</h3>
                <p class="text-sm text-gray-600 mt-2">Promote your workspace to reach more customers</p>
            </a>

            <a href="{{ route('analytics.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Analytics</h3>
                <p class="text-sm text-gray-600 mt-2">View detailed performance metrics</p>
            </a>
        </div>
    </div>

    <!-- Listings Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Your Listings</h2>
        </div>

        @if(auth()->user()->listings()->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listing</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inquiries</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(auth()->user()->listings() as $listing)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-start">
                                        @if($listing->images->count() > 0)
                                            <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}"
                                                 alt="{{ $listing->name }}"
                                                 class="h-10 w-10 rounded-full object-cover mr-3">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                <i class="fas fa-building text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $listing->name }}</div>
                                            <div class="mt-1">
                                                @if($listing->featured)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-star mr-1"></i>Featured
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{
                                        $listing->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                                    }}">
                                        {{ $listing->status == 'approved' ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $listing->category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $listing->analytics()->where('event_type', 'view')->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $listing->analytics()->whereIn('event_type', ['phone_click', 'whatsapp_click'])->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ₦{{ number_format($listing->analytics()->whereIn('event_type', ['booking_payment', 'booking_confirmed'])->sum(function($listing) {
                                        return $listing->price * 30; // Assuming 30 days average booking
                                    }), 0) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $listing->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('listings.edit', $listing->id) }}"
                                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <a href="{{ route('listings.show', $listing->slug) }}"
                                           class="text-green-600 hover:text-green-700 text-sm font-medium ml-2">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        <a href="{{ route('listings.destroy', $listing->id) }}"
                                           class="text-red-600 hover:text-red-700 text-sm font-medium ml-2"
                                           onclick="return confirm('Are you sure you want to delete this listing?')">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 md:py-12 px-4 md:px-6">
                <i class="fas fa-building text-gray-300 text-4xl md:text-5xl mb-4"></i>
                <p class="text-gray-600 text-base md:text-lg">You haven't created any listings yet.</p>
                <a href="{{ route('listings.create') }}"
                   class="bg-blue-600 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg font-medium hover:bg-blue-700 text-sm md:text-base">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden md:inline">Create Your First Listing</span>
                    <span class="md:hidden">Create Listing</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
