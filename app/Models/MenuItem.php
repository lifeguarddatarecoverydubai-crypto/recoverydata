<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'title',
        'url',
        'route_name',
        'type',
        'target',
        'icon',
        'badge',
        'order',
        'is_active',
        'location',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Parent menu item relationship.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Child menu items relationship.
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Active child menu items relationship.
     */
    public function activeChildren(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->where('is_active', true)->orderBy('order');
    }

    /**
     * Scope for active menu items.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for header menu items.
     */
    public function scopeHeader(Builder $query): Builder
    {
        return $query->where('location', 'header');
    }

    /**
     * Scope for ordered items.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    /**
     * Scope for root menu items (no parent).
     */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Resolve the target URL for this menu item.
     */
    public function getUrl(): string
    {
        if ($this->route_name && Route::has($this->route_name)) {
            return route($this->route_name);
        }

        if (empty($this->url) || $this->url === '#') {
            return '#';
        }

        if (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://')) {
            return $this->url;
        }

        return url($this->url);
    }

    /**
     * Determine if this menu item matches current route or path.
     */
    public function isActive(): bool
    {
        if ($this->route_name && request()->routeIs($this->route_name)) {
            return true;
        }

        $path = trim($this->url, '/');
        if ($path === '' && request()->is('/')) {
            return true;
        }

        return $path !== '' && request()->is($path);
    }

    /**
     * Reset and seed the default 7 navigation items.
     */
    public static function seedDefaults(): void
    {
        static::truncate();

        $defaults = [
            [
                'title' => 'Home',
                'url' => '/',
                'route_name' => 'home',
                'type' => 'standard',
                'target' => '_self',
                'order' => 1,
                'is_active' => true,
                'location' => 'header',
            ],
            [
                'title' => 'About',
                'url' => '/about',
                'route_name' => 'about',
                'type' => 'standard',
                'target' => '_self',
                'order' => 2,
                'is_active' => true,
                'location' => 'header',
            ],
            [
                'title' => 'Odoo Partner',
                'url' => '/odoo',
                'route_name' => 'odoo.index',
                'type' => 'odoo_badge',
                'target' => '_self',
                'order' => 3,
                'is_active' => true,
                'location' => 'header',
            ],
            [
                'title' => 'Products',
                'url' => '/products',
                'route_name' => 'product.index',
                'type' => 'products_dropdown',
                'target' => '_self',
                'order' => 4,
                'is_active' => true,
                'location' => 'header',
            ],
            [
                'title' => 'Services',
                'url' => '/services',
                'route_name' => 'service.index',
                'type' => 'services_dropdown',
                'target' => '_self',
                'order' => 5,
                'is_active' => true,
                'location' => 'header',
            ],
            [
                'title' => 'Blogs',
                'url' => '/blog',
                'route_name' => 'blog.index',
                'type' => 'standard',
                'target' => '_self',
                'order' => 6,
                'is_active' => true,
                'location' => 'header',
            ],
            [
                'title' => 'Contact us',
                'url' => '/contact',
                'route_name' => 'contact',
                'type' => 'standard',
                'target' => '_self',
                'order' => 7,
                'is_active' => true,
                'location' => 'header',
            ],
        ];

        foreach ($defaults as $item) {
            static::create($item);
        }
    }
}
