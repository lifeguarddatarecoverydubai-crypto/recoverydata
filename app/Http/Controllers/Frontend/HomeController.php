<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index(): View
    {
        // 1. Enterprise Products (HRMS, CRM, Cloud ERP, AI Agents)
        $products = Product::published()
            ->ordered()
            ->get();

        // 2. Services from Recovery Data
        $services = Service::published()
            ->ordered()
            ->get();

        // 3. Featured Post & Latest Articles
        $featuredPost = Post::published()
            ->with(['user', 'categories'])
            ->whereNotNull('featured_image')
            ->latest('published_at')
            ->first();

        $featuredId = $featuredPost?->id;

        $latestPosts = Post::published()
            ->with(['user', 'categories', 'tags'])
            ->when($featuredId, fn ($q) => $q->where('id', '!=', $featuredId))
            ->latest('published_at')
            ->take(3)
            ->get();

        $categories = Category::whereHas('posts', fn ($q) => $q->where('status', 'published'))
            ->withCount(['posts' => fn ($q) => $q->where('status', 'published')])
            ->take(8)
            ->get();

        return view('frontend.home', compact('products', 'services', 'featuredPost', 'latestPosts', 'categories'));
    }

    /**
     * About page showcasing Recovery Data Dubai cleanroom laboratory legacy.
     */
    public function about(): View
    {
        $servicesCount = Service::published()->count();
        $productsCount = Product::published()->count();

        return view('frontend.about', compact('servicesCount', 'productsCount'));
    }
}
