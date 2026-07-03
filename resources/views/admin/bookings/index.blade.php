@extends('layouts.admin')

@section('title', 'Booking Management | GridSpace')

@section('admin_content')
<section class="mb-6 md:mb-8">
    <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] tracking-tight">Booking Management</h1>
    <p class="font-inter text-sm text-on-surface-variant mt-1">Manage and view all booking requests across the platform</p>
</section>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-800 font-inter">{{ session('success') }}</div>
@endif

<div class="bg-white border border-outline-variant/60 rounded-2xl overflow-hidden card-lift">
    @if($bookings->isEmpty())
        <div class="p-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-outline">event_busy</span>
            </div>
            <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-2">No bookings yet</h3>
            <p class="font-inter text-sm text-on-surface-variant">Bookings will appear here once guests reserve workspaces.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-outline-variant/40 bg-surface-container-low/50">
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Booking ID & Date</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Space</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Host</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Guest</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Status</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Amount</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @foreach($bookings as $booking)
                        @php
                            $statusClass = match($booking->status) {
                                'confirmed', 'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-amber-100 text-amber-800',
                            };
                        @endphp
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="px-5 py-4">
                                <p class="font-inter text-sm font-medium text-[#1c2c40]">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                                <p class="font-inter text-xs text-on-surface-variant">{{ $booking->created_at->format('M d, Y') }}</p>
                            </td>
                            <td class="px-5 py-4 font-inter text-sm text-on-surface-variant">{{ $booking->listing?->name ?? '—' }}</td>
                            <td class="px-5 py-4 font-inter text-sm text-on-surface-variant">{{ $booking->listing?->user?->display_name ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <p class="font-inter text-sm font-medium text-[#1c2c40]">{{ $booking->guest_name }}</p>
                                <p class="font-inter text-xs text-on-surface-variant">{{ $booking->guest_email }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold uppercase {{ $statusClass }}">{{ $booking->status }}</span>
                            </td>
                            <td class="px-5 py-4 font-manrope text-sm font-semibold whitespace-nowrap">₦{{ number_format($booking->total_price ?? 0, 0) }}</td>
                            <td class="px-5 py-4 text-right">
                                <button type="button"
                                        class="w-9 h-9 inline-flex items-center justify-center rounded-lg hover:bg-surface-container text-on-surface-variant hover:text-[#1c2c40] transition-colors"
                                        onclick="openBookingModal({{ json_encode([
                                            'id' => $booking->id,
                                            'status' => strtoupper($booking->status),
                                            'space' => $booking->listing?->name,
                                            'host' => $booking->listing?->user?->display_name,
                                            'guest' => $booking->guest_name,
                                            'email' => $booking->guest_email,
                                            'date' => $booking->check_in_date?->format('M d, Y'),
                                            'time' => $booking->check_in_date?->format('g:i A') . ($booking->check_out_date ? ' – ' . $booking->check_out_date->format('g:i A') : ''),
                                            'amount' => number_format($booking->total_price ?? 0, 0),
                                            'cancelUrl' => ! in_array($booking->status, ['cancelled', 'completed']) ? route('admin.bookings.update-status', $booking) : null,
                                        ]) }})">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
            <div class="px-5 py-4 border-t border-outline-variant/40">{{ $bookings->links() }}</div>
        @endif
    @endif
</div>

<div id="booking-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeBookingModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl max-w-md w-full pointer-events-auto shadow-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-manrope text-xl font-bold text-[#1c2c40]">Booking Details</h2>
                <span id="booking-modal-status" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold"></span>
            </div>
            <dl class="space-y-3 mb-6">
                <div><dt class="font-inter text-xs text-on-surface-variant uppercase">Space</dt><dd class="font-inter text-sm font-medium mt-0.5" id="booking-modal-space"></dd></div>
                <div><dt class="font-inter text-xs text-on-surface-variant uppercase">Host</dt><dd class="font-inter text-sm font-medium mt-0.5" id="booking-modal-host"></dd></div>
                <div><dt class="font-inter text-xs text-on-surface-variant uppercase">Guest</dt><dd class="font-inter text-sm font-medium mt-0.5" id="booking-modal-guest"></dd></div>
                <div><dt class="font-inter text-xs text-on-surface-variant uppercase">Date</dt><dd class="font-inter text-sm font-medium mt-0.5" id="booking-modal-date"></dd></div>
                <div><dt class="font-inter text-xs text-on-surface-variant uppercase">Time</dt><dd class="font-inter text-sm font-medium mt-0.5" id="booking-modal-time"></dd></div>
                <div><dt class="font-inter text-xs text-on-surface-variant uppercase">Amount</dt><dd class="font-manrope text-lg font-bold text-primary-container mt-0.5" id="booking-modal-amount"></dd></div>
            </dl>
            <div id="booking-modal-actions"></div>
            <button type="button" onclick="closeBookingModal()"
                    class="mt-4 w-full py-2.5 rounded-lg border border-outline-variant font-inter text-sm font-semibold text-on-surface-variant hover:bg-surface-container">
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openBookingModal(data) {
    document.getElementById('booking-modal-space').textContent = data.space || '—';
    document.getElementById('booking-modal-host').textContent = data.host || '—';
    document.getElementById('booking-modal-guest').textContent = data.guest || '—';
    document.getElementById('booking-modal-date').textContent = data.date || '—';
    document.getElementById('booking-modal-time').textContent = data.time || '—';
    document.getElementById('booking-modal-amount').textContent = '₦' + data.amount;

    const badge = document.getElementById('booking-modal-status');
    badge.textContent = data.status;
    badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold ' +
        (data.status === 'CONFIRMED' || data.status === 'COMPLETED' ? 'bg-green-100 text-green-800' :
         data.status === 'CANCELLED' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800');

    const actions = document.getElementById('booking-modal-actions');
    if (data.cancelUrl) {
        actions.innerHTML = `
            <form method="POST" action="${data.cancelUrl}" onsubmit="return confirm('Cancel this booking?')">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="w-full py-2.5 rounded-lg bg-red-600 text-white font-inter text-sm font-semibold hover:bg-red-700">Cancel Booking</button>
            </form>`;
    } else {
        actions.innerHTML = '';
    }

    document.getElementById('booking-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeBookingModal() {
    document.getElementById('booking-modal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
@endpush
@endsection
