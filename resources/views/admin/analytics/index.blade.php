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

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-gutter mb-8">
    @foreach([
        ['label' => 'Total Listings', 'value' => number_format($summary['total_listings']), 'icon' => 'apartment', 'color' => 'bg-blue-500'],
        ['label' => 'Total Views', 'value' => number_format($summary['total_views']), 'icon' => 'visibility', 'color' => 'bg-green-500'],
        ['label' => 'Unique Views', 'value' => number_format($summary['unique_views']), 'icon' => 'groups', 'color' => 'bg-amber-500'],
        ['label' => 'Inquiries', 'value' => number_format($summary['total_inquiries']), 'icon' => 'mail', 'color' => 'bg-purple-500'],
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
        <div class="flex flex-col gap-1 min-w-[120px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Featured</label>
            <select name="featured" class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm bg-white">
                <option value="">All</option>
                <option value="1" @selected(request('featured') === '1')>Yes</option>
                <option value="0" @selected(request('featured') === '0')>No</option>
            </select>
        </div>
        <div class="flex flex-col gap-1 min-w-[140px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Sort by</label>
            <select name="sort" class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm bg-white">
                <option value="created_desc" @selected(request('sort', 'created_desc') === 'created_desc')>Newest</option>
                <option value="views_desc" @selected(request('sort') === 'views_desc')>Most views</option>
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
                <table class="w-full text-left min-w-[900px]">
                    <thead>
                        <tr class="border-b border-outline-variant/40 bg-surface-container-low/50">
                            <th class="px-5 py-3 w-10"></th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Listing</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Category</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Host</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">Views</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">Unique</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">Phone</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">WhatsApp</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">Inquiries</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">7d</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-center">30d</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @foreach($analyticsData as $data)
                            @php $listing = $data['listing']; @endphp
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
                                            @if($listing->featured)
                                                <span class="text-[10px] font-semibold text-amber-600 uppercase">Featured</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-inter text-sm text-on-surface-variant">{{ $listing->category?->name ?? '—' }}</td>
                                <td class="px-5 py-4 font-inter text-sm text-on-surface-variant">{{ $listing->user?->display_name ?? '—' }}</td>
                                <td class="px-5 py-4 text-center font-inter text-sm font-semibold">{{ number_format($data['total_views']) }}</td>
                                <td class="px-5 py-4 text-center font-inter text-sm">{{ number_format($data['unique_views']) }}</td>
                                <td class="px-5 py-4 text-center font-inter text-sm text-green-600">{{ number_format($data['phone_clicks']) }}</td>
                                <td class="px-5 py-4 text-center font-inter text-sm text-green-600">{{ number_format($data['whatsapp_clicks']) }}</td>
                                <td class="px-5 py-4 text-center font-inter text-sm text-purple-600">{{ number_format($data['inquiries']) }}</td>
                                <td class="px-5 py-4 text-center font-inter text-sm text-blue-600">{{ number_format($data['last_7_days']) }}</td>
                                <td class="px-5 py-4 text-center font-inter text-sm text-blue-600">{{ number_format($data['last_30_days']) }}</td>
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
