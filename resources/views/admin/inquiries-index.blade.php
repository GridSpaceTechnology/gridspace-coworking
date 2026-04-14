@extends('layouts.app')

@section('title', 'All Inquiries - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-envelope text-blue-600 mr-2"></i>
            All Inquiries
        </h1>
        <p class="mt-2 text-gray-600">View and manage all customer inquiries</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-envelope text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Inquiries</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $inquiries->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Inquiries Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Inquiry List</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Listing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $inquiry->name }}</div>
                                <div class="text-sm text-gray-500">{{ $inquiry->email }}</div>
                                <div class="text-sm text-gray-500">{{ $inquiry->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($inquiry->listing)
                                    <div class="text-sm font-medium text-gray-900">{{ $inquiry->listing->name }}</div>
                                    <div class="text-sm text-gray-500">Host: {{ $inquiry->listing->user->firstname ?? 'Unknown' }}</div>
                                @else
                                    <span class="text-gray-400">Listing not found</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-md truncate">{{ $inquiry->message }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $inquiry->created_at->format('M j, Y g:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <i class="fas fa-envelope-open text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No inquiries found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $inquiries->links() }}
        </div>
    </div>
</div>
@endsection
