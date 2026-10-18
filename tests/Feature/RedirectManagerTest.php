<?php

namespace Tests\Feature;

use App\Models\RedirectRule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class RedirectManagerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_redirect_manager(): void
    {
        $response = $this->get(route('admin.redirects.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_redirects_index(): void
    {
        RedirectRule::create([
            'source_url' => '/old-test-url',
            'target_url' => '/new-test-url',
            'status_code' => 301,
            'match_type' => 'exact',
            'query_param_option' => 'ignore',
            'group' => 'SEO',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.redirects.index'));

        $response->assertStatus(200);
        $response->assertSee('301 Redirection Manager');
        $response->assertSee('/old-test-url');
        $response->assertSee('/new-test-url');
    }

    public function test_admin_can_create_new_redirect_rule(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.redirects.store'), [
            'source_url' => 'https://recoverydata.ae/old-broken-slug',
            'target_url' => '/service/hard-drive-recovery',
            'status_code' => 301,
            'match_type' => 'exact',
            'query_param_option' => 'pass',
            'group' => 'Services',
            'description' => 'Fix broken hard drive link',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.redirects.index'));
        $this->assertDatabaseHas('redirect_rules', [
            'source_url' => '/old-broken-slug', // normalized leading slash & stripped domain
            'target_url' => '/service/hard-drive-recovery',
            'status_code' => 301,
            'match_type' => 'exact',
            'group' => 'Services',
        ]);
    }

    public function test_middleware_executes_301_redirect_and_increments_hits(): void
    {
        $rule = RedirectRule::create([
            'source_url' => '/vintage-data-recovery',
            'target_url' => '/service/hard-drive-recovery',
            'status_code' => 301,
            'match_type' => 'exact',
            'query_param_option' => 'ignore',
            'is_active' => true,
            'hits' => 0,
        ]);

        $response = $this->get('/vintage-data-recovery');

        $response->assertStatus(301);
        $response->assertRedirect('/service/hard-drive-recovery');

        $rule->refresh();
        $this->assertEquals(1, $rule->hits);
        $this->assertNotNull($rule->last_accessed_at);
    }

    public function test_inactive_redirect_rule_is_ignored(): void
    {
        RedirectRule::create([
            'source_url' => '/paused-redirect-page',
            'target_url' => '/service/hard-drive-recovery',
            'status_code' => 301,
            'is_active' => false,
        ]);

        $response = $this->get('/paused-redirect-page');

        // Should not redirect, 404 since page doesn't exist
        $response->assertStatus(404);
    }

    public function test_query_parameter_passing_mode(): void
    {
        RedirectRule::create([
            'source_url' => '/promo-campaign-landing',
            'target_url' => '/contact',
            'status_code' => 302,
            'match_type' => 'exact',
            'query_param_option' => 'pass',
            'is_active' => true,
        ]);

        $response = $this->get('/promo-campaign-landing?utm_source=google&utm_campaign=dubai_promo');

        $response->assertStatus(302);
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringStartsWith(url('/contact'), $redirectUrl);
        $this->assertStringContainsString('utm_source=google', $redirectUrl);
        $this->assertStringContainsString('utm_campaign=dubai_promo', $redirectUrl);
    }

    public function test_wildcard_redirect_matches_subpaths(): void
    {
        RedirectRule::create([
            'source_url' => '/old-articles/*',
            'target_url' => '/blog/$1',
            'match_type' => 'wildcard',
            'status_code' => 301,
            'query_param_option' => 'pass',
            'is_active' => true,
        ]);

        $response = $this->get('/old-articles/how-to-fix-broken-ssd');

        $response->assertStatus(301);
        $response->assertRedirect('/blog/how-to-fix-broken-ssd');
    }

    public function test_regex_redirect_evaluates_pattern(): void
    {
        RedirectRule::create([
            'source_url' => '^/archive/(\d{4})/(.*)$',
            'target_url' => '/post/$2',
            'match_type' => 'regex',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $response = $this->get('/archive/2021/data-recovery-tips');

        $response->assertStatus(301);
        $response->assertRedirect('/post/data-recovery-tips');
    }

    public function test_http_410_content_gone_status(): void
    {
        RedirectRule::create([
            'source_url' => '/obsolete-discontinued-service',
            'target_url' => null,
            'status_code' => 410,
            'match_type' => 'exact',
            'is_active' => true,
        ]);

        $response = $this->get('/obsolete-discontinued-service');

        $response->assertStatus(410);
    }

    public function test_infinite_loop_prevention_validation(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.redirects.store'), [
            'source_url' => '/contact',
            'target_url' => '/contact',
            'status_code' => 301,
            'match_type' => 'exact',
            'query_param_option' => 'ignore',
        ]);

        $response->assertSessionHasErrors('target_url');
    }

    public function test_admin_route_protection_prevents_hijack(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.redirects.store'), [
            'source_url' => '/admin',
            'target_url' => '/',
            'status_code' => 301,
            'match_type' => 'exact',
            'query_param_option' => 'ignore',
        ]);

        $response->assertSessionHasErrors('source_url');
    }

    public function test_live_url_simulation_endpoint(): void
    {
        $rule = RedirectRule::create([
            'source_url' => '/simulate-test-source',
            'target_url' => '/service/raid-recovery-dubai',
            'status_code' => 301,
            'match_type' => 'exact',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('admin.redirects.test'), [
            'url' => '/simulate-test-source?source=seo_audit',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'matched' => true,
            'status_code' => 301,
            'target_url' => '/service/raid-recovery-dubai',
            'rule_id' => $rule->id,
        ]);
    }

    public function test_toggle_active_status_endpoint(): void
    {
        $rule = RedirectRule::create([
            'source_url' => '/toggle-rule',
            'target_url' => '/destination',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->patchJson(route('admin.redirects.toggle', $rule));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_active' => false,
        ]);

        $rule->refresh();
        $this->assertFalse($rule->is_active);
    }

    public function test_csv_export_endpoint(): void
    {
        RedirectRule::create([
            'source_url' => '/export-source',
            'target_url' => '/export-target',
            'status_code' => 301,
            'group' => 'ExportGroup',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.redirects.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Source URL', $response->streamedContent());
        $this->assertStringContainsString('/export-source', $response->streamedContent());
        $this->assertStringContainsString('/export-target', $response->streamedContent());
    }

    public function test_csv_import_endpoint(): void
    {
        $csvContent = "Source URL,Target URL,Status Code,Match Type,Query Param,Group,Description\n".
            "/csv-imported-1,/services/raid,301,exact,pass,Bulk Import,First imported\n".
            "/csv-imported-2,/about,302,exact,ignore,Bulk Import,Second imported\n";

        $file = UploadedFile::fake()->createWithContent('redirects.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('admin.redirects.import'), [
            'csv_file' => $file,
        ]);

        $response->assertRedirect(route('admin.redirects.index'));
        $this->assertDatabaseHas('redirect_rules', [
            'source_url' => '/csv-imported-1',
            'target_url' => '/services/raid',
            'status_code' => 301,
        ]);
        $this->assertDatabaseHas('redirect_rules', [
            'source_url' => '/csv-imported-2',
            'target_url' => '/about',
            'status_code' => 302,
        ]);
    }

    public function test_bulk_operations(): void
    {
        $rule1 = RedirectRule::create(['source_url' => '/bulk-1', 'target_url' => '/target-1', 'is_active' => true]);
        $rule2 = RedirectRule::create(['source_url' => '/bulk-2', 'target_url' => '/target-2', 'is_active' => true]);

        // Disable bulk
        $response = $this->actingAs($this->admin)->post(route('admin.redirects.bulk'), [
            'action' => 'disable',
            'selected' => [$rule1->id, $rule2->id],
        ]);

        $response->assertRedirect(route('admin.redirects.index'));
        $this->assertFalse($rule1->fresh()->is_active);
        $this->assertFalse($rule2->fresh()->is_active);

        // Delete bulk
        $this->actingAs($this->admin)->post(route('admin.redirects.bulk'), [
            'action' => 'delete',
            'selected' => [$rule1->id],
        ]);

        $this->assertDatabaseMissing('redirect_rules', ['id' => $rule1->id]);
    }
}
