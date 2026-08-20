@props([
    'title',
    'category',
    'description',
    'validity',
    'image',
    'route'
])

<div class="group bg-white rounded-2xl overflow-hidden border border-gray-100 flex flex-col md:flex-row h-full md:h-[380px] hover:shadow-xl transition-all duration-300">
    <!-- Image -->
    <div class="w-full md:w-1/2 h-64 md:h-full relative overflow-hidden bg-gray-100">
        <img 
            src="{{ $image }}" 
            alt="{{ $title }}" 
            class="absolute inset-0 w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105"
            loading="lazy"
        >
    </div>
    
    <!-- Content -->
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
        <span class="text-vanniyan-gold font-semibold text-xs tracking-[0.2em] uppercase mb-3">{{ $category }}</span>
        <h3 class="text-2xl md:text-3xl font-serif font-bold text-vanniyan-green-900 mb-4">{{ $title }}</h3>
        <p class="text-gray-600 mb-8 leading-relaxed">{{ $description }}</p>
        
        <div class="mt-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <span class="text-sm text-gray-500">{{ $validity }}</span>
            <a href="{{ route($route) }}" class="inline-flex items-center gap-2 bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-6 py-2.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900">
                View Special
            </a>
        </div>
    </div>
</div>