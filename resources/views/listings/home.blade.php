@extends('layouts.home')

@section('title', 'GridSpace - Find a flexible workspace near you')

@section('content')
@php
    $heroImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuCVbUx5ex8rgVuC6bVY0a_jZOq5akzUT-fOpWN3yeh1F62BWVXvHvEjE8hivErkk--Quny_qyqCWoVBTzHUucED4I1lt0Oc1zvjFKlRj6azHEHnnOue13LrIwNaTICNFEwBY60dIq02N0y9BvZRtSApg6hnfE-7nu9FdVb1iqAkL3FLbCq994QclvUbsnqE9yJe6WLRW63BKjntRlJ_iJcGtQEfjSTtFVlLOSOVOSJAnjoT-KW1Jf2jkaMQMjEZ1BV-8k_CLDWXtl0';
    $fallbackListingImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuCTp2s1Hs1YRRylGaNnImFuO1FKV9Cs-9v-C35165x9zxj8SCO1o9Y4SI1cLeM-8HhcFlgXYvmsWGaLxuW03DSNPDDIi6vmwSGp9Ou08_7LUsNDNroTY8Vot3T5Us7aZh3vlblUCI0W5oCwCdH4aQSYWs5swvJfUZ0tPsfgxsAzzT4aHIhCq8iFOEPLxFPYyo0-iUFG5y5bUfmEAbZjYjw2pjd467lJQuVmnGOcWyXA5_WshG_FTI2B3nocn1ugAsMMdZIroWK_9o8';
    $cityImages = [
        'lagos' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAO6jnccSDYZ5gHIUXzZTq3YmTm95mZxW2FOjI_noAAba-oP15hL3bAtyugJyW32d6kibpjtniQyzBL4O_KezektHSRz-9wlFrek6wHR0TT1QpCv_sMIR8mbhO2Sb4mCsxiO55HB-7n8EtNto5TUlVzMiJ4MCa2MSNRZMHG9oJ_3X0zpe3MFV46ehpp6N2KQX3Q6xTZ2y3AS8Jh6dpuXzn_JtRkDKaJtNjlnPB36H9QH5yaLGbORCuMiLv_KZaaxMqfaacwVDENnJE',
        'abuja' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDEKdEXlOEUgMn-Dlmz1iFznljkhOdIvrdk3nHhwRaHafhxkAVo8p9q97vhNykUcfEJnNCpM3Slxrnr5w86NM2wb9qTc7t31SrtPQnfw0JffDhcsKnPHN3ImRocnesV4nWOPie0Inp4jxxw1H3R5fI_6q45oCdt8ejv-itz-bWtbzjVFfGeF3TMHaIC4Z7ws_1UsdwJx7x83psOFUMeFAyleswb7OT-6fJhOktgg3b5gloOFDXmt94jRbIplfSJwhfUKqjTHgE1038',
        'port-harcourt' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB41M8XWR7htCvg-6ZzpqOih19rW0EYxH4a_c9zxhZBMRw7ACW4jRuxDEJyFBlw4W13eOiwUBb9KDwO3tBzg7PztX_7d5JnpnOpVQ1nGNb7kAexFaIR4bO8SYEe7ib09_4aKagTZgGGVZpHUEgo6rU7vaTYsSfP8IhSuJF8xngHtaVrOcsx3Z5n5bscvovPPYWwLhh2DDDHYlIJESfrMN-LzGiClmdGphInRAqtnCTf9DH3m5URtVhQakBc0sGihWspyVZvlG_5jiw',
    ];
    $defaultCityImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAO6jnccSDYZ5gHIUXzZTq3YmTm95mZxW2FOjI_noAAba-oP15hL3bAtyugJyW32d6kibpjtniQyzBL4O_KezektHSRz-9wlFrek6wHR0TT1QpCv_sMIR8mbhO2Sb4mCsxiO55HB-7n8EtNto5TUlVzMiJ4MCa2MSNRZMHG9oJ_3X0zpe3MFV46ehpp6N2KQX3Q6xTZ2y3AS8Jh6dpuXzn_JtRkDKaJtNjlnPB36H9QH5yaLGbORCuMiLv_KZaaxMqfaacwVDENnJE';
