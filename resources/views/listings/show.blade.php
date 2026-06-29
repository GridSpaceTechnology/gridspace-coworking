@extends('layouts.gridspace')

@section('title', $listing->name . ' | GridSpace')

@php
    $images = $listing->images;
    $location = $listing->city
        ? $listing->city->name . ($listing->address ? ', ' . $listing->address : '')
        : ($listing->address ?? 'Nigeria');
    $pricePeriod = $listing->price_period ?? 'day';
    $host = $listing->user;
    $amenityIcons = [
        'wifi' => 'wifi', 'coffee' => 'coffee', 'parking' => 'local_parking',
        'ac' => 'ac_unit', 'air-conditioning' => 'ac_unit', 'print' => 'print',
        'printer' => 'print', 'security' => 'security', 'quiet' => 'volume_off',
        'power' => 'battery_charging_full', 'meeting' => 'groups', 'kitchen' => 'restaurant',
    ];
@endphp

@push('head')
<style>
    .bento-gallery {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: repeat(2, 180px);
        gap: 12px;
    }
    .bento-main { grid-column: span 2; grid-row: span 2; }
    .booking-card { position: sticky; top: 100px; }
    @media (max-width: 768px) {
        .bento-gallery { grid-template-columns: 1fr; grid-template-rows: auto; }
        .bento-main { grid-column: span 1; grid-row: span 1; height: 240px; }
        .booking-card { position: static; }
    }
</style>
@endpush

@section('content')
{{-- Breadcrumbs & actions --}}
<div class="flex justify-between items-center mb-6 gap-4">
    <nav class="flex items-center gap-2 text-on-surface-variant font-inter text-sm flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-base">chevron_right</span>
        @if($listing->city)
            <a href="{{ route('listings.index', ['city' => $listing->city->slug]) }}" class="hover:text-primary transition-colors">{{ $listing->city->name }}</a>
            <span class="material-symbols-outlined text-base">chevron_right</span>
        @endif
        <span class="font-semibold text-on-surface truncate max-w-[200px] sm:max-w-none">{{ $listing->name }}</span>
    </nav>
    <div class="flex gap-2 shrink-0">
        <button type="button" onclick="navigator.share?.({ title: '{{ addslashes($listing->name) }}', url: window.location.href })" class="p-2 border border-outline rounded-full hover:bg-surface-container transition-all" aria-label="Share">
            <span class="material-symbols-outlined text-xl">share</span>
        </button>
    </div>
</div>

