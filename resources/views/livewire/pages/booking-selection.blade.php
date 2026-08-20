<div class="bg-gray-50 min-h-screen py-16 sm:py-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs md:text-sm block mb-4">Book With Us</span>
            <h1 class="text-3xl sm:text-4xl font-serif font-bold text-vanniyan-green-900 mb-5">WHAT WOULD YOU LIKE TO BOOK?</h1>
            <div class="w-14 h-px bg-vanniyan-gold mx-auto mb-7"></div>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto font-light">Vanniyan provides two booking options. Please select whether you'd like to reserve a table for dining or book our outdoor venue for your own event.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
            
            <!-- Table Option -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 hover:border-vanniyan-gold/30 transition-all duration-500 border border-gray-100 overflow-hidden flex flex-col h-full group">
                <div class="p-10 flex-grow text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-vanniyan-gold/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-vanniyan-gold/20 transition-colors">
                        <svg class="w-10 h-10 text-vanniyan-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-4">Table</h2>
                    <div class="w-8 h-[2px] bg-vanniyan-gold mb-5"></div>
                    <p class="text-gray-600 mb-8">Reserve a table for dining at Vanniyan.</p>
                    <a href="{{ route('reservation') }}" class="mt-auto w-full inline-flex justify-center items-center px-6 py-3.5 text-sm font-bold uppercase tracking-wider rounded-full text-white bg-vanniyan-gold hover:bg-yellow-600 transition-colors">
                        Book Table
                    </a>
                </div>
            </div>

            <!-- Venue Option -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 hover:border-vanniyan-gold/30 transition-all duration-500 border border-gray-100 overflow-hidden flex flex-col h-full group">
                <div class="p-10 flex-grow text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-vanniyan-green-900/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-vanniyan-green-900/20 transition-colors">
                        <svg class="w-10 h-10 text-vanniyan-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-4">Venue</h2>
                    <div class="w-8 h-[2px] bg-vanniyan-gold mb-5"></div>
                    <p class="text-gray-600 mb-8">Book Vanniyan's available outdoor venue space for your own event.</p>
                    <a href="{{ route('venue.booking') }}" class="mt-auto w-full inline-flex justify-center items-center px-6 py-3.5 text-sm font-bold uppercase tracking-wider rounded-full text-white bg-vanniyan-green-900 hover:bg-vanniyan-green-800 transition-colors">
                        Book Venue
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>