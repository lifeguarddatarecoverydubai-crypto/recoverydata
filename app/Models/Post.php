<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'is_indexable',
        'focus_keyword',
        'seo_score',
        'readability_score',
        'schema_markup',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_indexable' => 'boolean',
            'seo_score' => 'integer',
            'readability_score' => 'integer',
        ];
    }

    /**
     * Author relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Recovery Data Team',
            'email' => 'info@recoverydata.ae',
        ]);
    }

    /**
     * Post categories.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Post tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Scope for published posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope for searching posts.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%")
                ->orWhere('content', 'like', "%{$term}%");
        });
    }

    /**
     * Featured image URL accessor.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (! $this->featured_image) {
            return $this->getTopicPlaceholderUrl();
        }

        $feat = trim($this->featured_image);

        // 1. External HTTP URL
        if (str_starts_with($feat, 'http://') || str_starts_with($feat, 'https://')) {
            $parsed = parse_url($feat);
            $path = $parsed['path'] ?? '';
            if (str_starts_with($path, '/storage/')) {
                $sub = substr($path, 9);
                if (file_exists(public_path('storage/'.$sub)) || file_exists(storage_path('app/public/'.$sub))) {
                    return $feat;
                }

                return $this->getTopicPlaceholderUrl();
            }

            return $feat;
        }

        // 2. Images path in public/
        if (str_starts_with($feat, 'images/')) {
            if (file_exists(public_path($feat))) {
                return asset($feat).(str_ends_with($feat, '.svg') ? '?v=2' : '');
            }

            return $this->getTopicPlaceholderUrl();
        }

        // 3. Clean legacy WordPress upload paths
        $cleanPath = ltrim($feat, '/');
        if (preg_match('#^wp-content/uploads/(.*)$#i', $cleanPath, $matches)) {
            $cleanPath = $matches[1];
        }

        // 4. Check if file exists in public/storage or storage/app/public
        if (file_exists(public_path('storage/'.$cleanPath)) || file_exists(storage_path('app/public/'.$cleanPath))) {
            return asset('storage/'.$cleanPath);
        }

        // 5. File does not exist on disk -> return context-aware topic placeholder SVG
        return $this->getTopicPlaceholderUrl();
    }

    /**
     * Resolve a context-aware topic placeholder relative path based on title, slug, or categories.
     */
    public function getTopicPlaceholderPath(): string
    {
        $text = strtolower(($this->title ?? '').' '.($this->slug ?? ''));

        if (str_contains($text, 'ssd') || str_contains($text, 'nvme') || str_contains($text, 'm.2') || str_contains($text, 'm2')) {
            return 'images/placeholders/ssd-nvme.svg';
        }

        if (str_contains($text, 'hard-disk') || str_contains($text, 'hard-drive') || str_contains($text, 'hdd') || str_contains($text, 'drive') || str_contains($text, 'disk') || str_contains($text, 'platter') || str_contains($text, 'pcb') || str_contains($text, 'firmware') || str_contains($text, 'clicking') || str_contains($text, 'bad sector')) {
            return 'images/placeholders/hard-drive.svg';
        }

        if (str_contains($text, 'raid') || str_contains($text, 'server')) {
            return 'images/placeholders/raid-server.svg';
        }

        if (str_contains($text, 'nas') || str_contains($text, 'synology') || str_contains($text, 'qnap')) {
            return 'images/placeholders/nas-storage.svg';
        }

        if (str_contains($text, 'cctv') || str_contains($text, 'dvr') || str_contains($text, 'nvr') || str_contains($text, 'footage') || str_contains($text, 'camera')) {
            return 'images/placeholders/cctv-dvr.svg';
        }

        if (str_contains($text, 'iphone') || str_contains($text, 'android') || str_contains($text, 'phone') || str_contains($text, 'mobile') || str_contains($text, 'smartphone') || str_contains($text, 'ios')) {
            return 'images/placeholders/mobile-iphone.svg';
        }

        if (str_contains($text, 'usb') || str_contains($text, 'sd') || str_contains($text, 'flash') || str_contains($text, 'card') || str_contains($text, 'pen-drive') || str_contains($text, 'thumb drive')) {
            return 'images/placeholders/usb-sd.svg';
        }

        if (str_contains($text, 'ransomware') || str_contains($text, 'decrypt') || str_contains($text, 'malware') || str_contains($text, 'encrypted')) {
            return 'images/placeholders/ransomware.svg';
        }

        if (str_contains($text, 'mac') || str_contains($text, 'laptop') || str_contains($text, 'apple') || str_contains($text, 'windows') || str_contains($text, 'pc') || str_contains($text, 'computer')) {
            return 'images/placeholders/macbook-laptop.svg';
        }

        if (str_contains($text, 'cloud') || str_contains($text, 'whatsapp') || str_contains($text, 'backup') || str_contains($text, 'database') || str_contains($text, 'sql')) {
            return 'images/placeholders/cloud-backup.svg';
        }

        if (str_contains($text, 'cleanroom') || str_contains($text, 'lab') || str_contains($text, 'forensic') || str_contains($text, 'engineering')) {
            return 'images/placeholders/cleanroom-lab.svg';
        }

        return 'images/placeholders/blog-default.svg';
    }

    /**
     * Resolve a context-aware topic placeholder full URL.
     */
    public function getTopicPlaceholderUrl(): string
    {
        return asset($this->getTopicPlaceholderPath()).'?v=2';
    }

    /**
     * Estimated reading time in minutes.
     */
    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content ?? ''));

        return max(1, (int) ceil($words / 200));
    }

    /**
     * Clean SEO title without phone numbers or duplicate brand strings.
     */
    public function getCleanSeoTitleAttribute(): string
    {
        $title = $this->meta_title ?: $this->title;

        $cleaned = preg_replace('/\s*-\s*\+?971[\s0-9]+/i', '', $title);
        $cleaned = preg_replace('/\s*-\s*05[0-9]{8}/i', '', $cleaned);
        $cleaned = preg_replace('/\s*-\s*Call\s+Now.*$/i', '', $cleaned);
        $cleaned = preg_replace('/\s*-\s*Recovery Data Dubai/i', '', $cleaned);
        $cleaned = preg_replace('/\s*\|\s*Recovery Data Dubai/i', '', $cleaned);
        $cleaned = trim($cleaned, " -\t\n\r\0\x0B");

        return $cleaned ?: $this->title;
    }

    /**
     * SEO Title fallback.
     */
    public function getSeoTitleAttribute(): string
    {
        return $this->clean_seo_title;
    }

    /**
     * Safe self-referencing canonical URL.
     */
    public function getCanonicalUrlAttribute(?string $value): string
    {
        if ($value && str_starts_with($value, 'https://www.recoverydata.ae/post/')) {
            return $value;
        }

        if (app()->environment('production') || str_contains(config('app.url'), 'recoverydata.ae')) {
            return 'https://www.recoverydata.ae/post/'.$this->slug;
        }

        return route('post.show', $this->slug);
    }

    /**
     * SEO Description fallback.
     */
    public function getSeoDescriptionAttribute(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        if ($this->excerpt) {
            return Str::limit(strip_tags($this->excerpt), 160);
        }

        return Str::limit(strip_tags($this->content ?? ''), 160);
    }

    public function getSeoImageAttribute(): ?string
    {
        if ($this->og_image && ! str_contains($this->og_image, 'wp-content/uploads')) {
            return $this->og_image;
        }

        return $this->featured_image_url;
    }

    /**
     * Automatically generate unique slug.
     */
    public static function createUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Check if custom schema markup explicitly defines an FAQPage schema.
     */
    public function getHasCustomFaqSchemaAttribute(): bool
    {
        return ! empty($this->schema_markup) && str_contains($this->schema_markup, 'FAQPage');
    }

    /**
     * Automatically extract FAQPage schema from article content if Q&A patterns are present.
     */
    public function extractFaqSchema(): ?array
    {
        if (empty($this->content) || $this->has_custom_faq_schema) {
            return null;
        }

        $faqs = [];
        // Match <h3> or <h4> or <h2> ending in ?, followed by a direct <p> answer
        $pattern = '/<(h[2-4])[^>]*>([^<]*?\?[^<]*?)<\/\1>\s*<p[^>]*>(.*?)<\/p>/is';
        if (preg_match_all($pattern, $this->content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $q = trim(strip_tags($m[2]));
                $a = trim(strip_tags($m[3]));

                // Clean entities and spaces
                $q = html_entity_decode($q, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $a = html_entity_decode($a, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                if (strlen($q) >= 10 && strlen($q) <= 120 && strlen($a) >= 20 && str_ends_with($q, '?')) {
                    $faqs[] = [
                        '@type' => 'Question',
                        'name' => $q,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => Str::limit($a, 350),
                        ],
                    ];
                }

                if (count($faqs) >= 10) {
                    break;
                }
            }
        }

        if (count($faqs) >= 2) {
            return [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqs,
            ];
        }

        return null;
    }
}
