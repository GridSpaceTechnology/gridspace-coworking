@extends('layouts.dashboard')

@section('title', 'Create Listing | GridSpace')

@section('content')
<section class="mb-6">
    <h1 class="font-manrope text-3xl font-bold text-[#1c2c40]">Create Listing</h1>
    <p class="font-inter text-sm text-on-surface-variant mt-1">Add a new workspace to the platform</p>
</section>

@include('host.partials.listing-wizard-modal')

@push('head')
<style>
    .wizard-step { display: none; }
    .wizard-step.active { display: block; }
    .step-pill.done { background-color: #ff5a1f; color: #fff; }
    .step-pill.active { background-color: #1c2c40; color: #fff; }
    .amenity-card input:checked + .amenity-inner { border-color: #ff5a1f; background-color: #fff5f0; }
    .amenity-card input:checked + .amenity-inner .amenity-check { opacity: 1; }
</style>
@endpush

@push('scripts')
<script>
function openListingModal() {
    document.getElementById('listing-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeListingModal() {
    document.getElementById('listing-modal').classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('DOMContentLoaded', () => openListingModal());
</script>
@include('host.partials.listing-wizard-scripts')
@endpush
@endsection
