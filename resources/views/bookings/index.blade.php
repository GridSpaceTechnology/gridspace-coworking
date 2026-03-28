@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-calendar-alt mr-3"></i>My Bookings
            </h1>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <!-- Status Filter -->
                <div class="mb-6">
                    <div class="flex space-x-4">
                        <a href="{{ route('bookings.index') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            All
                        </a>
                        <a href="{{ route('bookings.index', ['status' => 'pending']) }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Pending
                        </a>
                        <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'confirmed' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Confirmed
                        </a>
                        <a href="{{ route('bookings.index', ['status' => 'completed']) }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'completed' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Completed
                        </a>
                    </div>
                </div>

                <!-- Bookings List -->
                <div class="space-y-4">
                    @forelse($bookings as $booking)
                        <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow duration-300">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $booking->listing->name }}
                                        </h3>
                                        <span class="ml-auto">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                                  {{ ($booking->status == 'pending') ? 'bg-yellow-100 text-yellow-800' :
                                                  (($booking->status == 'confirmed') ? 'bg-green-100 text-green-800' :
                                                  ($booking->status == 'completed' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </span>
                                    </div>

                                    <div class="text-sm text-gray-600 mb-4">
                                        {{ $booking->listing->name }} • {{ $booking->listing->address }}
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                                        <div>
                                            <p class="font-medium">Check-in</p>
                                            <p>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M j, Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Check-out</p>
                                            <p>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M j, Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Guest</p>
                                            <p>{{ $booking->guest_name }}</p>
                                        </div>
                                        <div>
                                            <p class="font-medium">Total</p>
                                            <p class="font-bold text-blue-600">₦{{ number_format($booking->total_price, 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900">No bookings found</h3>
                            <p class="mt-2 text-gray-600">
                                You don't have any {{ request('status') == 'all' ? '' : request('status') }} bookings yet.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
