@extends('layouts.host')

@section('title', 'Calendar | GridSpace')

@section('host_content')
<section class="mb-6 md:mb-8">
    <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] tracking-tight">Calendar</h1>
    <p class="font-inter text-sm text-on-surface-variant mt-1">View your workspace bookings here</p>
</section>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-800 font-inter">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 md:gap-gutter">
    {{-- Calendar --}}
    <div class="xl:col-span-2 bg-white border border-outline-variant/60 rounded-2xl p-5 md:p-6 card-lift">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-manrope text-xl font-bold text-[#1c2c40]">{{ $current->format('F Y') }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('host.calendar', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg border border-outline-variant/60 hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </a>
                <a href="{{ route('host.calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg border border-outline-variant/60 hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-7 gap-1 mb-2">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                <div class="text-center font-inter text-xs font-semibold text-on-surface-variant py-2">{{ $dayName }}</div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 gap-1">
            @for($i = 0; $i < $startDayOfWeek; $i++)
                <div class="aspect-square"></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dayBookings = $calendarBookings->filter(
                        fn ($b) => (int) $b->check_in_date?->day === $day
                    );
                    $hasUpcoming = $dayBookings->whereIn('status', ['pending', 'confirmed'])->isNotEmpty();
                    $hasCancelled = $dayBookings->where('status', 'cancelled')->isNotEmpty();
                    $hasCompleted = $dayBookings->where('status', 'completed')->isNotEmpty();
                    $isToday = $current->copy()->day($day)->isToday();
                    $isSelected = (int) $selectedDay === $day;
                @endphp
                <a href="{{ route('host.calendar', ['month' => $current->month, 'year' => $current->year, 'day' => $day]) }}"
                   @class([
                       'aspect-square rounded-lg flex flex-col items-center justify-center relative text-sm font-inter transition-colors',
                       'bg-primary-container text-white font-semibold' => $isSelected,
                       'ring-2 ring-primary-container/30 bg-primary-fixed' => $isToday && ! $isSelected,
                       'hover:bg-surface-container' => ! $isSelected,
                   ])>
                    {{ $day }}
                    @if($dayBookings->isNotEmpty())
                        <div class="flex gap-0.5 mt-1">
                            @if($hasCompleted)<span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>@endif
                            @if($hasUpcoming)<span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>@endif
                            @if($hasCancelled)<span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>@endif
                        </div>
                    @endif
                </a>
            @endfor
        </div>

        <div class="flex flex-wrap gap-4 mt-6 pt-4 border-t border-outline-variant/40">
            <span class="flex items-center gap-2 font-inter text-xs text-on-surface-variant">
                <span class="w-2 h-2 rounded-full bg-gray-400"></span> Completed
            </span>
            <span class="flex items-center gap-2 font-inter text-xs text-on-surface-variant">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span> Upcoming
            </span>
            <span class="flex items-center gap-2 font-inter text-xs text-on-surface-variant">
                <span class="w-2 h-2 rounded-full bg-red-500"></span> Cancelled
            </span>
        </div>
    </div>

    {{-- Day sidebar --}}
    <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 md:p-6 card-lift">
        @if($selectedDay)
            <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-4">
                {{ $current->copy()->day((int) $selectedDay)->format('F jS') }}
            </h3>
            @if($selectedDateBookings->isEmpty())
                <p class="font-inter text-sm text-on-surface-variant">No bookings on this day.</p>
            @else
                <div class="space-y-3">
                    @foreach($selectedDateBookings as $booking)
                        @php
                            $guestName = $booking->user?->display_name ?? $booking->guest_name ?? 'Guest';
                        @endphp
                        <div class="border border-outline-variant/50 rounded-xl p-4">
                            <p class="font-inter text-sm font-semibold text-[#1c2c40]">{{ $guestName }}</p>
                            <p class="font-inter text-xs text-on-surface-variant mt-0.5">{{ $booking->listing?->name }}</p>
                            <p class="font-inter text-xs text-on-surface-variant mt-1">
                                {{ $booking->check_in_date?->format('g:i A') }}
                                @if($booking->check_out_date)
                                    – {{ $booking->check_out_date->format('g:i A') }}
                                @endif
                            </p>
                            <span class="inline-flex mt-2 px-2 py-0.5 rounded-full text-[11px] font-semibold capitalize
                                @if(in_array($booking->status, ['confirmed', 'completed'])) bg-green-100 text-green-800
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-amber-100 text-amber-800 @endif">
                                {{ $booking->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center py-8">
                <span class="material-symbols-outlined text-4xl text-outline mb-3">touch_app</span>
                <p class="font-inter text-sm text-on-surface-variant">Select a day to view bookings</p>
            </div>
        @endif
    </div>
</div>

{{-- Booking requests --}}
<section class="mt-8">
    <h2 class="font-manrope text-lg font-bold text-[#1c2c40] mb-4">Booking Requests</h2>
    <div class="bg-white border border-outline-variant/60 rounded-2xl overflow-hidden card-lift">
        @if($pendingRequests->isEmpty())
            <div class="p-12 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-outline">inbox</span>
                </div>
                <p class="font-inter text-sm text-on-surface-variant">No booking requests yet</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-outline-variant/40 bg-surface-container-low/50">
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Guest</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Workspace</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Date</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Price</th>
                            <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @foreach($pendingRequests as $booking)
                            @php $guestName = $booking->user?->display_name ?? $booking->guest_name ?? 'Guest'; @endphp
                            <tr>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-primary-container/10 flex items-center justify-center font-manrope font-bold text-primary-container text-sm">
                                            {{ strtoupper(substr($guestName, 0, 1)) }}
                                        </div>
                                        <span class="font-inter text-sm font-medium text-[#1c2c40]">{{ $guestName }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-inter text-sm text-on-surface-variant">{{ $booking->listing?->name }}</td>
                                <td class="px-5 py-4 font-inter text-sm text-on-surface-variant whitespace-nowrap">
                                    {{ $booking->check_in_date?->format('M d, Y') }}
                                </td>
                                <td class="px-5 py-4 font-manrope text-sm font-semibold">₦{{ number_format($booking->total_price ?? 0, 0) }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('bookings.update-status', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg border border-red-300 text-red-600 font-inter text-xs font-semibold hover:bg-red-50 transition-colors">
                                                Decline
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('bookings.update-status', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg bg-[#1c2c40] text-white font-inter text-xs font-semibold hover:bg-[#2a3d56] transition-colors">
                                                Approve
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection
