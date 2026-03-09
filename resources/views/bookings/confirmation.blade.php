@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-green-800">
                        Booking Request Submitted!
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Thank you for your booking request. The host will review your request and confirm your booking.</p>
                        <p class="mt-2">
                            <strong>Booking Reference:</strong> #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
                        </p>
                        <p class="mt-2">
                            <strong>Status:</strong> 
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Booking Details -->
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Booking Details
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Space</p>
                            <p class="text-lg font-semibold">{{ $booking->listing->title }}</p>
                            <p class="text-gray-600">{{ $booking->listing->category->name }} • {{ $booking->listing->city->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Check-in</p>
                            <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('F j, Y') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Check-out</p>
                            <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('F j, Y') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Price</p>
                            <p class="text-lg font-bold text-blue-600">₦{{ number_format($booking->total_price, 0) }}</p>
                        </div>
                    </div>

                    <!-- Guest Information -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Guest Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Name</p>
                                <p class="text-lg">{{ $booking->guest_name }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="text-lg">{{ $booking->guest_email }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Phone</p>
                                <p class="text-lg">{{ $booking->guest_phone }}</p>
                            </div>
                            
                            @if($booking->notes)
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-500">Notes</p>
                                    <p class="text-lg">{{ $booking->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
