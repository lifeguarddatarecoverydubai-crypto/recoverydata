<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display a listing of navigation menu items.
     */
    public function index(): View
    {
        $menuItems = MenuItem::with(['children' => function ($query) {
            $query->orderBy('order');
        }])
            ->header()
            ->root()
            ->ordered()
            ->get();

        $parentCandidates = MenuItem::header()
            ->root()
            ->orderBy('title')
            ->get();

        $publishedPages = Page::where('status', 'published')
            ->orderBy('title')
            ->get(['id', 'title', 'slug']);

        return view('admin.menus.index', compact('menuItems', 'parentCandidates', 'publishedPages'));
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'route_name' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:standard,products_dropdown,services_dropdown,odoo_badge,dropdown'],
            'target' => ['required', 'string', 'in:_self,_blank'],
            'parent_id' => ['nullable', 'exists:menu_items,id'],
            'badge' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $order = $validated['order'] ?? null;
        if ($order === null) {
            $maxOrder = MenuItem::where('parent_id', $validated['parent_id'] ?? null)->max('order') ?? 0;
            $order = $maxOrder + 1;
        }

        $url = $validated['url'] ?? '';
        if (empty($url)) {
            $url = match ($validated['type']) {
                'products_dropdown' => '/products',
                'services_dropdown' => '/services',
                'odoo_badge' => '/odoo',
                'dropdown' => '#',
                default => '/',
            };
        }

        MenuItem::create([
            'title' => $validated['title'],
            'url' => $url,
            'route_name' => $validated['route_name'] ?? null,
            'type' => $validated['type'],
            'target' => $validated['target'],
            'parent_id' => $validated['parent_id'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'order' => $order,
            'is_active' => $request->has('is_active') ? (bool) $request->input('is_active') : true,
            'location' => 'header',
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', "Menu item '{$validated['title']}' created successfully.");
    }

    /**
     * Update the specified menu item.
     */
    public function update(Request $request, MenuItem $menu): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'route_name' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:standard,products_dropdown,services_dropdown,odoo_badge,dropdown'],
            'target' => ['required', 'string', 'in:_self,_blank'],
            'parent_id' => ['nullable', 'exists:menu_items,id', 'not_in:'.$menu->id],
            'badge' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $url = $validated['url'] ?? $menu->url;
        if (empty($url)) {
            $url = match ($validated['type']) {
                'products_dropdown' => '/products',
                'services_dropdown' => '/services',
                'odoo_badge' => '/odoo',
                'dropdown' => '#',
                default => '/',
            };
        }

        $menu->update([
            'title' => $validated['title'],
            'url' => $url,
            'route_name' => $validated['route_name'] ?? null,
            'type' => $validated['type'],
            'target' => $validated['target'],
            'parent_id' => $validated['parent_id'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'order' => $validated['order'] ?? $menu->order,
            'is_active' => $request->has('is_active') ? (bool) $request->input('is_active') : false,
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', "Menu item '{$menu->title}' updated successfully.");
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy(MenuItem $menu): RedirectResponse
    {
        $title = $menu->title;
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', "Menu item '{$title}' removed successfully.");
    }

    /**
     * Toggle the active status of a menu item.
     */
    public function toggleActive(MenuItem $menu): RedirectResponse
    {
        $menu->update(['is_active' => ! $menu->is_active]);

        $status = $menu->is_active ? 'enabled' : 'disabled';

        return redirect()->route('admin.menus.index')
            ->with('success', "Menu item '{$menu->title}' is now {$status}.");
    }

    /**
     * Move menu item up in order.
     */
    public function moveUp(MenuItem $menu): RedirectResponse
    {
        $previousItem = MenuItem::where('parent_id', $menu->parent_id)
            ->where('location', $menu->location)
            ->where('order', '<', $menu->order)
            ->orderByDesc('order')
            ->first();

        if ($previousItem) {
            $currentOrder = $menu->order;
            $menu->update(['order' => $previousItem->order]);
            $previousItem->update(['order' => $currentOrder]);
        }

        return redirect()->route('admin.menus.index')
            ->with('success', "Reordered '{$menu->title}'.");
    }

    /**
     * Move menu item down in order.
     */
    public function moveDown(MenuItem $menu): RedirectResponse
    {
        $nextItem = MenuItem::where('parent_id', $menu->parent_id)
            ->where('location', $menu->location)
            ->where('order', '>', $menu->order)
            ->orderBy('order')
            ->first();

        if ($nextItem) {
            $currentOrder = $menu->order;
            $menu->update(['order' => $nextItem->order]);
            $nextItem->update(['order' => $currentOrder]);
        }

        return redirect()->route('admin.menus.index')
            ->with('success', "Reordered '{$menu->title}'.");
    }

    /**
     * Reset menu items back to default system navigation.
     */
    public function resetDefaults(): RedirectResponse
    {
        MenuItem::seedDefaults();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Navigation menu reset to original default items.');
    }
}
