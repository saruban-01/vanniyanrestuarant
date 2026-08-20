@php
    $analytics = app(\App\Services\AnalyticsService::class);
    $gtmId = $analytics->gtmContainerId();
@endphp

@if ($analytics->gtmEnabled() && $gtmId !== '')
    {{-- Runtime configuration consumed by the analytics client --}}
    <script>
        window.vanniyanConfig = {
            events_enabled: {{ $analytics->eventsEnabled() ? 'true' : 'false' }},
            consent_enabled: {{ $analytics->consentEnabled() ? 'true' : 'false' }},
            environment: @js($analytics->environment()),
            test_mode: {{ $analytics->testMode() ? 'true' : 'false' }},
            currency: @js(\App\Services\AnalyticsService::CURRENCY)
        };
    </script>

    {{-- Consent Mode defaults: everything non-essential denied until the visitor chooses --}}
    @if ($analytics->consentEnabled())
        <script>
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                'event': 'consent_default',
                'consent': {
                    'ad_storage': 'denied',
                    'ad_user_data': 'denied',
                    'ad_personalization': 'denied',
                    'analytics_storage': 'denied',
                    'functionality_storage': 'granted',
                    'personalization_storage': 'denied',
                    'security_storage': 'granted'
                }
            });
        </script>
    @endif

    {{-- Site configuration exposed to GTM (Meta Pixel ID is delivered through this layer) --}}
    <script>
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'vanniyan': {
                'environment': @js($analytics->environment()),
                'currency': @js(\App\Services\AnalyticsService::CURRENCY),
                'meta_pixel_id': @js($analytics->metaEnabled() ? $analytics->metaPixelId() : ''),
                'events_enabled': {{ $analytics->eventsEnabled() ? 'true' : 'false' }},
                'consent_enabled': {{ $analytics->consentEnabled() ? 'true' : 'false' }},
                'test_mode': {{ $analytics->testMode() ? 'true' : 'false' }}
            }
        });
    </script>

    {{-- Official Google Tag Manager head snippet --}}
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer',@js($gtmId));</script>
@endif