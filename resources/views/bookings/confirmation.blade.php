@extends('layouts.gridspace')

@section('title', 'Booking Confirmation | GridSpace')

@php
    $listing = $booking->listing;
    $listingImage = $listing?->images->first()
        ? asset('storage/' . $listing->images->first()->image_path)
        : null;
@endphp

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="rounded-xl border border-green-200 bg-green-50 p-6 md:p-8 mb-8">
        <div class="flex flex-col sm:flex-row items-start gap-4">
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-3xl text-green-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>
            <div class="flex-1">
                <h1 class="font-manrope text-2xl md:text-3xl font-bold text-green-900 mb-2">Booking request submitted!</h1>
                <p class="font-inter text-green-800 mb-4">Thank you for your booking request. The host will review it and confirm your reservation.</p>
                <div class="flex flex-wrap gap-4">
                    <div>
                        <p class="font-mono text-xs uppercase tracking-wider text-green-700 mb-1">Reference</p>
                        <p class="font-manrope font-bold text-green-900">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="font-mono text-xs uppercase tracking-wider text-green-700 mb-1">Status</p>
                        <span class="inline-block font-mono text-xs uppercase tracking-wide px-3 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-outline-variant rounded-xl overflow-hidden mb-8">
        @if($listingImage)
            <div class="h-48 bg-surface-container">
                <img src="{{ $listingImage }}" alt="{{ $listing->name }}" class="w-full h-full object-cover">
            </div>
        @endif
        <div class="p-6 md:p-8">
            <h2 class="font-manrope text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-container">receipt_long</span>
                Booking details
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Space</p>
                    @if($booking->space)
                        <p class="font-manrope text-lg font-semibold text-on-surface">{{ $booking->space->name }}</p>
                        <p class="font-inter text-sm text-on-surface-variant">
                            {{ $booking->space->category?->name }}
                            @if($listing) &middot; {{ $listing->name }} @endif
                            @if($listing?->city) &middot; {{ $listing->city->name }} @endif
                        </p>
                    @elseif($listing)
                        <p class="font-manrope text-lg font-semibold text-on-surface">{{ $listing->name }}</p>
                        <p class="font-inter text-sm text-on-surface-variant">{{ $listing->category?->name }} &middot; {{ $listing->city?->name }}</p>
                    @else
                        <p class="font-inter text-on-surface-variant">Listing unavailable</p>
                    @endif
                </div>
                <div>
                    <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Total price</p>
                    <p class="font-manrope text-2xl font-bold text-primary-container">₦{{ number_format($booking->total_price, 0) }}</p>
                </div>
                <div>
                    <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Check-in</p>
                    <p class="font-inter font-medium">{{ $booking->check_in_date->format('F j, Y') }}</p>
                </div>
                <div>
                    <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Check-out</p>
                    <p class="font-inter font-medium">{{ $booking->check_out_date->format('F j, Y') }}</p>
                </div>
                <div>
                    <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Guests</p>
                    <p class="font-inter font-medium">{{ $booking->number_of_people }}</p>
                </div>
            </div>

            <div class="pt-6 border-t border-outline-variant/40">
                <h3 class="font-manrope text-lg font-semibold text-on-surface mb-4">Guest information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Name</p>
                        <p class="font-inter">{{ $booking->guest_name }}</p>
                    </div>
                    <div>
                        <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Email</p>
                        <p class="font-inter">{{ $booking->guest_email }}</p>
                    </div>
                    <div>
                        <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Phone</p>
                        <p class="font-inter">{{ $booking->guest_phone }}</p>
                    </div>
                    @if($booking->notes)
                        <div class="sm:col-span-2">
                            <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Notes</p>
                            <p class="font-inter text-on-surface-variant">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        @auth
            <a href="{{ route('bookings.index') }}" class="inline-flex items-center justify-center gap-2 bg-primary-container text-white px-8 py-4 rounded-xl font-manrope font-semibold shadow-lg shadow-primary-container/20 hover:scale-[1.02] active:scale-95 transition-all">
                <span class="material-symbols-outlined">calendar_month</span>
                View my bookings
            </a>
        @endauth
        <a href="{{ route('listings.index') }}" class="inline-flex items-center justify-center gap-2 bg-white border border-outline-variant text-on-surface px-8 py-4 rounded-xl font-manrope font-semibold hover:border-primary-container hover:text-primary transition-all">
            <span class="material-symbols-outlined">search</span>
            Browse more spaces
        </a>
    </div>
</div>
@endsection
