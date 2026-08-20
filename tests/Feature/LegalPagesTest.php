<?php

namespace Tests\Feature;

use App\Livewire\Admin\Settings\Legal;
use App\Models\AdminUser;
use App\Models\AuditLog;
use App\Models\RestaurantSetting;
use App\Services\LegalService;
use App\Services\RestaurantSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['admin.path' => 'vanniyan-control']);

        app(RestaurantSettingsService::class)->clearCache();
    }

    private function admin(): AdminUser
    {
        return AdminUser::factory()->create();
    }

    // ------------------------------------------------------------------
    // Public pages
    // ------------------------------------------------------------------

    public function test_privacy_policy_page_loads_with_content(): void
    {
        $this->get('/privacy-policy')
            ->assertOk()
            ->assertSee('Privacy Policy')
            ->assertSee('Last updated')
            ->assertSee('Information We Collect')
            ->assertSee('Introduction');
    }

    public function test_terms_page_loads_with_content(): void
    {
        $this->get('/terms-and-conditions')
            ->assertOk()
            ->assertSee('Terms & Conditions')
            ->assertSee('About These Terms')
            ->assertSee('Vanniyan provides the venue');
    }

    public function test_sitemap_page_loads(): void
    {
        $this->get('/sitemap')
            ->assertOk()
            ->assertSee('Sitemap')
            ->assertSee(route('privacy-policy'))
            ->assertSee(route('terms-and-conditions'));
    }

    public function test_legal_pages_have_indexable_meta(): void
    {
        $this->get('/privacy-policy')
            ->assertOk()
            ->assertSee('Vanniyan Restaurant Privacy Policy')
            ->assertSee('index, follow');

        $this->get('/terms-and-conditions')
            ->assertOk()
            ->assertSee('Vanniyan Restaurant Terms & Conditions')
            ->assertSee('index, follow');
    }

    public function test_published_content_is_never_served_when_draft_contains_scripts(): void
    {
        RestaurantSetting::where('key', 'legal_privacy_published')->update(['value' => '<h2>Intro</h2><p>Hello <script>alert(1)</script></p><p><iframe src="https://evil.example"></iframe></p><a href="javascript:alert(1)">x</a>']);

        $response = $this->get('/privacy-policy');

        $response->assertOk()
            ->assertSee('Intro')
            ->assertSee('Hello');

        $this->assertStringNotContainsString('<script>alert(1)</script>', $response->getContent());
        $this->assertStringNotContainsString('<iframe', $response->getContent());
        $this->assertStringNotContainsString('href="javascript:', $response->getContent());
    }

    // ------------------------------------------------------------------
    // Admin panel
    // ------------------------------------------------------------------

    public function test_guest_is_redirected_away_from_legal_settings(): void
    {
        $this->get('/vanniyan-control/settings/legal')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_the_legal_settings_page(): void
    {
        $this->actingAs($this->admin(), 'admin')
            ->get('/vanniyan-control/settings/legal')
            ->assertOk()
            ->assertSee('Legal Documents')
            ->assertSee('reviewed and approved by the business/legal adviser');
    }

    public function test_admin_can_publish_privacy_policy_with_sanitization_and_audit_log(): void
    {
        $admin = $this->admin();

        Livewire::actingAs($admin, 'admin')
            ->test(Legal::class)
            ->set('privacyDraft', '<h2>New Section</h2><p>Fresh text <script>alert(1)</script></p>')
            ->set('privacyPublishedAt', '2026-08-20')
            ->call('publishPrivacy')
            ->assertHasNoErrors()
            ->assertSet('privacyStatus', 'Published 2026-08-20 by '.$admin->name.'. Draft has content.');

        $this->assertSame(
            '<h2 id="new-section-1">New Section</h2><p>Fresh text</p>',
            app(LegalService::class)->published(LegalService::DOC_PRIVACY),
        );

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'LEGAL_PUBLISHED',
            'module' => 'LEGAL',
        ]);
    }

    public function test_publishing_terms_records_updated_by_and_clears_settings_cache(): void
    {
        $admin = $this->admin();

        Livewire::actingAs($admin, 'admin')
            ->test(Legal::class)
            ->set('termsDraft', '<h2>Terms v2</h2><p>Updated.</p>')
            ->set('termsPublishedAt', '2026-08-21')
            ->call('publishTerms')
            ->assertHasNoErrors();

        $this->assertSame('2026-08-21', app(LegalService::class)->publishedAt(LegalService::DOC_TERMS));
        $this->assertSame($admin->name, app(LegalService::class)->updatedBy(LegalService::DOC_TERMS));
    }

    public function test_saving_a_draft_never_publishes_it(): void
    {
        $admin = $this->admin();

        Livewire::actingAs($admin, 'admin')
            ->test(Legal::class)
            ->set('privacyDraft', '<h2>Draft Only</h2>')
            ->call('savePrivacyDraft')
            ->assertHasNoErrors();

        $this->assertStringContainsString('Introduction', app(LegalService::class)->published(LegalService::DOC_PRIVACY));
        $this->assertSame('<h2 id="draft-only-1">Draft Only</h2>', app(LegalService::class)->draft(LegalService::DOC_PRIVACY));
    }

    public function test_admin_can_update_governing_law(): void
    {
        $admin = $this->admin();

        Livewire::actingAs($admin, 'admin')
            ->test(Legal::class)
            ->set('governingLaw', 'Democratic Socialist Republic of Sri Lanka')
            ->call('saveGoverningLaw')
            ->assertHasNoErrors();

        $this->assertSame('Democratic Socialist Republic of Sri Lanka', app(LegalService::class)->governingLaw());
    }

    // ------------------------------------------------------------------
    // Sitemap & robots
    // ------------------------------------------------------------------

    public function test_xml_sitemap_includes_legal_pages_and_sitemap_page(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk();

        $content = $response->getContent();

        $this->assertStringContainsString('<loc>'.url('/privacy-policy').'</loc>', $content);
        $this->assertStringContainsString('<loc>'.url('/terms-and-conditions').'</loc>', $content);
        $this->assertStringContainsString('<loc>'.url('/sitemap').'</loc>', $content);
        $this->assertStringContainsString('<loc>'.url('/menu').'</loc>', $content);
    }

    public function test_xml_sitemap_excludes_private_and_admin_urls(): void
    {
        $content = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringNotContainsString('/vanniyan-control', $content);
        $this->assertStringNotContainsString('/order/', $content);
        $this->assertStringNotContainsString('/cart', $content);
        $this->assertStringNotContainsString('/checkout', $content);
        $this->assertStringNotContainsString('/booking/venue/', $content);
        $this->assertStringNotContainsString('/login', $content);
    }

    public function test_xml_sitemap_excludes_stories_marked_noindex(): void
    {
        $story = \App\Models\Story::create([
            'title' => 'Hidden Story',
            'slug' => 'hidden-story-'.uniqid(),
            'excerpt' => 'Should not appear in the sitemap.',
            'content' => 'Body text.',
            'is_published' => true,
        ]);
        $story->seoMetadata()->create(['robots' => 'noindex, nofollow']);

        $content = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringNotContainsString($story->slug, $content);
    }

    public function test_robots_txt_points_to_production_sitemap_and_blocks_private_paths(): void
    {
        RestaurantSetting::firstOrCreate(
            ['key' => 'seo_canonical_base'],
            ['value' => 'https://www.vanniyanrestaurant.com'],
        );
        app(RestaurantSettingsService::class)->clearCache();

        $response = $this->get('/robots.txt')->assertOk();

        $content = $response->getContent();

        $this->assertStringContainsString('Sitemap: https://www.vanniyanrestaurant.com/sitemap.xml', $content);
        $this->assertStringContainsString('Disallow: /vanniyan-control', $content);
        $this->assertStringContainsString('Disallow: /order/', $content);
        $this->assertStringNotContainsString('/privacy-policy', $content);
    }
}