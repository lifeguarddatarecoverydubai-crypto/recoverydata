<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OdooController as AdminOdooController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OdooController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\ServiceController as FrontendServiceController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\StorageFallbackController;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Official Odoo UAE Partner Landing Page
Route::get('/odoo', [OdooController::class, 'index'])->name('odoo.index');

// Products Routes
Route::get('/products', [FrontendProductController::class, 'index'])->name('product.index');
Route::get('/product/{slug}', [FrontendProductController::class, 'show'])->name('product.show');

// Services Routes
Route::get('/services', [FrontendServiceController::class, 'index'])->name('service.index');
Route::get('/service/{slug}', [FrontendServiceController::class, 'show'])->name('service.show');

// Blog & Content Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/post/{slug}', [BlogController::class, 'show'])->name('post.show');
Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category.show');
Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('tag.show');
Route::get('/author/{id}', [BlogController::class, 'author'])->name('author.show');
Route::get('/page/{slug}', [FrontendPageController::class, 'show'])->name('page.show');

// Contact
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit')->middleware('throttle:10,1');

// XML Sitemaps (Master Unified Sitemap, Sitemap Index, & Section Sitemaps)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-index.xml', [SitemapController::class, 'sitemapIndex'])->name('sitemap.index');
Route::get('/sitemap_index.xml', fn () => redirect()->route('sitemap.index', [], 301));
Route::get('/sitemap-posts.xml', [SitemapController::class, 'posts'])->name('sitemap.posts');
Route::get('/post-sitemap.xml', [SitemapController::class, 'posts']);
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/page-sitemap.xml', [SitemapController::class, 'pages']);
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/category-sitemap.xml', [SitemapController::class, 'categories']);
Route::get('/sitemap-services.xml', [SitemapController::class, 'services'])->name('sitemap.services');
Route::get('/service-sitemap.xml', [SitemapController::class, 'services']);

// Robots.txt
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');
});

// Public registration is disabled; only administrators can create users from the dashboard
Route::redirect('/register', '/login', 301);

// Google Two-Factor Authentication Flow
Route::get('/login/two-factor', [TwoFactorController::class, 'showChallenge'])->name('login.two-factor');
Route::post('/login/two-factor', [TwoFactorController::class, 'verifyChallenge'])->middleware('throttle:10,1');
Route::get('/login/two-factor-setup', [TwoFactorController::class, 'showSetup'])->name('login.two-factor.setup');
Route::post('/login/two-factor-setup', [TwoFactorController::class, 'confirmSetup'])->middleware('throttle:10,1');
Route::post('/login/two-factor/cancel', [TwoFactorController::class, 'cancel'])->name('login.two-factor.cancel');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin CMS Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'two_factor', 'content.manager'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard, Sitemap & Cache
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/quick-draft', [DashboardController::class, 'quickDraft'])->name('quick-draft');
    Route::post('/sitemap/generate', [DashboardController::class, 'generateSitemap'])->name('sitemap.generate');
    Route::post('/cache/clear', [DashboardController::class, 'clearCache'])->name('cache.clear');

    // Odoo Partner Page Management (New)
    Route::get('/odoo', [AdminOdooController::class, 'edit'])->name('odoo.edit');
    Route::put('/odoo', [AdminOdooController::class, 'update'])->name('odoo.update');

    // Products Management (New)
    Route::resource('products', AdminProductController::class)->except(['show']);

    // Services Management (New)
    Route::resource('services', AdminServiceController::class)->except(['show']);

    // Leads & Client Inquiries (New)
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('/inquiries/{inquiry}/toggle-read', [InquiryController::class, 'toggleRead'])->name('inquiries.toggle-read');
    Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Posts Management
    Route::resource('posts', PostController::class)->except(['show']);

    // Categories Management
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

    // Tags Management
    Route::resource('tags', TagController::class)->except(['create', 'show', 'edit']);

    // Pages Management
    Route::resource('pages', AdminPageController::class)->except(['show']);

    // Navigation Menus Management
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
    Route::patch('/menus/{menu}/toggle-active', [MenuController::class, 'toggleActive'])->name('menus.toggle-active');
    Route::post('/menus/{menu}/move-up', [MenuController::class, 'moveUp'])->name('menus.move-up');
    Route::post('/menus/{menu}/move-down', [MenuController::class, 'moveDown'])->name('menus.move-down');
    Route::post('/menus/reset-defaults', [MenuController::class, 'resetDefaults'])->name('menus.reset-defaults');

    // 301 & URL Redirection Management (WordPress-style, High Performance)
    Route::get('/redirects', [RedirectController::class, 'index'])->name('redirects.index');
    Route::post('/redirects', [RedirectController::class, 'store'])->name('redirects.store');
    Route::put('/redirects/{redirect}', [RedirectController::class, 'update'])->name('redirects.update');
    Route::delete('/redirects/{redirect}', [RedirectController::class, 'destroy'])->name('redirects.destroy');
    Route::patch('/redirects/{redirect}/toggle', [RedirectController::class, 'toggle'])->name('redirects.toggle');
    Route::post('/redirects/{redirect}/reset-hits', [RedirectController::class, 'resetHits'])->name('redirects.reset-hits');
    Route::post('/redirects/bulk', [RedirectController::class, 'bulk'])->name('redirects.bulk');
    Route::post('/redirects/test', [RedirectController::class, 'test'])->name('redirects.test');
    Route::get('/redirects/export', [RedirectController::class, 'exportCsv'])->name('redirects.export');
    Route::post('/redirects/import', [RedirectController::class, 'importCsv'])->name('redirects.import');
    Route::post('/redirects/seed-defaults', [RedirectController::class, 'seedDefaults'])->name('redirects.seed-defaults');

    // Media Library
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::put('/media/{medium}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::get('/media-modal-list', [MediaController::class, 'modalList'])->name('media.modal-list');

    // User Profile & Two-Factor Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/two-factor/recovery-codes', [ProfileController::class, 'regenerateRecoveryCodes'])->name('profile.two-factor.recovery-codes');
    Route::post('/profile/two-factor/reset', [ProfileController::class, 'resetTwoFactor'])->name('profile.two-factor.reset');

    // Administrator Only: User Management
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});

