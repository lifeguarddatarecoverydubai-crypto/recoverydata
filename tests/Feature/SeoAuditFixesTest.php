<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoAuditFixesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        $category = Category::create([
            'name' => 'SSD & NVMe Recovery',
            'slug' => 'ssd-recovery',
        ]);

        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'NVMe SSD Recovery Mirdif',
            'slug' => 'nvme-ssd-recovery-mirdif',
            'meta_title' => 'NVMe SSD Recovery Mirdif - +971564101040 - Recovery Data Dubai',
            'meta_description' => 'Fast NVMe SSD recovery in Mirdif by Recovery Data Dubai.',
            'canonical_url' => 'https://www.recoverydata.ae/post/nvme-ssd-recovery-mirdif',
            'content' => '<p>Emergency cleanroom recovery for solid state drives.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'is_indexable' => true,
        ]);
        $post->categories()->attach($category->id);
    }

    public function test_blog_post_outputs_self_referencing_www_canonical_and_og_url(): void
    {
        $response = $this->get('/post/nvme-ssd-recovery-mirdif');

        $response->assertStatus(200);
        $expectedCanonical = 'https://www.recoverydata.ae/post/nvme-ssd-recovery-mirdif';
        $response->assertSee('<link rel="canonical" href="'.$expectedCanonical.'">', false);
        $response->assertSee('<meta property="og:url" content="'.$expectedCanonical.'">', false);
        $response->assertSee('<meta name="twitter:url" content="'.$expectedCanonical.'">', false);
    }

    public function test_blog_post_title_strips_phone_numbers_and_branding(): void
    {
        $response = $this->get('/post/nvme-ssd-recovery-mirdif');

        $response->assertStatus(200);
        $response->assertDontSee('<title>NVMe SSD Recovery Mirdif - +971564101040', false);
        $response->assertSee('<title>NVMe SSD Recovery Mirdif | Recovery Data Dubai</title>', false);
    }

    public function test_blog_index_pagination_has_self_canonical(): void
    {
        $user = User::first();
        for ($i = 1; $i <= 12; $i++) {
            Post::create([
                'user_id' => $user->id,
                'title' => "Post Number {$i}",
                'slug' => "post-number-{$i}",
                'status' => 'published',
                'published_at' => now()->subHours($i),
                'is_indexable' => true,
            ]);
        }

        $response = $this->get('/blog?page=2');
        $response->assertStatus(200);
        $response->assertSee('Page 2 | Recovery Data Dubai', false);
        $response->assertSee('<link rel="canonical" href="'.route('blog.index', ['page' => 2]).'">', false);
    }

    public function test_tag_view_has_noindex_directive(): void
    {
        $tag = Tag::create([
            'name' => 'Data Recovery',
            'slug' => 'data-recovery',
        ]);

        $response = $this->get('/tag/data-recovery');
        $response->assertStatus(200);
        $response->assertSee('<meta name="robots" content="noindex, follow">', false);
    }

    public function test_auth_views_have_noindex_directive(): void
    {
        $loginResponse = $this->get('/login');
        $loginResponse->assertStatus(200);
        $loginResponse->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_public_prefix_is_redirected_to_clean_url(): void
    {
        $response = $this->get('/public/blog');
        $response->assertStatus(301);
        $response->assertRedirect('/blog');
    }

    public function test_legacy_post_slug_redirects_to_canonical_post_route(): void
    {
        $response = $this->get('/nvme-ssd-recovery-mirdif');
        $response->assertStatus(301);
        $response->assertRedirect(route('post.show', 'nvme-ssd-recovery-mirdif'));
    }
}
