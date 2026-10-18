<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_editor_can_view_admin_dashboard(): void
    {
        $editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        $response = $this->actingAs($editor)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Admin Panel');
    }

    public function test_editor_can_create_post_with_seo_meta(): void
    {
        $editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        $category = Category::create([
            'name' => 'Technology',
            'slug' => 'technology',
        ]);

        $response = $this->actingAs($editor)->post(route('admin.posts.store'), [
            'title' => 'My First Great Post',
            'slug' => 'my-first-great-post',
            'excerpt' => 'A wonderful excerpt of the post.',
            'content' => '<p>Article body formatted nicely.</p>',
            'status' => 'published',
            'categories' => [$category->id],
            'tags_input' => 'laravel, testing',
            'meta_title' => 'Custom SEO Title for Google',
            'meta_description' => 'Custom meta description for search engine ranking.',
            'meta_keywords' => 'laravel, testing, cms',
            'is_indexable' => 1,
        ]);

        $post = Post::where('slug', 'my-first-great-post')->first();
        $this->assertNotNull($post);
        $this->assertEquals('Custom SEO Title for Google', $post->meta_title);
        $this->assertEquals('Custom meta description for search engine ranking.', $post->meta_description);
        $this->assertTrue($post->categories->contains($category));
        $this->assertCount(2, $post->tags);

        $response->assertRedirect(route('admin.posts.edit', $post));
    }

    public function test_admin_can_create_new_user(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Contributor',
            'email' => 'contributor@example.com',
            'password' => 'Password123!',
            'role' => 'editor',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'contributor@example.com',
            'role' => 'editor',
        ]);
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        $response = $this->actingAs($editor)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_deleting_user_does_not_delete_posts_and_reassigns_to_admin(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $editor = User::factory()->create([
            'role' => 'editor',
            'name' => 'Editor To Delete',
            'email' => 'editor.delete@example.com',
            'is_active' => true,
        ]);

        $post = Post::create([
            'user_id' => $editor->id,
            'title' => 'Important Article',
            'slug' => 'important-article',
            'content' => 'Article content that must never be deleted',
            'status' => 'published',
        ]);

        $page = Page::create([
            'user_id' => $editor->id,
            'title' => 'Important Page',
            'slug' => 'important-page',
            'content' => 'Page content',
            'status' => 'published',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $editor));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $editor->id]);

        // Assert post still exists and was safely preserved and reassigned to admin
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'user_id' => $admin->id,
            'title' => 'Important Article',
        ]);

        // Assert page still exists and was safely preserved and reassigned to admin
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'user_id' => $admin->id,
            'title' => 'Important Page',
        ]);
    }

    public function test_editor_sees_sitemap_generation_card_on_dashboard(): void
    {
        $editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        $response = $this->actingAs($editor)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('XML Sitemaps');
        $response->assertSee('Generate XML Sitemaps');
        $response->assertSee(route('admin.sitemap.generate'));
    }

    public function test_editor_can_trigger_sitemap_generation_from_dashboard(): void
    {
        $editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        $response = $this->actingAs($editor)->post(route('admin.sitemap.generate'));
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');
    }

    public function test_editor_can_trigger_cache_clearing_from_dashboard(): void
    {
        $editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        $response = $this->actingAs($editor)->post(route('admin.cache.clear'));
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');
    }

    public function test_admin_can_create_new_user_from_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Editor User',
            'email' => 'neweditor@example.com',
            'password' => 'SecurePass123!',
            'role' => 'editor',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'neweditor@example.com',
            'role' => 'editor',
        ]);
    }

    public function test_editor_cannot_create_new_user(): void
    {
        $editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);

        $response = $this->actingAs($editor)->post(route('admin.users.store'), [
            'name' => 'Intruder User',
            'email' => 'intruder@example.com',
            'password' => 'SecurePass123!',
            'role' => 'admin',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', [
            'email' => 'intruder@example.com',
        ]);
    }
}