/*
|--------------------------------------------------------------------------
| Legacy WordPress Media 301 Redirect Fallback
|--------------------------------------------------------------------------
*/
Route::get('/wp-content/uploads/{path}', function (string $path) {
    return redirect('/storage/'.ltrim($path, '/'), 301);
})->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Storage Asset Fallback (Handles Missing Media Seamlessly)
|--------------------------------------------------------------------------
*/
Route::get('/storage/{path}', [StorageFallbackController::class, 'handle'])
    ->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Dynamic Slug Resolution (Matching WordPress Permalinks)
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', function (string $slug) {
    // 0. Canonical 301 redirects for legacy cannibalizing URLs
    $legacyPermalinks = [
        'hard-drive-data-recovery-in-dubai' => route('service.show', 'hard-drive-recovery'),
        'server-recovery-dubai' => route('service.show', 'raid-recovery-dubai'),
        'sd-card-recovery-dubai' => route('service.show', 'usb-data-recovery-dubai'),
        'home' => route('home'),
    ];

    if (isset($legacyPermalinks[$slug])) {
        return redirect($legacyPermalinks[$slug], 301);
    }

    // 1. Check if it matches a Service (Core business offering takes priority)
    if (Service::where('slug', $slug)->where('status', 'published')->exists()) {
        return app(FrontendServiceController::class)->show($slug);
    }

    // 2. Check if it matches a CMS Page
    if (Page::where('slug', $slug)->where('status', 'published')->exists()) {
        return app(FrontendPageController::class)->show($slug);
    }

    // 3. Check if it matches a Blog Post -> 301 redirect to canonical /post/{slug}
    if (Post::where('slug', $slug)->where('status', 'published')->exists()) {
        return redirect()->route('post.show', $slug, 301);
    }

    // 4. If logged-in admin, allow draft preview
    if (auth()->check() && auth()->user()->canManageContent()) {
        if (Service::where('slug', $slug)->exists()) {
            return app(FrontendServiceController::class)->show($slug);
        }
        if (Page::where('slug', $slug)->exists()) {
            return app(FrontendPageController::class)->show($slug);
        }
        if (Post::where('slug', $slug)->exists()) {
            return app(BlogController::class)->show($slug);
        }
    }

    abort(404);
})->where('slug', '[a-zA-Z0-9\-_]+');
