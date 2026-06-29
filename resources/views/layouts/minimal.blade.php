<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GridSpace')</title>
    @include('layouts.partials.theme-init')
    @include('layouts.partials.head-assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('head')
</head>
<body class="bg-gray-50 dark:bg-[#0b0f14] min-h-screen flex flex-col transition-colors duration-200">
    @include('layouts.partials.flash-messages')
    @yield('content')
    @stack('scripts')
</body>
</html>
