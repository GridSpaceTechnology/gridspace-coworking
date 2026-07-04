@extends('layouts.host')

@section('title', 'My Listings | GridSpace')

@section('host_content')
@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-800 font-inter">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm text-red-800 font-inter">{{ session('error') }}</div>
@endif

<section class="mb-6 md:mb-8">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] tracking-tight">My Listings</h1>
            <p class="font-inter text-sm text-on-surface-variant mt-1">Manage your workspaces and track bookings</p>
        </div>
        <button type="button" onclick="openListingModal()"
           class="inline-flex items-center justify-center gap-2 bg-primary-container text-white px-5 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors shrink-0">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Add Listing
        </button>
    </div>
</section>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-gutter mb-8">
    @foreach([
        ['label' => 'Total Listings', 'value' => $stats['total_listings'], 'icon' => 'apartment', 'bg' => 'bg-surface-container'],
        ['label' => 'Approved Listings', 'value' => $stats['approved'], 'icon' => 'check_circle', 'bg' => 'bg-green-50'],
        ['label' => 'Pending Listings', 'value' => $stats['pending'], 'icon' => 'schedule', 'bg' => 'bg-amber-50'],
        ['label' => 'Total Bookings', 'value' => $stats['total_bookings'], 'icon' => 'event_available', 'bg' => 'bg-blue-50'],
    ] as $card)
        <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
            <div class="w-10 h-10 rounded-full {{ $card['bg'] }} flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-xl text-on-surface">{{ $card['icon'] }}</span>
            </div>
            <p class="font-manrope text-2xl md:text-3xl font-bold text-[#1c2c40]">{{ number_format($card['value']) }}</p>
            <p class="font-inter text-xs md:text-sm text-on-surface-variant mt-1">{{ $card['label'] }}</p>
        </div>
    @endforeach
</div>

@if($listings->isEmpty())
    <div class="bg-white border border-outline-variant/60 rounded-2xl p-12 md:p-16 text-center card-lift">
        <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-surface-container flex items-center justify-center">
            <span class="material-symbols-outlined text-4xl text-outline">apartment</span>
        </div>
        <h3 class="font-manrope text-xl font-bold text-[#1c2c40] mb-2">No listings found yet</h3>
        <p class="font-inter text-sm text-on-surface-variant mb-6 max-w-md mx-auto">
            Create your first workspace listing to start receiving bookings from guests.
        </p>
        <button type="button" onclick="openListingModal()"
           class="inline-flex items-center gap-2 bg-primary-container text-white px-6 py-3 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Add Listing
        </button>
    </div>
