@extends('layouts.app')

@section('title', 'Gridspace - Find Your Perfect Workspace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-8 mb-8 text-white">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Find Your Perfect Workspace</h1>
            <p class="text-xl mb-8">Discover coworking spaces, meeting rooms, and more in your area</p>

            <!-- Quick Search -->
            <form method="GET" action="{{ route('home') }}" class="max-w-2xl mx-auto" id="quickSearchForm">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input
                            type="text"
                            name="search"
                            placeholder="Search by location, name, or description..."
                            value="{{ request('search') }}"
                            class="w-full px-4 py-3 pr-10 rounded-lg border-0 focus:ring-2 focus:ring-blue-500 text-gray-900 placeholder-gray-400"
                            id="searchInput"
                            autocomplete="off"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>

                        <!-- Live Search Results Dropdown -->
                        <div id="searchResults" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50 max-h-96 overflow-y-auto">
                        </div>
                    </div>
                    <button type="submit" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200 shadow-md">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </div>

                <!-- Active Search Indicator -->
                @if(request('search'))
                    <div class="mt-3 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            <i class="fas fa-filter mr-2"></i>
                            Searching for: "{{ request('search') }}"
                            <a href="{{ route('home') }}" class="ml-2 hover:text-blue-600">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Featured Listings -->
    @if($featuredListings->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Featured Spaces</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredListings as $listing)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        @if($listing->images->count() > 0)
                            <div class="h-48 bg-gray-200">
                                <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}"
                                     alt="{{ $listing->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="p-4">
                            @if($listing->featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mb-2">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            @endif

                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $listing->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $listing->category->name }}</p>
                            <p class="text-gray-500 text-sm mb-3">{{ Str::limit($listing->description, 100) }}</p>

                            <div class="flex justify-between items-center">
                                <span class="text-blue-600 font-semibold">{{ $listing->price_range }}</span>
                                <a href="{{ route('listings.show', $listing->slug) }}"
                                   class="bg-blue-600 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Category Quick Links -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Browse by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('home', ['category' => $category->slug]) }}"
                   class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 text-center group">
                    <div class="text-3xl text-gray-400 mb-2 group-hover:text-blue-600">
                        @switch($category->name)
                            @case('Coworking Spaces')
                                <i class="fas fa-laptop-house"></i>
                                @break
                            @case('Meeting Rooms')
                                <i class="fas fa-users"></i>
                                @break
                            @case('Virtual Offices')
                                <i class="fas fa-building"></i>
                                @break
                            @case('Event Spaces')
                                <i class="fas fa-calendar-alt"></i>
                                @break
                            @case('Corporate Workspace Solutions')
                                <i class="fas fa-briefcase"></i>
                                @break
                            @case('Startup Infrastructure Services')
                                <i class="fas fa-rocket"></i>
                                @break
                            @default
                                <i class="fas fa-door-open"></i>
                        @endswitch
                    </div>
                    <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Search Results -->
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>

                <form method="GET" action="{{ route('home') }}">
                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City Filter -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <select name="city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->slug }}" {{ request('city') == $city->slug ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Capacity Filter -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Capacity</label>
                        <input type="number"
                               name="capacity"
                               value="{{ request('capacity') }}"
                               min="1"
                               placeholder="e.g., 10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <input type="text"
                               name="price_range"
                               value="{{ request('price_range') }}"
                               placeholder="e.g., $500-1000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 font-medium">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Listings Grid -->
        <div class="lg:w-3/4">
            @if(request()->filled('search') || request()->filled('category') || request()->filled('city') || request()->filled('capacity') || request()->filled('price_range'))
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-900">
                                Search Results
                                @if($listings->total() > 0)
                                    <span class="text-blue-700 font-normal">({{ $listings->total() }} found)</span>
                                @endif
                            </h3>
                            @if(request()->filled('search'))
                                <p class="text-sm text-blue-700 mt-1">
                                    Showing results for: <strong>"{{ request('search') }}"</strong>
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-times mr-1"></i>Clear all
                        </a>
                    </div>
                </div>
            @endif

            @if($listings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($listings as $listing)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            @if($listing->images->count() > 0)
                                <div class="h-48 bg-gray-200">
                                    <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}"
                                         alt="{{ $listing->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="p-4">
                                @if($listing->featured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mb-2">
                                        <i class="fas fa-star mr-1"></i>Featured
                                    </span>
                                @endif

                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $listing->name }}</h3>
                                <p class="text-gray-600 text-sm mb-2">{{ $listing->category->name }}</p>
                                <p class="text-gray-500 text-sm mb-3">{{ Str::limit($listing->description, 100) }}</p>

                                <div class="flex justify-between items-center">
                                    <span class="text-blue-600 font-semibold">{{ $listing->price_range }}</span>
                                    <a href="{{ route('listings.show', $listing->slug) }}"
                                       class="bg-blue-600 text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $listings->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No listings found</h3>
                    <p class="text-gray-500 mb-4">Try adjusting your filters or search terms.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-redo mr-2"></i>Start over
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const quickSearchForm = document.getElementById('quickSearchForm');
    let searchTimeout;

    if (searchInput && searchResults) {
        // Live search functionality
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(searchTimeout);

            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }

            // Debounce search
            searchTimeout = setTimeout(() => {
                performLiveSearch(query);
            }, 300);
        });

        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        // Show search results when focusing on input
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                performLiveSearch(this.value.trim());
            }
        });
    }

    function performLiveSearch(query) {
        fetch(`{{ route('home') }}?search=${encodeURIComponent(query)}&live=1`)
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data.listings, query);
            })
            .catch(error => {
                console.error('Search error:', error);
                searchResults.classList.add('hidden');
            });
    }

    function displaySearchResults(listings, query) {
        if (listings.length === 0) {
            searchResults.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    <i class="fas fa-search mb-2"></i>
                    <p>No results found for "${query}"</p>
                </div>
            `;
        } else {
            const resultsHtml = listings.slice(0, 5).map(listing => `
                <a href="/listings/${listing.slug}"
                   class="block p-4 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors">
                    <div class="flex items-start space-x-3">
                        ${listing.image ? `
                            <img src="${listing.image}" alt="${listing.name}"
                                 class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                        ` : `
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex-shrink-0 flex items-center justify-center">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                        `}
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 truncate">${listing.name}</h4>
                            <p class="text-xs text-gray-500 truncate">${listing.category_name}</p>
                            <p class="text-xs text-blue-600 font-medium">${listing.price_range}</p>
                        </div>
                    </div>
                </a>
            `).join('');

            searchResults.innerHTML = `
                <div class="p-2">
                    ${resultsHtml}
                    ${listings.length > 5 ? `
                        <div class="p-2 text-center border-t border-gray-100">
                            <a href="/?search=${encodeURIComponent(query)}"
                               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View all ${listings.length} results →
                            </a>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        searchResults.classList.remove('hidden');
    }

    // Enhance filter form with better UX
    const filterForm = document.querySelector('form[method="GET"]:not(#quickSearchForm)');
    if (filterForm) {
        const filterInputs = filterForm.querySelectorAll('input, select');

        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Auto-submit filter form when values change
                if (this.type !== 'text' || this.value.length > 0) {
                    filterForm.submit();
                }
            });
        });
    }
});
</script>
@endsection
