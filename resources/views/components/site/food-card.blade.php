@props([
    'name',
    'description',
    'price',
    'image',
    'isSignature' => false
])

<div class="group bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col h-full">
    <!-- Image -->
    <div class="relative h-64 overflow-hidden bg-gray-100">
        <img 
            src="{{ $image }}" 
            alt="{{ $name }}" 
            class="absolute inset-0 w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105"
            loading="lazy"
        >
        @if($isSignature)
            <span class="absolute top-4 right-4 bg-vanniyan-green-900/90 text-white text-[11px] font-medium px-3 py-1 rounded-full tracking-wider uppercase">
                Signature
            </span>
        @endif
    </div>
    
    <!-- Content -->
    <div class="p-6 flex flex-col flex-1">
        <h3 class="text-lg font-serif font-bold text-vanniyan-green-900 mb-2">{{ $name }}</h3>
        <p class="text-gray-600 text-sm mb-6 flex-1 leading-relaxed">{{ $description }}</p>
        
        <div class="flex items-center justify-between mt-auto">
            <span class="text-vanniyan-green-900 font-bold">{{ $price }}</span>
            <a href="{{ route('menu') }}" class="text-sm font-medium text-vanniyan-gold hover:text-vanniyan-green-900 transition-colors focus:outline-none focus:underline" aria-label="View {{ $name }} in menu">
                View &rarr;
            </a>
        </div>
    </div>
</div>
