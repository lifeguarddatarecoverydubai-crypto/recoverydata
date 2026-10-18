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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('focus_keyword')->nullable()->after('is_indexable');
            $table->integer('seo_score')->default(0)->after('focus_keyword');
            $table->integer('readability_score')->default(0)->after('seo_score');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('focus_keyword')->nullable()->after('is_indexable');
            $table->integer('seo_score')->default(0)->after('focus_keyword');
            $table->integer('readability_score')->default(0)->after('seo_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['focus_keyword', 'seo_score', 'readability_score']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['focus_keyword', 'seo_score', 'readability_score']);
        });
    }
};
