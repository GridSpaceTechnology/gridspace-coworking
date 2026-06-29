<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GridSpace - Create Account')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
    </style>
    @stack('head')
</head>
<body class="bg-gray-50 font-sans antialiased">
    @yield('content')
    @stack('scripts')
</body>
</html>
