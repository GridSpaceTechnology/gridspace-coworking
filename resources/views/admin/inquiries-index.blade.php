@extends('layouts.admin')

@section('title', 'Inquiries | GridSpace')

@section('admin_content')
<section class="mb-6 md:mb-8">
    <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] tracking-tight">Inquiries</h1>
    <p class="font-inter text-sm text-on-surface-variant mt-1">View and manage customer inquiries across all listings</p>
</section>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-800 font-inter">{{ session('success') }}</div>
@endif

<div class="bg-white border border-outline-variant/60 rounded-2xl overflow-hidden card-lift">
    <x-admin.filters-bar :action="route('admin.inquiries.index')">
        <div class="flex flex-col gap-1 min-w-[160px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, listing…"
                   class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm focus:ring-2 focus:ring-primary-container/20 focus:border-primary-container">
        </div>
        <div class="flex flex-col gap-1 min-w-[120px]">
            <label class="font-inter text-xs font-semibold text-on-surface-variant uppercase">Contacted</label>
            <select name="contacted" class="px-3 py-2 rounded-lg border border-outline-variant/60 font-inter text-sm bg-white">
                <option value="">All</option>
                <option value="1" @selected(request('contacted') === '1')>Yes</option>
                <option value="0" @selected(request('contacted') === '0')>No</option>
            </select>
        </div>
    </x-admin.filters-bar>

    @if($inquiries->isEmpty())
        <div class="p-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-outline">mail</span>
            </div>
            <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-2">No inquiries found</h3>
            <p class="font-inter text-sm text-on-surface-variant">Inquiries will appear here when guests reach out to hosts.</p>
        </div>
    @else
        <form method="POST" action="{{ route('admin.inquiries.bulk-delete') }}">
            @csrf
            @include('admin.partials.bulk-toolbar', [
                'bulkAction' => route('admin.inquiries.bulk-delete'),
                'paginator' => $inquiries,
            ])

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-outline-variant/40 bg-surface-container-low/50">
                            <th class="px-5 py-3 w-10"></th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">From</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Listing</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Message</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Status</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @foreach($inquiries as $inquiry)
                            <tr class="hover:bg-surface-container-low/40 transition-colors">
                                <td class="px-5 py-4">
                                    <input type="checkbox" name="ids[]" value="{{ $inquiry->id }}"
                                           class="bulk-row-check rounded border-outline-variant text-primary-container focus:ring-primary-container/30">
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-inter text-sm font-medium text-[#1c2c40]">{{ $inquiry->name }}</p>
                                    <p class="font-inter text-xs text-on-surface-variant">{{ $inquiry->email }}</p>
                                    @if($inquiry->phone)
                                        <p class="font-inter text-xs text-on-surface-variant">{{ $inquiry->phone }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if($inquiry->listing)
                                        <p class="font-inter text-sm font-medium text-[#1c2c40]">{{ $inquiry->listing->name }}</p>
                                        <p class="font-inter text-xs text-on-surface-variant">Host: {{ $inquiry->listing->user?->display_name ?? '—' }}</p>
                                    @else
                                        <span class="font-inter text-sm text-on-surface-variant">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-inter text-sm text-on-surface-variant max-w-xs truncate">{{ $inquiry->message }}</td>
                                <td class="px-5 py-4">
                                    <button type="submit" form="toggle-inquiry-{{ $inquiry->id }}"
                                            class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $inquiry->contacted ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $inquiry->contacted ? 'Contacted' : 'Pending' }}
                                    </button>
                                </td>
                                <td class="px-5 py-4 font-inter text-sm text-on-surface-variant whitespace-nowrap">
                                    {{ $inquiry->created_at->format('M j, Y g:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @include('admin.partials.pagination', ['paginator' => $inquiries])
        </form>

        @foreach($inquiries as $inquiry)
            <form id="toggle-inquiry-{{ $inquiry->id }}" method="POST" action="{{ route('admin.inquiries.toggle-contacted', $inquiry) }}" class="hidden">
                @csrf
                @method('PATCH')
            </form>
        @endforeach
    @endif
</div>
@endsection
