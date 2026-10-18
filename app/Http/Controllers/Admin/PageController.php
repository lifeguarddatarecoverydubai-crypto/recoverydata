<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a listing of pages.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Page::with('user')->latest();

        if ($status && in_array($status, ['published', 'draft'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $pages = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Page::count(),
            'published' => Page::where('status', 'published')->count(),
            'draft' => Page::where('status', 'draft')->count(),
        ];

        return view('admin.pages.index', compact('pages', 'status', 'search', 'counts'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create(): View
    {
        $templates = [
            'default' => 'Default Template',
            'full-width' => 'Full Width',
            'contact' => 'Contact Page',
        ];

        return view('admin.pages.create', compact('templates'));
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string'],
            'template' => ['required', 'string', 'in:default,full-width,contact'],
            'status' => ['required', 'in:draft,published'],
            // SEO
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'string'],
            'is_indexable' => ['nullable', 'boolean'],
            'focus_keyword' => ['nullable', 'string', 'max:255'],
            'seo_score' => ['nullable', 'integer'],
            'readability_score' => ['nullable', 'integer'],
            'schema_markup' => ['nullable', 'string'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Page::createUniqueSlug($validated['slug'])
            : Page::createUniqueSlug($validated['title']);

        $page = Page::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'] ?? '',
            'featured_image' => $validated['featured_image'] ?? null,
            'template' => $validated['template'],
            'status' => $validated['status'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'canonical_url' => $validated['canonical_url'] ?? null,
            'og_title' => $validated['og_title'] ?? null,
            'og_description' => $validated['og_description'] ?? null,
            'og_image' => $validated['og_image'] ?? null,
            'is_indexable' => $request->has('is_indexable'),
            'focus_keyword' => $validated['focus_keyword'] ?? null,
            'seo_score' => (int) ($validated['seo_score'] ?? 0),
            'readability_score' => (int) ($validated['readability_score'] ?? 0),
            'schema_markup' => $validated['schema_markup'] ?? null,
        ]);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', "Page '{$page->title}' created successfully.");
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page): View
    {
        $templates = [
            'default' => 'Default Template',
            'full-width' => 'Full Width',
            'contact' => 'Contact Page',
        ];

        return view('admin.pages.edit', compact('page', 'templates'));
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug,'.$page->id],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string'],
            'template' => ['required', 'string', 'in:default,full-width,contact'],
            'status' => ['required', 'in:draft,published'],
            // SEO
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'string'],
            'is_indexable' => ['nullable', 'boolean'],
            'focus_keyword' => ['nullable', 'string', 'max:255'],
            'seo_score' => ['nullable', 'integer'],
            'readability_score' => ['nullable', 'integer'],
            'schema_markup' => ['nullable', 'string'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Page::createUniqueSlug($validated['slug'], $page->id)
            : Page::createUniqueSlug($validated['title'], $page->id);

        $page->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'] ?? '',
            'featured_image' => $validated['featured_image'] ?? null,
            'template' => $validated['template'],
            'status' => $validated['status'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'canonical_url' => $validated['canonical_url'] ?? null,
            'og_title' => $validated['og_title'] ?? null,
            'og_description' => $validated['og_description'] ?? null,
            'og_image' => $validated['og_image'] ?? null,
            'is_indexable' => $request->has('is_indexable'),
            'focus_keyword' => $validated['focus_keyword'] ?? null,
            'seo_score' => (int) ($validated['seo_score'] ?? 0),
            'readability_score' => (int) ($validated['readability_score'] ?? 0),
            'schema_markup' => $validated['schema_markup'] ?? null,
        ]);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', "Page '{$page->title}' updated successfully.");
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page): RedirectResponse
    {
        $title = $page->title;
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', "Page '{$title}' deleted successfully.");
    }
}
