@extends('layouts.host')

@section('title', 'Earnings | GridSpace')

@section('host_content')
<section class="mb-6 md:mb-8">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] tracking-tight">Earnings</h1>
            <p class="font-inter text-sm text-on-surface-variant mt-1">Track your workspace income and performance</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('host.earnings', ['period' => 'weekly']) }}"
               @class([
                   'px-4 py-2 rounded-lg font-inter text-sm font-semibold transition-colors',
                   'bg-primary-container text-white' => $period === 'weekly',
                   'bg-white border border-outline-variant/60 text-on-surface-variant hover:border-primary-container/40' => $period !== 'weekly',
               ])>Weekly</a>
            <a href="{{ route('host.earnings', ['period' => 'monthly']) }}"
               @class([
                   'px-4 py-2 rounded-lg font-inter text-sm font-semibold transition-colors',
                   'bg-primary-container text-white' => $period === 'monthly',
                   'bg-white border border-outline-variant/60 text-on-surface-variant hover:border-primary-container/40' => $period !== 'monthly',
               ])>Monthly</a>
        </div>
    </div>
</section>

{{-- Summary cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-gutter mb-8">
    <div class="bg-white border border-outline-variant/60 rounded-2xl p-6 card-lift">
        <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-2">Total Earnings</p>
        <p class="font-manrope text-3xl font-bold text-[#1c2c40]">₦{{ number_format($totalEarnings, 0) }}</p>
    </div>
    <div class="bg-white border border-outline-variant/60 rounded-2xl p-6 card-lift">
        <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-2">Total Bookings</p>
        <p class="font-manrope text-3xl font-bold text-[#1c2c40]">{{ number_format($totalBookings) }}</p>
    </div>
    <div class="bg-white border border-outline-variant/60 rounded-2xl p-6 card-lift">
        <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-2">Avg. Booking Value</p>
        <p class="font-manrope text-3xl font-bold text-[#1c2c40]">₦{{ number_format($avgBookingValue, 0) }}</p>
    </div>
</div>

{{-- Chart --}}
<div class="bg-white border border-outline-variant/60 rounded-2xl p-6 md:p-8 card-lift mb-8">
    <h2 class="font-manrope text-lg font-bold text-[#1c2c40] mb-6">
        {{ $period === 'monthly' ? 'Monthly' : 'Weekly' }} Earnings Breakdown
    </h2>

    @if($totalBookings === 0)
        <div class="py-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-outline">bar_chart</span>
            </div>
            <p class="font-inter text-sm text-on-surface-variant">Earnings data will appear once you receive confirmed bookings</p>
        </div>
    @else
        <div class="flex items-end justify-between gap-3 md:gap-6 h-56 px-2">
            @foreach($chartLabels as $index => $label)
                @php
                    $bookingVal = $chartBookings[$index] ?? 0;
                    $payoutVal = $chartPayouts[$index] ?? 0;
                    $bookingHeight = ($bookingVal / $maxChart) * 100;
                    $payoutHeight = ($payoutVal / $maxChart) * 100;
                @endphp
                <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
                    <div class="w-full flex items-end justify-center gap-1 h-44">
                        <div class="w-5 md:w-8 rounded-t-md bg-[#1c2c40] transition-all"
                             style="height: {{ max($bookingHeight, 4) }}%"
                             title="Bookings: ₦{{ number_format($bookingVal, 0) }}"></div>
                        <div class="w-5 md:w-8 rounded-t-md bg-primary-container transition-all"
                             style="height: {{ max($payoutHeight, 4) }}%"
                             title="Payouts: ₦{{ number_format($payoutVal, 0) }}"></div>
                    </div>
                    <span class="font-inter text-[10px] md:text-xs text-on-surface-variant text-center">{{ $label }}</span>
                </div>
            @endforeach
        </div>
        <div class="flex flex-wrap gap-6 mt-6 pt-4 border-t border-outline-variant/40">
            <span class="flex items-center gap-2 font-inter text-xs text-on-surface-variant">
                <span class="w-3 h-3 rounded-sm bg-[#1c2c40]"></span> Bookings
            </span>
            <span class="flex items-center gap-2 font-inter text-xs text-on-surface-variant">
                <span class="w-3 h-3 rounded-sm bg-primary-container"></span> Payouts
            </span>
        </div>
    @endif
</div>

{{-- Recent transactions --}}
<div class="bg-white border border-outline-variant/60 rounded-2xl overflow-hidden card-lift">
    <div class="px-6 py-4 border-b border-outline-variant/40 flex items-center justify-between">
        <h2 class="font-manrope text-lg font-bold text-[#1c2c40]">Recent Transactions</h2>
        <a href="{{ route('wallet.index') }}" class="font-inter text-xs font-semibold text-primary-container hover:underline">Wallet</a>
    </div>
    @if($bookings->isEmpty())
        <div class="p-10 text-center font-inter text-sm text-on-surface-variant">No transactions yet</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-outline-variant/40 bg-surface-container-low/50">
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Guest</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Workspace</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Date</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Amount</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @foreach($bookings->take(10) as $booking)
                        @php
                            $guestName = $booking->user?->display_name ?? $booking->guest_name ?? 'Guest';
                            $statusClass = match($booking->status) {
                                'confirmed', 'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-amber-100 text-amber-800',
                            };
                        @endphp
                        <tr class="hover:bg-surface-container-low/40">
                            <td class="px-5 py-4 font-inter text-sm font-medium text-[#1c2c40]">{{ $guestName }}</td>
                            <td class="px-5 py-4 font-inter text-sm text-on-surface-variant">{{ $booking->listing?->name }}</td>
                            <td class="px-5 py-4 font-inter text-sm text-on-surface-variant whitespace-nowrap">
                                {{ $booking->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-5 py-4 font-manrope text-sm font-semibold">₦{{ number_format($booking->total_price ?? 0, 0) }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold capitalize {{ $statusClass }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
