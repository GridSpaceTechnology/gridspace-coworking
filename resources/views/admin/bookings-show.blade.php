@extends('layouts.app')

@section('title', 'Booking Details - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-calendar text-blue-600 mr-2"></i>
            Booking Details
        </h1>
        <p class="mt-2 text-gray-600">View and manage booking information</p>
    </div>

    <!-- Booking Details Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Booking Information</h2>
        </div>
        
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Guest Information -->
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Guest Information</h3>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Name:</span> 
                            {{ $booking->user->firstname ?? 'Unknown' }} {{ $booking->user->lastname ?? '' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Email:</span> 
                            {{ $booking->user->email ?? 'Unknown' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Phone:</span> 
                            {{ $booking->user->phone ?? 'Not provided' }}
                        </p>
                    </div>
                </div>

                <!-- Listing Information -->
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Listing Information</h3>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Property:</span> 
                            {{ $booking->listing->name ?? 'Unknown' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Host:</span> 
                            {{ $booking->listing->user->firstname ?? 'Unknown' }} {{ $booking->listing->user->lastname ?? '' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Status:</span> 
                            <span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $booking->status }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Booking Dates -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Booking Dates</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Check-in:</span> 
                            {{ $booking->check_in_date ? $booking->check_in_date->format('M j, Y') : 'Not set' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Check-out:</span> 
                            {{ $booking->check_out_date ? $booking->check_out_date->format('M j, Y') : 'Not set' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Created:</span> 
                            {{ $booking->created_at->format('M j, Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex space-x-4">
        <a href="{{ route('admin.bookings.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Bookings
        </a>
    </div>
</div>
@endsection
