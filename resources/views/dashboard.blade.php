@extends('layouts.app')

@section('title', 'Dashboard - Gridspace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Listings</h1>
        <p class="text-gray-600 mt-2">Manage your workspace listings and track their performance.</p>
    </div>

    <!-- Approval Status Alert -->
    @if(auth()->user()->isHost() && !auth()->user()->isApproved())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-yellow-800">
                        Account Pending Approval
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Your host account is currently pending admin approval. You can view your dashboard, but you won't be able to create or manage listings until your account is approved.</p>
                        <p class="mt-1">We'll notify you once your account is approved. This typically takes 24-48 hours.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Listings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $listings->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $listings->sum(function($listing) { return $listing->analytics()->where('event_type', 'view')->count(); }) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                    <i class="fas fa-phone text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Contact Clicks</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $listings->sum(function($listing) {
                            return $listing->analytics()->whereIn('event_type', ['phone_click', 'whatsapp_click'])->count();
                        }) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3">
                    <i class="fas fa-envelope text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Inquiries</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $listings->sum(function($listing) { return $listing->analytics()->where('event_type', 'inquiry')->count(); }) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mb-6">
        @if(auth()->user()->isHost() && !auth()->user()->isApproved())
            <button disabled
                    class="bg-gray-400 text-gray-200 px-4 py-2 rounded-lg font-medium cursor-not-allowed">
                <i class="fas fa-plus mr-2"></i>Add New Listing (Approval Required)
            </button>
        @else
            <a href="{{ route('listings.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add New Listing
            </a>
        @endif
    </div>

    <!-- Listings Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Your Listings</h2>
        </div>

        @if($listings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listing</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inquiries</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($listings as $listing)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($listing->images->count() > 0)
                                            <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}"
                                                 alt="{{ $listing->name }}"
                                                 class="h-10 w-10 rounded-full object-cover mr-3">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                <i class="fas fa-building text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $listing->name }}</div>
                                            @if($listing->featured)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-star mr-1"></i>Featured
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $listing->category->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full
                                           {{ $listing->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $listing->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $listing->analytics()->where('event_type', 'view')->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $listing->analytics()->where('event_type', 'inquiry')->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $listing->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('listings.show', $listing->slug) }}"
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('listings.edit', $listing) }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('listings.destroy', $listing) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this listing?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $listings->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No listings yet</h3>
                <p class="text-gray-500 mb-6">Start by adding your first workspace listing.</p>
                <a href="{{ route('listings.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Create Your First Listing
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
