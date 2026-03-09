@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-user-check mr-3"></i>Host Approval
            </h1>
            <p class="mt-2 text-gray-600">
                Review and approve new host registrations
            </p>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-blue-600">{{ $pendingHosts->total() }}</p>
                                <p class="text-sm text-blue-600">Pending Approval</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Hosts List -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pending Hosts</h3>
                    
                    @forelse($pendingHosts as $host)
                        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-4 hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $host->display_name }}</h4>
                                        <span class="ml-auto">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending Approval
                                            </span>
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                        <div>
                                            <p class="font-medium">Email</p>
                                            <p>{{ $host->email }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Phone</p>
                                            <p>{{ $host->phone }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Gender</p>
                                            <p>{{ ucfirst($host->gender) }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Marital Status</p>
                                            <p>{{ ucfirst($host->marital_status) }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Residence</p>
                                            <p>{{ $host->residence ?: 'Not provided' }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">State of Origin</p>
                                            <p>{{ $host->state_of_origin ?: 'Not provided' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 text-sm text-gray-500">
                                        <p><strong>Registered:</strong> {{ $host->created_at->format('F j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <!-- Approve Button -->
                                    <form method="POST" action="{{ route('admin.hosts.approve', $host->id) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                                            <i class="fas fa-check mr-2"></i>
                                            Approve
                                        </button>
                                    </form>
                                    
                                    <!-- Reject Button -->
                                    <form method="POST" action="{{ route('admin.hosts.reject', $host->id) }}" 
                                          onsubmit="return confirm('Are you sure you want to reject this host? This will remove them from the system.')">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                                            <i class="fas fa-times mr-2"></i>
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-user-check text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900">No Pending Hosts</h3>
                            <p class="mt-2 text-gray-600">
                                All host registrations have been reviewed and approved.
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($pendingHosts->hasPages())
                    <div class="mt-6">
                        {{ $pendingHosts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
