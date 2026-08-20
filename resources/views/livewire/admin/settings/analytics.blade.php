<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Analytics &amp; Marketing</h1>
            <p class="text-gray-500 text-sm">Google Tag Manager, Meta Pixel, event tracking and consent. Everything is served from this panel — no redeploy needed.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="runTest" class="px-4 py-2 bg-white border border-gray-300 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 transition-colors">
                Test Configuration
            </button>
            <button wire:click="save" class="px-6 py-2 bg-vanniyan-gold text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-yellow-600 transition-colors shadow-sm">
                Save Settings
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-8 p-4 bg-green-50 text-green-800 text-sm font-medium border border-green-200 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-8 p-4 bg-red-50 text-red-800 text-sm font-medium border border-red-200 rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($testResult)
        <div class="mb-8 p-4 bg-gray-50 text-gray-700 text-sm font-mono whitespace-pre-line border border-gray-200 rounded-lg">
            {{ $testResult }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-32">
        <div class="lg:col-span-2 space-y-6">
            <!-- Google Tag Manager -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-lg font-bold text-gray-900">Google Tag Manager</h2>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $gtmEnabled && \App\Services\AnalyticsService::isValidGtmId($gtmContainerId) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                        {{ $gtmEnabled && \App\Services\AnalyticsService::isValidGtmId($gtmContainerId) ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm mb-5">GTM is the single tag layer: GA4, Meta Pixel and any other tag are published through it. The container snippet is injected into the public site header.</p>

                <div class="space-y-4">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <div>
                            <span class="font-bold text-sm text-gray-900">Enable Google Tag Manager</span>
                            <span class="block text-xs text-gray-500">Loads the container snippet on the public site.</span>
                        </div>
                        <input type="checkbox" wire:model.live="gtmEnabled" class="h-5 w-5 rounded text-vanniyan-green-900 focus:ring-vanniyan-green-900">
                    </label>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Container ID</label>
                        <input type="text" wire:model="gtmContainerId" placeholder="GTM-XXXXXXX" autocomplete="off"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-vanniyan-green-900 focus:ring-vanniyan-green-900 font-mono">
                        <p class="text-xs text-gray-400 mt-1">Format: <code>GTM-</code> followed by 4–15 uppercase letters/digits. Anything else (including whitespace or markup) is rejected.</p>
                    </div>
                </div>
            </div>

            <!-- Meta Pixel -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-lg font-bold text-gray-900">Meta Pixel</h2>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $metaEnabled && \App\Services\AnalyticsService::isValidPixelId($metaPixelId) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                        {{ $metaEnabled && \App\Services\AnalyticsService::isValidPixelId($metaPixelId) ? 'Configured through GTM' : 'Inactive' }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm mb-5">The site never loads Meta code directly. The Pixel ID is exposed to the GTM container through the data layer — publish the official Meta Pixel tag in GTM and it will fire. Loading can only be confirmed in the GTM Preview, not from this panel.</p>

                <div class="space-y-4">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <div>
                            <span class="font-bold text-sm text-gray-900">Enable Meta Pixel</span>
                            <span class="block text-xs text-gray-500">Exposes the Pixel ID to GTM for the Meta Pixel tag.</span>
                        </div>
                        <input type="checkbox" wire:model.live="metaEnabled" class="h-5 w-5 rounded text-vanniyan-green-900 focus:ring-vanniyan-green-900">
                    </label>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Pixel ID</label>
                        <input type="text" wire:model="metaPixelId" placeholder="123456789012345" autocomplete="off"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-vanniyan-green-900 focus:ring-vanniyan-green-900 font-mono">
                        <p class="text-xs text-gray-400 mt-1">13–17 digits only. Stored in the database, never in code or environment files.</p>
                    </div>
                </div>
            </div>

            <!-- Event Tracking -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-lg font-bold text-gray-900">Event Tracking</h2>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $eventsEnabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                        {{ $eventsEnabled ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm mb-5">Structured events are pushed to the data layer for GTM to consume. No personal information is ever included. Conversion events (purchase, booking confirmations) are server-guarded so refreshes cannot duplicate them.</p>

                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors mb-5">
                    <div>
                        <span class="font-bold text-sm text-gray-900">Enable event tracking</span>
                        <span class="block text-xs text-gray-500">Master switch for all dataLayer events below.</span>
                    </div>
                    <input type="checkbox" wire:model.live="eventsEnabled" class="h-5 w-5 rounded text-vanniyan-green-900 focus:ring-vanniyan-green-900">
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @php
                        $toggles = [
                            ['key' => 'trackMenu', 'label' => 'Menu views', 'desc' => 'view_menu, view_item'],
                            ['key' => 'trackCart', 'label' => 'Cart activity', 'desc' => 'add_to_cart, remove_from_cart, view_cart'],
                            ['key' => 'trackOrders', 'label' => 'Takeaway orders', 'desc' => 'begin_checkout, purchase'],
                            ['key' => 'trackTableBookings', 'label' => 'Table bookings', 'desc' => 'booking_started, booking_submitted, booking_confirmed'],
                            ['key' => 'trackVenueBookings', 'label' => 'Venue bookings', 'desc' => 'venue_booking_started, venue_booking_submitted, venue_booking_confirmed'],
                            ['key' => 'trackOffers', 'label' => 'Offers & deals', 'desc' => 'offer_viewed, offer_clicked'],
                            ['key' => 'trackStories', 'label' => 'Stories', 'desc' => 'story_viewed'],
                            ['key' => 'trackGoogleReviews', 'label' => 'Google Reviews', 'desc' => 'google_reviews_clicked, google_write_review_clicked'],
                        ];
                    @endphp

                    @foreach ($toggles as $toggle)
                        <label class="flex items-start justify-between p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <div>
                                <span class="font-bold text-sm text-gray-900">{{ $toggle['label'] }}</span>
                                <span class="block text-xs text-gray-400 font-mono">{{ $toggle['desc'] }}</span>
                            </div>
                            <input type="checkbox" wire:model="{{ $toggle['key'] }}" class="h-5 w-5 rounded text-vanniyan-green-900 focus:ring-vanniyan-green-900">
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Consent -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-lg font-bold text-gray-900">Consent Management</h2>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $consentEnabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                        {{ $consentEnabled ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm mb-5">When enabled, Consent Mode defaults every non-essential category to <em>denied</em> before GTM loads, and visitors see a banner to grant Analytics and/or Marketing consent. Marketing-category events are buffered until consent is granted. When disabled, tags fire without a consent prompt — make sure this matches your privacy notice.</p>

                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <div>
                        <span class="font-bold text-sm text-gray-900">Enable consent management</span>
                        <span class="block text-xs text-gray-500">Shows the visitor banner and gates non-essential tracking.</span>
                    </div>
                    <input type="checkbox" wire:model.live="consentEnabled" class="h-5 w-5 rounded text-vanniyan-green-900 focus:ring-vanniyan-green-900">
                </label>
            </div>

            <!-- Test Mode -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-lg font-bold text-gray-900">Test Mode</h2>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $testMode ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-500' }}">
                        {{ $testMode ? 'On' : 'Off' }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm mb-5">Adds <code>debug_mode: true</code> to every dataLayer payload so you can identify test traffic in GTM Preview or GA4 debug view. Turn it off before launch.</p>

                <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <div>
                        <span class="font-bold text-sm text-gray-900">Enable test mode</span>
                        <span class="block text-xs text-gray-500">Flags payloads as debug traffic.</span>
                    </div>
                    <input type="checkbox" wire:model.live="testMode" class="h-5 w-5 rounded text-vanniyan-green-900 focus:ring-vanniyan-green-900">
                </label>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Security Note -->
            <div class="bg-vanniyan-green-900 text-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-vanniyan-gold uppercase tracking-widest text-xs mb-3">Security</h3>
                <ul class="text-sm space-y-2 text-white/90">
                    <li>• IDs are stored in the database and validated by strict format rules — arbitrary scripts are never accepted.</li>
                    <li>• No personal information is ever added to events.</li>
                    <li>• Purchase and booking-confirmation events are server-guarded against duplicates.</li>
                    <li>• Every change here is written to the audit log.</li>
                </ul>
            </div>

            <!-- Guidance -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-900 uppercase tracking-widest text-xs mb-3">Guidance</h3>
                <ul class="text-sm space-y-2 text-gray-600">
                    <li>• Create the container at <span class="font-mono text-xs">tagmanager.google.com</span> and paste its <code>GTM-</code> ID above.</li>
                    <li>• Publish GA4 and Meta Pixel tags inside GTM — never on the site.</li>
                    <li>• Use the <em>Test Configuration</em> button for a status summary; real tag verification happens in GTM Preview mode.</li>
                    <li>• Docs: <span class="font-mono text-xs">docs/google-tag-manager.md</span>, <span class="font-mono text-xs">docs/meta-pixel.md</span>, <span class="font-mono text-xs">docs/analytics-events.md</span>.</li>
                </ul>
            </div>
        </div>
    </div>
</div>