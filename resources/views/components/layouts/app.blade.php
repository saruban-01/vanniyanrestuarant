<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <x-analytics.gtm />

        @php
            $seo = \App\Services\SeoService::getMetadata($seoModel ?? null, \Illuminate\Support\Facades\Route::currentRouteName());
        @endphp
        
        <x-seo.meta :seo="$seo" />
        <x-seo.structured-data :seo="$seo" :model="$seoModel ?? null" :routeName="\Illuminate\Support\Facades\Route::currentRouteName()" />

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Noto+Sans+Tamil:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-vanniyan-white text-gray-900 min-h-screen flex flex-col">
        <x-analytics.gtm-noscript />
        <x-analytics.consent />

        <x-header />

        <main class="flex-grow">
            {{ $slot }}
        </main>

        <x-site.footer />
        <x-site.toast />

        @livewireScripts
    </body>
</html>
