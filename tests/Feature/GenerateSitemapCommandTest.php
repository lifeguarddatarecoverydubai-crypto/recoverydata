<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GenerateSitemapCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        Service::create([
            'title' => 'SSD Recovery Dubai',
            'slug' => 'ssd-recovery-dubai',
            'status' => 'published',
            'order' => 1,
            'image_url' => 'https://recoverydata.ae/images/ssd.jpg',
        ]);

        $category = Category::create([
            'name' => 'Data Recovery',
            'slug' => 'data-recovery',
        ]);

        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'RAID Array Recovery Guide',
            'slug' => 'raid-array-recovery-guide',
            'status' => 'published',
            'published_at' => now(),
            'is_indexable' => true,
            'featured_image_url' => 'https://recoverydata.ae/images/raid.jpg',
        ]);
        $post->categories()->attach($category);

        Page::create([
            'user_id' => $user->id,
            'title' => 'ISO Cleanroom Standards',
            'slug' => 'iso-cleanroom-standards',
            'status' => 'published',
            'is_indexable' => true,
        ]);
    }

    public function test_sitemap_generate_command_warms_cache_without_writing_static_files(): void
    {
        $this->artisan('sitemap:generate', ['--url' => 'https://www.recoverydata.ae'])
            ->expectsOutputToContain('Setting canonical URL base to: https://www.recoverydata.ae')
            ->expectsOutputToContain('Validated & cached: sitemap.xml')
            ->assertSuccessful();

        // Must NOT write files into public/ - a static file there would permanently
        // shadow the dynamic /sitemap.xml route under Apache's "file exists" rewrite
        // condition, freezing the sitemap and hiding all future published content.
        $files = [
            'sitemap.xml',
            'sitemap-index.xml',
            'sitemap-posts.xml',
            'sitemap-pages.xml',
            'sitemap-services.xml',
            'sitemap-categories.xml',
        ];

        foreach ($files as $file) {
            $this->assertFileDoesNotExist(public_path($file), "public/{$file} must not exist - it would shadow the dynamic sitemap route.");
        }

        $this->assertNotNull(Cache::get('sitemap_last_generated_at'));

        // The live dynamic route must serve the freshly warmed, valid XML.
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');

        $content = $response->getContent();
        $parsed = simplexml_load_string($content);
        $this->assertNotFalse($parsed, 'sitemap.xml must be valid XML.');
        $this->assertStringContainsString('https://www.recoverydata.ae', $content);
        $this->assertStringContainsString('xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"', $content);
        $this->assertStringContainsString('<image:image>', $content);
    }
}
