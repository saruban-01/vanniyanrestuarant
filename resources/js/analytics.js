/**
 * Vanniyan analytics client.
 *
 * Single controlled entry point for all dataLayer pushes. The public site never
 * touches GTM or Meta directly: GTM is injected by the server-side component,
 * Meta Pixel is configured inside GTM, and every event flows through here.
 *
 * Consent: when consent management is enabled, the server pushes Consent Mode
 * defaults (denied) before GTM loads. Events in the "marketing" category are
 * buffered until the visitor grants marketing consent, then flushed.
 */

(function () {
    'use strict';

    var config = window.vanniyanConfig || {
        events_enabled: false,
        consent_enabled: false,
        environment: 'development',
        test_mode: false,
        currency: 'LKR',
    };

    var CONSENT_KEY = 'vanniyan_consent';
    var consent = null;

    try {
        consent = JSON.parse(window.localStorage.getItem(CONSENT_KEY) || 'null');
    } catch (e) {
        consent = null;
    }

    var pending = [];

    function categoryAllowed(category) {
        if (!config.consent_enabled) return true;
        if (category === 'necessary') return true;
        return !!consent && consent[category] === true;
    }

    function fire(payload) {
        (window.dataLayer = window.dataLayer || []).push(payload);
    }

    function push(event, data, opts) {
        if (!config.events_enabled) return;
        data = data || {};
        opts = opts || {};

        var category = opts.consent || 'analytics';
        var payload = { event: event };
        Object.keys(data).forEach(function (key) {
            payload[key] = data[key];
        });
        payload.vanniyan = {
            consent_category: category,
            environment: config.environment,
            currency: config.currency,
            debug_mode: !!(config.test_mode || config.environment !== 'production'),
        };

        if (!categoryAllowed(category)) {
            pending.push(payload);
            return;
        }

        fire(payload);
    }

    function flushPending() {
        pending.forEach(fire);
        pending = [];
    }

    function buildConsentPayload() {
        var analyticsGranted = !!consent && consent.analytics === true;
        var marketingGranted = !!consent && consent.marketing === true;

        return {
            event: 'consent_update',
            consent: {
                ad_storage: marketingGranted ? 'granted' : 'denied',
                ad_user_data: marketingGranted ? 'granted' : 'denied',
                ad_personalization: marketingGranted ? 'granted' : 'denied',
                analytics_storage: analyticsGranted ? 'granted' : 'denied',
                functionality_storage: 'granted',
                personalization_storage: 'denied',
                security_storage: 'granted',
            },
        };
    }

    function setConsent(categories) {
        consent = consent || {};
        Object.keys(categories).forEach(function (key) {
            consent[key] = !!categories[key];
        });

        try {
            window.localStorage.setItem(CONSENT_KEY, JSON.stringify(consent));
        } catch (e) {
            /* storage unavailable – consent applies to this session only */
        }

        fire(buildConsentPayload());
        fire({ event: 'consent_choice', consent: consent });
        flushPending();
    }

    function hasChoice() {
        return consent !== null && Object.keys(consent).length > 0;
    }

    // ------------------------------------------------------------------
    // Banner wiring
    // ------------------------------------------------------------------

    function initBanner() {
        var banner = document.getElementById('vanniyan-consent-banner');
        if (!banner || !config.consent_enabled) return;

        if (hasChoice()) {
            banner.remove();
            return;
        }

        banner.classList.remove('hidden');

        document.getElementById('vanniyan-consent-accept-all').addEventListener('click', function () {
            setConsent({ analytics: true, marketing: true });
            banner.remove();
        });

        document.getElementById('vanniyan-consent-analytics').addEventListener('click', function () {
            setConsent({ analytics: true, marketing: false });
            banner.remove();
        });

        document.getElementById('vanniyan-consent-necessary').addEventListener('click', function () {
            setConsent({ analytics: false, marketing: false });
            banner.remove();
        });
    }

    // ------------------------------------------------------------------
    // Declarative click tracking: <button data-track-event="offer_clicked"
    // data-track-data='{"offer_id": 3}' data-track-consent="marketing">
    // ------------------------------------------------------------------

    document.addEventListener('click', function (event) {
        var el = event.target.closest ? event.target.closest('[data-track-event]') : null;
        if (!el) return;

        var raw = el.getAttribute('data-track-data') || '{}';
        var data = {};
        try {
            data = JSON.parse(raw);
        } catch (e) {
            /* ignore malformed data attributes */
        }

        push(el.getAttribute('data-track-event'), data, {
            consent: el.getAttribute('data-track-consent') || 'analytics',
        });
    }, true);

    // ------------------------------------------------------------------
    // Livewire bridge: $this->dispatch('vanniyan-track', event: ..., data: ...)
    // ------------------------------------------------------------------

    window.addEventListener('vanniyan-track', function (event) {
        var detail = event.detail || {};
        push(detail.event, detail.data || {}, { consent: detail.consent || 'analytics' });
    });

    // ------------------------------------------------------------------

    window.VanniyanAnalytics = {
        push: push,
        setConsent: setConsent,
        getConsent: function () { return consent; },
        hasConsentChoice: hasChoice,
        config: config,
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBanner);
    } else {
        initBanner();
    }
})();