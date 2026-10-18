<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('odoo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_badge')->default('Official Partner');
            $table->string('hero_country')->default('United Arab Emirates');
            $table->string('hero_title')->default('Transform Your Business with Official Odoo ERP in Dubai');
            $table->text('hero_subtitle')->nullable();
            $table->string('contact_phone')->default('+971 56 976 3386');
            $table->string('contact_email')->default('info@recoverydata.ae');
            $table->json('trust_items')->nullable();
            $table->json('modules')->nullable();
            $table->json('services')->nullable();
            $table->json('faqs')->nullable();
            $table->json('comparison_points')->nullable();
            $table->string('focus_keyword')->nullable()->default('odoo partner dubai');
            $table->string('meta_title')->nullable()->default('Official Odoo Partner Dubai & UAE | Certified ERP Implementation | Recovery Data');
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->integer('seo_score')->default(95);
            $table->integer('readability_score')->default(90);
            $table->boolean('is_indexable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odoo_pages');
    }
};
