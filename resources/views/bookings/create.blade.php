@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Listing Header -->
            <div class="relative h-48 bg-gray-200">
                @if($listing->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}"
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
                        Book {{ $listing->name }}
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

                    <!-- Number of People -->
                    <div class="mt-6">
                        <x-input-label for="number_of_people" :value="__('Number of People')" />
                        <div class="mt-1 flex items-center">
                            <input type="number" id="number_of_people" name="number_of_people"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   value="1" min="1" max="{{ $listing->capacity }}" required>
                            <span class="ml-3 text-sm text-gray-500">
                                (Max capacity: {{ $listing->capacity }} people)
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('number_of_people')" class="mt-2" />
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get booked dates from server
    const bookedDates = @json($bookedDates);

    // Convert booked dates to disabled date ranges
    const disabledDates = [];

    bookedDates.forEach(function(booking) {
        const checkIn = new Date(booking.check_in_date);
        const checkOut = new Date(booking.check_out_date);

        // Add all dates from check_in to check_out (exclusive of check_out)
        const currentDate = new Date(checkIn);
        while (currentDate < checkOut) {
            disabledDates.push(currentDate.toISOString().split('T')[0]);
            currentDate.setDate(currentDate.getDate() + 1);
        }
    });

    // Function to check if a date is disabled
    function isDateDisabled(dateString) {
        return disabledDates.includes(dateString);
    }

    // Function to set min date for check-out based on check-in
    function updateCheckOutMinDate() {
        const checkInDate = document.getElementById('check_in_date').value;
        const checkOutInput = document.getElementById('check_out_date');

        if (checkInDate) {
            const minCheckOut = new Date(checkInDate);
            minCheckOut.setDate(minCheckOut.getDate() + 1);
            checkOutInput.min = minCheckOut.toISOString().split('T')[0];

            // Clear check-out if it's before the new min date
            if (checkOutInput.value && new Date(checkOutInput.value) <= new Date(checkInDate)) {
                checkOutInput.value = '';
            }
        }
    }

    // Function to check if a date range conflicts with booked dates
    function hasDateRangeConflict(checkInDate, checkOutDate) {
        if (!checkInDate || !checkOutDate) return false;

        const checkIn = new Date(checkInDate);
        const checkOut = new Date(checkOutDate);

        // Check each date in the range
        const currentDate = new Date(checkIn);
        while (currentDate < checkOut) {
            const dateString = currentDate.toISOString().split('T')[0];
            if (disabledDates.includes(dateString)) {
                return true;
            }
            currentDate.setDate(currentDate.getDate() + 1);
        }

        return false;
    }

    // Function to validate date range
    function validateDateRange() {
        const checkInDate = document.getElementById('check_in_date').value;
        const checkOutDate = document.getElementById('check_out_date').value;

        if (checkInDate && checkOutDate) {
            if (hasDateRangeConflict(checkInDate, checkOutDate)) {
                document.getElementById('check_out_date').value = '';
                alert('One or more dates in your selected range are already booked. Please select different dates.');
                return false;
            }
        }
        return true;
    }

    // Function to disable dates in date inputs
    function disableDates(input) {
        input.addEventListener('input', function() {
            const selectedDate = this.value;
            const checkInDate = document.getElementById('check_in_date').value;
            const checkOutDate = document.getElementById('check_out_date').value;

            // Check if individual date is disabled
            if (selectedDate && isDateDisabled(selectedDate)) {
                this.value = '';
                alert('This date is already booked. Please select a different date.');
                return;
            }

            // If this is check-out date, validate the entire range
            if (this.id === 'check_out_date' && checkInDate) {
                validateDateRange();
            }
        });
    }

    // Initialize date inputs
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');

    // Disable booked dates for both inputs
    disableDates(checkInInput);
    disableDates(checkOutInput);

    // Update check-out min date when check-in changes
    checkInInput.addEventListener('change', updateCheckOutMinDate);

    // Add visual indicator for booked dates (optional enhancement)
    function addBookedDateStyles() {
        const style = document.createElement('style');
        style.textContent = `
            input[type="date"]::-webkit-calendar-picker-indicator {
                cursor: pointer;
            }
            .booked-date-indicator {
                color: #ef4444;
                font-size: 0.8rem;
                margin-top: 0.25rem;
            }
        `;
        document.head.appendChild(style);
    }

    addBookedDateStyles();

    // Show booked dates info if any exist
    if (disabledDates.length > 0) {
        const infoDiv = document.createElement('div');
        infoDiv.className = 'mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md';
        infoDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-800">
                        <strong>Note:</strong> Some dates are already booked and cannot be selected.
                        Booked dates are automatically disabled in the calendar.
                    </p>
                </div>
            </div>
        `;

        // Insert before the form
        const form = document.querySelector('form');
        form.parentNode.insertBefore(infoDiv, form);
    }
});
</script>

@endsection
