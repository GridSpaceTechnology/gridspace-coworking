<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GridSpace Onboarding')</title>
    @include('layouts.partials.theme-init')
    @include('layouts.partials.head-assets')
    <style>
        .card-elevation { box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.1); }
        .dark .card-elevation { box-shadow: 0 1px 3px rgba(0,0,0,0.35); }
        .progress-segment { height: 4px; border-radius: 2px; }
        .custom-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2349607e'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.5em;
        }
        .dark .custom-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        }
    </style>
    @stack('head')
</head>
<body class="min-h-screen flex flex-col text-on-surface transition-colors duration-200">
    @include('layouts.partials.flash-messages')
    @yield('content')
    @stack('scripts')
</body>
</html>