{{-- Bento gallery --}}
<section class="bento-gallery mb-8 md:mb-12 rounded-xl overflow-hidden">
    @if($images->isNotEmpty())
        <div class="bento-main relative">
            <img src="{{ asset('storage/' . $images->first()->image_path) }}" alt="{{ $listing->name }}" class="w-full h-full object-cover">
            @if($listing->featured)
                <span class="absolute top-4 left-4 bg-primary-container text-white font-mono text-xs uppercase tracking-wide px-3 py-1 rounded-full">Featured</span>
            @endif
        </div>
        @foreach($images->skip(1)->take(4) as $image)
            <div class="hidden md:block">
                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $listing->name }}" class="w-full h-full object-cover">
            </div>
        @endforeach
        @for($i = $images->count(); $i < 5; $i++)
            @if($i > 0)
                <div class="hidden md:block bg-surface-container"></div>
            @endif
        @endfor
    @else
        <div class="bento-main bg-surface-container flex items-center justify-center min-h-[240px] md:min-h-0">
            <span class="material-symbols-outlined text-6xl text-outline-variant">apartment</span>
        </div>
    @endif
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
    {{-- Main content --}}
    <div class="lg:col-span-2 space-y-8 md:space-y-12">
        {{-- Title & meta --}}
        <section>
            <h1 class="font-manrope text-3xl md:text-4xl font-bold text-on-surface mb-3">{{ $listing->name }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-on-surface-variant font-inter text-sm">
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-primary-container text-lg">location_on</span>
                    <span>{{ $location }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-primary-container text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span class="font-bold text-on-surface">4.8</span>
                </div>
                @if($listing->capacity)
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-lg">group</span>
                        <span>Up to {{ $listing->capacity }} people</span>
                    </div>
                @endif
                @if($listing->category)
                    <span class="bg-primary-fixed text-primary px-3 py-1 rounded-full font-mono text-xs uppercase tracking-wide">{{ $listing->category->name }}</span>
                @endif
            </div>
        </section>

        <hr class="border-outline-variant/50">

        {{-- Description --}}
        <section>
            <h2 class="font-manrope text-xl md:text-2xl font-semibold text-on-surface mb-4">About this space</h2>
            <div class="space-y-4 text-on-surface-variant font-inter text-base leading-relaxed">
                {!! nl2br(e($listing->description)) !!}
            </div>
        </section>

        <hr class="border-outline-variant/50">

        {{-- Host --}}
        @if($host)
            <section class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-6 bg-white border border-outline-variant rounded-xl shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full overflow-hidden bg-primary-container flex items-center justify-center shrink-0">
                        @if($host->profile_photo_url)
                            <img src="{{ $host->profile_photo_url }}" alt="{{ $host->display_name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-white font-manrope font-bold text-xl">{{ strtoupper(substr($host->firstname, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-manrope font-bold text-lg text-on-surface">{{ $host->display_name }}</h3>
                            <span class="bg-primary-container text-white px-2 py-0.5 rounded font-mono text-xs uppercase tracking-wide">Host</span>
                        </div>
                        <p class="text-sm text-on-surface-variant">
                            @if($host->residence){{ $host->residence }} &middot; @endif
                            Host since {{ $host->created_at->format('Y') }}
                        </p>
                    </div>
                </div>
                <a href="#inquiry-form" class="flex items-center gap-2 border-2 border-primary-container text-primary-container font-manrope font-semibold px-6 py-2 rounded-lg hover:bg-primary-container/5 transition-all shrink-0">
                    <span class="material-symbols-outlined">mail</span>
                    Contact
                </a>
            </section>
        @endif

        {{-- Amenities --}}
        @if($listing->amenities->isNotEmpty())
            <section>
                <h2 class="font-manrope text-xl md:text-2xl font-semibold text-on-surface mb-6">Amenities</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($listing->amenities as $amenity)
                        @php
                            $icon = $amenityIcons[strtolower($amenity->icon ?? '')] ?? 'check_circle';
                        @endphp
                        <div class="flex items-center gap-4 p-4 border border-outline-variant rounded-lg bg-white">
                            <span class="material-symbols-outlined text-primary-container">{{ $icon }}</span>
                            <span class="font-inter font-medium text-on-surface">{{ $amenity->name }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
            <hr class="border-outline-variant/50">
        @endif

        {{-- Contact actions --}}
        <section>
            <h2 class="font-manrope text-xl md:text-2xl font-semibold text-on-surface mb-4">Get in touch</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @if($listing->contact_phone)
                    <a href="{{ route('track', ['listing' => $listing->id, 'type' => 'phone']) }}" class="flex items-center justify-center gap-2 bg-white border border-outline-variant rounded-xl py-3 font-manrope font-semibold text-on-surface hover:border-primary-container hover:text-primary transition-all">
                        <span class="material-symbols-outlined">call</span>
                        Call
                    </a>
                @endif
                @if($listing->whatsapp_number)
                    <a href="{{ route('track', ['listing' => $listing->id, 'type' => 'whatsapp']) }}" class="flex items-center justify-center gap-2 bg-white border border-outline-variant rounded-xl py-3 font-manrope font-semibold text-on-surface hover:border-green-500 hover:text-green-600 transition-all">
                        <span class="material-symbols-outlined">chat</span>
                        WhatsApp
                    </a>
                @endif
                @if($listing->website)
                    <a href="{{ $listing->website }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 bg-white border border-outline-variant rounded-xl py-3 font-manrope font-semibold text-on-surface hover:border-primary-container hover:text-primary transition-all">
                        <span class="material-symbols-outlined">language</span>
                        Website
                    </a>
                @endif
            </div>
        </section>

        <hr class="border-outline-variant/50">

        {{-- Inquiry form --}}
        <section id="inquiry-form" class="bg-white border border-outline-variant rounded-xl p-6 md:p-8">
            <h2 class="font-manrope text-xl md:text-2xl font-semibold text-on-surface mb-2">Make an inquiry</h2>
            <p class="font-inter text-on-surface-variant mb-6">Interested in this space? Send a message and the host will get back to you.</p>

            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('inquiries.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="listing_id" value="{{ $listing->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-mono text-xs uppercase text-secondary tracking-wider mb-2">Your name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()?->display_name) }}" required
                            class="w-full bg-white border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all">
                    </div>
                    <div>
                        <label class="block font-mono text-xs uppercase text-secondary tracking-wider mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" {{ auth()->check() ? 'readonly' : '' }} required
                            class="w-full bg-white border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all {{ auth()->check() ? 'bg-surface-container-low' : '' }}">
                    </div>
                </div>
                <div>
                    <label class="block font-mono text-xs uppercase text-secondary tracking-wider mb-2">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" required
                        class="w-full bg-white border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block font-mono text-xs uppercase text-secondary tracking-wider mb-2">Message</label>
                    <textarea name="message" rows="4" required placeholder="Tell us about your requirements..."
                        class="w-full bg-white border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all resize-none">{{ old('message') }}</textarea>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="newsletter_opt_in" value="1" class="rounded border-outline-variant text-primary-container focus:ring-primary-container">
                    <span class="font-inter text-sm text-on-surface-variant">Send me updates about similar spaces</span>
                </label>
                <button type="submit" class="w-full sm:w-auto bg-primary-container text-white px-8 py-4 rounded-xl font-manrope font-semibold shadow-lg shadow-primary-container/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">send</span>
                    Send inquiry
                </button>
            </form>
        </section>
    </div>

    {{-- Sidebar: booking card --}}
    <aside class="lg:col-span-1">
        <div class="booking-card bg-white border border-outline-variant rounded-xl p-6 shadow-sm space-y-6">
            <div>
                <p class="font-mono text-xs uppercase tracking-wider text-secondary mb-1">From</p>
                <p class="font-manrope text-3xl font-bold text-on-surface">
                    @if($listing->price > 0)
                        ₦{{ number_format($listing->price, 0) }}
                        <span class="text-lg font-normal text-on-surface-variant">/{{ $pricePeriod }}</span>
                    @else
                        <span class="text-xl">{{ $listing->price_range ?: 'Contact for price' }}</span>
                    @endif
                </p>
            </div>

            @if($listing->available && $listing->price > 0)
                <a href="{{ route('bookings.create', $listing->slug) }}" class="w-full bg-primary-container text-white px-6 py-4 rounded-xl font-manrope font-semibold shadow-lg shadow-primary-container/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">event_available</span>
                    Book this space
                </a>
            @elseif(!$listing->available)
                <div class="w-full bg-surface-container text-on-surface-variant px-6 py-4 rounded-xl font-manrope font-semibold text-center flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">event_busy</span>
                    Currently unavailable
                </div>
            @else
                <a href="#inquiry-form" class="w-full bg-primary-container text-white px-6 py-4 rounded-xl font-manrope font-semibold shadow-lg shadow-primary-container/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">mail</span>
                    Request pricing
                </a>
            @endif

            <div class="space-y-3 pt-4 border-t border-outline-variant/40 text-sm font-inter text-on-surface-variant">
                @if($listing->capacity)
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary-container">group</span>
                        <span>Up to {{ $listing->capacity }} people</span>
                    </div>
                @endif
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary-container">verified</span>
                    <span>Verified listing</span>
                </div>
            </div>

            @auth
                @if(auth()->id() === $listing->user_id && !$listing->featured)
                    <div class="pt-4 border-t border-outline-variant/40">
                        <p class="font-inter text-sm text-on-surface-variant mb-3">Boost visibility with a featured listing.</p>
                        <a href="{{ route('feature-requests.create', $listing) }}" class="w-full flex items-center justify-center gap-2 border-2 border-amber-400 text-amber-700 px-4 py-3 rounded-xl font-manrope font-semibold hover:bg-amber-50 transition-all">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                            Request featured
                        </a>
                    </div>
                @endif
            @endauth

            {{-- Map --}}
            <div class="pt-4 border-t border-outline-variant/40">
                <h3 class="font-manrope font-semibold text-on-surface mb-3">Location</h3>
                <div class="bg-surface-container rounded-xl h-48 overflow-hidden">
                    <div id="map" class="w-full h-full"></div>
                </div>
                <p class="mt-3 font-inter text-sm text-on-surface-variant">{{ $listing->address }}</p>
                @if($listing->city)
                    <p class="font-inter text-sm text-on-surface-variant">{{ $listing->city->name }}</p>
                @endif
            </div>
        </div>
    </aside>
</div>
@endsection

@push('scripts')
<script>
function initMap() {
    const address = @json(trim($listing->address . ' ' . ($listing->city?->name ?? '')));
    const mapDiv = document.getElementById('map');
    if (!mapDiv || typeof google === 'undefined') return;

    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address }, (results, status) => {
        if (status === 'OK') {
            const map = new google.maps.Map(mapDiv, {
                zoom: 15,
                center: results[0].geometry.location,
                disableDefaultUI: true,
                zoomControl: true,
            });
            new google.maps.Marker({
                position: results[0].geometry.location,
                map,
                title: @json($listing->name),
            });
        } else {
            mapDiv.innerHTML = '<div class="flex items-center justify-center h-full text-on-surface-variant gap-2"><span class="material-symbols-outlined">location_off</span>Map unavailable</div>';
        }
    });
}
window.initMap = initMap;
</script>
@if(env('GOOGLE_MAPS_API_KEY'))
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap"></script>
@else
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapDiv = document.getElementById('map');
    if (mapDiv) {
        mapDiv.innerHTML = '<div class="flex items-center justify-center h-full text-on-surface-variant text-sm text-center px-4"><span class="material-symbols-outlined mr-2">map</span>{{ $location }}</div>';
    }
});
</script>
@endif
@endpush