@endphp

{{-- Mobile: search-first (Airbnb-style) --}}
<section class="md:hidden sticky top-14 z-40 bg-white border-b border-outline-variant/40 px-4 py-3">
    <form method="GET" action="{{ route('listings.index') }}" id="mobileSearchForm" class="relative">
        <div class="flex items-center gap-2 bg-white rounded-full border border-outline-variant/60 shadow-md px-4 py-2.5">
            <span class="material-symbols-outlined text-primary-container text-[22px] shrink-0">search</span>
            <div class="flex-1 min-w-0">
                <input type="text"
                       name="search"
                       id="mobileSearchInput"
                       value="{{ request('search') }}"
                       placeholder="Search workspaces"
                       class="w-full border-none focus:ring-0 text-sm font-semibold text-navy placeholder:text-gray-400 p-0"
                       autocomplete="off">
                <select name="city" class="w-full border-none focus:ring-0 text-[11px] text-gray-500 bg-transparent p-0 mt-0.5">
                    <option value="">Anywhere in Nigeria</option>
                    @foreach($cities->sortBy('state')->groupBy('state') as $state => $stateCities)
                        <optgroup label="{{ $state ?: 'Other' }}">
                            @foreach($stateCities->sortBy('name') as $city)
                                <option value="{{ $city->slug }}" @selected(request('city') === $city->slug)>{{ $city->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="w-9 h-9 rounded-full bg-primary-container text-white flex items-center justify-center shrink-0"
                    aria-label="Search">
                <span class="material-symbols-outlined text-[18px]">tune</span>
            </button>
        </div>
        <div id="mobileSearchResults" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 hidden z-50 max-h-80 overflow-y-auto"></div>
    </form>
</section>

{{-- Desktop hero --}}
<section class="hidden md:block relative bg-surface py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <h1 class="text-5xl lg:text-6xl font-extrabold text-navy leading-tight mb-6">
                Find a flexible <br/><span class="text-primary">workspace</span> near you
            </h1>
            <p class="text-lg text-gray-600 mb-10 max-w-lg">
                Discover verified, flexible workspaces with reliable power, high-speed internet, and everything you need to stay productive on the go.
            </p>

            <form method="GET" action="{{ route('listings.index') }}" id="heroSearchForm" class="bg-white p-2 rounded-grid shadow-xl border border-gray-100 flex flex-col md:flex-row items-center gap-2 max-w-2xl relative">
                <div class="flex-1 flex items-center px-4 md:border-r border-gray-200 w-full">
                    <svg class="w-5 h-5 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Enter location or workspace name" class="w-full border-none focus:ring-0 text-sm py-3" autocomplete="off"/>
                </div>
                <div class="flex-1 flex items-center px-4 w-full">
                    <svg class="w-5 h-5 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <select name="city" class="w-full border-none focus:ring-0 text-sm py-3 bg-transparent text-gray-700">
                        <option value="">All cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->slug }}" @selected(request('city') === $city->slug)>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full md:w-auto bg-primary text-white font-bold px-8 py-3 rounded-grid hover:bg-orange-600 transition flex items-center justify-center gap-2 shrink-0">
                    Find a Space
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
                <div id="searchResults" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-grid shadow-xl border border-gray-100 hidden z-50 max-h-96 overflow-y-auto"></div>
            </form>

            <div class="mt-12 flex flex-wrap gap-8 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-primary">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3.005 3.005 0 013.75-2.906z"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-navy text-xl">50K+</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Happy Users</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-primary">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-navy text-xl">{{ $cities->sum('listings_count') ?: '1200' }}+</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Locations</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-primary">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-navy text-xl">4.9/5</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Rating</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative flex justify-center">
            <div class="relative w-full max-w-md aspect-[3/4] overflow-hidden rounded-2xl shadow-2xl">
                <img src="{{ $heroImage }}" alt="Workspace" class="w-full h-full object-cover"/>
            </div>
            <div class="absolute -top-4 -right-4 w-24 h-24 border-t-4 border-r-4 border-primary rounded-tr-3xl"></div>
            <div class="absolute -bottom-4 -left-4 w-24 h-24 border-b-4 border-l-4 border-navy rounded-bl-3xl"></div>
        </div>
    </div>
