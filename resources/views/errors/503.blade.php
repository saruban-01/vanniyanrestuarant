<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Service Unavailable</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans text-white bg-vanniyan-green-900 min-h-screen flex flex-col items-center justify-center">
    <div class="max-w-xl w-full px-4 text-center">
        <!-- Logo -->
        <div class="inline-block mb-12">
            <span class="font-serif font-bold text-2xl tracking-widest uppercase text-white">Vanniyan</span>
        </div>
        
        <div class="mb-8">
            <span class="text-vanniyan-gold font-bold uppercase tracking-widest text-sm block mb-4">503</span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-5">Vanniyan Is Taking A Moment</h1>
            <p class="text-gray-300 text-lg">We're making improvements. Please check back shortly.</p>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-12">
            <button onclick="window.location.reload()" class="px-8 py-3 bg-vanniyan-gold text-white font-bold uppercase tracking-wider text-sm rounded hover:bg-yellow-600 transition-colors">Try Again</button>
        </div>
    </div>
</body>
</html>
