<div class="bg-[#F7F7F5] min-h-screen" x-data="{
    copyAddress() {
        navigator.clipboard.writeText('{{ $settings['name'] ?? '' }}, {{ $settings['address'] ?? '' }}, {{ $settings['city'] ?? '' }}').then(() => {
            $refs.copyBtnText.innerText = 'ADDRESS COPIED';
            setTimeout(() => { $refs.copyBtnText.innerText = 'COPY ADDRESS'; }, 2000);
        });
    }
}">

    <!-- HERO SECTION -->
    <div class="w-full h-[40vh] md:h-[50vh] bg-vanniyan-green-900 relative">
        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1600&q=80" alt="Vanniyan Restaurant Exterior" class="absolute inset-0 w-full h-full object-cover opacity-60 blur-[2px]">
        <div class="absolute inset-0 bg-vanniyan-green-900/50 backdrop-blur-sm"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-vanniyan-green-900 via-transparent to-transparent"></div>
        
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
            <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em] mb-4">Vanniyan Restaurant</span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-5">Visit Vanniyan</h1>
            <div class="w-14 h-px bg-vanniyan-gold mb-6"></div>
            <p class="text-gray-200 text-lg md:text-xl font-light max-w-xl mb-8">Find us, check our hours and plan your visit to the Vanniyan table.</p>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ $settings['maps_url'] ?? '#' }}" target="_blank" class="px-8 py-3 bg-vanniyan-gold text-white font-bold uppercase tracking-wider text-sm rounded-full hover:bg-yellow-600 transition-colors">Get Directions</a>
                <a href="{{ route('reservation') }}" class="px-8 py-3 bg-white text-vanniyan-green-900 font-bold uppercase tracking-wider text-sm rounded-full hover:bg-gray-100 transition-colors">Reserve a Table</a>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1.5 transition-all duration-500">
                <div>
                    <h3 class="text-xl font-serif font-bold text-vanniyan-green-900 mb-1">Call</h3>
                    <p class="text-sm text-gray-500 mb-6">Have a quick question?</p>
                </div>
                <a href="tel:{{ str_replace(' ', '', $settings['phone'] ?? '') }}" class="text-sm font-bold text-vanniyan-green-900 uppercase tracking-wide border-t border-gray-100 pt-4 flex items-center group">
                    <span class="mr-2 text-vanniyan-gold">Phone</span> {{ $settings['phone'] ?? '—' }}
                </a>
            </div>
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1.5 transition-all duration-500">
                <div>
                    <h3 class="text-xl font-serif font-bold text-vanniyan-green-900 mb-1">Directions</h3>
                    <p class="text-sm text-gray-500 mb-6">Find your way to the restaurant.</p>
                </div>
                <a href="{{ $settings['maps_url'] ?? '#' }}" target="_blank" class="text-sm font-bold text-vanniyan-green-900 uppercase tracking-wide border-t border-gray-100 pt-4 flex items-center hover:text-vanniyan-gold transition-colors">
                    Get Directions &rarr;
                </a>
            </div>
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1.5 transition-all duration-500">
                <div>
                    <h3 class="text-xl font-serif font-bold text-vanniyan-green-900 mb-1">Reserve</h3>
                    <p class="text-sm text-gray-500 mb-6">Plan your visit.</p>
                </div>
                <a href="{{ route('reservation') }}" class="text-sm font-bold text-vanniyan-green-900 uppercase tracking-wide border-t border-gray-100 pt-4 flex items-center hover:text-vanniyan-gold transition-colors">
                    Reserve a Table &rarr;
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
            
            <!-- LEFT COLUMN: INFO -->
            <div class="lg:col-span-5 space-y-16">
                
                <!-- FIND US -->
                <section>
                    <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-widest mb-2 block">Find Vanniyan</span>
                        <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-2">Find Vanniyan</h2>
                        <div class="w-10 h-[2px] bg-vanniyan-gold mb-6"></div>
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 lg:p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $settings['name'] ?? 'Vanniyan Restaurant' }}</h3>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            {{ $settings['address'] ?? '' }}<br>
                            {{ $settings['city'] ?? '' }}, {{ $settings['country'] ?? '' }}
                        </p>
                        
                        <button @click="copyAddress()" class="flex items-center text-sm font-bold text-vanniyan-green-900 uppercase tracking-wide hover:text-vanniyan-gold transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <span x-ref="copyBtnText">Copy Address</span>
                        </button>
                    </div>
                </section>

                <!-- WHEN TO VISIT -->
                <section>
                    <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-widest mb-2 block">When to Visit</span>
                        <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-2">When to Visit</h2>
                        <div class="w-10 h-[2px] bg-vanniyan-gold mb-6"></div>
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 lg:p-8">
                        
                        <div class="mb-8 pb-8 border-b border-gray-100">
                            @if($isOpenNow)
                                <div class="inline-flex items-center bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm font-bold uppercase tracking-wider border border-green-200 mb-2">
                                    <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span> Open Now
                                </div>
                            @else
                                <div class="inline-flex items-center bg-red-50 text-red-700 px-3 py-1 rounded-full text-sm font-bold uppercase tracking-wider border border-red-200 mb-2">
                                    <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span> Closed Now
                                </div>
                                @if($nextOpening)
                                    <p class="text-sm text-gray-500 font-medium">Next opening: {{ $nextOpening }}</p>
                                @endif
                            @endif

                            @if($specialToday)
                                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <p class="text-xs font-bold text-yellow-800 uppercase tracking-wider mb-1">Today — Special Hours</p>
                                    @if($specialToday->is_closed)
                                        <p class="text-sm text-yellow-900">Closed ({{ $specialToday->reason }})</p>
                                    @else
                                        <p class="text-sm text-yellow-900">{{ \Carbon\Carbon::parse($specialToday->open_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($specialToday->close_time)->format('g:i A') }} ({{ $specialToday->reason }})</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            @foreach($weeklySchedule as $day => $hours)
                                <div class="flex justify-between items-center text-sm {{ \Carbon\Carbon::now('Asia/Colombo')->format('l') === $day ? 'font-bold text-vanniyan-green-900' : 'text-gray-600' }}">
                                    <span>{{ mb_strtoupper($day) }}</span>
                                    <span>{{ $hours }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- SERVICE STATUS -->
                <section>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white border border-gray-200 rounded p-4 text-center">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Dine-In</p>
                            <p class="font-bold text-vanniyan-green-900">{{ $isOpenNow ? 'Open' : 'Closed' }}</p>
                        </div>
                        <div class="bg-white border border-gray-200 rounded p-4 text-center">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Takeaway</p>
                            <p class="font-bold text-vanniyan-green-900">{{ $isOpenNow ? 'Open' : 'Closed' }}</p>
                        </div>
                    </div>
                </section>

                <!-- CONTACT DETAILS -->
                <section>
                    <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-widest mb-2 block">Contact Vanniyan</span>
                        <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-2">Contact Vanniyan</h2>
                        <div class="w-10 h-[2px] bg-vanniyan-gold mb-6"></div>
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 lg:p-8 space-y-6">
                        @if(!empty($settings['phone']))
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Phone</p>
                                <a href="tel:{{ str_replace(' ', '', $settings['phone']) }}" class="text-lg font-bold text-vanniyan-green-900 hover:text-vanniyan-gold transition-colors">{{ $settings['phone'] }}</a>
                            </div>
                        @endif
                        @if(!empty($settings['email']))
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Email</p>
                                <a href="mailto:{{ $settings['email'] }}" class="text-lg font-bold text-vanniyan-green-900 hover:text-vanniyan-gold transition-colors">{{ $settings['email'] }}</a>
                            </div>
                        @endif
                        
                        @php
    try {
        $globalContent = app(\App\Services\CmsService::class)->getPublishedContent('global')['content'] ?? [];
    } catch (\Throwable $e) {
        $globalContent = [];
    }
    $socialInstagram = $globalContent['social_instagram'] ?? ($settings['instagram_url'] ?? '');
    $socialFacebook  = $globalContent['social_facebook'] ?? ($settings['facebook_url'] ?? '');
    $socialTiktok    = $globalContent['social_tiktok'] ?? '';
    $socialWhatsapp  = $globalContent['social_whatsapp'] ?? null;
    if (!$socialWhatsapp) {
        $digits = preg_replace('/\D+/', '', $settings['phone'] ?? '');
        $socialWhatsapp = $digits ? ltrim($digits, '0') : '';
    }
@endphp
                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Social</p>
                            <div class="flex space-x-3">
                                @if(!empty($socialInstagram))
                                    <a href="{{ $socialInstagram }}" target="_blank" rel="noopener" aria-label="Instagram" class="w-11 h-11 rounded-full border border-gray-200 flex items-center justify-center text-vanniyan-green-900 hover:text-white hover:bg-vanniyan-green-900 hover:border-vanniyan-green-900 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($socialFacebook))
                                    <a href="{{ $socialFacebook }}" target="_blank" rel="noopener" aria-label="Facebook" class="w-11 h-11 rounded-full border border-gray-200 flex items-center justify-center text-vanniyan-green-900 hover:text-white hover:bg-vanniyan-green-900 hover:border-vanniyan-green-900 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($socialWhatsapp))
                                    <a href="https://wa.me/{{ $socialWhatsapp }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="w-11 h-11 rounded-full border border-gray-200 flex items-center justify-center text-vanniyan-green-900 hover:text-white hover:bg-vanniyan-green-900 hover:border-vanniyan-green-900 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($socialTiktok))
                                    <a href="{{ $socialTiktok }}" target="_blank" rel="noopener" aria-label="TikTok" class="w-11 h-11 rounded-full border border-gray-200 flex items-center justify-center text-vanniyan-green-900 hover:text-white hover:bg-vanniyan-green-900 hover:border-vanniyan-green-900 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
                
            </div>

            <!-- RIGHT COLUMN: MAP & FORM -->
            <div class="lg:col-span-7 space-y-16">
                
                <!-- MAP -->
                @if(!empty($settings['latitude']) && !empty($settings['longitude']))
                <section>
                    <div class="w-full h-[300px] md:h-[400px] bg-gray-200 rounded-2xl overflow-hidden border border-gray-100 shadow-sm relative group">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8610.954756163199!2d80.39542807541214!3d9.38303499069309!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3afe950041467deb%3A0x8ac9974ba4f3ba9c!2sVanniyan%20Restaurant!5e1!3m2!1sen!2slk!4v1787199656198!5m2!1sen!2slk"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"
                            class="w-full h-full"
                            title="Vanniyan Restaurant location map"
                        ></iframe>
                        <a href="{{ $settings['maps_url'] ?? '#' }}" target="_blank" class="absolute bottom-4 left-4 bg-white px-4 py-2 text-xs font-bold text-vanniyan-green-900 uppercase tracking-wider rounded shadow hover:bg-gray-50 transition-colors pointer-events-auto">
                            Open in Maps
                        </a>
                    </div>
                </section>
                @endif

                <!-- CONTACT FORM -->
                <section>
                    <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-widest mb-2 block">Contact</span>
                    <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-2">Send Us a Message</h2>
                    <div class="w-10 h-[2px] bg-vanniyan-gold mb-6"></div>
                    <p class="text-gray-600 mb-8">Have a question about visiting Vanniyan? Send us a message and our team will review it.</p>
                    
                    @if($isSubmitted)
                        <div class="bg-green-50 border border-green-200 rounded-2xl p-8 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 text-green-700 mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="text-2xl font-serif font-bold text-green-900 mb-2">Thank You</h3>
                            <p class="text-green-800 mb-6">Your message has been received.</p>
                            <button wire:click="$set('isSubmitted', false)" class="text-sm font-bold text-green-800 uppercase tracking-wider border-b-2 border-green-300 hover:border-green-800 transition-colors">Send Another Message</button>
                        </div>
                    @else
                        <form wire:submit.prevent="submitMessage" class="bg-white border border-gray-100 rounded-2xl p-6 lg:p-8">
                            
                            @if ($errors->has('submit'))
                                <div class="bg-red-50 text-red-700 p-4 rounded mb-6 text-sm">
                                    {{ $errors->first('submit') }}
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" wire:model="name" class="w-full px-4 py-3 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 bg-gray-50">
                                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Mobile Number *</label>
                                    <input type="tel" wire:model="phone" class="w-full px-4 py-3 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 bg-gray-50">
                                    @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                                    <input type="email" wire:model="email" class="w-full px-4 py-3 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 bg-gray-50">
                                    @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Subject *</label>
                                    <select wire:model="subject" class="w-full px-4 py-3 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 bg-gray-50">
                                        <option value="">Select a topic</option>
                                        <option value="General Question">General Question</option>
                                        <option value="Menu">Menu</option>
                                        <option value="Takeaway">Takeaway</option>
                                        <option value="Reservation">Reservation</option>
                                        <option value="Event">Event</option>
                                        <option value="Feedback">Feedback</option>
                                    </select>
                                    @error('subject') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="mb-8">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Message *</label>
                                <textarea wire:model="message" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 bg-gray-50"></textarea>
                                @error('message') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <button type="submit" wire:loading.attr="disabled" class="w-full py-4 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded-full shadow hover:bg-vanniyan-green-800 transition-colors disabled:opacity-70">
                                <span wire:loading.remove wire:target="submitMessage">Send Message</span>
                                <span wire:loading wire:target="submitMessage">Sending...</span>
                            </button>
                            <p class="text-xs text-gray-500 mt-3 font-medium text-center">Your information will be handled according to our <a href="{{ route('privacy-policy') }}" class="underline text-vanniyan-green-900 hover:text-vanniyan-gold transition-colors">Privacy Policy</a>.</p>
                        </form>
                    @endif
                </section>
                
                <!-- FAQ -->
                <section x-data="{ active: null }">
                    <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-widest mb-2 block">Questions</span>
                    <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-2">Frequently Asked Questions</h2>
                    <div class="w-10 h-[2px] bg-vanniyan-gold mb-6"></div>
                    
                    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden divide-y divide-gray-100">
                        <!-- FAQ Item -->
                        <div>
                            <button @click="active = active === 1 ? null : 1" class="w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none" :aria-expanded="active === 1">
                                <span class="font-bold text-gray-900 pr-4" :class="{ 'text-vanniyan-gold': active === 1 }">Where is Vanniyan?</span>
                                <span class="text-gray-400">
                                    <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180 text-vanniyan-gold': active === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </button>
                            <div x-show="active === 1" x-collapse x-cloak>
                                <div class="px-6 pb-5 text-gray-600 leading-relaxed text-sm">
                                    We are located at {{ $settings['address'] ?? '' }}, {{ $settings['city'] ?? '' }}. You can find directions using the map on this page.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item -->
                        <div>
                            <button @click="active = active === 2 ? null : 2" class="w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none" :aria-expanded="active === 2">
                                <span class="font-bold text-gray-900 pr-4" :class="{ 'text-vanniyan-gold': active === 2 }">What are your opening hours?</span>
                                <span class="text-gray-400">
                                    <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180 text-vanniyan-gold': active === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </button>
                            <div x-show="active === 2" x-collapse x-cloak>
                                <div class="px-6 pb-5 text-gray-600 leading-relaxed text-sm">
                                    We are open from Tuesday to Sunday, 11:00 AM to 10:00 PM. We are closed on Mondays. Please check the "When to Visit" section for real-time status.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item -->
                        <div>
                            <button @click="active = active === 3 ? null : 3" class="w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none" :aria-expanded="active === 3">
                                <span class="font-bold text-gray-900 pr-4" :class="{ 'text-vanniyan-gold': active === 3 }">Do you offer takeaway?</span>
                                <span class="text-gray-400">
                                    <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180 text-vanniyan-gold': active === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </button>
                            <div x-show="active === 3" x-collapse x-cloak>
                                <div class="px-6 pb-5 text-gray-600 leading-relaxed text-sm">
                                    Yes, you can order your favourites and collect them from the restaurant. Browse our menu online to place a takeaway order. We currently do not offer delivery.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item -->
                        <div>
                            <button @click="active = active === 4 ? null : 4" class="w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none" :aria-expanded="active === 4">
                                <span class="font-bold text-gray-900 pr-4" :class="{ 'text-vanniyan-gold': active === 4 }">Can I reserve a table?</span>
                                <span class="text-gray-400">
                                    <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180 text-vanniyan-gold': active === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </button>
                            <div x-show="active === 4" x-collapse x-cloak>
                                <div class="px-6 pb-5 text-gray-600 leading-relaxed text-sm">
                                    Yes, we strongly recommend reserving your table in advance, especially for evenings and weekends. You can book instantly via our Reservations page.
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

            </div>
        </div>
    </div>

    <!-- FUNNELS -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center flex flex-col justify-center items-center">
                <h3 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-2">Taking Vanniyan Home?</h3>
                <p class="text-gray-600 mb-6">Order your favourites and collect them from the restaurant.</p>
                <a href="{{ route('menu', ['mode' => 'takeaway']) }}" class="text-sm font-bold text-vanniyan-gold uppercase tracking-wider hover:text-yellow-600">Order Takeaway &rarr;</a>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center flex flex-col justify-center items-center">
                <h3 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-2">Planning a Visit?</h3>
                <p class="text-gray-600 mb-6">Reserve your table and enjoy the Vanniyan dining experience.</p>
                <a href="{{ route('reservation') }}" class="text-sm font-bold text-vanniyan-gold uppercase tracking-wider hover:text-yellow-600">Reserve a Table &rarr;</a>
            </div>
        </div>
    </div>

    <!-- EXPERIENCE SECTION -->
    <div class="w-full bg-white py-16 lg:py-24 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-widest mb-4 block">The Restaurant</span>
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-vanniyan-green-900 mb-2">Come Experience Vanniyan</h2>
            <div class="w-14 h-px bg-vanniyan-gold mx-auto mb-6"></div>
            <p class="text-gray-600 max-w-2xl mx-auto mb-16 text-lg">Discover the food, atmosphere and hospitality of Vanni.</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="aspect-square bg-gray-100 rounded overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&q=80" alt="Restaurant Interior" class="w-full h-full object-cover">
                </div>
                <div class="aspect-square bg-gray-100 rounded overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1554679665-f5537f187268?w=800&q=80" alt="Vanniyan Dish" class="w-full h-full object-cover">
                </div>
                <div class="aspect-square bg-gray-100 rounded overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?w=800&q=80" alt="Restaurant Atmosphere" class="w-full h-full object-cover">
                </div>
                <div class="aspect-square bg-gray-100 rounded overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&q=80" alt="Hospitality" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>

    <!-- FINAL CTA -->
    <div class="w-full bg-vanniyan-green-900 py-24 text-center px-4">
        <h2 class="text-3xl md:text-5xl font-serif font-bold text-white mb-6">We'll See You at Vanniyan</h2>
        <p class="text-gray-300 max-w-xl mx-auto mb-10 text-lg">Plan your visit, explore the menu and experience the Royal Taste of Vanni.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('menu') }}" class="px-10 py-4 bg-white text-vanniyan-green-900 font-bold uppercase tracking-wider text-sm rounded-full shadow hover:bg-gray-100 transition-colors">View Menu</a>
            <a href="{{ route('reservation') }}" class="px-10 py-4 bg-vanniyan-gold text-white font-bold uppercase tracking-wider text-sm rounded-full shadow hover:bg-yellow-600 transition-colors">Reserve a Table</a>
        </div>
    </div>

</div>

<!-- Structured Data -->
<script type="application/ld+json">
{!! $schemaJson !!}
</script>