@else
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6 md:gap-gutter">
        {{-- Listings column --}}
        <div class="xl:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-manrope text-lg font-bold text-[#1c2c40]">Listings</h2>
                <span class="font-inter text-xs text-on-surface-variant">{{ $listings->count() }} total</span>
            </div>
            <div class="space-y-3">
                @foreach($listings as $listing)
                    @php
                        $image = $listing->images->first();
                        $imageUrl = $image
                            ? asset('storage/' . $image->image_path)
                            : 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&h=300&fit=crop';
                        $statusLabel = $listing->status === 'published' ? 'Approved' : ucfirst($listing->status);
                        $statusClass = match($listing->status) {
                            'published' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-amber-100 text-amber-800',
                            default => 'bg-gray-100 text-gray-700',
                        };
                    @endphp
                    <article class="bg-white border border-outline-variant/60 rounded-xl overflow-hidden flex gap-0 card-lift group">
                        <div class="w-28 sm:w-32 shrink-0">
                            <img src="{{ $imageUrl }}" alt="" class="w-full h-full min-h-[100px] object-cover">
                        </div>
                        <div class="flex-1 p-4 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h3 class="font-manrope font-semibold text-[#1c2c40] truncate">{{ $listing->name }}</h3>
                                    <span class="inline-flex mt-1 px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    <a href="{{ route('listings.edit', $listing->slug) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg text-on-surface-variant hover:bg-surface-container hover:text-primary-container transition-colors"
                                       title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('listings.destroy', $listing->slug) }}"
                                          onsubmit="return confirm('Delete this listing?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-on-surface-variant hover:bg-red-50 hover:text-red-600 transition-colors"
                                                title="Delete">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p class="font-manrope font-bold text-primary-container mt-2 text-sm">
                                ₦{{ number_format($listing->price ?? 0, 0) }}/day
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        {{-- Bookings column --}}
        <div class="xl:col-span-3">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-manrope text-lg font-bold text-[#1c2c40]">
                    Bookings
                    @if(($stats['pending_bookings'] ?? 0) > 0)
                        <span class="ml-1 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full bg-amber-100 text-amber-800 text-xs font-semibold">{{ $stats['pending_bookings'] }}</span>
                    @endif
                </h2>
                <a href="{{ route('host.calendar') }}" class="font-inter text-xs font-semibold text-primary-container hover:underline">View calendar</a>
            </div>
            <div class="bg-white border border-outline-variant/60 rounded-2xl overflow-hidden card-lift">
                @if($recentBookings->isEmpty())
                    <div class="p-10 text-center">
                        <span class="material-symbols-outlined text-4xl text-outline mb-3">event_busy</span>
                        <p class="font-inter text-sm text-on-surface-variant">No bookings yet</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-outline-variant/40 bg-surface-container-low/50">
                                    <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Guest</th>
                                    <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Space</th>
                                    <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Date</th>
                                    <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Price</th>
                                    <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Status</th>
                                    <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/30">
                                @foreach($recentBookings as $booking)
                                    @php
                                        $statusLabel = match($booking->status) {
                                            'confirmed' => 'Booked',
                                            'completed' => 'Completed',
                                            'cancelled' => 'Declined',
                                            default => 'Pending',
                                        };
                                        $statusClass = match($booking->status) {
                                            'confirmed', 'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            default => 'bg-amber-100 text-amber-800',
                                        };
                                        $guestName = $booking->user?->display_name ?? $booking->guest_name ?? 'Guest';
                                    @endphp
                                    <tr class="hover:bg-surface-container-low/40 transition-colors">
                                        <td class="px-5 py-4">
                                            <p class="font-inter text-sm font-medium text-[#1c2c40]">{{ $guestName }}</p>
                                            <p class="font-inter text-xs text-on-surface-variant truncate max-w-[140px]">{{ $booking->listing?->name }}</p>
                                        </td>
                                        <td class="px-5 py-4 font-inter text-sm text-on-surface-variant">
                                            {{ $booking->space?->name ?? '—' }}
                                        </td>
                                        <td class="px-5 py-4 font-inter text-sm text-on-surface-variant whitespace-nowrap">
                                            {{ $booking->check_in_date?->format('M d, Y') ?? '—' }}
                                        </td>
                                        <td class="px-5 py-4 font-manrope text-sm font-semibold text-[#1c2c40] whitespace-nowrap">
                                            ₦{{ number_format($booking->total_price ?? 0, 0) }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            @if($booking->status === 'pending')
                                                <div class="inline-flex items-center gap-2">
                                                    <form method="POST" action="{{ route('bookings.update-status', $booking) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg border border-red-300 text-red-600 font-inter text-xs font-semibold hover:bg-red-50">
                                                            Decline
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('bookings.update-status', $booking) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-600 text-white font-inter text-xs font-semibold hover:bg-green-700">
                                                            Accept
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="font-inter text-xs text-on-surface-variant">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

@if($listings->isNotEmpty())
    <details class="mt-10 bg-white border border-outline-variant/60 rounded-2xl overflow-hidden">
        <summary class="px-6 py-4 cursor-pointer font-manrope font-semibold text-[#1c2c40] hover:bg-surface-container-low/50 transition-colors flex items-center justify-between">
            <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-container">star</span>
                Feature Requests
            </span>
            <span class="font-inter text-xs text-on-surface-variant font-normal">{{ $featureRequests->count() }} requests</span>
        </summary>
        <div class="px-6 pb-6 pt-2 border-t border-outline-variant/40">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <p class="font-inter text-sm text-on-surface-variant mb-4">Request admin to feature your listings on the homepage.</p>
                    <form id="featureRequestForm" onsubmit="return handleFeatureRequestSubmit(event)">
                        <div class="space-y-4">
                            <div>
                                <label for="listing_id" class="block font-inter text-sm font-medium text-on-surface mb-1.5">Select Listing</label>
                                <select id="listing_id" required
                                        class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none">
                                    <option value="">Choose a listing...</option>
                                    @foreach($listings as $listing)
                                        <option value="{{ $listing->id }}" {{ $listing->featured ? 'disabled' : '' }}>
                                            {{ $listing->name }}{{ $listing->featured ? ' (Already Featured)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 bg-primary-container text-white px-5 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">send</span>
                                Request Feature
                            </button>
                        </div>
                    </form>
                </div>
                <div class="space-y-3">
                    @forelse($featureRequests as $featureRequest)
                        <div class="border border-outline-variant/50 rounded-xl p-4">
                            <div class="flex items-center gap-3">
                                @if($featureRequest->listing->images->first())
                                    <img src="{{ asset('storage/' . $featureRequest->listing->images->first()->image_path) }}"
                                         alt="" class="w-10 h-10 rounded-lg object-cover">
                                @endif
                                <div>
                                    <p class="font-inter text-sm font-medium text-[#1c2c40]">{{ $featureRequest->listing->name }}</p>
                                    <span class="text-[11px] font-semibold capitalize px-2 py-0.5 rounded-full
                                        @if($featureRequest->status === 'approved') bg-green-100 text-green-800
                                        @elseif($featureRequest->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-amber-100 text-amber-800 @endif">
                                        {{ $featureRequest->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="font-inter text-sm text-on-surface-variant text-center py-6">No feature requests yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </details>
@endif

@include('host.partials.listing-wizard-modal')

@push('head')
<style>
    .wizard-step { display: none; }
    .wizard-step.active { display: block; }
    .step-pill.done { background-color: #ff5a1f; color: #fff; }
    .step-pill.active { background-color: #1c2c40; color: #fff; }
    .amenity-card input:checked + .amenity-inner { border-color: #ff5a1f; background-color: #fff5f0; }
    .amenity-card input:checked + .amenity-inner .amenity-check { opacity: 1; }
</style>
@endpush

@push('scripts')
<script>
function openListingModal() {
    document.getElementById('listing-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeListingModal() {
    document.getElementById('listing-modal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
@include('host.partials.listing-wizard-scripts')
@endpush

@push('scripts')
<script>
function handleFeatureRequestSubmit(event) {
    event.preventDefault();
    const listingId = document.getElementById('listing_id').value;
    if (!listingId) {
        alert('Please select a listing to feature.');
        return false;
    }
    window.location.href = `/feature-requests/create/${listingId}`;
    return false;
}
</script>
@endpush
@endsection
