<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}

    @if (app()->getLocale() !== 'en')
        <meta property="og:locale" content="{{ app()->getLocale() }}">
    @endif

    <link crossorigin="crossorigin" href="https://fonts.gstatic.com" rel="preconnect" />

    <link as="style" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="preload" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" />

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('styles')

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body
    x-data="global()"
    x-init="themeInit()"
    :class="isMobileMenuOpen ? 'max-h-screen overflow-hidden relative' : ''"
    class="antialiased dark:bg-primary">
    <div class="min-h-screen bg-gray-100">
        <x-navigation-menu />

        <!-- Page Content -->
        <main class="container mx-auto">
            {{ $slot }}
        </main>

        <x-footer />
    </div>

    @filamentScripts
    @vite('resources/js/app.js')

    @stack('scripts')
</body>

</html>
