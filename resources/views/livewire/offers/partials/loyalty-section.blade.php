<section class="bg-white py-16 md:py-24 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Loyalty Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs md:text-sm block mb-4">
                Vanniyan Loyalty Card
            </span>
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-vanniyan-green-900 mb-6">
                {{ $config->heading }}
            </h2>
            <div class="w-10 h-[2px] bg-vanniyan-gold mx-auto mb-7"></div>
            <p class="text-lg text-gray-600 font-light leading-relaxed">
                {{ $config->description }}
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-center mb-24">
            <!-- Physical Card Image -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center gap-6">
                <div class="relative w-full max-w-md mx-auto aspect-[1050/600] rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('images/cards/loyalty-front.png') }}" alt="Vanniyan Loyalty Card — front" class="w-full h-full object-cover">
                </div>
                <div class="relative w-full max-w-md mx-auto aspect-[1050/600] rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('images/cards/loyalty-back.png') }}" alt="Vanniyan Loyalty Card — back" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- How It Works -->
            <div class="w-full lg:w-1/2">
                <div class="space-y-12">
                    @if($config->how_it_works)
                        @foreach($config->how_it_works as $index => $step)
                            <div class="flex gap-6">
                                <div class="shrink-0 w-14 h-14 rounded-full bg-vanniyan-green-100 text-vanniyan-green-900 flex items-center justify-center font-serif font-bold text-xl">
                                    0{{ $index + 1 }}
                                </div>
                                <div class="pt-1">
                                    <h3 class="text-xl font-bold text-vanniyan-green-900 mb-2">{{ $step['title'] }}</h3>
                                    <p class="text-gray-600 leading-relaxed">{{ $step['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Reward Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-24">
            <!-- 5th Visit -->
            <div class="bg-white border border-gray-100 rounded-2xl p-8 md:p-12 shadow-sm text-center hover:shadow-xl hover:-translate-y-1.5 hover:border-vanniyan-gold/30 transition-all duration-500 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[2px] bg-vanniyan-green-900"></div>
                <span class="inline-block bg-gray-100 text-gray-600 text-xs font-bold uppercase tracking-widest px-3 py-1 mb-8 rounded-full">
                    5th Visit
                </span>
                <div class="text-7xl font-serif font-bold text-vanniyan-gold mb-6">5</div>
                <h3 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-4">{{ $config->visit_5_title }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ $config->visit_5_reward }}</p>
            </div>

            <!-- 10th Visit -->
            <div class="bg-white border border-gray-100 rounded-2xl p-8 md:p-12 shadow-sm text-center hover:shadow-xl hover:-translate-y-1.5 hover:border-vanniyan-gold/30 transition-all duration-500 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[2px] bg-vanniyan-gold"></div>
                <span class="inline-block bg-gray-100 text-gray-600 text-xs font-bold uppercase tracking-widest px-3 py-1 mb-8 rounded-full">
                    10th Visit
                </span>
                <div class="text-7xl font-serif font-bold text-vanniyan-gold mb-6">10</div>
                <h3 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-4">{{ $config->visit_10_title }}</h3>
                <p class="text-gray-600 leading-relaxed">{{ $config->visit_10_reward }}</p>
            </div>
        </div>

<!-- Physical Card Notice -->
        <div class="bg-gray-50 rounded-2xl p-8 md:p-12 text-center max-w-4xl mx-auto mb-24 border border-gray-100">
            <h3 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-4">KEEP YOUR CARD WITH YOU</h3>
            <div class="w-8 h-[2px] bg-vanniyan-gold mx-auto mb-5"></div>
            <p class="text-gray-600 text-lg font-light">Bring your Vanniyan Loyalty Card when you visit so eligible visits can be recorded on the physical card.</p>
        </div>

    </div>
</section>