@extends('layouts.admin')

@section('title', 'Analytics | GridSpace')

@section('admin_content')
<section class="mb-6 md:mb-8">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] tracking-tight">Analytics</h1>
            <p class="font-inter text-sm text-on-surface-variant mt-1">Track listing performance and engagement across the platform</p>
        </div>
        <a href="{{ route('analytics.export', request()->query()) }}"
           class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-green-700 transition-colors shrink-0">
            <span class="material-symbols-outlined text-[20px]">download</span>
            Export CSV
        </a>
    </div>
</section>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-800 font-inter">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-gutter mb-4">
    @foreach([
        ['label' => 'Listings', 'value' => number_format($summary['total_listings']), 'icon' => 'apartment', 'color' => 'bg-blue-500'],
        ['label' => 'Total Spaces', 'value' => number_format($summary['total_spaces']), 'icon' => 'meeting_room', 'color' => 'bg-indigo-500'],
        ['label' => 'Booked Spaces', 'value' => number_format($summary['booked_spaces']), 'icon' => 'event_busy', 'color' => 'bg-red-500'],
        ['label' => 'Views', 'value' => number_format($summary['total_views']), 'icon' => 'visibility', 'color' => 'bg-green-500'],
    ] as $stat)
        <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl {{ $stat['color'] }} flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-[22px]">{{ $stat['icon'] }}</span>
                </div>
                <div>
                    <p class="font-manrope text-2xl font-bold text-[#1c2c40]">{{ $stat['value'] }}</p>
                    <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide">{{ $stat['label'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-3 gap-4 md:gap-gutter mb-8">
    @foreach([
        ['label' => 'Phone Clicks', 'value' => number_format($summary['phone_clicks']), 'icon' => 'call', 'color' => 'text-green-600'],
        ['label' => 'WhatsApp Clicks', 'value' => number_format($summary['whatsapp_clicks']), 'icon' => 'chat', 'color' => 'text-emerald-600'],
        ['label' => 'Inquiries', 'value' => number_format($summary['total_inquiries']), 'icon' => 'mail', 'color' => 'text-purple-600'],
    ] as $stat)
        <div class="bg-white border border-outline-variant/60 rounded-2xl p-4 card-lift text-center">
            <span class="material-symbols-outlined {{ $stat['color'] }} text-[22px]">{{ $stat['icon'] }}</span>
            <p class="font-manrope text-xl font-bold text-[#1c2c40] mt-1">{{ $stat['value'] }}</p>
            <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide">{{ $stat['label'] }}</p>
        </div>
    @endforeach
</div>

