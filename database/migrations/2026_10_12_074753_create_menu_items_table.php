<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->string('title');
            $table->string('url')->default('/');
            $table->string('route_name')->nullable();
            $table->string('type')->default('standard'); // standard, products_dropdown, services_dropdown, odoo_badge, dropdown
            $table->string('target')->default('_self'); // _self, _blank
            $table->string('icon')->nullable();
            $table->string('badge')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('location')->default('header');
            $table->timestamps();
        });

        // Seed initial default navigation items
        DB::table('menu_items')->insert([
            [
                'title' => 'Home',
                'url' => '/',
                'route_name' => 'home',
                'type' => 'standard',
                'target' => '_self',
                'order' => 1,
                'is_active' => true,
                'location' => 'header',
                'created_at' => now(),
                'updated_at' => now(),
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
                'created_at' => now(),
                'updated_at' => now(),
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
                'created_at' => now(),
                'updated_at' => now(),
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
                'created_at' => now(),
                'updated_at' => now(),
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
                'created_at' => now(),
                'updated_at' => now(),
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
                'created_at' => now(),
                'updated_at' => now(),
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
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
