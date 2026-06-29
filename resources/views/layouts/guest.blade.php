<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'GridSpace' }}</title>
    @include('layouts.partials.theme-init')
    @include('layouts.partials.head-assets')
    @stack('head')
</head>
<body class="font-sans text-gray-900 dark:text-gray-100 antialiased min-h-screen flex flex-col bg-gray-100 dark:bg-[#0b0f14] transition-colors duration-200">
    @include('layouts.partials.flash-messages')
    <div class="flex-grow flex flex-col sm:justify-center items-center py-6 sm:py-8 px-4">
        <div class="w-full sm:max-w-lg">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
