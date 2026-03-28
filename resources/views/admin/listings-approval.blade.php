@extends('layouts.app')

@section('title', 'Listings Approval - Gridspace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pending Listings Approval</h1>
        <p class="text-gray-600 mt-2">Review and manage pending workspace listings</p>
    </div>

    @if($pendingListings->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Pending Listings ({{ $pendingListings->count() }})</h2>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.listings.pending') }}" 
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-sync mr-1"></i>Refresh
                        </a>
                        <form method="POST" action="{{ route('admin.bulk-approve') }}" class="flex items-center">
                            @csrf
                            <button type="submit" 
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-check mr-2"></i>
                                Approve All
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listing</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Host</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingListings as $listing)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-start">
                                        @if($listing->images->count() > 0)
                                            <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}"
                                                 alt="{{ $listing->name }}"
                                                 class="h-12 w-12 rounded-full object-cover mr-3 cursor-pointer hover:scale-105 transition-transform duration-200"
                                                 onclick="openImageModal('{{ $listing->id }}', '{{ asset('storage/' . $listing->images->first()->image_path) }}')">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                <i class="fas fa-building text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $listing->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $listing->category->name }} • {{ $listing->city->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                        $listing->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' 
                                    }}">
                                        {{ $listing->status === 'published' ? 'Published' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $listing->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $listing->category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $listing->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <form method="POST" action="{{ route('admin.listings.approve', $listing->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-green-600 text-white px-3 py-1 rounded text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                    title="Approve this listing">
                                                <i class="fas fa-check mr-1"></i>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('admin.listings.reject', $listing->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-red-600 text-white px-3 py-1 rounded text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                    title="Reject this listing"
                                                <i class="fas fa-times mr-1"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                <p class="text-gray-600 text-lg">No pending listings to review</p>
                <a href="{{ route('admin.index') }}" class="text-blue-600 hover:text-blue-700 text-lg font-medium">
                    Return to Dashboard
                </a>
            </div>
        @endif
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-auto">
                <div class="flex justify-between items-start p-4">
                    <h3 class="text-lg font-medium text-gray-900">Listing Images</h3>
                    <button onclick="closeImageModal()" 
                            class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="flex-1 overflow-auto">
                    @if($listingImages)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($listingImages as $index => $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         alt="{{ $listing->name }} - Image {{ $index + 1 }}"
                                         class="w-full h-48 object-cover rounded-lg cursor-pointer hover:scale-105 transition-transform duration-200"
                                         onclick="openImageModal('{{ $listing->id }}', '{{ asset('storage/' . $image->image_path) }}')">
                                    </div>
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Image {{ $index + 1 }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No images available for this listing</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentListingId = null;
let currentImageIndex = 0;

function openImageModal(listingId, imagePath) {
    currentListingId = listingId;
    currentImageIndex = 0;
    
    const modal = document.getElementById('imageModal');
    const modalTitle = modal.querySelector('h3');
    const modalImages = modal.querySelector('.grid');
    
    modalTitle.textContent = 'Listing Images';
    modal.classList.remove('hidden');
    
    // Update current image index
    const imageElements = modalImages.querySelectorAll('img');
    imageElements.forEach((img, index) => {
        if (img.src.includes(imagePath)) {
            currentImageIndex = index;
        }
        const badge = img.parentElement.querySelector('.absolute .top-2 .right-2 span');
        if (badge) {
            badge.textContent = `Image ${index + 1}`;
            badge.classList.add('bg-blue-500');
            badge.classList.remove('bg-red-500');
        }
    });
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    currentListingId = null;
    currentImageIndex = 0;
}
</script>
@endsection
