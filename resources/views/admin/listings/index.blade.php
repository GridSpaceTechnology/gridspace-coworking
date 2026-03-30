@extends('layouts.app')

@section('title', 'Manage Listings - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manage Listings</h1>
                <p class="text-gray-600 mt-2">View and manage all coworking space listings.</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('listings.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md shadow-sm text-white text-base font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-plus mr-2"></i>
                    Create New Listing
                </a>
                <a href="{{ route('admin.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md shadow-sm text-white text-base font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
                <select id="statusFilter" class="block px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="published">Published</option>
                    <option value="pending">Pending</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category Filter</label>
                <select id="categoryFilter" class="block px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Categories</option>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Listings Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Listing
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Host
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Price
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Approval
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($listings as $listing)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($listing->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}"
                                             alt="{{ $listing->name }}"
                                             class="h-10 w-10 rounded-lg object-cover mr-3">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center mr-3">
                                            <i class="fas fa-building text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $listing->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $listing->city->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $listing->user->firstname }} {{ $listing->user->lastname }}</div>
                                <div class="text-xs text-gray-500">{{ $listing->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $listing->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">₦{{ number_format($listing->price, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full
                                    {{ $listing->status === 'published' ? 'bg-green-100 text-green-800' :
                                      ($listing->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($listing->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($listing->status === 'pending')
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2 space-y-2 sm:space-y-0">
                                        <form method="POST" action="{{ route('admin.listings.approve', $listing->slug) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-green-600 hover:text-green-900 text-sm bg-green-100 px-3 py-2 rounded-md w-full sm:w-auto"
                                                    title="Approve Listing"
                                                    onclick="return confirm('Are you sure you want to approve this listing?')">
                                                <i class="fas fa-check mr-1"></i>
                                                <span>Approve</span>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.listings.reject', $listing->slug) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 text-sm bg-red-100 px-3 py-2 rounded-md w-full sm:w-auto"
                                                    title="Reject Listing"
                                                    onclick="return confirm('Are you sure you want to reject this listing?')">
                                                <i class="fas fa-times mr-1"></i>
                                                <span>Reject</span>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $listing->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2 space-y-2 sm:space-y-0">
                                    <a href="{{ route('listings.show', $listing->slug) }}"
                                       class="text-blue-600 hover:text-blue-900 text-sm order-1 sm:order-1"
                                       title="View Listing">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span class="sm:hidden">View</span>
                                    </a>
                                    <a href="{{ route('listings.edit', $listing->slug) }}"
                                       class="text-green-600 hover:text-green-900 text-sm order-2 sm:order-2"
                                       title="Edit Listing">
                                        <i class="fas fa-edit mr-1"></i>
                                        <span class="sm:hidden">Edit</span>
                                    </a>
                                    @if($listing->status === 'published')
                                        <form method="POST" action="{{ route('admin.toggle-featured', $listing) }}" class="inline order-3 sm:order-3">
                                            @csrf
                                            <button type="submit"
                                                    class="text-yellow-600 hover:text-yellow-900 text-sm"
                                                    title="{{ $listing->featured ? 'Remove from Featured' : 'Add to Featured' }}"
                                                    onclick="return confirm('Are you sure you want to {{ $listing->featured ? 'remove from' : 'add to' }} featured?')">
                                                <i class="fas fa-star{{ $listing->featured ? '' : '-o' }} mr-1"></i>
                                                <span class="sm:hidden">Featured</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <i class="fas fa-building text-4xl mb-4"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No listings found</h3>
                                <p class="text-gray-500">No listings match the current filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($listings->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $listings->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');

    function applyFilters() {
        const status = statusFilter ? statusFilter.value : '';
        const category = categoryFilter ? categoryFilter.value : '';

        let url = new URL(window.location);

        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }

        if (category) {
            url.searchParams.set('category', category);
        } else {
            url.searchParams.delete('category');
        }

        window.location.href = url.toString();
    }

    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (categoryFilter) categoryFilter.addEventListener('change', applyFilters);
});
</script>
@endsection
