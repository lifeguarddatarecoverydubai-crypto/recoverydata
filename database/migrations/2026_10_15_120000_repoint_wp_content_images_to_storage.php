<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $prefixes = [
            'https://recoverydata.ae/wp-content/uploads/',
            'http://recoverydata.ae/wp-content/uploads/',
            'https://www.recoverydata.ae/wp-content/uploads/',
            'http://www.recoverydata.ae/wp-content/uploads/',
            '/wp-content/uploads/',
        ];

        foreach (['posts', 'pages'] as $table) {
            foreach ($prefixes as $prefix) {
                DB::table($table)
                    ->where('featured_image', 'like', "{$prefix}%")
                    ->get(['id', 'featured_image'])
                    ->each(function ($record) use ($table, $prefix): void {
                        $clean = ltrim(substr((string) $record->featured_image, strlen($prefix)), '/');
                        DB::table($table)->where('id', $record->id)->update([
                            'featured_image' => $clean,
                        ]);
                    });
            }

            foreach (['https://recoverydata.ae/wp-content/uploads/', 'http://recoverydata.ae/wp-content/uploads/', 'https://www.recoverydata.ae/wp-content/uploads/', 'http://www.recoverydata.ae/wp-content/uploads/'] as $domainPrefix) {
                DB::table($table)
                    ->where('content', 'like', "%{$domainPrefix}%")
                    ->get(['id', 'content'])
                    ->each(function ($record) use ($table, $domainPrefix): void {
                        $updated = str_replace($domainPrefix, '/storage/', (string) $record->content);
                        DB::table($table)->where('id', $record->id)->update([
                            'content' => $updated,
                        ]);
                    });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Irreversible data canonicalization
    }
};
