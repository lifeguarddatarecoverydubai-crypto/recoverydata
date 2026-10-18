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
        Schema::create('redirect_rules', function (Blueprint $table) {
            $table->id();
            $table->string('source_url', 1000);
            $table->string('target_url', 1000)->nullable();
            $table->unsignedSmallInteger('status_code')->default(301); // 301, 302, 307, 308, 410
            $table->string('match_type', 20)->default('exact'); // exact, wildcard, regex
            $table->string('query_param_option', 20)->default('ignore'); // ignore, pass, exact
            $table->string('group', 60)->default('General');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('hits')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            // Indexes for high performance lookup & filtering
            $table->index('is_active');
            $table->index('status_code');
            $table->index('group');
            $table->index('match_type');
            $table->index(['is_active', 'order']);

            if (DB::getDriverName() === 'mysql') {
                $table->rawIndex('source_url(191)', 'redirect_rules_source_url_index');
            } else {
                $table->index('source_url', 'redirect_rules_source_url_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redirect_rules');
    }
};
