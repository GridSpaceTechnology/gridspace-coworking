@extends('layouts.dashboard')

@section('title', 'Wallet | GridSpace')

@section('content')
<section class="mb-8">
    <h1 class="font-manrope text-4xl md:text-5xl font-extrabold text-on-surface mb-2 tracking-tight">Wallet</h1>
    <p class="font-inter text-lg text-on-surface-variant">
        {{ $isHost ? 'Track your earnings and payouts from workspace bookings' : 'Manage your balance and booking payments' }}
    </p>
</section>

{{-- Balance hero --}}
<div class="relative overflow-hidden rounded-2xl mb-8 p-8 md:p-10 text-white" style="background: linear-gradient(135deg, #1c2c40 0%, #49607e 50%, #ae3200 100%);">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-primary-container/20 rounded-full translate-y-1/2 -translate-x-1/4"></div>
    <div class="relative flex flex-col md:flex-row md:items-end md:justify-between gap-8">
        <div>
            <p class="font-mono text-xs uppercase tracking-widest text-white/70 mb-2">Available Balance</p>
            <p class="font-manrope text-5xl md:text-6xl font-extrabold tracking-tight">₦{{ number_format($stats->balance, 0) }}</p>
            <p class="font-inter text-sm text-white/80 mt-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">account_balance_wallet</span>
                GridSpace Wallet
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="button" onclick="document.getElementById('add-funds-modal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 bg-white text-on-surface px-6 py-3 rounded-xl font-manrope font-semibold text-sm hover:bg-primary-fixed transition-colors">
                <span class="material-symbols-outlined">add</span>
                Add Funds
            </button>
            <button type="button" disabled
                class="inline-flex items-center gap-2 border border-white/30 text-white/70 px-6 py-3 rounded-xl font-manrope font-semibold text-sm cursor-not-allowed">
                <span class="material-symbols-outlined">south_west</span>
                Withdraw
            </button>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter mb-8">
    @if($isHost)
        <div class="bg-white border border-outline-variant rounded-xl p-6 card-lift">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-2xl text-green-600">trending_up</span>
            </div>
            <p class="font-manrope text-3xl font-bold text-on-surface mb-1">₦{{ number_format($stats->total_earned, 0) }}</p>
            <p class="font-manrope text-sm font-semibold text-on-surface">Total Earned</p>
            <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide mt-1">Confirmed bookings</p>
        </div>
    @else
        <div class="bg-white border border-outline-variant rounded-xl p-6 card-lift">
            <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-2xl text-primary-container">payments</span>
            </div>
            <p class="font-manrope text-3xl font-bold text-on-surface mb-1">₦{{ number_format($stats->total_spent, 0) }}</p>
            <p class="font-manrope text-sm font-semibold text-on-surface">Total Spent</p>
            <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide mt-1">On bookings</p>
        </div>
    @endif
    <div class="bg-white border border-outline-variant rounded-xl p-6 card-lift">
        <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-2xl text-amber-600">hourglass_top</span>
        </div>
        <p class="font-manrope text-3xl font-bold text-on-surface mb-1">₦{{ number_format($stats->pending, 0) }}</p>
        <p class="font-manrope text-sm font-semibold text-on-surface">Pending</p>
        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide mt-1">Awaiting confirmation</p>
    </div>
    <div class="bg-white border border-outline-variant rounded-xl p-6 card-lift">
        <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-2xl text-secondary">calendar_month</span>
        </div>
        <p class="font-manrope text-3xl font-bold text-on-surface mb-1">₦{{ number_format($stats->this_month, 0) }}</p>
        <p class="font-manrope text-sm font-semibold text-on-surface">This Month</p>
        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide mt-1">{{ now()->format('F Y') }}</p>
    </div>
</div>

{{-- Transactions --}}
<section class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
    <div class="px-6 py-4 border-b border-outline-variant flex flex-wrap items-center justify-between gap-4">
        <h2 class="font-manrope text-xl font-bold text-on-surface">Transaction History</h2>
        <p class="font-mono text-xs text-on-surface-variant uppercase tracking-wide">{{ $transactions->count() }} records</p>
    </div>

    @if($transactions->isEmpty())
        <div class="p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-outline">receipt_long</span>
            </div>
            <h3 class="font-manrope text-xl font-bold text-on-surface mb-2">No transactions yet</h3>
            <p class="font-inter text-on-surface-variant mb-6 max-w-sm mx-auto">
                {{ $isHost ? 'Earnings from confirmed bookings will show up here.' : 'Your booking payments will appear here once you make a reservation.' }}
            </p>
            <a href="{{ route('listings.index') }}" class="inline-flex items-center gap-2 text-primary-container font-manrope font-semibold hover:underline">
                Browse workspaces
                <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </a>
        </div>
    @else
        <div class="divide-y divide-outline-variant/50">
            @foreach($transactions as $booking)
                @php
                    $listing = $booking->listing;
                    $image = $listing && $listing->images->first()
                        ? asset('storage/' . $listing->images->first()->image_path)
                        : null;
                    $statusColors = [
                        'pending' => 'bg-amber-50 text-amber-700',
                        'confirmed' => 'bg-green-50 text-green-700',
                        'completed' => 'bg-blue-50 text-blue-700',
                        'cancelled' => 'bg-red-50 text-red-700',
                    ];
                    $statusClass = $statusColors[$booking->status] ?? 'bg-surface-container text-on-surface-variant';
                    $isCredit = $isHost && in_array($booking->status, ['confirmed', 'completed']);
                    $isDebit = ! $isHost;
                @endphp
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-surface-container-low/50 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-surface-container overflow-hidden shrink-0 flex items-center justify-center">
                        @if($image)
                            <img src="{{ $image }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="material-symbols-outlined text-outline">
                                {{ $isCredit ? 'south_east' : 'north_east' }}
                            </span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-manrope font-semibold text-on-surface truncate">
                            {{ $listing?->name ?? 'Workspace booking' }}
                        </p>
                        <p class="font-inter text-xs text-on-surface-variant">
                            @if($isHost && $booking->user)
                                Guest: {{ $booking->guest_name ?? $booking->user->display_name }}
                                &middot;
                            @endif
                            {{ $booking->created_at->format('M j, Y') }}
                            @if($booking->check_in_date)
                                &middot; {{ $booking->check_in_date->format('M j') }} – {{ $booking->check_out_date?->format('M j') }}
                            @endif
                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-manrope font-bold {{ $isCredit ? 'text-green-600' : 'text-on-surface' }}">
                            {{ $isCredit ? '+' : '−' }}₦{{ number_format($booking->total_price, 0) }}
                        </p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full font-mono text-[10px] uppercase tracking-wider {{ $statusClass }}">
                            {{ $booking->status }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>

{{-- Add funds modal --}}
<div id="add-funds-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50" onclick="if(event.target===this) this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-8 border border-outline-variant text-center">
        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-primary-fixed flex items-center justify-center">
            <span class="material-symbols-outlined text-3xl text-primary-container">account_balance_wallet</span>
        </div>
        <h2 class="font-manrope text-2xl font-bold text-on-surface mb-2">Add Funds</h2>
        <p class="font-inter text-sm text-on-surface-variant mb-8">
            Wallet top-ups via card and bank transfer are coming soon. For now, booking payments are processed at checkout.
        </p>
        <button type="button" onclick="document.getElementById('add-funds-modal').classList.add('hidden')"
            class="w-full bg-primary-container text-white py-3 rounded-xl font-manrope font-semibold hover:bg-primary transition-colors">
            Got it
        </button>
    </div>
</div>
@endsection
