<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchemaMarkupTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_admin_can_save_and_update_custom_schema_on_post(): void
    {
        $this->actingAs($this->user);

        $customSchema = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'Can you recover water damaged hard drives?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes, our cleanroom engineers clean and dry platters in ISO Class 100 environments.',
                    ],
                ],
            ],
        ], JSON_PRETTY_PRINT);

        $storeResponse = $this->post(route('admin.posts.store'), [
            'title' => 'Water Damaged Hard Drive Recovery Dubai',
            'content' => '<p>Specialist recovery for submerged hard disk drives.</p>',
            'status' => 'published',
            'schema_markup' => $customSchema,
        ]);

        $storeResponse->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'title' => 'Water Damaged Hard Drive Recovery Dubai',
            'schema_markup' => $customSchema,
        ]);

        $post = Post::where('slug', 'water-damaged-hard-drive-recovery-dubai')->firstOrFail();

        $updatedSchema = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'HowTo',
            'name' => 'How to dry a wet hard drive safely',
        ]);

        $updateResponse = $this->put(route('admin.posts.update', $post), [
            'title' => 'Water Damaged Hard Drive Recovery Dubai',
            'content' => '<p>Updated content.</p>',
            'status' => 'published',
            'schema_markup' => $updatedSchema,
        ]);

        $updateResponse->assertRedirect();
        $post->refresh();
        $this->assertEquals($updatedSchema, $post->schema_markup);
    }

    public function test_admin_can_save_custom_schema_on_page(): void
    {
        $this->actingAs($this->user);

        $schema = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'AboutPage',
            'name' => 'About Recovery Data Dubai Cleanroom Lab',
        ]);

        $response = $this->post(route('admin.pages.store'), [
            'title' => 'About Our Dubai Lab',
            'content' => '<p>Lab description and cleanroom specs.</p>',
            'template' => 'default',
            'status' => 'published',
            'schema_markup' => $schema,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pages', [
            'title' => 'About Our Dubai Lab',
            'schema_markup' => $schema,
        ]);
    }

    public function test_frontend_post_renders_automatic_blogposting_and_breadcrumbs(): void
    {
        $category = Category::create([
            'name' => 'RAID Recovery',
            'slug' => 'raid-recovery',
        ]);

        $post = Post::create([
            'user_id' => $this->user->id,
            'title' => 'Enterprise RAID 5 Server Recovery Dubai',
            'slug' => 'enterprise-raid-5-server-recovery-dubai',
            'content' => '<p>Comprehensive RAID 5 rebuild and array recovery in Dubai.</p>',
            'status' => 'published',
            'published_at' => now()->subHour(),
            'is_indexable' => true,
        ]);
        $post->categories()->attach($category->id);

        $response = $this->get(route('post.show', $post->slug));
        $response->assertStatus(200);

        // Verify BlogPosting Schema
        $response->assertSee('"@type": "BlogPosting"', false);
        $response->assertSee('Recovery Data Cleanroom Engineering Lab', false);
        $response->assertSee('Recovery Data Dubai', false);
        $response->assertSee('+971564101040', false);

        // Verify BreadcrumbList with category included
        $response->assertSee('"@type": "BreadcrumbList"', false);
        $response->assertSee('"name": "RAID Recovery"', false);
    }

    public function test_frontend_post_renders_auto_extracted_faq_schema(): void
    {
        $post = Post::create([
            'user_id' => $this->user->id,
            'title' => 'SSD Firmware Failure Recovery Dubai',
            'slug' => 'ssd-firmware-failure-recovery-dubai',
            'content' => '
                <h2>Diagnostic Overview</h2>
                <p>When an SSD fails to initialize firmware, specialized hardware is required.</p>
                <h3>Can data be recovered from a dead SSD controller?</h3>
                <p>Yes, by accessing the Flash Translation Layer and NAND directly using hardware tools.</p>
                <h3>How long does emergency SSD recovery take?</h3>
                <p>Most emergency SSD recoveries are completed within 24 to 48 hours in our cleanroom lab.</p>
            ',
            'status' => 'published',
            'published_at' => now()->subHour(),
            'is_indexable' => true,
        ]);

        $response = $this->get(route('post.show', $post->slug));
        $response->assertStatus(200);

        // Auto-extracted FAQPage schema
        $response->assertSee('"@type": "FAQPage"', false);
        $response->assertSee('Can data be recovered from a dead SSD controller?', false);
        $response->assertSee('How long does emergency SSD recovery take?', false);
    }

    public function test_frontend_post_renders_custom_dashboard_schema_markup(): void
    {
        $customJson = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'SpecialService',
            'name' => 'Custom Forensic Chip-Off Recovery',
        ]);

        $post = Post::create([
            'user_id' => $this->user->id,
            'title' => 'Custom Forensic Lab Service',
            'slug' => 'custom-forensic-lab-service',
            'content' => '<p>Specialized forensic extraction.</p>',
            'status' => 'published',
            'published_at' => now()->subHour(),
            'is_indexable' => true,
            'schema_markup' => $customJson,
        ]);

        $response = $this->get(route('post.show', $post->slug));
        $response->assertStatus(200);

        // Check custom schema was rendered
        $response->assertSee('Custom Forensic Chip-Off Recovery', false);
        $response->assertSee('SpecialService', false);
    }

    public function test_frontend_page_renders_webpage_and_custom_schema(): void
    {
        $customSchema = '<script type="application/ld+json">{"@context":"https://schema.org","@type":"Organization","name":"Recovery Data HQ"}</script>';

        $page = Page::create([
            'user_id' => $this->user->id,
            'title' => 'Cleanroom Standards Dubai',
            'slug' => 'cleanroom-standards-dubai',
            'content' => '<p>ISO Class 100 specifications.</p>',
            'template' => 'default',
            'status' => 'published',
            'is_indexable' => true,
            'schema_markup' => $customSchema,
        ]);

        $response = $this->get(route('page.show', $page->slug));
        $response->assertStatus(200);

        // Default WebPage Schema
        $response->assertSee('"@type": "WebPage"', false);
        // Custom schema rendered
        $response->assertSee('Recovery Data HQ', false);
    }

    public function test_service_page_uses_canonical_www_host_in_schema(): void
    {
        $service = Service::create([
            'title' => 'Hard Drive Recovery Dubai',
            'slug' => 'hard-drive-recovery-dubai',
            'tagline' => 'ISO Class 100 Cleanroom Lab',
            'summary' => 'Comprehensive mechanical hard disk recovery.',
            'content' => '<p>Cleanroom platter transplant.</p>',
            'status' => 'published',
            'is_indexable' => true,
        ]);

        $response = $this->get(route('service.show', $service->slug));
        $response->assertStatus(200);
        $response->assertSee('"url": "https://www.recoverydata.ae"', false);
        $response->assertDontSee('"url": "https://recoverydata.ae"', false);
    }

    public function test_seo_generate_schemas_command_populates_all_records(): void
    {
        $service = Service::create([
            'title' => 'Flash Drive Recovery Dubai',
            'slug' => 'flash-drive-recovery-dubai',
            'summary' => 'Flash recovery in Dubai.',
            'content' => '<p>Flash chip-off recovery.</p>',
            'status' => 'published',
            'is_indexable' => true,
        ]);

        $post = Post::create([
            'user_id' => $this->user->id,
            'title' => 'Monolithic USB Recovery Dubai',
            'slug' => 'monolithic-usb-recovery-dubai',
            'content' => '<p>Direct pinout soldering on monoliths.</p>',
            'status' => 'published',
            'is_indexable' => true,
        ]);

        $page = Page::create([
            'user_id' => $this->user->id,
            'title' => 'About Cleanroom Lab',
            'slug' => 'about-cleanroom-lab',
            'content' => '<p>Certified facilities.</p>',
            'template' => 'default',
            'status' => 'published',
            'is_indexable' => true,
        ]);

        $this->artisan('seo:generate-schemas --force')
            ->assertSuccessful();

        $service->refresh();
        $this->assertNotNull($service->schema_markup);
        $this->assertStringContainsString('https://www.recoverydata.ae', $service->schema_markup);
        $this->assertStringContainsString('Service', $service->schema_markup);

        $post->refresh();
        $this->assertNotNull($post->schema_markup);
        $this->assertStringContainsString('BlogPosting', $post->schema_markup);
        $this->assertStringContainsString('FAQPage', $post->schema_markup);

        $page->refresh();
        $this->assertNotNull($page->schema_markup);
        $this->assertStringContainsString('WebPage', $page->schema_markup);
    }
}
