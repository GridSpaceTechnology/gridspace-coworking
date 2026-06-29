@php
    $accountRoutes = [
        'dashboard',
        'bookings.*',
        'inquiries.*',
        'wallet.*',
        'profile.*',
        'admin.*',
        'analytics.*',
        'listings.create',
        'listings.edit',
        'feature-requests.*',
    ];
    $useDashboardNav = auth()->check() && (
        View::hasSection('dashboard_nav') || request()->routeIs($accountRoutes)
    );
@endphp

@if($useDashboardNav)
    @include('layouts.partials.navbar-dashboard')
@else
    @include('layouts.partials.navbar-public')
@endif
