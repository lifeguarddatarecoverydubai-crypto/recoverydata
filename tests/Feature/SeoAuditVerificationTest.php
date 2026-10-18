<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoAuditVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_homepage_renders_valid_json_ld_without_blade_directive_corruption(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringNotContainsString('__contextArgs', $content);
        $this->assertStringNotContainsString('<?php', $content);

        // Extract JSON-LD script blocks and verify valid JSON with @context
        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $this->assertNotEmpty($matches[1]);

        foreach ($matches[1] as $jsonString) {
            $decoded = json_decode(trim($jsonString), true);
            $this->assertNotNull($decoded, 'JSON-LD failed to decode: '.json_last_error_msg());
            $this->assertArrayHasKey('@context', $decoded);
            $this->assertEquals('https://schema.org', $decoded['@context']);
        }
    }

    public function test_service_page_renders_valid_json_ld(): void
    {
        $service = Service::create([
            'title' => 'SSD Data Recovery',
            'slug' => 'ssd-recovery-test',
            'summary' => 'Lab SSD recovery in Dubai',
            'description' => '<p>Cleanroom SSD recovery</p>',
            'status' => 'published',
            'is_indexable' => true,
        ]);

        $response = $this->get('/service/ssd-recovery-test');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringNotContainsString('__contextArgs', $content);
        $this->assertStringNotContainsString('<?php', $content);

        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $this->assertNotEmpty($matches[1]);

        foreach ($matches[1] as $jsonString) {
            $decoded = json_decode(trim($jsonString), true);
            $this->assertNotNull($decoded);
            $this->assertArrayHasKey('@context', $decoded);
            $this->assertEquals('https://schema.org', $decoded['@context']);
        }
    }

    public function test_product_page_renders_valid_json_ld(): void
    {
        $product = Product::create([
            'title' => 'Lab Forensic Station',
            'slug' => 'lab-forensic-station',
            'summary' => 'Hardware diagnostic station',
            'description' => '<p>Lab recovery hardware</p>',
            'status' => 'published',
            'price' => 15000,
        ]);

        $response = $this->get('/product/lab-forensic-station');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringNotContainsString('__contextArgs', $content);

        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $this->assertNotEmpty($matches[1]);

        foreach ($matches[1] as $jsonString) {
            $decoded = json_decode(trim($jsonString), true);
            $this->assertNotNull($decoded);
            $this->assertArrayHasKey('@context', $decoded);
            $this->assertEquals('https://schema.org', $decoded['@context']);
        }
    }

    public function test_blog_post_renders_valid_json_ld(): void
    {
        $category = Category::create([
            'name' => 'Storage',
            'slug' => 'storage-lab',
        ]);

        $post = Post::create([
            'user_id' => $this->user->id,
            'title' => 'Cleanroom Diagnostics Guide',
            'slug' => 'cleanroom-diagnostics-guide',
            'content' => '<h2>Can formatted drives be recovered?</h2><p>Yes, formatted drives can usually be recovered provided sectors are not overwritten.</p><h2>How long does it take?</h2><p>Standard recovery takes 24 to 48 hours in our Dubai lab.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'is_indexable' => true,
        ]);
        $post->categories()->attach($category);

        $response = $this->get('/post/cleanroom-diagnostics-guide');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringNotContainsString('__contextArgs', $content);

        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $this->assertNotEmpty($matches[1]);

        foreach ($matches[1] as $jsonString) {
            $decoded = json_decode(trim($jsonString), true);
            $this->assertNotNull($decoded);
            $this->assertArrayHasKey('@context', $decoded);
            $this->assertEquals('https://schema.org', $decoded['@context']);
        }
    }

    public function test_cms_page_renders_valid_json_ld(): void
    {
        Page::create([
            'user_id' => $this->user->id,
            'title' => 'ISO Cleanroom Standards',
            'slug' => 'iso-cleanroom-standards',
            'content' => '<p>Class 100 cleanroom specifications</p>',
            'status' => 'published',
            'is_indexable' => true,
        ]);

        $response = $this->get('/iso-cleanroom-standards');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringNotContainsString('__contextArgs', $content);

        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $this->assertNotEmpty($matches[1]);

        foreach ($matches[1] as $jsonString) {
            $decoded = json_decode(trim($jsonString), true);
            $this->assertNotNull($decoded);
            $this->assertArrayHasKey('@context', $decoded);
            $this->assertEquals('https://schema.org', $decoded['@context']);
        }
    }

    public function test_legacy_wp_content_uploads_url_redirects_301_to_storage(): void
    {
        $response = $this->get('/wp-content/uploads/2026/09/sample-drive.jpg');
        $response->assertStatus(301);
        $response->assertRedirect('/storage/2026/09/sample-drive.jpg');
    }

    public function test_lucide_icon_script_is_pinned_to_specific_version(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringNotContainsString('unpkg.com/lucide@latest', $content);
        $this->assertStringContainsString('unpkg.com/lucide@0.469.0', $content);
    }

    public function test_products_index_canonical_handles_category_and_pagination(): void
    {
        Product::create([
            'title' => 'Software License',
            'slug' => 'software-license',
            'category' => 'enterprise-software',
            'status' => 'published',
        ]);

        $response = $this->get('/products?category=enterprise-software');
        $response->assertStatus(200);

        $expectedCanonical = route('product.index', ['category' => 'enterprise-software']);
        $response->assertSee('<link rel="canonical" href="'.$expectedCanonical.'">', false);
    }

    public function test_author_view_canonical_is_pagination_aware(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Post::create([
                'user_id' => $this->user->id,
                'title' => "Author Article {$i}",
                'slug' => "author-article-{$i}",
                'status' => 'published',
                'published_at' => now()->subHours($i),
                'is_indexable' => true,
            ]);
        }

        $posts = Post::where('user_id', $this->user->id)->paginate(5, ['*'], 'page', 2);
        $rendered = view('frontend.blog.author', [
            'author' => $this->user,
            'posts' => $posts,
        ])->render();

        $expectedCanonical = route('author.show', ['id' => $this->user->id, 'page' => 2]);
        $this->assertStringContainsString('<link rel="canonical" href="'.$expectedCanonical.'">', $rendered);

        // Also confirm route redirects author URL to blog
        $response = $this->get('/author/'.$this->user->id);
        $response->assertRedirect(route('blog.index'));
    }

    public function test_robots_txt_allows_tag_crawling_and_excludes_disallow_tag(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringNotContainsString('Disallow: /tag/', $content);
        $this->assertStringNotContainsString('Disallow: /tag/*', $content);
    }

    public function test_sitemap_excludes_non_indexable_services(): void
    {
        Service::create([
            'title' => 'Hidden Internal Service',
            'slug' => 'hidden-internal-service',
            'status' => 'published',
            'is_indexable' => false,
        ]);

        $response = $this->get('/sitemap-services.xml');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringNotContainsString('/service/hidden-internal-service', $content);
    }

    public function test_boost_browser_logger_is_never_rendered_in_testing_or_production(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringNotContainsString('browser-logger-active', $content);
        $this->assertStringNotContainsString('_boost/browser-logs', $content);
    }

    public function test_canonical_and_og_urls_resolve_to_production_host_when_configured(): void
    {
        config(['app.url' => 'https://www.recoverydata.ae']);

        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('https://www.recoverydata.ae', $content);
    }
}
