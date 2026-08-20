<?php

namespace App\Livewire\Admin\Settings;

use App\Models\AuditLog;
use App\Services\AnalyticsService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Analytics extends Component
{
    public bool $gtmEnabled = false;

    public string $gtmContainerId = '';

    public bool $metaEnabled = false;

    public string $metaPixelId = '';

    public bool $eventsEnabled = false;

    public bool $consentEnabled = false;

    public bool $testMode = false;

    public bool $trackMenu = false;

    public bool $trackCart = false;

    public bool $trackOrders = false;

    public bool $trackTableBookings = false;

    public bool $trackVenueBookings = false;

    public bool $trackOffers = false;

    public bool $trackStories = false;

    public bool $trackGoogleReviews = false;

    public ?string $testResult = null;

    protected function rules(): array
    {
        return [
            'gtmContainerId' => ['nullable', 'string', 'max:40', 'regex:/^GTM-[A-Z0-9]{4,15}$/'],
            'metaPixelId' => ['nullable', 'string', 'regex:/^\d{13,17}$/'],
            'gtmEnabled' => ['boolean'],
            'metaEnabled' => ['boolean'],
            'eventsEnabled' => ['boolean'],
            'consentEnabled' => ['boolean'],
            'testMode' => ['boolean'],
            'trackMenu' => ['boolean'],
            'trackCart' => ['boolean'],
            'trackOrders' => ['boolean'],
            'trackTableBookings' => ['boolean'],
            'trackVenueBookings' => ['boolean'],
            'trackOffers' => ['boolean'],
            'trackStories' => ['boolean'],
            'trackGoogleReviews' => ['boolean'],
        ];
    }

    public function mount(AnalyticsService $analytics): void
    {
        $this->gtmEnabled = $analytics->get('analytics_gtm_enabled') === '1';
        $this->gtmContainerId = (string) $analytics->get('analytics_gtm_container_id', '');
        $this->metaEnabled = $analytics->get('analytics_meta_enabled') === '1';
        $this->metaPixelId = (string) $analytics->get('analytics_meta_pixel_id', '');
        $this->eventsEnabled = $analytics->get('analytics_events_enabled') === '1';
        $this->consentEnabled = $analytics->get('analytics_consent_enabled') === '1';
        $this->testMode = $analytics->get('analytics_test_mode') === '1';
        $this->trackMenu = $analytics->get('track_menu') === '1';
        $this->trackCart = $analytics->get('track_cart') === '1';
        $this->trackOrders = $analytics->get('track_orders') === '1';
        $this->trackTableBookings = $analytics->get('track_table_bookings') === '1';
        $this->trackVenueBookings = $analytics->get('track_venue_bookings') === '1';
        $this->trackOffers = $analytics->get('track_offers') === '1';
        $this->trackStories = $analytics->get('track_stories') === '1';
        $this->trackGoogleReviews = $analytics->get('track_google_reviews') === '1';
    }

    public function save(AnalyticsService $analytics): void
    {
        $this->validate();

        $changes = $analytics->saveSettings([
            'analytics_gtm_enabled' => $this->gtmEnabled ? '1' : '0',
            'analytics_gtm_container_id' => trim($this->gtmContainerId),
            'analytics_meta_enabled' => $this->metaEnabled ? '1' : '0',
            'analytics_meta_pixel_id' => trim($this->metaPixelId),
            'analytics_events_enabled' => $this->eventsEnabled ? '1' : '0',
            'analytics_consent_enabled' => $this->consentEnabled ? '1' : '0',
            'analytics_test_mode' => $this->testMode ? '1' : '0',
            'track_menu' => $this->trackMenu ? '1' : '0',
            'track_cart' => $this->trackCart ? '1' : '0',
            'track_orders' => $this->trackOrders ? '1' : '0',
            'track_table_bookings' => $this->trackTableBookings ? '1' : '0',
            'track_venue_bookings' => $this->trackVenueBookings ? '1' : '0',
            'track_offers' => $this->trackOffers ? '1' : '0',
            'track_stories' => $this->trackStories ? '1' : '0',
            'track_google_reviews' => $this->trackGoogleReviews ? '1' : '0',
        ]);

        if ($changes === []) {
            session()->flash('message', 'No changes to save.');
            return;
        }

        AuditLog::log('ANALYTICS_SETTINGS_UPDATED', 'Analytics & Marketing settings updated.', [
            'module' => 'ANALYTICS',
            'changes' => $changes,
        ]);

        $this->testResult = null;
        session()->flash('message', 'Analytics & Marketing settings saved. Changes apply immediately.');
    }

    /**
     * Configuration summary shown in the panel. Never claims a connection to
     * Google/Meta has been verified — it only reports the configured state.
     */
    public function runTest(AnalyticsService $analytics): void
    {
        $lines = [];

        if (! $analytics->gtmEnabled()) {
            $lines[] = 'Google Tag Manager: NOT configured (add a valid GTM-[A-Z0-9] ID and enable it).';
        } else {
            $lines[] = 'Google Tag Manager: configured ('.$analytics->gtmContainerId().') in '.$analytics->environment().'.';
        }

        if (! $analytics->metaEnabled()) {
            $lines[] = 'Meta Pixel: NOT configured (add a numeric Pixel ID and enable it).';
        } else {
            $lines[] = 'Meta Pixel: configured through GTM (Pixel '.substr($analytics->metaPixelId(), 0, 4).'…). Loads only when the GTM container publishes the Pixel tag.';
        }

        if (! $analytics->eventsEnabled()) {
            $lines[] = 'Event tracking: DISABLED (no dataLayer events are emitted).';
        } else {
            $active = collect(\App\Services\AnalyticsService::EVENT_TRACKS)
                ->filter(fn ($track) => $analytics->eventEnabled($track))
                ->map(fn ($track) => str_replace('_', ' ', $track))
                ->implode(', ');

            $lines[] = 'Event tracking: ENABLED'.($active !== '' ? ' for: '.$active : '').'.';
        }

        $lines[] = 'Consent management: '.($analytics->consentEnabled() ? 'ENABLED (non-essential tags start denied).' : 'DISABLED (tags fire without a consent prompt — document this in your privacy notice).');

        $lines[] = 'Test mode: '.($analytics->testMode() ? 'ON (dataLayer payloads include debug_mode=true).' : 'OFF.');

        $this->testResult = implode("\n", $lines);
    }

    public function render()
    {
        return view('livewire.admin.settings.analytics');
    }
}