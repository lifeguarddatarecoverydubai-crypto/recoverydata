<?php

namespace App\Console\Commands;

use App\Http\Controllers\Frontend\SitemapController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate
                            {--url= : Canonical base URL for the sitemap (default: https://www.recoverydata.ae)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush sitemap cache and re-warm the cache so /sitemap.xml and friends serve freshly generated XML on the next request';

    /**
     * Cache keys used by SitemapController.
     *
     * @var array<int, string>
     */
    protected const CACHE_KEYS = [
        'sitemap_master_xml',
        'sitemap_index_xml',
        'sitemap_posts_xml',
        'sitemap_pages_xml',
        'sitemap_categories_xml',
        'sitemap_services_xml',
    ];

    /**
     * Execute the console command.
     */
    public function handle(SitemapController $controller): int
    {
        $baseUrl = (string) ($this->option('url') ?: (app()->environment('production') ? config('app.url') : 'https://www.recoverydata.ae'));

        $this->info("Setting canonical URL base to: {$baseUrl}");
        $scheme = parse_url($baseUrl, PHP_URL_SCHEME) ?: 'https';
        URL::forceScheme($scheme);
        URL::forceRootUrl($baseUrl);

        $this->info('Flushing sitemap cache keys...');
        foreach (self::CACHE_KEYS as $key) {
            Cache::forget($key);
        }

        // Re-render each sitemap so the cache is warm for the next visitor/Googlebot,
        // and so we fail loudly here if content is invalid - but deliberately do NOT
        // write these to public/, since a static file there would permanently shadow
        // the dynamic route (Apache's "RewriteCond %{REQUEST_FILENAME} !-f" only
        // routes to Laravel when no matching static file exists), freezing the
        // sitemap at this moment in time for every future visit.
        $targets = [
            'sitemap.xml' => fn () => $controller->index()->getContent(),
            'sitemap-index.xml' => fn () => $controller->sitemapIndex()->getContent(),
            'sitemap-posts.xml' => fn () => $controller->posts()->getContent(),
            'sitemap-pages.xml' => fn () => $controller->pages()->getContent(),
            'sitemap-services.xml' => fn () => $controller->services()->getContent(),
            'sitemap-categories.xml' => fn () => $controller->categories()->getContent(),
        ];

        foreach ($targets as $filename => $render) {
            $xmlContent = $render();
            $parsed = @simplexml_load_string($xmlContent);
            if ($parsed === false) {
                $this->error("Failed to generate valid XML for {$filename}!");

                return self::FAILURE;
            }

            $size = round(strlen($xmlContent) / 1024, 2);
            $this->info("Validated & cached: {$filename} ({$size} KB)");
        }

        Cache::put('sitemap_last_generated_at', now(), now()->addDays(30));

        $this->newLine();
        $this->info('All XML sitemaps regenerated, validated, and cached successfully. They are served live and will always reflect current published content.');

        return self::SUCCESS;
    }
}