<div class="bg-white border border-outline-variant/60 rounded-2xl overflow-hidden card-lift">
    <div class="px-5 py-4 border-b border-outline-variant/40">
        <h2 class="font-manrope text-lg font-bold text-[#1c2c40]">Listing Performance</h2>
    </div>

    <x-admin.filters-bar :action="route('analytics.index')">
        <div class="flex flex-col gap-1 min-w-[160px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Listing or host…"
                   class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container">
        </div>
        <div class="flex flex-col gap-1 min-w-[140px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Category</label>
            <select name="category_id" class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm bg-white">
                <option value="">All</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1 min-w-[120px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Status</label>
            <select name="status" class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm bg-white">
                <option value="">All</option>
                @foreach(['published', 'pending', 'draft'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1 min-w-[140px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Booking</label>
            <select name="booking_status" class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm bg-white">
                <option value="">All</option>
                <option value="booked" @selected(request('booking_status') === 'booked')>Has booked spaces</option>
                <option value="available" @selected(request('booking_status') === 'available')>All available</option>
            </select>
        </div>
        <div class="flex flex-col gap-1 min-w-[140px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Sort by</label>
            <select name="sort" class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm bg-white">
                <option value="created_desc" @selected(request('sort', 'created_desc') === 'created_desc')>Newest</option>
                <option value="spaces_desc" @selected(request('sort') === 'spaces_desc')>Most spaces</option>
                <option value="booked_desc" @selected(request('sort') === 'booked_desc')>Most booked</option>
                <option value="views_desc" @selected(request('sort') === 'views_desc')>Most views</option>
                <option value="phone_desc" @selected(request('sort') === 'phone_desc')>Most phone clicks</option>
                <option value="whatsapp_desc" @selected(request('sort') === 'whatsapp_desc')>Most WhatsApp clicks</option>
                <option value="name_asc" @selected(request('sort') === 'name_asc')>Name A–Z</option>
            </select>
        </div>
    </x-admin.filters-bar>

    @if($listings->isEmpty())
        <div class="p-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-outline">analytics</span>
            </div>
            <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-2">No analytics data</h3>
            <p class="font-inter text-sm text-on-surface-variant">Try adjusting your filters or wait for listings to get traffic.</p>
        </div>
    @else
        <form method="POST" action="{{ route('admin.analytics.bulk-delete') }}">
            @csrf
            @include('admin.partials.bulk-toolbar', [
                'bulkAction' => route('admin.analytics.bulk-delete'),
                'paginator' => $listings,
            ])

            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[1100px]">
                    <thead>
                        <tr class="border-b border-outline-variant/40 bg-surface-container-low/50">
                            <th class="px-5 py-3 w-10"></th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Listing</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Category</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">Spaces</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">Booked</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Status</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">Views<br><span class="font-normal normal-case">all / 7d / 30d</span></th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">Phone<br><span class="font-normal normal-case">all / 7d / 30d</span></th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">WhatsApp<br><span class="font-normal normal-case">all / 7d / 30d</span></th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">Inquiries</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @foreach($analyticsData as $data)
                            @php
                                $listing = $data['listing'];
                                $statusClass = match($data['booking_status']) {
                                    'Fully booked' => 'bg-red-100 text-red-800',
                                    'Partially booked' => 'bg-amber-100 text-amber-800',
                                    'All available' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <tr class="hover:bg-surface-container-low/40 transition-colors">
                                <td class="px-5 py-4">
                                    <input type="checkbox" name="ids[]" value="{{ $listing->id }}"
                                           class="bulk-row-check rounded border-outline-variant text-primary-container focus:ring-primary-container/30">
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-[180px]">
                                        @if($listing->images->first())
                                            <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
                                                <span class="material-symbols-outlined text-outline text-[18px]">apartment</span>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-inter text-sm font-medium text-[#1c2c40] truncate">{{ $listing->name }}</p>
                                            <p class="font-inter text-xs text-on-surface-variant">{{ $listing->user?->display_name ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-inter text-sm text-on-surface-variant whitespace-nowrap">
                                    {{ $listing->category?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-1 rounded-lg bg-surface-container font-manrope text-sm font-bold text-[#1c2c40]">
                                        {{ $data['spaces_count'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="font-manrope text-sm font-bold {{ $data['booked_spaces_count'] > 0 ? 'text-red-600' : 'text-on-surface-variant' }}">
                                        {{ $data['booked_spaces_count'] }}/{{ $data['spaces_count'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $statusClass }}">
                                        {{ $data['booking_status'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center font-inter text-sm whitespace-nowrap">
                                    <span class="font-semibold text-[#1c2c40]">{{ number_format($data['total_views']) }}</span>
                                    <span class="text-on-surface-variant"> / {{ number_format($data['views_7d']) }} / {{ number_format($data['views_30d']) }}</span>
                                </td>
                                <td class="px-5 py-4 text-center font-inter text-sm whitespace-nowrap">
                                    <span class="font-semibold text-green-600">{{ number_format($data['phone_clicks']) }}</span>
                                    <span class="text-on-surface-variant"> / {{ number_format($data['phone_7d']) }} / {{ number_format($data['phone_30d']) }}</span>
                                </td>
                                <td class="px-5 py-4 text-center font-inter text-sm whitespace-nowrap">
                                    <span class="font-semibold text-emerald-600">{{ number_format($data['whatsapp_clicks']) }}</span>
                                    <span class="text-on-surface-variant"> / {{ number_format($data['whatsapp_7d']) }} / {{ number_format($data['whatsapp_30d']) }}</span>
                                </td>
                                <td class="px-5 py-4 text-center font-inter text-sm font-semibold text-purple-600">{{ number_format($data['inquiries']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @include('admin.partials.pagination', ['paginator' => $listings])
        </form>
    @endif
</div>
@endsection
