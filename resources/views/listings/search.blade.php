@extends('layouts.gridspace')

@section('title', 'Find Your Perfect Workspace | GridSpace')

@php
    $fallbackImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuDusWxMYECK90nYcLBfLEIg4FGqS6U-gnC6qgmGwRkg3VU-sFszrgf9euXucetAkjMzviORmeFzlH-FynVBMun13kzNwrqTHQK_1YiqiTFFQsLPdD9OkjZotwGCSmILdsJCoR0F4DZ5BR4-EBZK4NDvEQ_IwBNMiKhcR12Tv54ZbXCEFVHgY7fiAtAe6M40Mqbmv-cN9AI152VSd0FejMx_ODYGcK1a8P_Gq9mSDTgXk8PrxRlpV7VZqz1-EjzUylEXSvfAMwQaNrk';
    $selectedCategories = array_values(array_unique(array_filter(array_merge(
        request()->filled('category') ? [request('category')] : [],
        (array) request('categories', [])
    ))));
    $selectedAmenities = array_map('intval', (array) request('amenities', []));
    $amenityIcons = [
        'wifi' => 'wifi', 'coffee' => 'coffee', 'parking' => 'local_parking',
        'ac' => 'ac_unit', 'print' => 'print', 'security' => 'security',
        'av' => 'settings_input_component', 'meeting' => 'groups',
    ];
    $currentCity = $cities->firstWhere('slug', request('city'));
@endphp

@push('head')
<style>
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(10, 37, 64, 0.1); }
    .list-view article { display: flex; flex-direction: row; }
    .list-view article > a { width: 280px; shrink: 0; }
    .list-view article .h-48 { height: 100%; min-height: 180px; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    @media (max-width: 768px) {
        .list-view article { flex-direction: column; }
        .list-view article > a { width: 100%; }
    }
</style>
@endpush

@section('content')
{{-- Search bar --}}
<section class="mb-10">
    <form method="GET" action="{{ route('listings.index') }}" id="searchForm" class="bg-white p-4 md:p-6 rounded-xl border border-outline-variant shadow-sm grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <div class="md:col-span-3 space-y-2">
            <label for="search" class="font-mono text-xs uppercase tracking-wider text-on-surface-variant block">Location</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">location_on</span>
                <input id="search" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-3 rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary-container focus:border-primary bg-surface font-inter text-sm outline-none" placeholder="Enter location or city" type="text">
            </div>
        </div>
        <div class="md:col-span-2 space-y-2">
            <label for="city" class="font-mono text-xs uppercase tracking-wider text-on-surface-variant block">City</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">map</span>
                <select id="city" name="city" class="w-full pl-10 pr-4 py-3 rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary-container focus:border-primary bg-surface font-inter text-sm outline-none appearance-none">
                    <option value="">All cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->slug }}" @selected(request('city') === $city->slug)>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="md:col-span-2 space-y-2">
            <label for="date" class="font-mono text-xs uppercase tracking-wider text-on-surface-variant block">Date</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">calendar_today</span>
                <input id="date" name="date" value="{{ request('date') }}" class="w-full pl-10 pr-4 py-3 rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary-container focus:border-primary bg-surface font-inter text-sm outline-none" type="date" min="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="md:col-span-2 space-y-2">
            <label for="capacity" class="font-mono text-xs uppercase tracking-wider text-on-surface-variant block">Guests</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">group</span>
                <input id="capacity" name="capacity" value="{{ request('capacity') }}" class="w-full pl-10 pr-4 py-3 rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary-container focus:border-primary bg-surface font-inter text-sm outline-none" placeholder="Guests" type="number" min="1">
            </div>
        </div>
        <div class="md:col-span-3">
            <button type="submit" class="w-full bg-primary-container text-white py-3.5 rounded-lg font-manrope font-bold flex items-center justify-center gap-2 hover:bg-primary transition-all active:scale-95">
                <span class="material-symbols-outlined">search</span>
                Find a Space
            </button>
        </div>
    </form>
</section>

{{-- Results header --}}
<section class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <h2 class="font-manrope text-2xl md:text-3xl font-bold text-on-surface">
        {{ $listings->total() }} Space{{ $listings->total() === 1 ? '' : 's' }} Found
        @if($currentCity)<span class="text-on-surface-variant font-normal text-lg"> in {{ $currentCity->name }}</span>@endif
    </h2>
    <div class="flex items-center gap-2">
        <div class="flex border border-outline-variant rounded-lg p-1 bg-surface-container-low">
            <button type="button" id="view-grid" class="p-2 rounded-lg bg-white text-primary-container shadow-sm" aria-label="Grid view">
                <span class="material-symbols-outlined">grid_view</span>
            </button>
            <button type="button" id="view-list" class="p-2 rounded-lg text-on-surface-variant hover:text-primary transition-colors" aria-label="List view">
                <span class="material-symbols-outlined">list</span>
            </button>
        </div>
    </div>
</section>

