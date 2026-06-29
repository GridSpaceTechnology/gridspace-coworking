<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GridSpace')</title>
    @include('layouts.partials.theme-init')
    @include('layouts.partials.head-assets')
    <style>
        :root {
            --brand-orange: #f25b19;
            --brand-dark-blue: #002b5c;
        }
        .bg-brand-orange { background-color: var(--brand-orange); }
        .text-brand-orange { color: var(--brand-orange); }
        .text-brand-blue { color: var(--brand-dark-blue); }
        .border-brand-orange { border-color: var(--brand-orange); }
        .ring-brand-orange:focus { --tw-ring-color: var(--brand-orange); }
        .focus\:ring-brand-orange:focus { --tw-ring-color: var(--brand-orange); }
        .focus\:border-brand-orange:focus { border-color: var(--brand-orange); }
        .dark .text-brand-blue { color: #e2e8f0 !important; }
        .dark .glass-box {
            background: rgba(15, 23, 42, 0.55);
            border-color: rgba(255, 255, 255, 0.12);
        }
    </style>
    @stack('head')
</head>
<body class="bg-gray-50 dark:bg-[#0b0f14] font-sans antialiased min-h-screen flex flex-col transition-colors duration-200">
    @include('layouts.partials.flash-messages')
    @yield('content')
    @stack('scripts')
</body>
</html>
