@php
    $analytics = app(\App\Services\AnalyticsService::class);
@endphp

@if ($analytics->consentEnabled() && $analytics->gtmEnabled())
    <div id="vanniyan-consent-banner" class="hidden fixed bottom-4 inset-x-4 sm:inset-x-auto sm:right-6 sm:left-6 md:left-auto md:max-w-md z-50 bg-vanniyan-green-900 text-white rounded-2xl shadow-2xl border border-vanniyan-gold/40 p-5">
        <p class="text-sm leading-relaxed mb-1 font-bold uppercase tracking-widest text-vanniyan-gold text-xs">Privacy</p>
        <p class="text-sm leading-relaxed mb-4">
            We use cookies and similar technologies to understand how visitors use our site
            and, with your consent, to measure marketing campaigns. You can change your
            choice at any time.
        </p>
        <div class="flex flex-wrap gap-2">
            <button type="button" id="vanniyan-consent-accept-all" class="px-4 py-2 bg-vanniyan-gold text-white rounded-full text-xs font-bold uppercase tracking-wider hover:bg-yellow-600 transition-colors">
                Accept All
            </button>
            <button type="button" id="vanniyan-consent-analytics" class="px-4 py-2 bg-white/10 border border-white/30 text-white rounded-full text-xs font-bold uppercase tracking-wider hover:bg-white/20 transition-colors">
                Analytics Only
            </button>
            <button type="button" id="vanniyan-consent-necessary" class="px-4 py-2 bg-transparent border border-white/30 text-white/80 rounded-full text-xs font-bold uppercase tracking-wider hover:bg-white/10 transition-colors">
                Necessary Only
            </button>
        </div>
        <p class="mt-4 text-xs text-gray-400">
            <a href="{{ route('privacy-policy') }}" class="underline hover:text-white transition-colors">Privacy Policy</a>
        </p>
    </div>
@endif