@extends('layouts.app')

@section('title', 'Admin Dashboard - Gridspace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage the Gridspace platform and monitor performance.</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Listings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_listings'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Published</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['published_listings'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                    <i class="fas fa-star text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Featured</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['featured_listings'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-3">
                    <i class="fas fa-user-tie text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Hosts</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_hosts'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-orange-500 rounded-lg p-3">
                    <i class="fas fa-user-check text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Hosts</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_hosts'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                    <i class="fas fa-calendar-check text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_bookings'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-500 rounded-lg p-3">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Bookings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_bookings'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('admin.hosts.approval') }}"
               class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300 border-2 border-orange-200 hover:border-orange-400">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-orange-500 rounded-lg p-3">
                        <i class="fas fa-user-check text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Host Approval</h3>
                        <p class="text-sm text-gray-600">Review and approve new host registrations</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('analytics.index') }}"
               class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3">
                        <i class="fas fa-chart-bar text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Analytics</h3>
                        <p class="text-sm text-gray-600">View platform analytics and export data</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Listings & Inquiries Section -->
    <div class="mt-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-8">Recent Activity</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Listings -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Recent Listings</h2>
            </div>
            <div class="p-6">
                @if($recentListings->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentListings as $listing)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if($listing->images->count() > 0)
                                        <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}"
                                             alt="{{ $listing->name }}"
                                             class="h-10 w-10 rounded-full object-cover mr-3">
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $listing->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            by {{ $listing->user->name }} • {{ $listing->category->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($listing->featured)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-star mr-1"></i>Featured
                                        </span>
                                    @endif
                                    <span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full
                                           {{ $listing->status === 'published' ? 'bg-green-100 text-green-800' :
                                             ($listing->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $listing->status }}
                                    </span>
                                    <form method="POST" action="{{ route('admin.toggle-featured', $listing) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="text-purple-600 hover:text-purple-900 text-sm font-medium"
                                                title="{{ $listing->featured ? 'Remove featured status' : 'Make featured' }}">
                                            <i class="fas fa-{{ $listing->featured ? 'star' : 'star' }}"></i>
                                        </button>
                                    </form>
                                    @if($listing->status === 'pending')
                                        <form method="POST" action="{{ route('admin.listings.approve', $listing) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-green-600 hover:text-green-900 text-sm font-medium"
                                                    title="Approve listing">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.listings.reject', $listing) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 text-sm font-medium"
                                                    title="Reject listing">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('listings.show', $listing) }}"
                                       class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                                       title="View listing">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-building text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No listings yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Inquiries -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Recent Inquiries</h2>
            </div>
            <div class="p-6">
                @if($recentInquiries->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentInquiries as $inquiry)
                            <div class="border-l-4 border-blue-500 pl-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $inquiry->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            Interested in: {{ $inquiry->listing->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $inquiry->created_at->format('M j, Y \a\t g:i A') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="mailto:{{ $inquiry->email }}"
                                           class="text-blue-600 hover:text-blue-900 text-sm">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                        <a href="tel:{{ $inquiry->phone }}"
                                           class="text-green-600 hover:text-green-900 text-sm">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-envelope text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No inquiries yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Recent Bookings</h2>
            </div>
            <div class="p-6">
                @if($recentBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentBookings as $booking)
                            <div class="border-l-4 border-green-500 pl-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->guest_name }}</div>
                                        <div class="text-xs text-gray-500">
                                            Booked: {{ $booking->listing->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $booking->check_in_date->format('M j, Y') }} - {{ $booking->check_out_date->format('M j, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $booking->created_at->format('M j, Y \a\t g:i A') }}
                                        </div>
                                        <div class="mt-2">
                                            <span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full
                                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                                  ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $booking->status }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="mailto:{{ $booking->guest_email }}"
                                           class="text-blue-600 hover:text-blue-900 text-sm">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                        <a href="tel:{{ $booking->guest_phone }}"
                                           class="text-green-600 hover:text-green-900 text-sm">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                        <span class="text-xs text-gray-500">
                                            ₦{{ number_format($booking->total_price) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No bookings yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
