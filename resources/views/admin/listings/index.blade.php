@extends('layouts.admin')

@section('title', 'Listing Management | GridSpace')

@section('admin_content')
<section class="mb-6 md:mb-8">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] tracking-tight">Listing Management</h1>
            <p class="font-inter text-sm text-on-surface-variant mt-1">View and manage all coworking space listings</p>
        </div>
        <a href="{{ route('listings.create') }}"
           class="inline-flex items-center gap-2 bg-primary-container text-white px-5 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors shrink-0">
            <span class="material-symbols-outlined text-[20px]">add</span>
            New Listing
        </a>
    </div>
</section>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-800 font-inter">{{ session('success') }}</div>
@endif

<div class="bg-white border border-outline-variant/60 rounded-2xl overflow-hidden card-lift">
    @if($listings->isEmpty())
        <div class="p-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-outline">apartment</span>
            </div>
            <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-2">No listings yet</h3>
            <p class="font-inter text-sm text-on-surface-variant">Listings will appear here once hosts create them.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-outline-variant/40 bg-surface-container-low/50">
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Space & Host</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Category</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Price</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Status</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @foreach($listings as $listing)
                        @php
                            $imageUrl = $listing->images->first()
                                ? asset('storage/' . $listing->images->first()->image_path)
                                : null;
                            $statusLabel = $listing->status === 'published' ? 'Active' : ucfirst($listing->status);
                            $statusClass = match($listing->status) {
                                'published' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-amber-100 text-amber-800',
                                default => 'bg-red-100 text-red-800',
                            };
                            $gallery = $listing->images->take(4)->map(fn($img) => asset('storage/' . $img->image_path))->values();
                        @endphp
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="" class="w-11 h-11 rounded-lg object-cover shrink-0">
                                    @else
                                        <div class="w-11 h-11 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-outline">apartment</span>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-inter text-sm font-medium text-[#1c2c40] truncate">{{ $listing->name }}</p>
                                        <p class="font-inter text-xs text-on-surface-variant">{{ $listing->user?->display_name ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-inter text-sm text-on-surface-variant">{{ $listing->category?->name ?? '—' }}</td>
                            <td class="px-5 py-4 font-manrope text-sm font-semibold whitespace-nowrap">₦{{ number_format($listing->price ?? 0, 0) }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <button type="button"
                                        class="w-9 h-9 inline-flex items-center justify-center rounded-lg hover:bg-surface-container text-on-surface-variant hover:text-[#1c2c40] transition-colors"
                                        title="View details"
                                        onclick="openListingModal({{ json_encode([
                                            'id' => $listing->id,
                                            'name' => $listing->name,
                                            'host' => $listing->user?->display_name,
                                            'address' => $listing->address,
                                            'city' => $listing->city?->name,
                                            'price' => number_format($listing->price ?? 0, 0),
                                            'status' => $listing->status,
                                            'description' => \Illuminate\Support\Str::limit($listing->description, 200),
                                            'image' => $imageUrl,
                                            'gallery' => $gallery,
                                            'slug' => $listing->slug,
                                            'approveUrl' => $listing->status === 'pending' ? route('admin.listings.approve', $listing->slug) : null,
                                            'rejectUrl' => $listing->status === 'pending' ? route('admin.listings.reject', $listing->slug) : null,
                                        ]) }})">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($listings->hasPages())
            <div class="px-5 py-4 border-t border-outline-variant/40">{{ $listings->links() }}</div>
        @endif
    @endif
</div>

{{-- Listing detail modal --}}
<div id="listing-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeListingModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto pointer-events-auto shadow-2xl">
            <div id="listing-modal-image" class="h-48 bg-surface-container bg-cover bg-center"></div>
            <div class="p-6">
                <h2 class="font-manrope text-xl font-bold text-[#1c2c40] mb-1" id="listing-modal-title"></h2>
                <p class="font-inter text-sm text-on-surface-variant mb-4" id="listing-modal-location"></p>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="font-inter text-xs text-on-surface-variant">Host</p>
                        <p class="font-inter text-sm font-medium" id="listing-modal-host"></p>
                    </div>
                    <p class="font-manrope text-lg font-bold text-primary-container" id="listing-modal-price"></p>
                </div>
                <p class="font-inter text-sm text-on-surface-variant mb-4" id="listing-modal-desc"></p>
                <div id="listing-modal-gallery" class="grid grid-cols-4 gap-2 mb-6"></div>
                <div id="listing-modal-actions" class="flex gap-3"></div>
                <button type="button" onclick="closeListingModal()"
                        class="mt-4 w-full py-2.5 rounded-lg border border-outline-variant font-inter text-sm font-semibold text-on-surface-variant hover:bg-surface-container transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openListingModal(data) {
    document.getElementById('listing-modal-title').textContent = data.name;
    document.getElementById('listing-modal-location').textContent = [data.address, data.city].filter(Boolean).join(', ');
    document.getElementById('listing-modal-host').textContent = data.host || '—';
    document.getElementById('listing-modal-price').textContent = '₦' + data.price + '/day';
    document.getElementById('listing-modal-desc').textContent = data.description || '';
    const imgEl = document.getElementById('listing-modal-image');
    imgEl.style.backgroundImage = data.image ? `url(${data.image})` : 'none';

    const gallery = document.getElementById('listing-modal-gallery');
    gallery.innerHTML = '';
    (data.gallery || []).forEach(url => {
        const img = document.createElement('img');
        img.src = url;
        img.className = 'w-full h-16 object-cover rounded-lg';
        gallery.appendChild(img);
    });

    const actions = document.getElementById('listing-modal-actions');
    actions.innerHTML = '';
    if (data.approveUrl) {
        actions.innerHTML = `
            <form method="POST" action="${data.approveUrl}" class="flex-1">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="w-full py-2.5 rounded-lg bg-[#1c2c40] text-white font-inter text-sm font-semibold hover:bg-[#2a3d56]">Approve Listing</button>
            </form>
            <form method="POST" action="${data.rejectUrl}" class="flex-1">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="w-full py-2.5 rounded-lg border border-red-300 text-red-600 font-inter text-sm font-semibold hover:bg-red-50">Reject Listing</button>
            </form>`;
    } else {
        actions.innerHTML = `<a href="/listings/${data.slug}" class="flex-1 text-center py-2.5 rounded-lg bg-[#1c2c40] text-white font-inter text-sm font-semibold">View Listing</a>`;
    }

    document.getElementById('listing-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeListingModal() {
    document.getElementById('listing-modal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
@endpush
@endsection
