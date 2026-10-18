<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $category = $request->query('category');

        $query = Product::query()->ordered();

        if ($status && in_array($status, ['published', 'draft'])) {
            $query->where('status', $status);
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('badge', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(15)->withQueryString();

        $categories = Product::select('category')->distinct()->whereNotNull('category')->pluck('category');

        $counts = [
            'all' => Product::count(),
            'published' => Product::where('status', 'published')->count(),
            'draft' => Product::where('status', 'draft')->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'status', 'search', 'category', 'counts'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:150'],
            'icon' => ['nullable', 'string', 'max:100'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'highlights_input' => ['nullable', 'string'],
            'modules_input' => ['nullable', 'string'],
            'demo_url' => ['nullable', 'string', 'max:255'],
            'brochure_url' => ['nullable', 'string', 'max:255'],
            'featured_image' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
            'order' => ['nullable', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
            // SEO Meta fields
            'focus_keyword' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'string'],
            'is_indexable' => ['nullable', 'boolean'],
            'seo_score' => ['nullable', 'integer'],
            'readability_score' => ['nullable', 'integer'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Product::createUniqueSlug($validated['slug'])
            : Product::createUniqueSlug($validated['title']);

        $highlights = $this->parseLinesToArray($request->input('highlights_input'));
        $modules = $this->parseModulesInput($request->input('modules_input'));

        $product = Product::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'tagline' => $validated['tagline'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'category' => $validated['category'] ?? null,
            'icon' => $validated['icon'] ?: 'box',
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'] ?? '',
            'highlights' => $highlights,
            'modules' => $modules,
            'demo_url' => $validated['demo_url'] ?? null,
            'brochure_url' => $validated['brochure_url'] ?? null,
            'featured_image' => $validated['featured_image'] ?? null,
            'status' => $validated['status'],
            'order' => (int) ($validated['order'] ?? 0),
            'is_featured' => $request->has('is_featured'),
            // SEO
            'focus_keyword' => $validated['focus_keyword'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'canonical_url' => $validated['canonical_url'] ?? null,
            'og_title' => $validated['og_title'] ?? null,
            'og_description' => $validated['og_description'] ?? null,
            'og_image' => $validated['og_image'] ?? null,
            'is_indexable' => $request->has('is_indexable'),
            'seo_score' => (int) ($validated['seo_score'] ?? 0),
            'readability_score' => (int) ($validated['readability_score'] ?? 0),
        ]);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', "Product '{$product->title}' created successfully.");
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $highlightsString = is_array($product->highlights) ? implode("\n", $product->highlights) : '';
        $modulesString = '';
        if (is_array($product->modules)) {
            $lines = [];
            foreach ($product->modules as $mod) {
                if (is_array($mod) && isset($mod['name'])) {
                    $lines[] = $mod['name'].' | '.($mod['desc'] ?? '');
                } elseif (is_string($mod)) {
                    $lines[] = $mod;
                }
            }
            $modulesString = implode("\n", $lines);
        }

        return view('admin.products.edit', compact('product', 'highlightsString', 'modulesString'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,'.$product->id],
            'tagline' => ['nullable', 'string', 'max:255'],
            'badge' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:150'],
            'icon' => ['nullable', 'string', 'max:100'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'highlights_input' => ['nullable', 'string'],
            'modules_input' => ['nullable', 'string'],
            'demo_url' => ['nullable', 'string', 'max:255'],
            'brochure_url' => ['nullable', 'string', 'max:255'],
            'featured_image' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
            'order' => ['nullable', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
            // SEO Meta fields
            'focus_keyword' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'string'],
            'is_indexable' => ['nullable', 'boolean'],
            'seo_score' => ['nullable', 'integer'],
            'readability_score' => ['nullable', 'integer'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Product::createUniqueSlug($validated['slug'], $product->id)
            : Product::createUniqueSlug($validated['title'], $product->id);

        $highlights = $this->parseLinesToArray($request->input('highlights_input'));
        $modules = $this->parseModulesInput($request->input('modules_input'));

        $product->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'tagline' => $validated['tagline'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'category' => $validated['category'] ?? null,
            'icon' => $validated['icon'] ?: 'box',
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'] ?? '',
            'highlights' => $highlights,
            'modules' => $modules,
            'demo_url' => $validated['demo_url'] ?? null,
            'brochure_url' => $validated['brochure_url'] ?? null,
            'featured_image' => $validated['featured_image'] ?? null,
            'status' => $validated['status'],
            'order' => (int) ($validated['order'] ?? 0),
            'is_featured' => $request->has('is_featured'),
            // SEO
            'focus_keyword' => $validated['focus_keyword'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'canonical_url' => $validated['canonical_url'] ?? null,
            'og_title' => $validated['og_title'] ?? null,
            'og_description' => $validated['og_description'] ?? null,
            'og_image' => $validated['og_image'] ?? null,
            'is_indexable' => $request->has('is_indexable'),
            'seo_score' => (int) ($validated['seo_score'] ?? 0),
            'readability_score' => (int) ($validated['readability_score'] ?? 0),
        ]);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', "Product '{$product->title}' updated successfully.");
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $title = $product->title;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Product '{$title}' removed successfully.");
    }

    /**
     * Helper to parse newline-separated lines into array.
     */
    protected function parseLinesToArray(?string $input): array
    {
        if (! $input) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $input);
        $clean = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed !== '') {
                $clean[] = $trimmed;
            }
        }

        return $clean;
    }

    /**
     * Helper to parse modules format "ModuleName | Module Description" per line.
     */
    protected function parseModulesInput(?string $input): array
    {
        if (! $input) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $input);
        $modules = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                continue;
            }

            if (str_contains($trimmed, '|')) {
                [$name, $desc] = explode('|', $trimmed, 2);
                $modules[] = [
                    'name' => trim($name),
                    'desc' => trim($desc),
                ];
            } else {
                $modules[] = [
                    'name' => $trimmed,
                    'desc' => '',
                ];
            }
        }

        return $modules;
    }
}
