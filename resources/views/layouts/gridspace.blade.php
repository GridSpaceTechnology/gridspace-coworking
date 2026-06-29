<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GridSpace')</title>
    @include('layouts.partials.theme-init')
    @include('layouts.partials.head-assets')
    @stack('head')
</head>
<body class="text-on-surface selection:bg-primary-fixed min-h-screen flex flex-col transition-colors duration-200">
    @include('layouts.partials.navbar')
    @include('layouts.partials.flash-messages')

    <main @class([
        'flex-grow w-full',
        'max-w-container-max mx-auto px-4 md:px-margin-desktop py-8 md:py-stack-lg' => ! View::hasSection('full_width'),
    ])>
        @if(\App\Support\Breadcrumbs::shouldShowToolbar())
            @include('layouts.partials.page-toolbar')
        @endif
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    <script>
        function toggleMainNav() {
            document.getElementById('main-mobile-menu').classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
