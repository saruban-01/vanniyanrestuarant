@props(['content' => []])

<section class="py-32 bg-vanniyan-green-900 text-white text-center px-6 relative overflow-hidden">
    <div class="absolute inset-0 opacity-15 bg-[url('https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&q=80')] bg-cover bg-center"></div>
    <div class="absolute inset-0 bg-vanniyan-green-900/85"></div>

    <div class="max-w-3xl mx-auto relative z-10">
        <h2 class="text-3xl md:text-5xl font-serif font-bold mb-5">{{ $content['final_cta_heading'] ?? 'Come Experience Vanniyan' }}</h2>
        <div class="w-12 h-px bg-vanniyan-gold mx-auto mb-8"></div>
        <p class="text-gray-300 text-xl mb-12 font-light">
            {{ $content['final_cta_text'] ?? 'Discover the food, atmosphere and hospitality of Vanni.' }}
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center gap-5">
            <a href="{{ $content['final_cta_primary_url'] ?? route('menu') }}" class="w-full sm:w-auto bg-white text-vanniyan-green-900 hover:bg-gray-100 transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-10 py-4 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-vanniyan-green-900 focus:ring-white">
                {{ $content['final_cta_primary'] ?? 'View Menu' }}
            </a>
            <a href="{{ $content['final_cta_secondary_url'] ?? route('reservation') }}" class="w-full sm:w-auto bg-transparent text-white border border-white/60 hover:bg-white/10 transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-10 py-4 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-vanniyan-green-900 focus:ring-white">
                {{ $content['final_cta_secondary'] ?? 'Reserve a Table' }}
            </a>
        </div>
    </div>
</section>