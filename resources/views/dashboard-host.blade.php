@extends('layouts.app')

@section('title', 'Dashboard - Gridspace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- User Details -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">
                @if(auth()->user()->role == 'admin') Admin Details
                @elseif(auth()->user()->role == 'host') Host Details
                @else User Details
                @endif
            </h2>
        </div>
        <div class="px-6 py-4">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center">
                        <span class="text-white text-xl font-medium">
                            {{ strtoupper(substr(auth()->user()->display_name, 0, 1)) }}
                        </span>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900">{{ auth()->user()->display_name }}</h3>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                    <p class="text-sm text-gray-500 mt-1">Role: {{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Listings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->listings()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Published Listings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->listings()->where('status', 'published')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Listings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->listings()->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Featured Listings</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->listings()->where('featured', true)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- My Listings Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">My Listings</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ auth()->user()->listings()->count() }} listings</span>
                    <a href="{{ route('listings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Listing
                    </a>
                </div>
            </div>
        </div>

        @if(auth()->user()->listings()->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listing Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $listings = auth()->user()->listings()->get();
                            foreach($listings as $listing) {
                                echo '<tr>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo '<div class="text-sm font-medium text-gray-900">' . $listing->name . '</div>';
                                if($listing->featured) {
                                    echo '<div class="mt-1">';
                                    echo '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">';
                                    echo '<i class="fas fa-star mr-1"></i>Featured';
                                    echo '</span>';
                                    echo '</div>';
                                }
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo '<span class="text-sm text-gray-900">' . ($listing->category->name ?? 'No Category') . '</span>';
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo '<span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full ';
                                if($listing->status == 'published') {
                                    echo 'bg-green-100 text-green-800';
                                } elseif($listing->status == 'pending') {
                                    echo 'bg-yellow-100 text-yellow-800';
                                } else {
                                    echo 'bg-gray-100 text-gray-800';
                                }
                                echo '">';
                                echo ucfirst($listing->status);
                                echo '</span>';
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo '<div class="text-sm text-gray-900">₦' . number_format($listing->price ?? 0, 0) . '</div>';
                                if($listing->price_range) {
                                    echo '<div class="text-xs text-gray-500">' . $listing->price_range . '</div>';
                                }
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                if($listing->featured) {
                                    echo '<span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">';
                                    echo '<i class="fas fa-star mr-1"></i>Yes';
                                    echo '</span>';
                                } else {
                                    echo '<span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">';
                                    echo 'No';
                                    echo '</span>';
                                }
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">';
                                echo '<span class="text-sm text-gray-500">' . $listing->created_at->format('M d, Y') . '</span>';
                                echo '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">';
                                echo '<div class="flex space-x-2">';
                                echo '<a href="' . route('listings.edit', $listing->slug) . '" class="text-blue-600 hover:text-blue-700">';
                                echo '<i class="fas fa-edit mr-1"></i>Edit';
                                echo '</a>';
                                echo '<a href="' . route('listings.show', $listing->slug) . '" class="text-green-600 hover:text-green-700">';
                                echo '<i class="fas fa-eye mr-1"></i>View';
                                echo '</a>';
                                echo '<a href="' . route('listings.destroy', $listing->slug) . '" class="text-red-600 hover:text-red-700" onclick="return confirm(\'Are you sure you want to delete this listing?\')">';
                                echo '<i class="fas fa-trash mr-1"></i>Delete';
                                echo '</a>';
                                echo '</div>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        @endphp
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 px-4">
                <div class="mx-auto w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mb-4">
                    <i class="fas fa-building text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No listings yet</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first workspace listing.</p>
                <a href="{{ route('listings.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i>
                    Create New Listing
                </a>
            </div>
        @endif
        </div>
    </div>

    @if(auth()->user()->role == 'host' && auth()->user()->listings()->count() > 0)
        <!-- Feature Request Sections - 50/50 Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Feature Request Section -->
            <div class="bg-white rounded-lg shadow overflow-hidden lg:ml-[12%] lg:mr-0 ml-0 mr-0">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">Feature Request</h2>
                        <span class="text-sm text-gray-500">Request admin to feature your listings</span>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <form id="featureRequestForm" action="#" method="POST" class="space-y-4" onsubmit="return handleFeatureRequestSubmit(event)">
                        @csrf
                        <div>
                            <label for="listing_id" class="block text-sm font-medium text-gray-700 mb-2">Select Listing to Feature</label>
                            <select id="listing_id" name="listing_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                <option value="">Choose a listing...</option>
                                @foreach(auth()->user()->listings()->get() as $listing)
                                    <option value="{{ $listing->id }}" {{ $listing->featured ? 'disabled' : '' }}>
                                        {{ $listing->name }} {{ $listing->featured ? '(Already Featured)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="plan" class="block text-sm font-medium text-gray-700 mb-2">Select Plan</label>
                            <select id="plan" name="plan" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                <option value="featured">Featured (₦5,000/month)</option>
                                <option value="premium">Premium (₦12,000/month)</option>
                            </select>
                        </div>

                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (months)</label>
                            <select id="duration" name="duration" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                <option value="1">1 month</option>
                                <option value="3">3 months</option>
                                <option value="6">6 months</option>
                                <option value="12">12 months</option>
                            </select>
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                            <input type="email" id="contact_email" name="contact_email" value="{{ auth()->user()->email }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Any additional information for the admin..."></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Feature Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Feature Request Status -->
            <div class="bg-white rounded-lg shadow overflow-hidden lg:mr-[12%] lg:ml-0 ml-0 mr-0">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">Feature Request Status</h2>
                        <span class="text-sm text-gray-500">Track your feature requests</span>
                    </div>
                </div>
                <div class="px-6 py-4">
                    @if($featureRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($featureRequests as $featureRequest)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                @if($featureRequest->listing->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $featureRequest->listing->images->first()->image_path) }}"
                                                         alt="{{ $featureRequest->listing->name }}"
                                                         class="h-10 w-10 rounded-lg object-cover">
                                                @else
                                                    <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                        <i class="fas fa-building text-gray-400 text-sm"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h3 class="text-sm font-medium text-gray-900">{{ $featureRequest->listing->name }}</h3>
                                                    <span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full
                                                        @if($featureRequest->status == 'approved') bg-green-100 text-green-800
                                                        @elseif($featureRequest->status == 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($featureRequest->status == 'rejected') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        @if($featureRequest->status == 'approved') <i class="fas fa-check mr-1"></i> Approved
                                                        @elseif($featureRequest->status == 'pending') <i class="fas fa-clock mr-1"></i> Pending
                                                        @elseif($featureRequest->status == 'rejected') <i class="fas fa-times mr-1"></i> Rejected
                                                        @else <i class="fas fa-question mr-1"></i> Unknown @endif
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mt-3 text-sm text-gray-500">
                                                <p><strong>Requested:</strong> {{ $featureRequest->created_at->format('M d, Y H:i') }}</p>
                                                @if($featureRequest->approved_at)
                                                    <p><strong>Approved:</strong> {{ $featureRequest->approved_at->format('M d, Y H:i') }}</p>
                                                @endif
                                                @if($featureRequest->rejected_at)
                                                    <p><strong>Rejected:</strong> {{ $featureRequest->rejected_at->format('M d, Y H:i') }}</p>
                                                @endif
                                            </div>

                                            @if($featureRequest->request_message)
                                                <div class="mt-3 p-2 bg-gray-50 rounded text-xs text-gray-600">
                                                    <strong>Your Message:</strong> {{ $featureRequest->request_message }}
                                                </div>
                                            @endif

                                            @if($featureRequest->payment_proof)
                                                <div class="mt-3">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Payment Proof Uploaded
                                                    </span>
                                                    <a href="{{ asset('storage/' . $featureRequest->payment_proof) }}"
                                                       target="_blank"
                                                       class="ml-2 text-blue-600 hover:text-blue-800 text-xs">
                                                        View Proof →
                                                    </a>
                                                </div>
                                            @else
                                                <div class="mt-3">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        Payment Proof Required
                                                    </span>
                                                </div>
                                            @endif

                                            @if($featureRequest->admin_notes)
                                                <div class="mt-3 p-2 @if($featureRequest->status == 'rejected') bg-red-50 @else bg-blue-50 @endif rounded text-xs @if($featureRequest->status == 'rejected') text-red-600 @else text-blue-600 @endif">
                                                    <strong>Admin Note:</strong> {{ $featureRequest->admin_notes }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ml-4">
                                            @if($featureRequest->status == 'pending')
                                                <button class="inline-flex items-center px-3 py-1 border border-yellow-300 shadow-sm text-xs font-medium rounded text-yellow-700 bg-yellow-50" disabled>
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Pending Review
                                                </button>
                                            @elseif($featureRequest->status == 'approved')
                                                <button class="inline-flex items-center px-3 py-1 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-green-600" disabled>
                                                    <i class="fas fa-check mr-1"></i>
                                                    Approved
                                                </button>
                                            @elseif($featureRequest->status == 'rejected')
                                                <button class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-red-50" disabled>
                                                    <i class="fas fa-times mr-1"></i>
                                                    Rejected
                                                </button>
                                            @else
                                                <button class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white" disabled>
                                                    <i class="fas fa-question mr-1"></i>
                                                    Unknown
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mb-4">
                                <i class="fas fa-star text-gray-400 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Feature Requests</h3>
                            <p class="text-gray-500">You haven't made any feature requests yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function handleFeatureRequestSubmit(event) {
    event.preventDefault();

    const listingId = document.getElementById('listing_id').value;

    if (!listingId) {
        alert('Please select a listing to feature.');
        return false;
    }

    // Redirect to feature request page with selected listing
    window.location.href = `/feature-requests/create/${listingId}`;

    return false;
}
</script>
@endsection
