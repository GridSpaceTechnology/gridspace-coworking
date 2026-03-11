@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Listing Header -->
            <div class="relative h-48 bg-gray-200">
                @if($listing->images->isNotEmpty())
                    <img src="{{ $listing->images->first()->image_path }}"
                         alt="{{ $listing->title }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-300">
                        <i class="fas fa-building text-gray-400 text-4xl"></i>
                    </div>
                @endif
            </div>

            <!-- Booking Form -->
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Book {{ $listing->title }}
                    </h2>
                    <p class="text-gray-600 mt-2">
                        {{ $listing->category->name }} • {{ $listing->city->name }}
                    </p>
                    <div class="flex items-center mt-2">
                        <span class="text-2xl font-bold text-blue-600">
                            ₦{{ number_format($listing->price, 0) }}
                        </span>
                        <span class="text-gray-500">/night</span>
                        <span class="ml-auto text-lg text-gray-600">
                            Capacity: {{ $listing->capacity }} people
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('bookings.store', $listing->slug) }}">
                    @csrf
                    <input type="hidden" name="listing_id" value="{{ $listing->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Check-in Date -->
                        <div>
                            <x-input-label for="check_in_date" :value="__('Check-in Date')" />
                            <input type="date" id="check_in_date" name="check_in_date"
                                   class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required min="{{ date('Y-m-d') }}">
                            <x-input-error :messages="$errors->get('check_in_date')" class="mt-2" />
                        </div>

                        <!-- Check-out Date -->
                        <div>
                            <x-input-label for="check_out_date" :value="__('Check-out Date')" />
                            <input type="date" id="check_out_date" name="check_out_date"
                                   class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            <x-input-error :messages="$errors->get('check_out_date')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Guest Information -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-user mr-2"></i>Guest Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="guest_name" :value="__('Full Name')" />
                                <input type="text" id="guest_name" name="guest_name"
                                       class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('guest_name') }}" required>
                                <x-input-error :messages="$errors->get('guest_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="guest_email" :value="__('Email Address')" />
                                <input type="email" id="guest_email" name="guest_email"
                                       class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('guest_email') }}" required>
                                <x-input-error :messages="$errors->get('guest_email')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="guest_phone" :value="__('Phone Number')" />
                                <input type="tel" id="guest_phone" name="guest_phone"
                                       class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('guest_phone') }}" required>
                                <x-input-error :messages="$errors->get('guest_phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="notes" :value="__('Additional Notes (Optional)')" />
                                <textarea id="notes" name="notes" rows="4"
                                          class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-md font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Submit Booking Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
