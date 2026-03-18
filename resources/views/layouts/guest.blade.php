<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO & Security Meta Tags -->
    <meta name="description" content="Login to Gridspace Cowork - Find your perfect workspace. Access premium coworking spaces with flexible booking options.">
    <meta name="keywords" content="coworking, workspace, login, gridspace, office space, flexible booking">
    <meta name="author" content="Gridspace Cowork">
    <meta name="robots" content="noindex, nofollow">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="Strict-Transport-Security" content="max-age=31536000; includeSubDomains">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Login - Gridspace Cowork">
    <meta property="og:description" content="Access your Gridspace Cowork account and find your perfect workspace">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Gridspace Cowork">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Login - Gridspace Cowork">
    <meta name="twitter:description" content="Access your Gridspace Cowork account and find your perfect workspace">

    <title>Login - {{ config('app.name', 'Gridspace Cowork') }}</title>

    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-lg mt-6 sm:mt-0 px-6 sm:px-0">
            <!-- Logo and form side by side -->
            <div class="flex items-start space-x-8">
                <!-- Logo on the left -->
                <div class="flex-shrink-0">
                    <img 
                        src="{{ asset('logo.jpeg') }}" 
                        alt="Gridspace Cowork Logo" 
                        class="h-12 w-auto rounded-lg shadow-sm"
                        loading="lazy"
                    >
                </div>

                <!-- Form on the right -->
                <div class="flex-1">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <!-- Security and Analytics Scripts -->
    @if(config('app.env') === 'production')
        <!-- Add production analytics scripts here -->
    @endif
</body>
</html>
