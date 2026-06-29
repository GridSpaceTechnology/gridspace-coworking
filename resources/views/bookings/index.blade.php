@extends('layouts.gridspace')

@section('title', 'My Bookings | GridSpace')

@section('content')
<section class="mb-8">
    <h1 class="font-manrope text-4xl md:text-5xl font-extrabold text-on-surface mb-2 tracking-tight">My Bookings</h1>
    <p class="font-inter text-lg text-on-surface-variant">View and track your workspace reservations</p>
</section>

@if(session('success'))
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 flex items-center gap-3">
        <span class="material-symbols-outlined text-green-600">check_circle</span>
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter mb-8">
    <a href="{{ route('bookings.index') }}" class="bg-white border border-outline-variant rounded-xl p-6 card-lift group block">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center group-hover:bg-primary-fixed transition-colors">
                <span class="material-symbols-outlined text-2xl text-on-surface-variant group-hover:text-primary">calendar_month</span>
            </div>
        </div>
        <p class="font-manrope text-3xl font-bold text-on-surface mb-1">{{ $stats->total ?? 0 }}</p>
        <p class="font-manrope text-sm font-semibold text-on-surface">Total Bookings</p>
        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide mt-1">All time</p>
    </a>

    <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="bg-white border border-outline-variant rounded-xl p-6 card-lift group block">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                <span class="material-symbols-outlined text-2xl text-amber-600">hourglass_top</span>
            </div>
        </div>
        <p class="font-manrope text-3xl font-bold text-on-surface mb-1">{{ $stats->pending ?? 0 }}</p>
        <p class="font-manrope text-sm font-semibold text-on-surface">Pending</p>
        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide mt-1">Awaiting host</p>
    </a>

    <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}" class="bg-white border border-outline-variant rounded-xl p-6 card-lift group block">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center group-hover:bg-green-100 transition-colors">
                <span class="material-symbols-outlined text-2xl text-green-600">check_circle</span>
            </div>
        </div>
        <p class="font-manrope text-3xl font-bold text-on-surface mb-1">{{ $stats->confirmed ?? 0 }}</p>
        <p class="font-manrope text-sm font-semibold text-on-surface">Confirmed</p>
        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide mt-1">{{ $stats->upcoming ?? 0 }} upcoming</p>
    </a>

    <div class="bg-white border border-outline-variant rounded-xl p-6 card-lift">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl text-primary-container">payments</span>
            </div>
        </div>
        <p class="font-manrope text-3xl font-bold text-primary-container mb-1">₦{{ number_format($stats->total_spent ?? 0, 0) }}</p>
        <p class="font-manrope text-sm font-semibold text-on-surface">Total Spent</p>
        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide mt-1">Confirmed &amp; completed</p>
    </div>
</div>

<div class="flex flex-wrap gap-2 mb-8">
    @foreach(['all' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $value => $label)
        @php
            $current = request('status', 'all');
            $active = $current === $value || ($value === 'all' && !request()->has('status'));
        @endphp
        <a href="{{ $value === 'all' ? route('bookings.index') : route('bookings.index', ['status' => $value]) }}"
           class="px-4 py-2 rounded-xl font-manrope text-sm font-semibold transition-all
                  {{ $active ? 'bg-primary-container text-white shadow-sm' : 'bg-white border border-outline-variant text-secondary hover:border-primary-container hover:text-primary' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="space-y-4">
    @forelse($bookings as $booking)
        @php
            $image = $booking->listing?->images->first()
                ? asset('storage/' . $booking->listing->images->first()->image_path)
                : null;
            $statusStyles = match($booking->status) {
                'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                'confirmed' => 'bg-green-50 text-green-800 border-green-200',
                'completed' => 'bg-blue-50 text-blue-800 border-blue-200',
                'cancelled' => 'bg-gray-50 text-gray-600 border-gray-200',
                default => 'bg-surface-container text-on-surface-variant border-outline-variant',
            };
        @endphp
        <article class="bg-white border border-outline-variant rounded-xl overflow-hidden card-lift">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-48 h-40 md:h-auto bg-surface-container shrink-0">
                    @if($image)
                        <img src="{{ $image }}" alt="{{ $booking->listing->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center min-h-[10rem]">
                            <span class="material-symbols-outlined text-4xl text-outline-variant">apartment</span>
                        </div>
                    @endif
                </div>
                <div class="flex-1 p-6">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                        <div>
                            @if($booking->listing)
                                <a href="{{ route('listings.show', $booking->listing->slug) }}" class="font-manrope text-xl font-bold text-on-surface hover:text-primary-container transition-colors">
                                    {{ $booking->listing->name }}
                                </a>
                                <p class="font-inter text-sm text-on-surface-variant mt-1 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">location_on</span>
                                    {{ $booking->listing->address }}
                                </p>
                            @else
                                <p class="font-manrope text-xl font-bold text-on-surface">Booking #{{ $booking->id }}</p>
                            @endif
                        </div>
                        <span class="font-mono text-xs uppercase tracking-wide px-3 py-1 rounded-full border {{ $statusStyles }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Check-in</p>
                            <p class="font-inter text-sm font-medium">{{ $booking->check_in_date->format('M j, Y') }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Check-out</p>
                            <p class="font-inter text-sm font-medium">{{ $booking->check_out_date->format('M j, Y') }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Guests</p>
                            <p class="font-inter text-sm font-medium">{{ $booking->number_of_people }}</p>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">Total</p>
                            <p class="font-manrope text-lg font-bold text-primary-container">₦{{ number_format($booking->total_price, 0) }}</p>
                        </div>
                    </div>

                    <p class="font-mono text-xs text-on-surface-variant mt-4">Ref #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }} &middot; Booked {{ $booking->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </article>
    @empty
        <div class="bg-white border border-outline-variant rounded-xl p-12 md:p-16 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-outline-variant">calendar_today</span>
            </div>
            <h3 class="font-manrope text-xl font-bold text-on-surface mb-2">No bookings found</h3>
            <p class="font-inter text-on-surface-variant mb-8">
                @if(request('status') && request('status') !== 'all')
                    You don't have any {{ request('status') }} bookings yet.
                @else
                    You haven't made any bookings yet.
                @endif
            </p>
            <a href="{{ route('listings.index') }}" class="inline-flex items-center gap-2 bg-primary-container text-white px-8 py-4 rounded-xl font-manrope font-semibold shadow-lg shadow-primary-container/20 hover:scale-[1.02] active:scale-95 transition-all">
                <span class="material-symbols-outlined">search</span>
                Find a workspace
            </a>
        </div>
    @endforelse
</div>

@if($bookings->hasPages())
    <div class="mt-8">
        {{ $bookings->withQueryString()->links() }}
    </div>
@endif
@endsection
