@props(['content' => []])
@inject('settingsService', 'App\Services\RestaurantSettingsService')
@inject('hoursService', 'App\Services\RestaurantHoursService')

@php
    $settings = $settingsService->getAll();
    $isOpen = $hoursService->isOpenNow();
    $schedule = $hoursService->getWeeklySchedule();
    $todayName = now()->format('l');
    $todayHours = $schedule[$todayName] ?? null;
@endphp

<section class="py-24 bg-white border-t border-gray-100">
    <div class="max-w-[1280px] mx-auto px-6 md:px-12">
        <x-site.section-heading 
            title="{{ $content['location_heading'] ?? 'Visit Vanniyan' }}" 
            align="center"
        />
        
        <div class="mt-16 flex flex-col md:flex-row gap-12 lg:gap-20">
            <!-- Location Details -->
            <div class="w-full md:w-1/2 flex flex-col justify-center">
                <div class="mb-8">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-vanniyan-green-50 border border-vanniyan-green-100 text-vanniyan-green-800 text-sm font-semibold tracking-wider mb-6">
                        <span class="w-2 h-2 rounded-full mr-2 animate-pulse {{ $isOpen ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        {{ $isOpen ? 'OPEN NOW' : 'CLOSED NOW' }}
                    </span>
                    <h3 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-2">Vanniyan Restaurant</h3>
                    <p class="text-gray-600 text-lg">{{ $settings['address'] ?? ($content['location_text'] ?? 'A9 Road, Kilinochchi, Sri Lanka') }}@if(!empty($settings['city']) && !Str::contains($settings['address'] ?? '', Str::before($settings['city'], ','))), {{ $settings['city'] }}@endif</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-10">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2 uppercase tracking-wide text-sm">Service Status</h4>
                        <ul class="text-gray-600 space-y-2">
                            <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Dine-In — {{ $isOpen ? 'Open' : 'Closed' }}</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Takeaway — {{ $isOpen ? 'Open' : 'Closed' }}</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2 uppercase tracking-wide text-sm">Hours</h4>
                        <ul class="text-gray-600 space-y-1">
                            @if($todayHours && $todayHours !== 'Closed')
                                <li>{{ $todayName }}</li>
                                <li>{{ $todayHours }}</li>
                            @else
                                <li>{{ $todayName }}</li>
                                <li>Closed</li>
                            @endif
                        </ul>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ $settings['maps_url'] ?? 'https://www.google.com/maps/search/?api=1&query=Vanniyan+Restaurant+Kilinochchi' }}" target="_blank" rel="noopener noreferrer" class="text-center bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-300 font-medium rounded-md px-6 py-3 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900">
                        Get Directions
                    </a>
                    <a href="tel:{{ str_replace(' ', '', $settings['phone'] ?? '') }}" class="text-center border border-vanniyan-green-900 text-vanniyan-green-900 hover:bg-vanniyan-green-900 hover:text-white transition-colors duration-300 font-medium rounded-md px-6 py-3 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900">
                        Call Vanniyan
                    </a>
                </div>
            </div>
            
            <!-- Map -->
            <div class="w-full md:w-1/2 rounded-lg overflow-hidden border border-gray-200 relative">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8610.954756163199!2d80.39542807541214!3d9.38303499069309!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3afe950041467deb%3A0x8ac9974ba4f3ba9c!2sVanniyan%20Restaurant!5e1!3m2!1sen!2slk!4v1787199656198!5m2!1sen!2slk"
                    class="w-full h-[400px] border-0"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Vanniyan Restaurant location map"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>
</section>