<div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-8">
    {{-- Sidebar filters --}}
    <aside class="md:col-span-3">
        <form method="GET" action="{{ route('listings.index') }}" class="bg-white p-6 rounded-xl border border-outline-variant sticky top-24 space-y-8">
            @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
            @if(request('city'))<input type="hidden" name="city" value="{{ request('city') }}">@endif
            @if(request('date'))<input type="hidden" name="date" value="{{ request('date') }}">@endif
            @if(request('capacity'))<input type="hidden" name="capacity" value="{{ request('capacity') }}">@endif

            <div class="flex justify-between items-center">
                <h3 class="font-manrope text-xl font-semibold text-on-surface">Filter</h3>
                <a href="{{ route('listings.index') }}" class="text-primary-container font-mono text-xs uppercase tracking-wide hover:underline">Reset</a>
            </div>

            <div>
                <h4 class="font-inter font-bold text-on-surface mb-4">Price Range</h4>
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">₦</span>
                        <input name="min_price" value="{{ request('min_price', '') }}" class="w-full pl-6 pr-2 py-2 border border-outline-variant rounded-lg font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container" type="number" placeholder="Min" min="0">
                    </div>
                    <span class="text-outline-variant">—</span>
                    <div class="relative flex-1">
                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">₦</span>
                        <input name="max_price" value="{{ request('max_price', '') }}" class="w-full pl-6 pr-2 py-2 border border-outline-variant rounded-lg font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container" type="number" placeholder="Max" min="0">
                    </div>
                </div>
            </div>

            @if($categories->isNotEmpty())
                <div>
                    <h4 class="font-inter font-bold text-on-surface mb-4">Space Type</h4>
                    <div class="space-y-3">
                        @foreach($categories as $category)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="categories[]" value="{{ $category->slug }}" @checked(in_array($category->slug, $selectedCategories))
                                    class="rounded border-outline-variant text-primary-container focus:ring-primary-container">
                                <span class="font-inter text-sm text-on-surface-variant group-hover:text-primary transition-colors">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($amenities->isNotEmpty())
                <div>
                    <h4 class="font-inter font-bold text-on-surface mb-4">Amenities</h4>
                    <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar">
                        @foreach($amenities as $amenity)
                            @php $icon = $amenityIcons[strtolower($amenity->icon ?? '')] ?? 'check_circle'; @endphp
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" @checked(in_array($amenity->id, $selectedAmenities))
                                    class="rounded border-outline-variant text-primary-container focus:ring-primary-container">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg text-primary-container">{{ $icon }}</span>
                                    <span class="font-inter text-sm text-on-surface-variant group-hover:text-primary transition-colors">{{ $amenity->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <button type="submit" class="w-full bg-primary-container text-white py-3 rounded-lg font-manrope font-semibold hover:bg-primary transition-all">Apply Filters</button>
            <a href="{{ route('listings.index') }}" class="block w-full text-center text-primary-container font-semibold py-2 hover:underline">Clear Filters</a>
        </form>
    </aside>

    {{-- Results grid --}}
    <div class="md:col-span-9">
        <div id="results-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($listings as $listing)
                @include('listings.partials.search-card', ['listing' => $listing, 'fallbackImage' => $fallbackImage])
            @empty
                <div class="col-span-full bg-white border border-outline-variant rounded-xl p-12 md:p-16 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-surface-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-4xl text-outline-variant">search_off</span>
                    </div>
                    <h3 class="font-manrope text-xl font-bold text-on-surface mb-2">No spaces found</h3>
                    <p class="font-inter text-on-surface-variant mb-8">Try adjusting your filters or search in a different location.</p>
                    <a href="{{ route('listings.index') }}" class="inline-flex items-center gap-2 bg-primary-container text-white px-8 py-4 rounded-xl font-manrope font-semibold hover:scale-[1.02] transition-all">
                        Clear all filters
                    </a>
                </div>
            @endforelse
        </div>

        @if($listings->hasPages())
            <div class="mt-10">
                {{ $listings->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('results-grid');
    const btnGrid = document.getElementById('view-grid');
    const btnList = document.getElementById('view-list');

    btnGrid?.addEventListener('click', function() {
        grid.classList.remove('list-view', 'grid-cols-1');
        grid.classList.add('grid-cols-1', 'md:grid-cols-2', 'xl:grid-cols-3');
        btnGrid.classList.add('bg-white', 'text-primary-container', 'shadow-sm');
        btnList.classList.remove('bg-white', 'text-primary-container', 'shadow-sm');
        btnList.classList.add('text-on-surface-variant');
    });

    btnList?.addEventListener('click', function() {
        grid.classList.add('list-view');
        grid.classList.remove('md:grid-cols-2', 'xl:grid-cols-3');
        grid.classList.add('grid-cols-1');
        btnList.classList.add('bg-white', 'text-primary-container', 'shadow-sm');
        btnGrid.classList.remove('bg-white', 'text-primary-container', 'shadow-sm');
        btnGrid.classList.add('text-on-surface-variant');
    });
});
</script>
@endpush
