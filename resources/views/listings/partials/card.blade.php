@php
    $image = $listing->images->first()
        ? asset('storage/' . $listing->images->first()->image_path)
        : ($fallbackImage ?? '');
    $location = $listing->city
        ? ($listing->city->name . ($listing->address ? ', ' . Str::limit($listing->address, 30) : ''))
        : ($listing->address ?? 'Nigeria');
    $price = $listing->price > 0
        ? $listing->formatted_price
        : ($listing->price_range ?: 'Contact for price');
@endphp

<a href="{{ route('listings.show', $listing->slug) }}" class="group cursor-pointer block">
    <div class="relative h-64 overflow-hidden rounded-xl mb-4">
        <img src="{{ $image }}" alt="{{ $listing->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500"/>
        @if($listing->featured)
            <div class="absolute top-4 left-4 bg-primary text-white px-2 py-1 rounded text-xs font-bold">Featured</div>
        @endif
        <div class="absolute top-4 right-4 bg-white px-2 py-1 rounded text-xs font-bold text-navy flex items-center shadow-sm">
            <svg class="w-3 h-3 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            4.8/5
        </div>
    </div>
    <h3 class="text-lg font-bold text-navy group-hover:text-primary transition">{{ $listing->name }}</h3>
    <p class="text-sm text-gray-500 mb-2">{{ $location }}</p>
    <p class="text-primary font-extrabold">{{ $price }}</p>
</a>
