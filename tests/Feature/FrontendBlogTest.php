<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendBlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully_and_shows_published_posts(): void
    {
        $user = User::factory()->create();

        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Sample Public Article',
            'slug' => 'sample-public-article',
            'content' => '<p>Article content</p>',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Sample Public Article');
    }

    public function test_single_post_renders_with_seo_meta_tags(): void
    {
        $user = User::factory()->create();

        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Article with SEO',
            'slug' => 'article-with-seo',
            'content' => '<p>Rich article content</p>',
            'status' => 'published',
            'published_at' => now()->subHour(),
            'meta_title' => 'Targeted SEO Title for Crawlers',
            'meta_description' => 'Targeted meta description for Google index.',
            'meta_keywords' => 'keyword1, keyword2',
            'is_indexable' => true,
        ]);

        $response = $this->get('/post/article-with-seo');
        $response->assertStatus(200);
        $response->assertSee('<title>Targeted SEO Title for Crawlers', false);
        $response->assertSee('content="Targeted meta description for Google index."', false);
        $response->assertDontSee('name="keywords"', false);
    }

    public function test_static_page_renders_with_seo_meta(): void
    {
        $user = User::factory()->create();

        $page = Page::create([
            'user_id' => $user->id,
            'title' => 'Company Mission',
            'slug' => 'company-mission',
            'content' => '<p>Our company values and vision.</p>',
            'status' => 'published',
            'meta_title' => 'Company Mission Statement',
            'meta_description' => 'Learn about our core principles.',
        ]);

        $response = $this->get('/page/company-mission');
        $response->assertStatus(200);
        $response->assertSee('Company Mission Statement');
        $response->assertSee('Our company values and vision.', false);
    }

    public function test_draft_post_returns_404_for_guest(): void
    {
        $user = User::factory()->create();

        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Draft Post Title',
            'slug' => 'draft-post-title',
            'content' => '<p>Secret content</p>',
            'status' => 'draft',
        ]);

        $response = $this->get('/post/draft-post-title');
        $response->assertStatus(404);
    }

    public function test_author_name_is_not_displayed_on_blog_pages(): void
    {
        $user = User::factory()->create([
            'name' => 'Shafia Begum',
        ]);

        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Comprehensive Data Recovery Strategy',
            'slug' => 'comprehensive-data-recovery-strategy',
            'content' => '<p>Data recovery guide details.</p>',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        // Home page
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Comprehensive Data Recovery Strategy');
        $homeResponse->assertDontSee('Shafia Begum');

        // Blog listing
        $blogResponse = $this->get('/blog');
        $blogResponse->assertStatus(200);
        $blogResponse->assertSee('Comprehensive Data Recovery Strategy');
        $blogResponse->assertDontSee('Shafia Begum');

        // Single post
        $showResponse = $this->get('/post/comprehensive-data-recovery-strategy');
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Comprehensive Data Recovery Strategy');
        $showResponse->assertDontSee('Shafia Begum');
        $showResponse->assertDontSee('Written by');

        // Author URL redirects to blog
        $authorResponse = $this->get('/author/'.$user->id);
        $authorResponse->assertRedirect(route('blog.index'));
    }

    public function test_single_post_renders_with_service_right_sidebar_including_form_hotline_and_services(): void
    {
        $user = User::factory()->create();

        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'NVMe SSD Controller Failure Repair',
            'slug' => 'nvme-ssd-controller-failure-repair',
            'content' => '<p>Deep technical article detailing silicon recovery.</p>',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        $service = Service::create([
            'title' => 'Hard Drive Data Recovery',
            'slug' => 'hard-drive-data-recovery-dubai',
            'summary' => 'ISO Class 100 mechanical platter lab recovery.',
            'status' => 'published',
            'order' => 1,
        ]);

        $response = $this->get('/post/nvme-ssd-controller-failure-repair');
        $response->assertStatus(200);

        // Right sidebar evaluation form
        $response->assertSee('Free Lab Evaluation');
        $response->assertSee('Request Evaluation');
        $response->assertSee('Submit your storage device details');
        $response->assertSee('name="device_model"', false);
        $response->assertSee('name="pickup_requested"', false);
        $response->assertSee('Submit Free Evaluation Request');

        // Right sidebar 24/7 hotline and WhatsApp card
        $response->assertSee('24/7 Emergency Lab');
        $response->assertSee('+971 56 410 1040');
        $response->assertSee('Chat on WhatsApp');
        $response->assertSee('Al Barsha 1, Dubai');

        // Right sidebar lab guarantees
        $response->assertSee('ISO Class 100 Cleanroom');
        $response->assertSee('No Data – No Charge');

        // Right sidebar service navigation
        $response->assertSee('Our Recovery Services');
        $response->assertSee('Hard Drive Data Recovery');
        $response->assertSee(route('service.show', 'hard-drive-data-recovery-dubai'));
    }

    public function test_blog_sidebar_evaluation_form_submits_successfully(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Khalid Al Nuaimi',
            'phone' => '+971 50 999 8888',
            'email' => 'khalid@example.ae',
            'device_model' => 'WD My Passport 4TB',
            'pickup_requested' => 'yes',
            'message' => 'Hard drive dropped from table, makes clicking sound.',
            'subject' => 'Evaluation Request from Blog: NVMe SSD Controller Failure Repair',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inquiries', [
            'name' => 'Khalid Al Nuaimi',
            'email' => 'khalid@example.ae',
            'phone' => '+971 50 999 8888',
            'subject' => 'Evaluation Request from Blog: NVMe SSD Controller Failure Repair',
            'type' => 'evaluation',
        ]);
    }
}
