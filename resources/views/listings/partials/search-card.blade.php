@php
    $image = $listing->images->first()
        ? asset('storage/' . $listing->images->first()->image_path)
        : ($fallbackImage ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuDusWxMYECK90nYcLBfLEIg4FGqS6U-gnC6qgmGwRkg3VU-sFszrgf9euXucetAkjMzviORmeFzlH-FynVBMun13kzNwrqTHQK_1YiqiTFFQsLPdD9OkjZotwGCSmILdsJCoR0F4DZ5BR4-EBZK4NDvEQ_IwBNMiKhcR12Tv54ZbXCEFVHgY7fiAtAe6M40Mqbmv-cN9AI152VSd0FejMx_ODYGcK1a8P_Gq9mSDTgXk8PrxRlpV7VZqz1-EjzUylEXSvfAMwQaNrk');
    $location = $listing->city
        ? $listing->city->name . ($listing->address ? ', ' . Str::limit($listing->address, 25) : '')
        : ($listing->address ?? 'Nigeria');
    $price = $listing->min_price > 0
        ? $listing->price_from
        : ($listing->price_range ?: 'Contact for price');
    $bookUrl = route('listings.show', $listing->slug) . '#spaces';
    $bookLabel = $listing->min_price > 0 ? 'View Spaces' : 'View Details';
@endphp

<article class="bg-white rounded-xl overflow-hidden border border-outline-variant hover-lift flex flex-col h-full">
    <a href="{{ route('listings.show', $listing->slug) }}" class="block">
        <div class="h-48 w-full relative">
            <img src="{{ $image }}" alt="{{ $listing->name }}" class="w-full h-full object-cover">
            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-2 py-1 rounded-lg flex items-center gap-1 shadow-sm">
                <span class="material-symbols-outlined text-primary-container text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                <span class="font-mono text-xs font-bold">4.8</span>
            </div>
            @if($listing->featured)
                <span class="absolute top-4 left-4 bg-primary-container text-white font-mono text-xs uppercase tracking-wide px-2 py-1 rounded-full">Featured</span>
            @endif
        </div>
    </a>
    <div class="p-6 flex flex-col flex-1">
        <a href="{{ route('listings.show', $listing->slug) }}" class="font-manrope text-xl font-semibold text-on-surface mb-2 hover:text-primary-container transition-colors">{{ $listing->name }}</a>
        <p class="font-inter text-sm text-on-surface-variant mb-4 line-clamp-2 flex-1">{{ Str::limit($listing->description, 100) }}</p>
        <div class="space-y-2 mb-6">
            <div class="flex items-center gap-2 text-on-surface-variant">
                <span class="material-symbols-outlined text-lg">location_on</span>
                <span class="font-inter text-sm truncate">{{ $location }}</span>
            </div>
            <div class="flex items-center gap-2 text-primary-container font-bold">
                <span class="material-symbols-outlined text-lg">payments</span>
                <span class="font-inter text-sm">{{ $price }}</span>
            </div>
        </div>
        <a href="{{ $bookUrl }}" class="w-full bg-primary-container text-white py-3 rounded-lg font-manrope font-semibold text-center hover:bg-primary transition-all active:scale-95 block">
            {{ $bookLabel }}
        </a>
    </div>
</article>
