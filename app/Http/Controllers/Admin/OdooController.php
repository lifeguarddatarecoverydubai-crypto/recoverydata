<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OdooPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OdooController extends Controller
{
    /**
     * Show the edit form for the Odoo Partner Landing Page.
     */
    public function edit(): View
    {
        $odoo = OdooPage::getSettings();

        return view('admin.odoo.edit', compact('odoo'));
    }

    /**
     * Update the Odoo Partner Landing Page content & SEO settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $odoo = OdooPage::getSettings();

        $validated = $request->validate([
            'hero_badge' => ['required', 'string', 'max:255'],
            'hero_country' => ['required', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['required', 'string', 'max:2000'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_email' => ['required', 'email', 'max:100'],
            'trust_items_input' => ['nullable', 'string'],
            'modules_json' => ['nullable', 'string'],
            'services_json' => ['nullable', 'string'],
            'faqs_json' => ['nullable', 'string'],
            // SEO
            'focus_keyword' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'seo_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'readability_score' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        // Process trust items from lines if provided
        $trustItems = $odoo->trust_items;
        if ($request->filled('trust_items_input')) {
            $trustItems = array_values(array_filter(array_map('trim', explode("\n", $request->input('trust_items_input')))));
        }

        // Process modules JSON if provided
        $modules = $odoo->modules;
        if ($request->filled('modules_json')) {
            $decoded = json_decode($request->input('modules_json'), true);
            if (is_array($decoded)) {
                $modules = $decoded;
            }
        }

        // Process services JSON if provided
        $services = $odoo->services;
        if ($request->filled('services_json')) {
            $decoded = json_decode($request->input('services_json'), true);
            if (is_array($decoded)) {
                $services = $decoded;
            }
        }

        // Process faqs JSON if provided
        $faqs = $odoo->faqs;
        if ($request->filled('faqs_json')) {
            $decoded = json_decode($request->input('faqs_json'), true);
            if (is_array($decoded)) {
                $faqs = $decoded;
            }
        }

        $odoo->update([
            'hero_badge' => $validated['hero_badge'],
            'hero_country' => $validated['hero_country'],
            'hero_title' => $validated['hero_title'],
            'hero_subtitle' => $validated['hero_subtitle'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'],
            'trust_items' => $trustItems,
            'modules' => $modules,
            'services' => $services,
            'faqs' => $faqs,
            'focus_keyword' => $validated['focus_keyword'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'seo_score' => (int) ($validated['seo_score'] ?? 95),
            'readability_score' => (int) ($validated['readability_score'] ?? 90),
            'is_indexable' => $request->has('is_indexable'),
        ]);

        return redirect()->route('admin.odoo.edit')
            ->with('success', 'Odoo Partner Landing Page updated successfully! Changes are live on the website.');
    }
}
