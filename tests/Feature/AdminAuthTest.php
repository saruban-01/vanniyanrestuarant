<?php

namespace Tests\Feature;

use App\Livewire\Admin\Auth\Login;
use App\Models\AdminUser;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Keep the panel path deterministic regardless of the local .env.
        config(['admin.path' => 'vanniyan-control']);
    }

    public function test_admin_login_page_is_served_at_the_configured_path(): void
    {
        $this->get('/vanniyan-control/login')->assertStatus(200);
        $this->assertSame('/vanniyan-control/login', parse_url(route('admin.login'), PHP_URL_PATH));
        $this->assertSame('/vanniyan-control', parse_url(route('admin.dashboard'), PHP_URL_PATH));
    }

    public function test_legacy_admin_urls_return_404_without_redirecting(): void
    {
        $this->get('/admin')->assertNotFound();
        $this->get('/admin/login')->assertNotFound();
        $this->get('/admin/anything/deep')->assertNotFound();
        $this->post('/admin')->assertNotFound();
    }

    public function test_guest_is_redirected_to_admin_login_when_visiting_the_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_in_and_is_redirected_to_the_dashboard(): void
    {
        $admin = AdminUser::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('secret123'),
        ]);

        Livewire::test(Login::class)
            ->set('username', 'admin')
            ->set('password', 'secret123')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.dashboard'));

        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertSame($admin->id, Auth::guard('admin')->id());

        $this->assertNotNull($admin->fresh()->last_login_at);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'LOGIN',
            'module' => 'AUTHENTICATION',
        ]);
    }

    public function test_login_with_invalid_credentials_fails_with_a_generic_error(): void
    {
        AdminUser::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('secret123'),
        ]);

        Livewire::test(Login::class)
            ->set('username', 'admin')
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors(['username' => 'The username or password is incorrect.']);

        $this->assertFalse(Auth::guard('admin')->check());

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'LOGIN_FAILED',
            'module' => 'AUTHENTICATION',
        ]);
    }

    public function test_login_is_rate_limited_after_five_failed_attempts(): void
    {
        AdminUser::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('secret123'),
        ]);

        RateLimiter::clear('admin_login|127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            Livewire::test(Login::class)
                ->set('username', 'admin')
                ->set('password', 'wrong-password')
                ->call('login')
                ->assertHasErrors('username');
        }

        $test = Livewire::test(Login::class)
            ->set('username', 'admin')
            ->set('password', 'secret123')
            ->call('login')
            ->assertHasErrors('username')
            ->assertNoRedirect();

        $this->assertStringContainsString('Too many login attempts', $test->errors()->first('username'));
        $this->assertFalse(Auth::guard('admin')->check());
    }

    public function test_authenticated_admin_visiting_the_login_page_is_redirected_to_the_dashboard(): void
    {
        $admin = AdminUser::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.login'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_deactivated_admin_is_signed_out_and_redirected_to_login(): void
    {
        $admin = AdminUser::factory()->inactive()->create();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));

        $response->assertSessionMissing(Auth::guard('admin')->getName());
        $this->assertFalse(Auth::guard('admin')->check());
    }

    public function test_logged_in_admin_can_reach_the_dashboard(): void
    {
        $admin = AdminUser::factory()->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_regular_web_user_cannot_access_the_admin_panel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'web')
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));

        $this->assertFalse(Auth::guard('admin')->check());
    }

    public function test_logout_invalidates_the_session_and_redirects_to_login(): void
    {
        $admin = AdminUser::factory()->create();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertFalse(Auth::guard('admin')->check());
    }
}