@extends('layouts.gridspace')

@section('title', 'Book ' . $space->name . ' | GridSpace')

@php
    $listingImage = $listing->images->first()
        ? asset('storage/' . $listing->images->first()->image_path)
        : null;
    $location = $listing->city
        ? $listing->city->name . ($listing->address ? ', ' . $listing->address : '')
        : ($listing->address ?? 'Nigeria');
    $pricePeriod = $space->price_period ?? 'day';
@endphp

@section('content')
<div class="mb-6">
    <a href="{{ route('listings.show', $listing->slug) }}" class="inline-flex items-center gap-2 font-manrope text-sm font-semibold text-secondary hover:text-primary transition-colors group">
        <span class="material-symbols-outlined text-lg transition-transform group-hover:-translate-x-1">arrow_back</span>
        Back to listing
    </a>
</div>

@if(session('error'))
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 flex items-start gap-3">
        <span class="material-symbols-outlined text-red-500 shrink-0">error</span>
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-5 gap-gutter">
    <aside class="lg:col-span-2">
        <div class="bg-white border border-outline-variant rounded-xl overflow-hidden card-lift sticky top-28">
            <div class="relative h-52 bg-surface-container">
                @if($listingImage)
                    <img src="{{ $listingImage }}" alt="{{ $listing->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-5xl text-outline-variant">apartment</span>
                    </div>
                @endif
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">{{ $space->category?->name ?? 'Workspace' }}</p>
                    <h1 class="font-manrope text-2xl font-bold text-on-surface">{{ $space->name }}</h1>
                    <p class="font-inter text-sm text-on-surface-variant mt-1">at {{ $listing->name }}</p>
                </div>
                <div class="flex items-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-lg">location_on</span>
                    <span class="font-inter text-sm">{{ $location }}</span>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-outline-variant/40">
                    <div>
                        <p class="font-mono text-xs uppercase tracking-wider text-secondary">Price</p>
                        <p class="font-manrope text-2xl font-bold text-on-surface">
                            ₦{{ number_format($space->price, 0) }}
                            <span class="text-base font-normal text-on-surface-variant">{{ $space->price_period_label }}</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-mono text-xs uppercase tracking-wider text-secondary">People capacity</p>
                        <p class="font-manrope text-lg font-semibold">{{ $space->capacity }} {{ $space->capacity === 1 ? 'person' : 'people' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <div class="lg:col-span-3">
        <div class="bg-white border border-outline-variant rounded-xl p-6 md:p-8">
            <div class="mb-8">
                <h2 class="font-manrope text-2xl md:text-3xl font-bold text-on-surface mb-2">Complete your booking</h2>
                <p class="font-inter text-on-surface-variant">Select your dates and provide guest details to request this workspace.</p>
            </div>

            <form method="POST" action="{{ route('bookings.store', [$listing, $space]) }}" id="booking-form" class="space-y-8">
                @csrf

                <section>
                    <h3 class="font-manrope text-lg font-semibold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container">calendar_month</span>
                        Dates &amp; guests
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="check_in_date" class="block font-mono text-xs uppercase text-secondary tracking-wider mb-2">Check-in date</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-secondary pointer-events-none">login</span>
                                <input type="date" id="check_in_date" name="check_in_date" value="{{ old('check_in_date') }}"
                                    class="w-full bg-white border border-outline-variant/50 rounded-lg pl-12 pr-4 py-3.5 focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all @error('check_in_date') border-red-500 @enderror"
                                    required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            </div>
                            @error('check_in_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="check_out_date" class="block font-mono text-xs uppercase text-secondary tracking-wider mb-2">Check-out date</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-secondary pointer-events-none">logout</span>
                                <input type="date" id="check_out_date" name="check_out_date" value="{{ old('check_out_date') }}"
                                    class="w-full bg-white border border-outline-variant/50 rounded-lg pl-12 pr-4 py-3.5 focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all @error('check_out_date') border-red-500 @enderror"
                                    required>
                            </div>
                            @error('check_out_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="number_of_people" class="block font-mono text-xs uppercase text-secondary tracking-wider mb-2">Number of people</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-secondary pointer-events-none">group</span>
                            <input type="number" id="number_of_people" name="number_of_people"
                                class="w-full bg-white border border-outline-variant/50 rounded-lg pl-12 pr-4 py-3.5 focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all @error('number_of_people') border-red-500 @enderror"
                                value="{{ old('number_of_people', 1) }}" min="1" max="{{ $space->capacity }}" required>
                        </div>
                        <p class="mt-1 font-mono text-xs text-on-surface-variant">This space holds up to {{ $space->capacity }} {{ $space->capacity === 1 ? 'person' : 'people' }}</p>
                        @error('number_of_people')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </section>

                <section>
                    <h3 class="font-manrope text-lg font-semibold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container">person</span>
                        Guest information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="guest_name" class="block font-mono text-xs uppercase text-secondary tracking-wider mb-2">Full name</label>
                            <input type="text" id="guest_name" name="guest_name"
                                class="w-full bg-white border border-outline-variant/50 rounded-lg px-4 py-3.5 focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all @error('guest_name') border-red-500 @enderror"
                                value="{{ old('guest_name', auth()->user()?->display_name) }}" required>
                            @error('guest_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="guest_email" class="block font-mono text-xs uppercase text-secondary tracking-wider mb-2">Email address</label>
                            <input type="email" id="guest_email" name="guest_email"
                                class="w-full bg-white border border-outline-variant/50 rounded-lg px-4 py-3.5 focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all @error('guest_email') border-red-500 @enderror"
                                value="{{ old('guest_email', auth()->user()?->email) }}" required>
                            @error('guest_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="guest_phone" class="block font-mono text-xs uppercase text-secondary tracking-wider mb-2">Phone number</label>
                            <input type="tel" id="guest_phone" name="guest_phone"
                                class="w-full bg-white border border-outline-variant/50 rounded-lg px-4 py-3.5 focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all @error('guest_phone') border-red-500 @enderror"
                                value="{{ old('guest_phone', auth()->user()?->phone) }}" required>
                            @error('guest_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="notes" class="block font-mono text-xs uppercase text-secondary tracking-wider mb-2">Additional notes <span class="normal-case">(optional)</span></label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full bg-white border border-outline-variant/50 rounded-lg px-4 py-3.5 focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all resize-none @error('notes') border-red-500 @enderror"
                                placeholder="Any special requirements...">{{ old('notes') }}</textarea>
                            @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                <div class="pt-6 border-t border-outline-variant/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="font-inter text-sm text-on-surface-variant text-center sm:text-left">
                        Your request will be sent to the host for confirmation.
                    </p>
                    <button type="submit" class="w-full sm:w-auto bg-primary-container text-white px-8 py-4 rounded-xl font-manrope font-semibold shadow-lg shadow-primary-container/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">event_available</span>
                        Submit booking request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookedDates = @json($bookedDates);
    const disabledDates = [];

    bookedDates.forEach(function(booking) {
        const checkIn = new Date(booking.check_in_date);
        const checkOut = new Date(booking.check_out_date);
        const currentDate = new Date(checkIn);
        while (currentDate < checkOut) {
            disabledDates.push(currentDate.toISOString().split('T')[0]);
            currentDate.setDate(currentDate.getDate() + 1);
        }
    });

    function isDateDisabled(dateString) {
        return disabledDates.includes(dateString);
    }

    function updateCheckOutMinDate() {
        const checkInDate = document.getElementById('check_in_date').value;
        const checkOutInput = document.getElementById('check_out_date');
        if (checkInDate) {
            const minCheckOut = new Date(checkInDate);
            minCheckOut.setDate(minCheckOut.getDate() + 1);
            checkOutInput.min = minCheckOut.toISOString().split('T')[0];
            if (checkOutInput.value && new Date(checkOutInput.value) <= new Date(checkInDate)) {
                checkOutInput.value = '';
            }
        }
    }

    function hasDateRangeConflict(checkInDate, checkOutDate) {
        if (!checkInDate || !checkOutDate) return false;
        const checkIn = new Date(checkInDate);
        const checkOut = new Date(checkOutDate);
        const currentDate = new Date(checkIn);
        while (currentDate < checkOut) {
            if (disabledDates.includes(currentDate.toISOString().split('T')[0])) return true;
            currentDate.setDate(currentDate.getDate() + 1);
        }
        return false;
    }

    function validateDateRange() {
        const checkInDate = document.getElementById('check_in_date').value;
        const checkOutDate = document.getElementById('check_out_date').value;
        if (checkInDate && checkOutDate && hasDateRangeConflict(checkInDate, checkOutDate)) {
            document.getElementById('check_out_date').value = '';
            alert('One or more dates in your selected range are already booked. Please select different dates.');
            return false;
        }
        return true;
    }

    function disableDates(input) {
        input.addEventListener('input', function() {
            if (this.value && isDateDisabled(this.value)) {
                this.value = '';
                alert('This date is already booked. Please select a different date.');
                return;
            }
            if (this.id === 'check_out_date') validateDateRange();
        });
    }

    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    disableDates(checkInInput);
    disableDates(checkOutInput);
    checkInInput.addEventListener('change', updateCheckOutMinDate);

    if (disabledDates.length > 0) {
        const infoDiv = document.createElement('div');
        infoDiv.className = 'mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 flex items-start gap-3';
        infoDiv.innerHTML = '<span class="material-symbols-outlined text-amber-500 shrink-0">info</span><p><strong>Note:</strong> Some dates are already booked and cannot be selected.</p>';
        document.getElementById('booking-form').prepend(infoDiv);
    }
});
</script>
@endpush
