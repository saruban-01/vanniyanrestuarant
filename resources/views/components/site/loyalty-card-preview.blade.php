@props(['content' => []])

<section class="py-24 bg-vanniyan-white">
    <div class="max-w-[1280px] mx-auto px-6 md:px-12 flex flex-col md:flex-row items-center gap-12 lg:gap-20">
        <!-- Loyalty Card Visual -->
        <div class="w-full md:w-1/2 flex justify-center">
            <!-- Simulated Physical Card Design -->
            <div class="w-full max-w-md aspect-[1.586/1] bg-vanniyan-green-900 rounded-xl shadow-2xl relative overflow-hidden border border-vanniyan-green-700 flex flex-col justify-between p-8 transform rotate-[-2deg] hover:rotate-0 transition-transform duration-500">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,_rgba(180,135,53,0.6),_transparent_70%)]"></div>
                
                <div class="relative z-10 flex justify-between items-start">
                    <span class="font-serif font-bold text-2xl text-white tracking-wide uppercase">Vanniyan Restaurant</span>
                    <span class="text-vanniyan-gold text-[10px] uppercase tracking-widest font-bold border border-vanniyan-gold px-2 py-1 rounded-sm">Loyalty</span>
                </div>
                
                <div class="relative z-10 grid grid-cols-5 gap-2 mt-auto">
                    <!-- Stamps placeholders -->
                    @for($i = 1; $i <= 10; $i++)
                        <div class="aspect-square rounded-full border-2 flex items-center justify-center font-semibold text-xs
                            {{ ($i == 5 || $i == 10) ? 'border-vanniyan-gold bg-vanniyan-gold/20 text-vanniyan-gold' : 'border-dashed border-vanniyan-green-700 text-vanniyan-green-700 bg-vanniyan-green-800/50' }}">
                            {{ $i }}
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="w-full md:w-1/2 flex flex-col justify-center">
            <span class="text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs mb-4">Vanniyan Loyalty Card</span>
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-5 ">{!! nl2br(e($content['loyalty_heading'] ?? "Collect visits.\nEnjoy rewards.")) !!}</h2>
            <div class="w-12 h-px bg-vanniyan-gold mb-8"></div>
            
            <div class="space-y-6 mb-8">
                <div class="flex items-start">
                    <div class="bg-vanniyan-green-900 text-vanniyan-gold w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm mr-4 shrink-0">5</div>
                    <div>
                        <h4 class="text-base font-bold text-gray-900 uppercase tracking-wide">5th Visit</h4>
                        <p class="text-gray-600">{{ $content['loyalty_visit_5'] ?? 'Free Drink' }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-vanniyan-green-900 text-vanniyan-gold w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm mr-4 shrink-0">10</div>
                    <div>
                        <h4 class="text-base font-bold text-gray-900 uppercase tracking-wide">10th Visit</h4>
                        <p class="text-gray-600">{{ $content['loyalty_visit_10'] ?? 'Rs. 1,000 Food Coupon' }}</p>
                    </div>
                </div>
            </div>
            
            <p class="text-gray-600 mb-8 text-lg">
                {{ $content['loyalty_text'] ?? 'Ask our team for a physical Vanniyan Loyalty Card during your next visit.' }}
            </p>
            
            <div>
                <a href="{{ route('offers') }}" class="inline-flex items-center justify-center bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-300 font-medium rounded-md px-8 py-3 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900">
                    View Our Deals
                </a>
            </div>
        </div>
    </div>
</section>