</section>

@if($hasActiveFilters)
<section class="py-8 md:py-12 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-extrabold text-navy">Search Results</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $listings->total() }} workspace{{ $listings->total() === 1 ? '' : 's' }} found</p>
            </div>
            <a href="{{ route('listings.index') }}" class="text-sm font-semibold text-primary hover:text-orange-600">Clear filters</a>
        </div>

        @if($listings->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
                @foreach($listings as $listing)
                    @include('listings.partials.card', ['listing' => $listing, 'fallbackImage' => $fallbackListingImage])
                @endforeach
            </div>
            {{ $listings->links() }}
        @else
            <div class="text-center py-16 bg-surface rounded-xl">
                <p class="text-gray-600 mb-4">No workspaces match your search. Try different filters.</p>
                <a href="{{ route('listings.index') }}" class="inline-block bg-primary text-white font-bold px-8 py-3 rounded-grid hover:bg-orange-600 transition">Browse all spaces</a>
            </div>
        @endif
    </div>
</section>
@endif

<!-- Featured Workspaces -->
<section id="featured-workspaces" class="py-6 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4 mb-5 md:mb-16 md:text-center md:flex-col md:items-center">
            <div class="md:text-center">
                <h2 class="text-xl md:text-3xl font-extrabold text-navy md:mb-4">Featured Workspaces</h2>
                <p class="hidden md:block text-gray-500">Discover the most popular coworking spaces trusted by thousands of professionals</p>
            </div>
            <a href="{{ route('featured') }}" class="md:hidden font-inter text-sm font-semibold text-primary-container shrink-0">See all</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-8 mb-6 md:mb-12">
            @forelse($featuredListings as $listing)
                @include('listings.partials.card', ['listing' => $listing, 'fallbackImage' => $fallbackListingImage])
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <p>No featured workspaces yet. Check back soon!</p>
                </div>
            @endforelse
        </div>
        <div class="hidden md:block text-center">
            <a href="{{ route('featured') }}" class="inline-block bg-primary text-white font-bold px-10 py-3 rounded-grid hover:bg-orange-600 transition">View All Spaces</a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section id="how-it-works" class="hidden md:block py-20 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-extrabold text-navy mb-4">How GridSpace Works</h2>
            <p class="text-gray-500">Get access to productive workspaces in just three simple steps.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-12">
            <div class="text-center flex flex-col items-center">
                <div class="w-16 h-16 bg-navy text-white rounded-full flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-navy mb-4">Search &amp; Discover</h3>
                <p class="text-gray-500">Browse verified workspaces in your area with detailed photos, amenities and real-time availability.</p>
            </div>
            <div class="text-center flex flex-col items-center">
                <div class="w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-navy mb-4">Book Instantly</h3>
                <p class="text-gray-500">Reserve your perfect workspace instantly with secure payment and flexible booking options.</p>
            </div>
            <div class="text-center flex flex-col items-center">
                <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center mb-6 shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-navy mb-4">Work Productively</h3>
                <p class="text-gray-500">Arrive and get productive immediately with reliable power, fast WiFi and a professional environment.</p>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose GridSpace -->
<section class="hidden md:block py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-extrabold text-navy mb-4">Why Choose GridSpace</h2>
            <p class="text-gray-500">Experience the difference with our commitment to quality and reliability</p>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-surface p-10 rounded-xl flex items-start space-x-6">
                <div class="bg-green-100 p-3 rounded-lg text-green-600 shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-navy mb-2">Reliable Power &amp; Internet</h4>
                    <p class="text-gray-600">Never worry about connectivity with guaranteed backup power and high-speed internet at every location.</p>
                </div>
            </div>
            <div class="bg-navy p-10 rounded-xl flex items-start space-x-6 text-white">
                <div class="bg-primary p-3 rounded-lg text-white shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-2">Verified &amp; Secure</h4>
                    <p class="text-blue-100">Every workspace is thoroughly vetted and verified to ensure quality, safety, and professionalism.</p>
                </div>
            </div>
            <div class="bg-surface p-10 rounded-xl flex items-start space-x-6">
                <div class="bg-purple-100 p-3 rounded-lg text-purple-600 shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-navy mb-2">Affordable Rates</h4>
                    <p class="text-gray-600">Get access to quality workspaces that suit your budget, whether you're looking for a day or a month.</p>
                </div>
            </div>
            <div class="bg-blue-50 p-10 rounded-xl flex items-start space-x-6">
                <div class="bg-blue-100 p-3 rounded-lg text-blue-600 shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-navy mb-2">Flexible Booking</h4>
                    <p class="text-gray-600">Book by the hour, day, or month with easy cancellation and modification options.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Locations -->
<section class="hidden md:block py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-navy mb-4">Popular Locations</h2>
        <p class="text-gray-500 mb-12">Explore workspaces in Nigeria's thriving business districts</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($cities->take(3) as $city)
                <a href="{{ route('listings.index', ['city' => $city->slug]) }}" class="relative group cursor-pointer h-80 rounded-xl overflow-hidden shadow-lg block">
                    <img src="{{ $cityImages[$city->slug] ?? $defaultCityImage }}" alt="{{ $city->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500"/>
                    <div class="absolute inset-0 gradient-overlay"></div>
                    <div class="absolute bottom-6 left-6 text-left">
                        <h3 class="text-2xl font-bold text-white">{{ $city->name }}</h3>
                        <p class="text-gray-200">{{ $city->listings_count }}+ workspaces</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Hosting CTA -->
<section class="hidden md:block py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-50 rounded-3xl overflow-hidden shadow-sm grid lg:grid-cols-2">
            <div class="p-12 lg:p-20">
                <h2 class="text-4xl font-extrabold text-navy mb-6">Earn Money Hosting Workspaces</h2>
                <p class="text-gray-600 mb-10 text-lg">
                    Transform your unused office space into a revenue stream. Join thousands of hosts earning money by sharing their workspace with professionals.
                </p>
                <ul class="space-y-6 mb-12">
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center text-green-600 mt-1 mr-4 shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-navy">Flexible Earnings</h4>
                            <p class="text-sm text-gray-500">Set your own prices and availability. Be your own boss on your schedule.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mt-1 mr-4 shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-navy">Secure Platform</h4>
                            <p class="text-sm text-gray-500">Verified users and secure payments handled by our platform.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mt-1 mr-4 shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-navy">Build Community</h4>
                            <p class="text-sm text-gray-500">Connect with entrepreneurs and professionals in your area.</p>
                        </div>
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="inline-block bg-primary text-white font-bold px-10 py-4 rounded-grid hover:bg-orange-600 transition shadow-lg shadow-orange-200">
                    Start Hosting Today
                </a>
            </div>
            <div class="hidden lg:block relative min-h-[320px]">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAeHrIR0g906hiCOVVkHDH78TMQlcIh6wUbemceTFPOYyC3h4Rj2oaW-mz6vFIggfbmc9flbVhQZW8qOss4D-T_VOFJ-JEdZTHkurTl3c-xrIEufdAmWGlLwKIbU4s5jcwv-b92vEn60jYMMQOLJC7B3ogrI66rFsMPWCDm_yxTAXRgv7rTgVgiyybA-DeOnZkYT32qSjFC4GFlEQSuRAudnFxjXBkxEuKHF_uCbmVYT2X1NIyQeuxOdSul-kWFdNof7YFM6BOx-_A" alt="Hosting Workspace" class="w-full h-full object-cover absolute inset-0"/>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section id="testimonials" class="hidden md:block py-24 bg-orange-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-extrabold text-navy mb-4">What Our Users Say</h2>
            <p class="text-gray-600">Join thousands of professionals who trust GridSpace for their workspace needs</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach([
                ['name' => 'Chukwu Davis', 'role' => 'Freelance UX Designer', 'quote' => 'GridSpace makes it easy to find reliable workspaces with power and internet. I don\'t have to worry about power disruptions anymore.', 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAMWsP93bjk5MeV7N_0PgzIoFfz3ROdA2-Flr19gBnNDl9m0NgcIQSfcy-OuCoaIZ484CBwARVmK7LNnDigIjuqcZ8t-_NB7G1HDNl5Mdwq-ZtippM1wlo_sStjWAMtXlIbZB2GeKFWEuLo8LVP0_-vBQ5FP1qYM9LRwtsFpTSZ9ikRLEO7vRNxdsVhQoSaFse60Evry4YSpudiX2sVggg8kg0esWZa2g6syhlU0zwLeSlbiEaNoAK3Ie4bpWeSYOhix861mG4USMI'],
                ['name' => 'Tobi Junior', 'role' => 'Software Engineer', 'quote' => 'As a developer, I love the premium vibe of the spaces I book on GridSpace. It\'s affordable and keeps me inspired.', 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD1-jKeMg0GzEsbqlGRU2uaxa3th02VbZ1aBhq_VvfgDmEMXZ7Uta6oZNEHtLN0Jq5jRD5PFp25ASrP_qaShcK-KdCuns-M9Ssj93uYqvHMAjpu-NVYtsQP5x8bTrBL-daBj3gGKdtvGVYWuzqW64PH2TXhDkkcwLBa83pgw0gsiFIOSApPRSSD4mbfYjhtU_G8ZZO3PYGO8rTP9ShiYNve7nuDySrq_fLfb2I-Wls1T2CpQDpneQk-zPkUuACk-vCEHV0b0OfTgpU'],
                ['name' => 'Tosin Elaiya', 'role' => 'Startup Founder', 'quote' => 'My team now has a proper office without expensive leases. GridSpace gave us the flexibility we needed to grow.', 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBtaNWSgp71zsdsRAHOAib1bdD7b6J71pW9qDiul9tUfIrXZMeIqfg1Xh7SXqwIlYARDS1WB6enBrA-xSyLMKbySJ07jKUcACC5YiZGnp2bBzrQnDeLTMIFF1mIo5LLdCV2FZ1O8fLD-ArRqAl6vbpyQnQwzjw4zlum1WtxDZOQMmfRfRNq3yaeaBNIue8gQcApLy8wR1LpeWG5pGJwYb4VYHaIh_09cFNuLTwebAB26NB2qORvatqj4excm1ZXhBfwEVNOvotgJg8'],
            ] as $testimonial)
            <div class="bg-white p-8 rounded-xl shadow-sm relative">
                <div class="absolute -top-4 right-8 text-primary opacity-20">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                </div>
                <div class="flex items-center mb-6">
                    <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}" class="w-12 h-12 rounded-full mr-4 object-cover"/>
                    <div>
                        <h4 class="font-bold text-navy">{{ $testimonial['name'] }}</h4>
                        <p class="text-xs text-gray-400">{{ $testimonial['role'] }}</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">"{{ $testimonial['quote'] }}"</p>
                <div class="flex text-yellow-400">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="hidden md:block py-24 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative bg-navy rounded-[2rem] p-12 lg:p-20 text-white overflow-hidden">
            <div class="absolute top-0 right-0 w-1/3 h-full opacity-10">
                <svg class="w-full h-full" fill="white" viewBox="0 0 100 100"><circle cx="100" cy="50" r="40"/></svg>
            </div>
            <div class="relative z-10 grid lg:grid-cols-2 items-center gap-12">
                <div>
                    <h2 class="text-4xl lg:text-5xl font-extrabold mb-6 leading-tight">Ready to Find Your Perfect Workspace?</h2>
                    <p class="text-xl text-blue-100 mb-10">Join thousands of professionals who trust GridSpace for their workspace needs</p>
                    <a href="{{ route('listings.index') }}" class="inline-block bg-primary text-white font-bold px-12 py-4 rounded-grid text-lg hover:bg-orange-600 transition shadow-xl">
                        Start Searching
                    </a>
                </div>
                <div class="hidden lg:block">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfT2PHai37fDLdk2Ekrw6IUqzoX3tvpgnlYk3Sdv3bczzTa6o_9pQG-WVVJO5TVcoBAlbcu-6TUti41AGtbShltcC2dO-YVj9U2QGmZSYYYCU9hY70EvZXjsERR38W9RsNVQA69om7v94Wr_sV1lTpaptvGrjNhXInG-wtkRT641mL2fGvxCKTF3h40LELTYtRXrVnuzAND4q55FesMTd4KWYSzfXwdo0mcyWamgRDiMW1BYfOu_a-0vJUAj9D3ECVf7QwD8d_2YQ" alt="Productive Professional" class="rounded-2xl shadow-2xl"/>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="hidden md:block py-20 bg-surface">
    <div class="max-w-2xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-extrabold text-navy mb-4">Join the Grid</h2>
        <p class="text-gray-500 mb-8">Get workspace tips, updates, and exclusive offers straight to your inbox.</p>
        <form class="flex flex-col sm:flex-row gap-3" onsubmit="event.preventDefault();">
            <input class="flex-1 rounded-grid border-gray-300 focus:ring-primary focus:border-primary px-6 py-3" placeholder="Enter your email" type="email"/>
            <button class="bg-primary text-white font-bold px-8 py-3 rounded-grid hover:bg-orange-600 transition" type="submit">Subscribe Now</button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function bindLiveSearch(inputId, resultsId) {
        const searchInput = document.getElementById(inputId);
        const searchResults = document.getElementById(resultsId);
        let searchTimeout;

        if (!searchInput || !searchResults) return;

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(searchTimeout);
            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }
            searchTimeout = setTimeout(() => performLiveSearch(query, searchResults), 300);
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    }

    function performLiveSearch(query, searchResults) {
        fetch(`{{ route('listings.index') }}?search=${encodeURIComponent(query)}&live=1`)
            .then(r => r.json())
            .then(data => displaySearchResults(data.listings, query, searchResults))
            .catch(() => searchResults.classList.add('hidden'));
    }

    function displaySearchResults(listings, query, searchResults) {
        if (!listings.length) {
            searchResults.innerHTML = `<div class="p-4 text-center text-gray-500 text-sm">No results for "${query}"</div>`;
        } else {
            searchResults.innerHTML = listings.slice(0, 5).map(l => `
                <a href="/listings/${l.slug}" class="block p-4 hover:bg-surface border-b border-gray-100 last:border-0">
                    <h4 class="text-sm font-bold text-navy">${l.name}</h4>
                    <p class="text-xs text-gray-500">${l.category_name}</p>
                    <p class="text-xs text-primary font-semibold mt-1">${l.price_range}</p>
                </a>
            `).join('');
        }
        searchResults.classList.remove('hidden');
    }

    bindLiveSearch('searchInput', 'searchResults');
    bindLiveSearch('mobileSearchInput', 'mobileSearchResults');
});
</script>
@endpush
