<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Service::query()->ordered();

        if ($status && in_array($status, ['published', 'draft'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('tagline', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        $services = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Service::count(),
            'published' => Service::where('status', 'published')->count(),
            'draft' => Service::where('status', 'draft')->count(),
        ];

        return view('admin.services.index', compact('services', 'status', 'search', 'counts'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create(): View
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:services,slug'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'hero_badge' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'features_input' => ['nullable', 'string'],
            'benefits_input' => ['nullable', 'string'],
            'benchmark_standard' => ['nullable', 'string', 'max:255'],
            'benchmark_evaluation' => ['nullable', 'string', 'max:255'],
            'benchmark_guarantee' => ['nullable', 'string', 'max:255'],
            'benchmark_turnaround' => ['nullable', 'string', 'max:255'],
            'benchmark_courier' => ['nullable', 'string', 'max:255'],
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
            'schema_markup' => ['nullable', 'string'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Service::createUniqueSlug($validated['slug'])
            : Service::createUniqueSlug($validated['title']);

        // Parse features and benefits into arrays (one per line)
        $features = $this->parseLinesToArray($request->input('features_input'));
        $benefits = $this->parseLinesToArray($request->input('benefits_input'));

        $benchmarks = [
            'standard' => $request->input('benchmark_standard') ?: 'ISO Class 100 / FED STD 209E',
            'evaluation' => $request->input('benchmark_evaluation') ?: '100% Free Diagnostic & Quote',
            'guarantee' => $request->input('benchmark_guarantee') ?: 'No Data – No Charge Guarantee',
            'turnaround' => $request->input('benchmark_turnaround') ?: 'Same-Day Emergency or 24-48h Standard',
            'courier' => $request->input('benchmark_courier') ?: 'Free Courier Across All 7 Emirates',
        ];

        $service = Service::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'tagline' => $validated['tagline'] ?? null,
            'hero_badge' => $validated['hero_badge'] ?? null,
            'icon' => $validated['icon'] ?: 'sparkles',
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'] ?? '',
            'features' => $features,
            'benefits' => $benefits,
            'benchmarks' => $benchmarks,
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
            'schema_markup' => $validated['schema_markup'] ?? null,
        ]);

        return redirect()->route('admin.services.edit', $service)
            ->with('success', "Service '{$service->title}' created successfully.");
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service): View
    {
        $featuresString = is_array($service->features) ? implode("\n", $service->features) : '';
        $benefitsString = is_array($service->benefits) ? implode("\n", $service->benefits) : '';
        $benchmarks = $service->effective_benchmarks;

        return view('admin.services.edit', compact('service', 'featuresString', 'benefitsString', 'benchmarks'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:services,slug,'.$service->id],
            'tagline' => ['nullable', 'string', 'max:255'],
            'hero_badge' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'features_input' => ['nullable', 'string'],
            'benefits_input' => ['nullable', 'string'],
            'benchmark_standard' => ['nullable', 'string', 'max:255'],
            'benchmark_evaluation' => ['nullable', 'string', 'max:255'],
            'benchmark_guarantee' => ['nullable', 'string', 'max:255'],
            'benchmark_turnaround' => ['nullable', 'string', 'max:255'],
            'benchmark_courier' => ['nullable', 'string', 'max:255'],
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
            'schema_markup' => ['nullable', 'string'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Service::createUniqueSlug($validated['slug'], $service->id)
            : Service::createUniqueSlug($validated['title'], $service->id);

        $features = $this->parseLinesToArray($request->input('features_input'));
        $benefits = $this->parseLinesToArray($request->input('benefits_input'));

        $benchmarks = [
            'standard' => $request->input('benchmark_standard') ?: 'ISO Class 100 / FED STD 209E',
            'evaluation' => $request->input('benchmark_evaluation') ?: '100% Free Diagnostic & Quote',
            'guarantee' => $request->input('benchmark_guarantee') ?: 'No Data – No Charge Guarantee',
            'turnaround' => $request->input('benchmark_turnaround') ?: 'Same-Day Emergency or 24-48h Standard',
            'courier' => $request->input('benchmark_courier') ?: 'Free Courier Across All 7 Emirates',
        ];

        $service->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'tagline' => $validated['tagline'] ?? null,
            'hero_badge' => $validated['hero_badge'] ?? null,
            'icon' => $validated['icon'] ?: 'sparkles',
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'] ?? '',
            'features' => $features,
            'benefits' => $benefits,
            'benchmarks' => $benchmarks,
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
            'schema_markup' => $validated['schema_markup'] ?? null,
        ]);

        return redirect()->route('admin.services.edit', $service)
            ->with('success', "Service '{$service->title}' updated successfully.");
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $title = $service->title;
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', "Service '{$title}' removed successfully.");
    }

    /**
     * Helper to parse newline-separated string into clean array.
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
}
