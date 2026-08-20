<?php

namespace Tests\Feature;

use App\Livewire\Admin\Settings\Analytics;
use App\Models\AdminUser;
use App\Models\AuditLog;
use App\Models\RestaurantSetting;
use App\Models\TakeawayOrder;
use App\Services\AnalyticsService;
use App\Services\RestaurantSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AnalyticsSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['admin.path' => 'vanniyan-control']);

        // The settings cache persists between tests when the cache driver is
        // file-based; always read fresh state.
        app(RestaurantSettingsService::class)->clearCache();
    }

    private function admin(): AdminUser
    {
        return AdminUser::factory()->create();
    }

    public function test_guest_is_redirected_away_from_analytics_settings(): void
    {
        $this->get('/vanniyan-control/settings/analytics')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_the_analytics_settings_page(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->get('/vanniyan-control/settings/analytics')
            ->assertOk()
            ->assertSee('Google Tag Manager')
            ->assertSee('Test Configuration');
    }

    public function test_saving_settings_persists_clears_cache_and_writes_audit_log(): void
    {
        $admin = $this->admin();

        Livewire::actingAs($admin, 'admin')
            ->test(Analytics::class)
            ->set('gtmEnabled', true)
            ->set('gtmContainerId', 'GTM-ABCDEF12')
            ->set('metaEnabled', true)
            ->set('metaPixelId', '123456789012345')
            ->set('eventsEnabled', true)
            ->set('trackMenu', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('1', RestaurantSetting::where('key', 'analytics_gtm_enabled')->value('value'));
        $this->assertSame('GTM-ABCDEF12', RestaurantSetting::where('key', 'analytics_gtm_container_id')->value('value'));
        $this->assertSame('123456789012345', RestaurantSetting::where('key', 'analytics_meta_pixel_id')->value('value'));
        $this->assertSame('1', RestaurantSetting::where('key', 'track_menu')->value('value'));

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'ANALYTICS_SETTINGS_UPDATED',
            'module' => 'ANALYTICS',
            'description' => 'Analytics & Marketing settings updated.',
        ]);

        $service = app(AnalyticsService::class);
        $this->assertTrue($service->gtmEnabled());
        $this->assertTrue($service->metaEnabled());
        $this->assertTrue($service->eventEnabled('menu'));
    }

    public function test_invalid_gtm_container_id_is_rejected(): void
    {
        Livewire::actingAs($this->admin(), 'admin')
            ->test(Analytics::class)
            ->set('gtmEnabled', true)
            ->set('gtmContainerId', '<script>alert(1)</script>')
            ->call('save')
            ->assertHasErrors('gtmContainerId');

        $this->assertDatabaseMissing('restaurant_settings', [
            'key' => 'analytics_gtm_container_id',
            'value' => '<script>alert(1)</script>',
        ]);
    }

    public function test_invalid_meta_pixel_id_is_rejected(): void
    {
        Livewire::actingAs($this->admin(), 'admin')
            ->test(Analytics::class)
            ->set('metaEnabled', true)
            ->set('metaPixelId', '123abc')
            ->call('save')
            ->assertHasErrors('metaPixelId');
    }

    public function test_gtm_snippet_does_not_render_when_disabled(): void
    {
        RestaurantSetting::updateOrCreate(['key' => 'analytics_gtm_enabled'], ['value' => '0']);
        RestaurantSetting::updateOrCreate(['key' => 'analytics_gtm_container_id'], ['value' => '']);

        $html = view('components.analytics.gtm')->render();
        $this->assertStringNotContainsString('googletagmanager.com', $html);
    }

    public function test_gtm_snippet_renders_when_enabled_with_valid_id(): void
    {
        RestaurantSetting::updateOrCreate(['key' => 'analytics_gtm_enabled'], ['value' => '1']);
        RestaurantSetting::updateOrCreate(['key' => 'analytics_gtm_container_id'], ['value' => 'GTM-ABCDEF12']);

        $html = view('components.analytics.gtm')->render();
        $this->assertStringContainsString('googletagmanager.com/gtm.js?id=', $html);
        $this->assertStringContainsString('GTM-ABCDEF12', $html);
        $this->assertStringContainsString('meta_pixel_id', $html);
    }

    public function test_garbage_gtm_id_never_reaches_the_snippet(): void
    {
        RestaurantSetting::updateOrCreate(['key' => 'analytics_gtm_enabled'], ['value' => '1']);
        RestaurantSetting::updateOrCreate(['key' => 'analytics_gtm_container_id'], ['value' => 'GTM-<script>bad</script>']);

        $html = view('components.analytics.gtm')->render();
        $this->assertStringNotContainsString('script>bad', $html);
        $this->assertStringNotContainsString('googletagmanager.com/gtm.js', $html);
    }

    public function test_purchase_event_is_server_authoritative_and_idempotent(): void
    {
        RestaurantSetting::updateOrCreate(['key' => 'analytics_events_enabled'], ['value' => '1']);
        RestaurantSetting::updateOrCreate(['key' => 'track_orders'], ['value' => '1']);

        $order = TakeawayOrder::create([
            'reference' => 'VAN-TA-ANALYTICS1',
            'customer_name' => 'Test Customer',
            'customer_phone' => '0771234567',
            'pickup_time' => now()->addHour(),
            'subtotal' => 2500,
            'total' => 2600,
        ]);

        $first = view('components.analytics.purchase', ['order' => $order])->render();
        $this->assertStringContainsString("'purchase'", $first);
        $this->assertTrue((bool) $order->fresh()->purchase_event_sent);

        $second = view('components.analytics.purchase', ['order' => $order])->render();
        $this->assertStringNotContainsString("'purchase'", $second);
    }

    public function test_purchase_event_does_not_fire_when_order_tracking_disabled(): void
    {
        RestaurantSetting::updateOrCreate(['key' => 'analytics_events_enabled'], ['value' => '1']);
        RestaurantSetting::updateOrCreate(['key' => 'track_orders'], ['value' => '0']);

        $order = TakeawayOrder::create([
            'reference' => 'VAN-TA-ANALYTICS2',
            'customer_name' => 'Test Customer',
            'customer_phone' => '0771234567',
            'pickup_time' => now()->addHour(),
            'subtotal' => 2500,
            'total' => 2600,
        ]);

        $html = view('components.analytics.purchase', ['order' => $order])->render();
        $this->assertStringNotContainsString("'purchase'", $html);
        $this->assertFalse((bool) $order->fresh()->purchase_event_sent);
    }

    public function test_defaults_are_all_off(): void
    {
        $service = app(AnalyticsService::class);

        $this->assertFalse($service->gtmEnabled());
        $this->assertFalse($service->metaEnabled());
        $this->assertFalse($service->eventsEnabled());
        $this->assertFalse($service->consentEnabled());
        $this->assertFalse($service->isConfigured());
    }

    public function test_validation_rules(): void
    {
        $this->assertTrue(AnalyticsService::isValidGtmId('GTM-ABCDEF12'));
        $this->assertFalse(AnalyticsService::isValidGtmId('GTM-abc'));
        $this->assertFalse(AnalyticsService::isValidGtmId('UA-123'));
        $this->assertTrue(AnalyticsService::isValidPixelId('123456789012345'));
        $this->assertFalse(AnalyticsService::isValidPixelId('12abc789012345'));
        $this->assertFalse(AnalyticsService::isValidPixelId(''));
    }

    public function test_event_gating_requires_both_master_switch_and_group_toggle(): void
    {
        RestaurantSetting::updateOrCreate(['key' => 'analytics_events_enabled'], ['value' => '0']);
        RestaurantSetting::updateOrCreate(['key' => 'track_menu'], ['value' => '1']);

        $service = app(AnalyticsService::class);
        $this->assertFalse($service->eventEnabled('menu'));

        RestaurantSetting::where('key', 'analytics_events_enabled')->update(['value' => '1']);
        app(\App\Services\RestaurantSettingsService::class)->clearCache();

        $this->assertTrue(app(AnalyticsService::class)->eventEnabled('menu'));
        $this->assertFalse(app(AnalyticsService::class)->eventEnabled('unknown'));
    }
}