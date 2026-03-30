@extends('layouts.app')

@section('title', 'Feature Requests - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-star text-yellow-500 mr-2"></i>
            Feature Requests
        </h1>
        <p class="mt-2 text-gray-600">Manage and approve featured listing requests from hosts</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $featureRequests->where('status', 'pending')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $featureRequests->where('status', 'approved')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-times text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $featureRequests->where('status', 'rejected')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $featureRequests->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="{{ route('feature-requests.index', ['status' => 'all']) }}" 
                   class="py-3 px-6 border-b-2 border-yellow-500 text-yellow-600 font-medium text-sm">
                    All Requests
                </a>
                <a href="{{ route('feature-requests.index', ['status' => 'pending']) }}" 
                   class="py-3 px-6 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    Pending
                </a>
                <a href="{{ route('feature-requests.index', ['status' => 'approved']) }}" 
                   class="py-3 px-6 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    Approved
                </a>
                <a href="{{ route('feature-requests.index', ['status' => 'rejected']) }}" 
                   class="py-3 px-6 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    Rejected
                </a>
            </nav>
        </div>
    </div>

    <!-- Feature Requests Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Recent Requests</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Host</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Proof</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($featureRequests as $featureRequest)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($featureRequest->listing->images->count() > 0)
                                        <img src="{{ asset('storage/' . $featureRequest->listing->images->first()->image_path) }}"
                                             alt="{{ $featureRequest->listing->name }}"
                                             class="h-10 w-10 rounded-lg object-cover mr-3">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center mr-3">
                                            <i class="fas fa-building text-gray-400 text-sm"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $featureRequest->listing->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $featureRequest->listing->category->name ?? 'No Category' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $featureRequest->user->firstname }} {{ $featureRequest->user->lastname }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $featureRequest->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full
                                    @if($featureRequest->status == 'approved') bg-green-100 text-green-800
                                    @elseif($featureRequest->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($featureRequest->status == 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($featureRequest->status == 'approved') Approved
                                    @elseif($featureRequest->status == 'pending') Pending
                                    @elseif($featureRequest->status == 'rejected') Rejected
                                    @else Unknown @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($featureRequest->payment_proof)
                                    <span class="inline-flex items-center px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Uploaded
                                    </span>
                                    <a href="{{ asset('storage/' . $featureRequest->payment_proof) }}" 
                                       target="_blank" 
                                       class="ml-2 text-blue-600 hover:text-blue-800 text-xs">
                                        View →
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Missing
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $featureRequest->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('listings.show', $featureRequest->listing->slug) }}"
                                       class="text-blue-600 hover:text-blue-900"
                                       title="View Listing">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($featureRequest->status == 'pending')
                                        @if($featureRequest->payment_proof)
                                            <form method="POST" action="{{ route('feature-requests.approve', $featureRequest) }}" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="text-green-600 hover:text-green-900"
                                                        onclick="return confirm('Approve this feature request and make the listing featured?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <!-- Reject Button with Modal -->
                                        <button type="button" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="openRejectModal({{ $featureRequest->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-center">
                                    <i class="fas fa-star text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500">No feature requests found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($featureRequests->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $featureRequests->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Reject Feature Request</h3>
            <div class="mt-2">
                <form id="rejectForm" method="POST">
                    @csrf
                    <input type="hidden" name="feature_request_id" id="feature_request_id">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Rejection Reason
                    </label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Please provide a reason for rejecting this request..."></textarea>
                </form>
            </div>
        </div>
        <div class="items-center px-4 py-3">
            <button type="button" onclick="closeRejectModal()" 
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">
                Cancel
            </button>
            <button type="button" onclick="submitRejection()" 
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                Reject Request
            </button>
        </div>
    </div>
</div>

<script>
function openRejectModal(requestId) {
    document.getElementById('feature_request_id').value = requestId;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
}

function submitRejection() {
    const form = document.getElementById('rejectForm');
    const requestId = document.getElementById('feature_request_id').value;
    const reason = document.getElementById('rejection_reason').value;
    
    if (!reason.trim()) {
        alert('Please provide a rejection reason.');
        return;
    }
    
    form.action = `/feature-requests/${requestId}/reject`;
    form.submit();
}
</script>
@endsection
