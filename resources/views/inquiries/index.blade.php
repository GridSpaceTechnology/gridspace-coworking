@extends('layouts.app')

@section('title', 'My Inquiries - Gridspace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header last -->
    <div class="mb-8">
        <div class="bg-blue-600 text-white px-6 py-4 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">My Inquiries</h1>
                    <p class="mt-2 text-blue-100">Track your workspace inquiries and responses</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-blue-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Inquiries List -->
    @if($inquiries->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Listing
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Phone
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Message
                            </th>
                            @if(Auth::user()->role === 'admin')
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contacted
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($inquiries as $inquiry)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($inquiry->listing)
                                        <a href="{{ route('listings.show', $inquiry->listing->slug) }}"
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $inquiry->listing->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">No listing</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $inquiry->name }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $inquiry->email }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $inquiry->phone }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900 max-w-xs">
                                    <div class="truncate" title="{{ $inquiry->message }}">
                                        {{ Str::limit($inquiry->message, 50) }}
                                    </div>
                                </td>
                                @if(Auth::user()->role === 'admin')
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <form method="POST" action="{{ route('admin.inquiries.toggle-contacted', $inquiry) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="{{ $inquiry->contacted ? 'text-green-600 hover:text-green-800' : 'text-gray-400 hover:text-gray-600' }} transition-colors" title="{{ $inquiry->contacted ? 'Mark as not contacted' : 'Mark as contacted' }}">
                                                <i class="fas {{ $inquiry->contacted ? 'fa-check-circle text-xl' : 'fa-circle text-xl' }}"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-envelope text-4xl mb-4"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Inquiries Yet</h3>
            <p class="text-gray-600 mb-6">You haven't submitted any workspace inquiries yet.
                <a href="{{ route('listings.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Browse Spaces
                </a> to find your perfect workspace and make an inquiry.
            </p>
        </div>
    @endif
</div>
@endsection
