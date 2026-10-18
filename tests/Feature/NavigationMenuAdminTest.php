<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationMenuAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $editor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->editor = User::factory()->create([
            'role' => 'editor',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_menu_management(): void
    {
        $response = $this->get(route('admin.menus.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_editor_can_view_menu_management(): void
    {
        $response = $this->actingAs($this->editor)->get(route('admin.menus.index'));

        $response->assertStatus(200);
        $response->assertSee('Navigation Menu');
        $response->assertSee('Current Navigation Structure');
    }

    public function test_dashboard_displays_navigation_menu_link_and_stats(): void
    {
        $response = $this->actingAs($this->editor)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Header Menu');
        $response->assertSee('Navigation');
    }

    public function test_editor_can_create_menu_item(): void
    {
        $response = $this->actingAs($this->editor)->post(route('admin.menus.store'), [
            'title' => 'Careers Hub',
            'url' => '/page/careers',
            'type' => 'standard',
            'target' => '_self',
            'badge' => 'Hiring',
            'order' => 10,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.menus.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('menu_items', [
            'title' => 'Careers Hub',
            'url' => '/page/careers',
            'badge' => 'Hiring',
            'order' => 10,
        ]);
    }

    public function test_editor_can_update_menu_item(): void
    {
        $item = MenuItem::create([
            'title' => 'Old Title',
            'url' => '/old-url',
            'type' => 'standard',
            'target' => '_self',
            'order' => 1,
            'is_active' => true,
            'location' => 'header',
        ]);

        $response = $this->actingAs($this->editor)->put(route('admin.menus.update', $item), [
            'title' => 'Updated Services Hub',
            'url' => '/services',
            'type' => 'services_dropdown',
            'target' => '_self',
            'order' => 2,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.menus.index'));
        $item->refresh();

        $this->assertEquals('Updated Services Hub', $item->title);
        $this->assertEquals('services_dropdown', $item->type);
        $this->assertEquals(2, $item->order);
    }

    public function test_editor_can_toggle_menu_item_active_status(): void
    {
        $item = MenuItem::create([
            'title' => 'Test Link',
            'url' => '/test',
            'type' => 'standard',
            'target' => '_self',
            'order' => 1,
            'is_active' => true,
            'location' => 'header',
        ]);

        $response = $this->actingAs($this->editor)->patch(route('admin.menus.toggle-active', $item));
        $response->assertRedirect(route('admin.menus.index'));

        $this->assertFalse($item->fresh()->is_active);

        // Toggle back to active
        $this->actingAs($this->editor)->patch(route('admin.menus.toggle-active', $item));
        $this->assertTrue($item->fresh()->is_active);
    }

    public function test_editor_can_move_menu_item_up_and_down(): void
    {
        MenuItem::query()->delete();

        $first = MenuItem::create([
            'title' => 'First',
            'url' => '/first',
            'type' => 'standard',
            'order' => 1,
            'is_active' => true,
            'location' => 'header',
        ]);

        $second = MenuItem::create([
            'title' => 'Second',
            'url' => '/second',
            'type' => 'standard',
            'order' => 2,
            'is_active' => true,
            'location' => 'header',
        ]);

        // Move second up
        $this->actingAs($this->editor)->post(route('admin.menus.move-up', $second));
        $this->assertEquals(1, $second->fresh()->order);
        $this->assertEquals(2, $first->fresh()->order);

        // Move second down
        $this->actingAs($this->editor)->post(route('admin.menus.move-down', $second));
        $this->assertEquals(2, $second->fresh()->order);
        $this->assertEquals(1, $first->fresh()->order);
    }

    public function test_editor_can_delete_menu_item(): void
    {
        $item = MenuItem::create([
            'title' => 'To Delete',
            'url' => '/delete-me',
            'type' => 'standard',
            'order' => 1,
            'is_active' => true,
            'location' => 'header',
        ]);

        $response = $this->actingAs($this->editor)->delete(route('admin.menus.destroy', $item));
        $response->assertRedirect(route('admin.menus.index'));

        $this->assertDatabaseMissing('menu_items', [
            'id' => $item->id,
        ]);
    }

    public function test_editor_can_reset_menu_to_defaults(): void
    {
        MenuItem::query()->delete();
        $this->assertEquals(0, MenuItem::count());

        $response = $this->actingAs($this->editor)->post(route('admin.menus.reset-defaults'));
        $response->assertRedirect(route('admin.menus.index'));

        $this->assertEquals(7, MenuItem::count());
        $this->assertDatabaseHas('menu_items', ['title' => 'Home']);
        $this->assertDatabaseHas('menu_items', ['title' => 'Odoo Partner']);
        $this->assertDatabaseHas('menu_items', ['title' => 'Contact us']);
    }

    public function test_frontend_renders_configured_menu_items(): void
    {
        MenuItem::seedDefaults();

        // Customise one of the items
        MenuItem::where('title', 'Blogs')->update([
            'title' => 'Industry Insights',
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Industry Insights');
        $response->assertSee('Odoo Partner');
        $response->assertSee('Products');
        $response->assertSee('Services');
    }
}
