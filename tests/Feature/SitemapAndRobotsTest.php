<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapAndRobotsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        // Seed sample service
        Service::create([
            'title' => 'Hard Drive Data Recovery',
            'slug' => 'hard-drive-recovery',
            'status' => 'published',
            'order' => 1,
        ]);

        // Seed sample category
        $category = Category::create([
            'name' => 'Storage Technology',
            'slug' => 'storage-technology',
        ]);

        // Seed sample post
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Recovering Formatted NVMe SSD',
            'slug' => 'recovering-formatted-nvme-ssd',
            'status' => 'published',
            'published_at' => now(),
            'is_indexable' => true,
        ]);
        $post->categories()->attach($category);

        // Seed sample page
        Page::create([
            'user_id' => $user->id,
            'title' => 'Laboratory Certifications',
            'slug' => 'laboratory-certifications',
            'status' => 'published',
            'is_indexable' => true,
        ]);
    }

    public function test_master_sitemap_returns_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $content = $response->getContent();

        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"', $content);
        $this->assertStringContainsString('xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"', $content);
        $this->assertStringContainsString('/service/hard-drive-recovery', $content);
        $this->assertStringContainsString('/post/recovering-formatted-nvme-ssd', $content);
        $this->assertStringContainsString('/category/storage-technology', $content);
        $this->assertStringContainsString('/laboratory-certifications', $content);

        $parsed = simplexml_load_string($content);
        $this->assertNotFalse($parsed, 'Sitemap XML must be valid XML and parse without namespace errors.');
    }

    public function test_sitemap_index_returns_valid_xml_index(): void
    {
        $response = $this->get('/sitemap-index.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $content = $response->getContent();

        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', $content);
        $this->assertStringContainsString('/sitemap-posts.xml', $content);
        $this->assertStringContainsString('/sitemap-pages.xml', $content);
        $this->assertStringContainsString('/sitemap-categories.xml', $content);
        $this->assertStringContainsString('/sitemap-services.xml', $content);
    }

    public function test_posts_sitemap_returns_only_published_and_indexable_posts(): void
    {
        // Add draft and noindex post
        $user = User::first();
        Post::create([
            'user_id' => $user->id,
            'title' => 'Draft Post Should Not Be Listed',
            'slug' => 'draft-post-not-listed',
            'status' => 'draft',
            'is_indexable' => true,
        ]);
        Post::create([
            'user_id' => $user->id,
            'title' => 'NoIndex Post Should Not Be Listed',
            'slug' => 'noindex-post-not-listed',
            'status' => 'published',
            'published_at' => now(),
            'is_indexable' => false,
        ]);

        foreach (['/sitemap-posts.xml', '/post-sitemap.xml'] as $url) {
            $response = $this->get($url);

            $response->assertStatus(200);
            $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
            $content = $response->getContent();

            $this->assertStringContainsString('/post/recovering-formatted-nvme-ssd', $content);
            $this->assertStringNotContainsString('draft-post-not-listed', $content);
            $this->assertStringNotContainsString('noindex-post-not-listed', $content);
        }
    }

    public function test_pages_sitemap_returns_core_routes_and_cms_pages(): void
    {
        foreach (['/sitemap-pages.xml', '/page-sitemap.xml'] as $url) {
            $response = $this->get($url);

            $response->assertStatus(200);
            $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
            $content = $response->getContent();

            $canonical = rtrim(config('app.canonical_domain'), '/');

            $this->assertStringContainsString('/laboratory-certifications', $content);
            $this->assertStringContainsString($canonical.'/', $content);
            $this->assertStringContainsString($canonical.'/about', $content);
            $this->assertStringContainsString($canonical.'/contact', $content);
            $this->assertStringContainsString($canonical.'/odoo', $content);
        }
    }

    public function test_categories_sitemap_returns_active_categories(): void
    {
        // Add empty category
        Category::create([
            'name' => 'Empty Category With No Posts',
            'slug' => 'empty-category-no-posts',
        ]);

        foreach (['/sitemap-categories.xml', '/category-sitemap.xml'] as $url) {
            $response = $this->get($url);

            $response->assertStatus(200);
            $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
            $content = $response->getContent();

            $this->assertStringContainsString('/category/storage-technology', $content);
            $this->assertStringNotContainsString('/category/empty-category-no-posts', $content);
        }
    }

    public function test_services_sitemap_returns_all_services(): void
    {
        foreach (['/sitemap-services.xml', '/service-sitemap.xml'] as $url) {
            $response = $this->get($url);

            $response->assertStatus(200);
            $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
            $content = $response->getContent();

            $this->assertStringContainsString('/service/hard-drive-recovery', $content);
        }
    }

    public function test_robots_txt_returns_proper_directives_and_sitemap_urls(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $content = $response->getContent();

        $this->assertStringContainsString('User-agent: *', $content);
        $this->assertStringContainsString('Disallow: /admin', $content);
        $this->assertStringContainsString('Disallow: /login', $content);
        $this->assertStringNotContainsString('Disallow: /tag/', $content);
        $this->assertStringContainsString('Disallow: /public/', $content);
        $this->assertStringContainsString('Allow: /images/', $content);
        $this->assertStringContainsString('Sitemap:', $content);
        $this->assertStringContainsString('/sitemap-index.xml', $content);
        $this->assertStringContainsString('/sitemap.xml', $content);
        $this->assertStringContainsString('/sitemap-posts.xml', $content);
        $this->assertStringContainsString('/sitemap-pages.xml', $content);
        $this->assertStringContainsString('/sitemap-categories.xml', $content);
    }
}
