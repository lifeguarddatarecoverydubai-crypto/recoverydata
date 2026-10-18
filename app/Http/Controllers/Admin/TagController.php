<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display a listing of the tags.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $tags = Tag::withCount('posts')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.tags.index', compact('tags', 'search'));
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tags,slug'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Tag::createUniqueSlug($validated['slug'])
            : Tag::createUniqueSlug($validated['name']);

        Tag::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.tags.index')
            ->with('success', "Tag '{$validated['name']}' created successfully.");
    }

    /**
     * Update the specified tag.
     */
    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tags,slug,'.$tag->id],
            'description' => ['nullable', 'string'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Tag::createUniqueSlug($validated['slug'], $tag->id)
            : Tag::createUniqueSlug($validated['name'], $tag->id);

        $tag->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.tags.index')
            ->with('success', "Tag '{$tag->name}' updated successfully.");
    }

    /**
     * Remove the specified tag.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $name = $tag->name;
        $tag->posts()->detach();
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', "Tag '{$name}' deleted successfully.");
    }
}
