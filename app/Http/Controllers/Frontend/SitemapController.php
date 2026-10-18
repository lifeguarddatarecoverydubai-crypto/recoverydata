<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Excluded slugs for internal redirects or dedicated system routes.
     */
    protected const EXCLUDED_PAGE_SLUGS = [
        'hard-drive-recovery',
        'hard-drive-data-recovery-in-dubai',
        'ssd-recovery-dubai',
        'nvme-ssd-recovery-dubai',
        'raid-recovery-dubai',
        'server-recovery-dubai',
        'nas-recovery-dubai',
        'cctv-footage-recovery-dubai',
        'usb-data-recovery-dubai',
        'sd-card-recovery-dubai',
        'iphone-data-recovery-dubai',
        'ransomware-data-recovery-dubai',
        'home',
        'services',
        'about',
        'contact',
    ];

    /**
     * Master XML sitemap containing all indexable pages, services, categories, and articles.
     */
    public function index(): Response
    {
        $xml = Cache::remember('sitemap_master_xml', 3600, function (): string {
            $entries = array_merge(
                $this->getPagesEntries(),
                $this->getServicesEntries(),
                $this->getProductsEntries(),
                $this->getCategoriesEntries(),
                $this->getPostsEntries()
            );

            return $this->buildUrlsetXml($entries);
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Master XML sitemap index referencing specialized sub-sitemaps.
     */
    public function sitemapIndex(): Response
    {
        $xml = Cache::remember('sitemap_index_xml', 3600, function (): string {
            $sitemaps = [
                [
                    'loc' => route('sitemap.pages'),
                    'lastmod' => now()->toAtomString(),
                ],
                [
                    'loc' => route('sitemap.services'),
                    'lastmod' => Service::published()->latest('updated_at')->first()?->updated_at?->toAtomString() ?? now()->toAtomString(),
                ],
                [
                    'loc' => route('sitemap.categories'),
                    'lastmod' => Category::latest('updated_at')->first()?->updated_at?->toAtomString() ?? now()->toAtomString(),
                ],
                [
                    'loc' => route('sitemap.posts'),
                    'lastmod' => Post::published()->latest('updated_at')->first()?->updated_at?->toAtomString() ?? now()->toAtomString(),
                ],
            ];

            return $this->buildIndexXml($sitemaps);
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Dedicated XML sitemap for blog posts and technical articles.
     */
    public function posts(): Response
    {
        $xml = Cache::remember('sitemap_posts_xml', 3600, function (): string {
            return $this->buildUrlsetXml($this->getPostsEntries());
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Dedicated XML sitemap for static and CMS pages.
     */
    public function pages(): Response
    {
        $xml = Cache::remember('sitemap_pages_xml', 3600, function (): string {
            return $this->buildUrlsetXml($this->getPagesEntries());
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Dedicated XML sitemap for blog categories with active content.
     */
    public function categories(): Response
    {
        $xml = Cache::remember('sitemap_categories_xml', 3600, function (): string {
            return $this->buildUrlsetXml($this->getCategoriesEntries());
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Dedicated XML sitemap for commercial data recovery services.
     */
    public function services(): Response
    {
        $xml = Cache::remember('sitemap_services_xml', 3600, function (): string {
            return $this->buildUrlsetXml($this->getServicesEntries());
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Dynamic robots.txt response.
     */
    public function robots(): Response
    {
        $sitemapIndexUrl = $this->formatUrl(route('sitemap.index'));
        $sitemapUrl = $this->formatUrl(route('sitemap'));
        $sitemapPostsUrl = $this->formatUrl(route('sitemap.posts'));
        $sitemapPagesUrl = $this->formatUrl(route('sitemap.pages'));
        $sitemapCategoriesUrl = $this->formatUrl(route('sitemap.categories'));
        $sitemapServicesUrl = $this->formatUrl(route('sitemap.services'));
        $host = parse_url(config('app.canonical_domain'), PHP_URL_HOST) ?? 'www.recoverydata.ae';

        $content = <<<ROBOTS
# robots.txt for Recovery Data Dubai
User-agent: *
Allow: /
Allow: /images/
Allow: /storage/
Allow: /build/
Allow: /css/
Allow: /js/
Disallow: /admin
Disallow: /admin/
Disallow: /login
Disallow: /login/
Disallow: /register
Disallow: /register/
Disallow: /password/
Disallow: /api/
Disallow: /public/
Disallow: /storage/framework/

# Sitemaps
Sitemap: {$sitemapIndexUrl}
Sitemap: {$sitemapUrl}
Sitemap: {$sitemapPostsUrl}
Sitemap: {$sitemapPagesUrl}
Sitemap: {$sitemapCategoriesUrl}
Sitemap: {$sitemapServicesUrl}

Host: {$host}
ROBOTS;

        return response(trim($content)."\n", 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }

    /**
     * Compile page entries for sitemaps.
     */
    protected function getPagesEntries(): array
    {
        $pages = Page::where('status', 'published')
            ->where('is_indexable', true)
            ->whereNotIn('slug', self::EXCLUDED_PAGE_SLUGS)
            ->latest('updated_at')
            ->get();

        $entries = [];

        // Core High-Priority Landing Pages
        $entries[] = [
            'loc' => route('home'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0',
            'image' => [
                'loc' => url('/images/recoverydata-logo.png'),
                'title' => 'Recovery Data Dubai - Cleanroom Data Recovery Lab',
            ],
        ];

        $entries[] = [
            'loc' => route('service.index'),
            'lastmod' => Service::published()->latest('updated_at')->first()?->updated_at?->toAtomString() ?? now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.9',
        ];

        $entries[] = [
            'loc' => route('about'),
            'lastmod' => now()->subDays(3)->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.8',
        ];

        $entries[] = [
            'loc' => route('contact'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.8',
        ];

        $entries[] = [
            'loc' => route('odoo.index'),
            'lastmod' => now()->subDays(5)->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.8',
        ];

        $entries[] = [
            'loc' => route('product.index'),
            'lastmod' => Product::published()->latest('updated_at')->first()?->updated_at?->toAtomString() ?? now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.8',
        ];

        $entries[] = [
            'loc' => route('blog.index'),
            'lastmod' => Post::published()->latest('updated_at')->first()?->updated_at?->toAtomString() ?? now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.8',
        ];

        // Standalone CMS Pages
        foreach ($pages as $page) {
            $entry = [
                'loc' => url('/'.$page->slug),
                'lastmod' => $page->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];

            if ($page->featured_image_url) {
                $entry['image'] = [
                    'loc' => $page->featured_image_url,
                    'title' => $page->title,
                ];
            }

            $entries[] = $entry;
        }

        return $entries;
    }

    /**
     * Compile commercial service entries for sitemaps.
     */
    protected function getServicesEntries(): array
    {
        $services = Service::published()
            ->where('is_indexable', true)
            ->latest('updated_at')
            ->get();

        $entries = [];
        foreach ($services as $service) {
            $entry = [
                'loc' => route('service.show', $service->slug),
                'lastmod' => $service->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ];

            if ($service->image_url) {
                $entry['image'] = [
                    'loc' => $service->image_url,
                    'title' => $service->title.' - Recovery Data Dubai',
                ];
            }

            $entries[] = $entry;
        }

        return $entries;
    }

    /**
     * Compile commercial product entries for sitemaps.
     */
    protected function getProductsEntries(): array
    {
        $products = Product::published()->latest('updated_at')->get();

        $entries = [];
        foreach ($products as $product) {
            $entry = [
                'loc' => route('product.show', $product->slug),
                'lastmod' => $product->updated_at?->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];

            if ($product->featured_image_url) {
                $entry['image'] = [
                    'loc' => $product->featured_image_url,
                    'title' => $product->title.' - Recovery Data Dubai',
                ];
            }

            $entries[] = $entry;
        }

        return $entries;
    }

    /**
     * Compile published blog posts entries for sitemaps.
     */
    protected function getPostsEntries(): array
    {
        $posts = Post::published()
            ->where('is_indexable', true)
            ->latest('updated_at')
            ->get();

        $entries = [];
        foreach ($posts as $post) {
            $entry = [
                'loc' => route('post.show', $post->slug),
                'lastmod' => ($post->updated_at ?? $post->published_at ?? now())->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];

            if ($post->featured_image_url) {
                $entry['image'] = [
                    'loc' => $post->featured_image_url,
                    'title' => $post->title,
                ];
            }

            $entries[] = $entry;
        }

        return $entries;
    }

    /**
     * Compile active category entries for sitemaps.
     */
    protected function getCategoriesEntries(): array
    {
        $categories = Category::whereNotIn('slug', ['uncategorized'])
            ->has('publishedPosts')
            ->with(['publishedPosts' => fn ($q) => $q->latest('updated_at')->take(1)])
            ->get();

        $entries = [];
        foreach ($categories as $category) {
            $latestPost = $category->publishedPosts->first();
            $lastmod = $latestPost?->updated_at?->toAtomString() ?? $category->updated_at?->toAtomString() ?? now()->toAtomString();

            $entries[] = [
                'loc' => route('category.show', $category->slug),
                'lastmod' => $lastmod,
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ];
        }

        return $entries;
    }

    /**
     * Enforce the canonical production hostname on all sitemap URLs,
     * regardless of APP_ENV/APP_URL, so sitemap output can never leak a
     * local/staging host to search engines (see config('app.canonical_domain')).
     */
    protected function formatUrl(string $url): string
    {
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? '/';
        $query = isset($parsed['query']) ? '?'.$parsed['query'] : '';

        // Map old wp-content/uploads path to storage
        if (str_starts_with($path, '/wp-content/uploads/')) {
            $path = '/storage/'.substr($path, strlen('/wp-content/uploads/'));
        }

        return rtrim(config('app.canonical_domain'), '/').$path.$query;
    }

    /**
     * Build valid XML urlset string with optional Google image extensions.
     */
    protected function buildUrlsetXml(array $entries): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";

        foreach ($entries as $entry) {
            $loc = $this->formatUrl($entry['loc']);
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars($loc, ENT_XML1, 'UTF-8')."</loc>\n";
            $xml .= "    <lastmod>{$entry['lastmod']}</lastmod>\n";
            $xml .= "    <changefreq>{$entry['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$entry['priority']}</priority>\n";

            if (! empty($entry['image']) && ! empty($entry['image']['loc'])) {
                $imgLoc = $this->formatUrl($entry['image']['loc']);
                $xml .= "    <image:image>\n";
                $xml .= '      <image:loc>'.htmlspecialchars($imgLoc, ENT_XML1, 'UTF-8')."</image:loc>\n";
                if (! empty($entry['image']['title'])) {
                    $xml .= '      <image:title>'.htmlspecialchars(strip_tags((string) $entry['image']['title']), ENT_XML1, 'UTF-8')."</image:title>\n";
                }
                $xml .= "    </image:image>\n";
            }

            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Build valid XML sitemapindex string.
     */
    protected function buildIndexXml(array $sitemaps): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($sitemaps as $sitemap) {
            $loc = $this->formatUrl($sitemap['loc']);
            $xml .= "  <sitemap>\n";
            $xml .= '    <loc>'.htmlspecialchars($loc, ENT_XML1, 'UTF-8')."</loc>\n";
            $xml .= "    <lastmod>{$sitemap['lastmod']}</lastmod>\n";
            $xml .= "  </sitemap>\n";
        }

        $xml .= '</sitemapindex>';

        return $xml;
    }
}
