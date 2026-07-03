@extends('layouts.admin')

@section('title', 'Admin Dashboard | GridSpace')

@section('admin_content')
@php $adminName = auth()->user()->firstname; @endphp

<section class="mb-8">
    <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] tracking-tight">Welcome {{ $adminName }}!</h1>
    <p class="font-inter text-sm text-on-surface-variant mt-1">Manage the GridSpace platform and monitor performance</p>
</section>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-800 font-inter">{{ session('success') }}</div>
@endif

{{-- Quick action cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-gutter mb-8">
    @foreach([
        ['route' => 'admin.listings.index', 'label' => 'Listing Management', 'icon' => 'apartment', 'desc' => 'Approve and manage spaces'],
        ['route' => 'admin.users.index', 'label' => 'User Management', 'icon' => 'group', 'desc' => 'Manage accounts'],
        ['route' => 'admin.bookings.index', 'label' => 'Booking Management', 'icon' => 'event_available', 'desc' => 'Track reservations'],
        ['route' => 'admin.blog.index', 'label' => 'Blog', 'icon' => 'article', 'desc' => 'Manage content'],
    ] as $card)
        <a href="{{ route($card['route']) }}"
           class="bg-white border border-outline-variant/60 rounded-2xl p-5 md:p-6 card-lift hover:border-[#1c2c40]/20 transition-colors group">
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center mb-4 group-hover:bg-[#1c2c40] transition-colors">
                <span class="material-symbols-outlined text-2xl text-on-surface group-hover:text-white transition-colors">{{ $card['icon'] }}</span>
            </div>
            <h3 class="font-manrope font-bold text-[#1c2c40] text-sm md:text-base">{{ $card['label'] }}</h3>
            <p class="font-inter text-xs text-on-surface-variant mt-1 hidden sm:block">{{ $card['desc'] }}</p>
        </a>
    @endforeach
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-gutter mb-8">
    <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
        <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-2">Total Listings</p>
        <p class="font-manrope text-3xl font-bold text-[#1c2c40]">{{ number_format($stats['total_listings']) }}</p>
        <p class="font-inter text-xs text-green-600 mt-2">{{ $stats['listings_today'] }} created today</p>
    </div>
    <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
        <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-2">Total Users</p>
        <p class="font-manrope text-3xl font-bold text-[#1c2c40]">{{ number_format($stats['total_users']) }}</p>
        <p class="font-inter text-xs text-green-600 mt-2">{{ $stats['users_today'] }} joined today</p>
    </div>
    <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
        <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-2">Active Bookings</p>
        <p class="font-manrope text-3xl font-bold text-[#1c2c40]">{{ number_format($stats['active_bookings']) }}</p>
        <p class="font-inter text-xs text-on-surface-variant mt-2">{{ $stats['bookings_ending_today'] }} ending today</p>
    </div>
    <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
        <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-2">Monthly Revenue</p>
        <p class="font-manrope text-3xl font-bold text-[#1c2c40]">₦{{ number_format($stats['monthly_revenue'], 0) }}</p>
        <p class="font-inter text-xs {{ $stats['revenue_change'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
            {{ $stats['revenue_change'] >= 0 ? '+' : '' }}{{ $stats['revenue_change'] }}% vs last month
        </p>
    </div>
</div>

@if($stats['pending_listings'] > 0 || $stats['pending_featured_requests'] > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 mb-8 flex flex-wrap items-center gap-4">
        <span class="material-symbols-outlined text-amber-600">pending_actions</span>
        <p class="font-inter text-sm text-amber-900 flex-1">
            @if($stats['pending_listings'] > 0)
                <strong>{{ $stats['pending_listings'] }}</strong> listing(s) awaiting approval.
            @endif
            @if($stats['pending_featured_requests'] > 0)
                <strong>{{ $stats['pending_featured_requests'] }}</strong> featured request(s) pending.
            @endif
        </p>
        @if($stats['pending_listings'] > 0)
            <a href="{{ route('admin.listings.index') }}?status=pending"
               class="font-inter text-sm font-semibold text-primary-container hover:underline">Review listings</a>
        @endif
    </div>
@endif

{{-- Recent activity --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-outline-variant/60 rounded-2xl overflow-hidden card-lift">
        <div class="px-6 py-4 border-b border-outline-variant/40 flex items-center justify-between">
            <h2 class="font-manrope font-bold text-[#1c2c40]">Recent Listings</h2>
            <a href="{{ route('admin.listings.index') }}" class="font-inter text-xs font-semibold text-primary-container hover:underline">View all</a>
        </div>
        <div class="divide-y divide-outline-variant/30">
            @forelse($recentListings->take(5) as $listing)
                <div class="px-6 py-4 flex items-center gap-3">
                    @if($listing->images->first())
                        <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-outline">apartment</span>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-inter text-sm font-medium text-[#1c2c40] truncate">{{ $listing->name }}</p>
                        <p class="font-inter text-xs text-on-surface-variant">{{ $listing->user?->display_name ?? 'Unknown host' }}</p>
                    </div>
                    <span class="shrink-0 px-2 py-0.5 rounded-full text-[11px] font-semibold capitalize
                        {{ $listing->status === 'published' ? 'bg-green-100 text-green-800' : ($listing->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700') }}">
                        {{ $listing->status === 'published' ? 'Active' : $listing->status }}
                    </span>
                </div>
            @empty
                <div class="p-10 text-center font-inter text-sm text-on-surface-variant">No listings yet</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white border border-outline-variant/60 rounded-2xl overflow-hidden card-lift">
        <div class="px-6 py-4 border-b border-outline-variant/40 flex items-center justify-between">
            <h2 class="font-manrope font-bold text-[#1c2c40]">Recent Bookings</h2>
            <a href="{{ route('admin.bookings.index') }}" class="font-inter text-xs font-semibold text-primary-container hover:underline">View all</a>
        </div>
        <div class="divide-y divide-outline-variant/30">
            @forelse($recentBookings as $booking)
                <div class="px-6 py-4 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-inter text-sm font-medium text-[#1c2c40]">{{ $booking->guest_name }}</p>
                        <p class="font-inter text-xs text-on-surface-variant truncate">{{ $booking->listing?->name }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-manrope text-sm font-semibold">₦{{ number_format($booking->total_price ?? 0, 0) }}</p>
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold capitalize
                            {{ in_array($booking->status, ['confirmed', 'completed']) ? 'bg-green-100 text-green-800' : ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ $booking->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center font-inter text-sm text-on-surface-variant">No bookings yet</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
