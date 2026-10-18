<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $category = $request->query('category');

        $query = Product::published()->ordered();

        if ($category) {
            $query->where('category', $category);
        }

        $products = $query->get();
        $categories = Product::published()->select('category')->distinct()->whereNotNull('category')->pluck('category');

        return view('frontend.products.index', compact('products', 'categories', 'category'));
    }

    /**
     * Display the specified product.
     */
    public function show(string $slug): View
    {
        $product = Product::published()->where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::published()
            ->where('id', '!=', $product->id)
            ->ordered()
            ->take(3)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}
