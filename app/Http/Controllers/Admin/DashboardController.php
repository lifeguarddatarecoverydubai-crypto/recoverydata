<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\Media;
use App\Models\MenuItem;
use App\Models\OdooPage;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\RedirectRule;
use App\Models\Service;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the CMS admin dashboard.
     */
    public function index(): View
    {
        $stats = [
            'total_products' => Product::count(),
            'published_products' => Product::where('status', 'published')->count(),
            'total_services' => Service::count(),
            'published_services' => Service::where('status', 'published')->count(),
            'total_inquiries' => Inquiry::count(),
            'unread_inquiries' => Inquiry::unread()->count(),
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'total_pages' => Page::count(),
            'total_categories' => Category::count(),
            'total_tags' => Tag::count(),
            'total_media' => Media::count(),
            'total_users' => User::count(),
            'total_menu_items' => MenuItem::count(),
            'active_menu_items' => MenuItem::where('is_active', true)->count(),
            'total_redirects' => RedirectRule::count(),
            'active_redirects' => RedirectRule::where('is_active', true)->count(),
            'redirect_hits' => (int) RedirectRule::sum('hits'),
        ];

        $topRedirects = RedirectRule::orderByDesc('hits')
            ->take(5)
            ->get();

        $recentPosts = Post::with(['user', 'categories'])
            ->latest()
            ->take(6)
            ->get();

        $recentMedia = Media::latest()
            ->take(8)
            ->get();

        $recentInquiries = Inquiry::latest()
            ->take(5)
            ->get();

        $services = Service::ordered()->get();

        $pages = Page::latest()
            ->take(8)
            ->get();

        $odoo = OdooPage::getSettings();

        $lastGenerated = Cache::get('sitemap_last_generated_at');
        $sitemapInfo = [
            'exists' => true, // sitemap.xml is always served live by SitemapController, never a static file
            'last_generated' => $lastGenerated,
            'canonical_domain' => config('app.canonical_domain'),
        ];

        return view('admin.dashboard', compact('stats', 'recentPosts', 'recentMedia', 'recentInquiries', 'odoo', 'services', 'pages', 'topRedirects', 'sitemapInfo'));
    }

    /**
     * Handle quick draft saving from the dashboard.
     */
    public function quickDraft(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        $slug = Post::createUniqueSlug($validated['title']);

        Post::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'] ?? '',
            'status' => 'draft',
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Quick draft saved successfully!');
    }

    /**
     * Generate XML sitemaps and refresh sitemap cache.
     */
    public function generateSitemap(Request $request): RedirectResponse
    {
        try {
            Artisan::call('sitemap:generate');

            return redirect()->route('admin.dashboard')
                ->with('success', 'XML Sitemaps regenerated and cache refreshed for '.config('app.canonical_domain').'. Live at /sitemap.xml.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to generate sitemap: '.$e->getMessage());
        }
    }

    /**
     * Clear all application, view, route, and configuration caches.
     */
    public function clearCache(Request $request): RedirectResponse
    {
        try {
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');

            try {
                Artisan::call('cache:clear');
            } catch (\Throwable $ignored) {
                // Ignore DB cache errors if database cache table is not used
            }

            try {
                Artisan::call('optimize:clear');
            } catch (\Throwable $ignored) {
            }

            return redirect()->route('admin.dashboard')
                ->with('success', 'All system caches (Blade Views, Route Cache, Config Cache, Application Cache) cleared successfully!');
        } catch (\Throwable $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to clear cache: '.$e->getMessage());
        }
    }
}
