<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans text-[#17201C] bg-[#F7F7F5] min-h-screen flex flex-col items-center justify-center">
    <div class="max-w-xl w-full px-4 text-center">
        <!-- Logo -->
        <a href="/" class="inline-block mb-12">
            <span class="font-serif font-bold text-2xl tracking-widest uppercase text-vanniyan-green-900">Vanniyan</span>
        </a>
        
        <div class="mb-8">
            <span class="text-vanniyan-gold font-bold uppercase tracking-widest text-sm block mb-4">@yield('code')</span>
            <h1 class="text-3xl sm:text-4xl font-serif font-bold text-vanniyan-green-900 mb-5">@yield('message')</h1>
            <p class="text-gray-600 text-lg">@yield('description')</p>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-12">
            @yield('actions')
        </div>
    </div>
</body>
</html>
