@extends('layouts.dashboard')

@section('title', 'GridSpace | Dashboard')

@section('content')
@php
    $user = auth()->user();
    $recommended = $recommendedListing ?? null;
    $recommendedImage = $recommended && $recommended->images->first()
        ? asset('storage/' . $recommended->images->first()->image_path)
        : 'https://lh3.googleusercontent.com/aida-public/AB6AXuDi5op3kwLxZNJjnmB1U5yfTmAMzIABpkYlz1a04TgtA_1r4GYbYx0rVK-O1zq_mU6xnjguf2rXpzURIJa-ndRJmep3X3IQIa56QEGrOB-LWfZ-Em7R7DsdnKS0aeVhDuZ1ODZUi7wupRY62YAUKquyyTWOHU3ZXzp9Io0hc5VqSqeEbdNUm4egSxsu5Oz8iE-qpZBG95wqffNDgbysVBDO0JtB6dBxgXeJkTb7Pfac7OajY9w9O2UvQiHhs_FwLLJs-90PPZrZr_g';
    $recommendedLocation = $recommended
        ? ($recommended->city ? $recommended->city->name . ($recommended->address ? ', ' . $recommended->address : '') : ($recommended->address ?? 'Nigeria'))
        : null;
    $recommendedPrice = $recommended
        ? ($recommended->price > 0 ? $recommended->formatted_price : ($recommended->price_range ?: 'Contact for price'))
        : null;
@endphp

<section class="mb-stack-lg">
    <h1 class="font-manrope text-4xl md:text-5xl font-extrabold text-on-surface mb-2 tracking-tight">Welcome {{ $user->firstname }}!</h1>
    <p class="font-inter text-lg text-on-surface-variant">Discover your next workspace or manage existing bookings</p>
</section>

@if(session('success'))
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter mb-stack-lg">
    <a href="{{ route('listings.index') }}" class="bg-white border border-outline-variant rounded-xl p-6 text-center card-lift group block">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center group-hover:bg-primary-fixed transition-colors">
            <span class="material-symbols-outlined text-3xl text-on-surface-variant group-hover:text-primary">search</span>
        </div>
        <h3 class="font-manrope text-xl font-semibold mb-1">Find Workspace</h3>
        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide">Search for available workspace</p>
    </a>

    <a href="{{ route('bookings.index') }}" class="bg-white border border-outline-variant rounded-xl p-6 text-center card-lift group block">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center group-hover:bg-primary-fixed transition-colors">
            <span class="material-symbols-outlined text-3xl text-on-surface-variant group-hover:text-primary">calendar_month</span>
        </div>
        <h3 class="font-manrope text-xl font-semibold mb-1">My Bookings</h3>
        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide">View upcoming bookings</p>
    </a>

    <a href="{{ route('wallet.index') }}" class="bg-white border border-outline-variant rounded-xl p-6 text-center card-lift group block">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center group-hover:bg-primary-fixed transition-colors">
            <span class="material-symbols-outlined text-3xl text-on-surface-variant group-hover:text-primary">account_balance_wallet</span>
        </div>
        <h3 class="font-manrope text-xl font-semibold mb-1">Wallet</h3>
        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide">₦{{ number_format(auth()->user()->wallet_balance ?? 0, 0) }}</p>
    </a>

    <a href="{{ route('inquiries.index') }}" class="bg-white border border-outline-variant rounded-xl p-6 text-center card-lift group block">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center group-hover:bg-primary-fixed transition-colors">
            <span class="material-symbols-outlined text-3xl text-on-surface-variant group-hover:text-primary">chat_bubble</span>
        </div>
        <h3 class="font-manrope text-xl font-semibold mb-1">Messages</h3>
        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide">Chat with hosts</p>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
    <div class="lg:col-span-2 bg-white border border-outline-variant rounded-xl p-6 md:p-10 flex flex-col min-h-[400px]">
        <div class="flex items-start justify-between w-full mb-6">
            <h2 class="font-manrope text-2xl md:text-3xl font-bold text-on-surface">Recent Activity</h2>
            @if($recentBookings->isNotEmpty())
                <a href="{{ route('bookings.index') }}" class="font-mono text-xs text-primary-container hover:underline uppercase tracking-wide">View all</a>
            @endif
        </div>

        @if($recentBookings->isNotEmpty())
            <div class="space-y-4 flex-1">
                @foreach($recentBookings as $booking)
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-outline-variant/40 hover:bg-surface-container-low transition-colors">
                        <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-outline">event</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-manrope font-semibold text-on-surface truncate">
                                @if($booking->listing)
                                    <a href="{{ route('listings.show', $booking->listing->slug) }}" class="hover:text-primary-container transition-colors">
                                        {{ $booking->listing->name }}
                                    </a>
                                @else
                                    Booking #{{ $booking->id }}
                                @endif
                            </p>
                            <p class="font-inter text-sm text-on-surface-variant">
                                {{ $booking->check_in_date?->format('M j, Y') }}
                                @if($booking->check_out_date)
                                    &mdash; {{ $booking->check_out_date->format('M j, Y') }}
                                @endif
                            </p>
                        </div>
                        <span class="font-mono text-xs uppercase tracking-wide px-3 py-1 rounded-full
                            @if($booking->status === 'confirmed') bg-green-50 text-green-700
                            @elseif($booking->status === 'pending') bg-yellow-50 text-yellow-700
                            @else bg-surface-container text-on-surface-variant
                            @endif">
                            {{ $booking->status }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center flex-1 py-12">
                <div class="w-20 h-20 mb-6 rounded-full bg-surface-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-4xl text-outline-variant">calendar_today</span>
                </div>
                <p class="text-on-surface-variant font-inter text-lg mb-8">No activity yet</p>
                <a href="{{ route('listings.index') }}" class="bg-primary-container text-white px-8 py-4 rounded-xl font-manrope font-semibold shadow-lg shadow-primary-container/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Book your first Space
                </a>
            </div>
        @endif
    </div>

    <div class="bg-white border border-outline-variant rounded-xl overflow-hidden card-lift">
        <div class="p-6">
            <h2 class="font-manrope text-2xl md:text-3xl font-bold text-on-surface mb-6">Recommended Space</h2>

            @if($recommended)
                <a href="{{ route('listings.show', $recommended->slug) }}" class="block group">
                    <div class="relative">
                        <div class="rounded-xl overflow-hidden h-56 mb-4">
                            <img
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                src="{{ $recommendedImage }}"
                                alt="{{ $recommended->name }}"
                            >
                        </div>
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-outlined text-yellow-500 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="font-mono text-xs text-on-surface font-bold">4.75</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <h3 class="font-manrope text-xl font-semibold text-on-surface group-hover:text-primary-container transition-colors">{{ $recommended->name }}</h3>
                        <div class="flex items-center gap-2 text-on-surface-variant mb-4">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            <span class="font-inter text-sm truncate">{{ $recommendedLocation }}</span>
                        </div>
                        <div class="pt-4 border-t border-outline-variant flex justify-between items-center gap-4">
                            <p class="font-manrope text-2xl font-bold text-on-surface">{{ $recommendedPrice }}</p>
                            <span class="text-primary-container font-manrope font-semibold group-hover:underline shrink-0">View Details</span>
                        </div>
                    </div>
                </a>
            @else
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 mb-4 rounded-full bg-surface-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl text-outline-variant">apartment</span>
                    </div>
                    <p class="text-on-surface-variant font-inter mb-6">No spaces to recommend yet.</p>
                    <a href="{{ route('listings.index') }}" class="text-primary-container font-manrope font-semibold hover:underline">Browse all spaces</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
