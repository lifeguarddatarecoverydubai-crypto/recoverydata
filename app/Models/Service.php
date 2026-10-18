<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'tagline',
        'hero_badge',
        'icon',
        'summary',
        'content',
        'features',
        'benefits',
        'benchmarks',
        'featured_image',
        'status',
        'order',
        'is_featured',
        'focus_keyword',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'is_indexable',
        'seo_score',
        'readability_score',
        'schema_markup',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'benefits' => 'array',
            'benchmarks' => 'array',
            'is_featured' => 'boolean',
            'is_indexable' => 'boolean',
            'order' => 'integer',
            'seo_score' => 'integer',
            'readability_score' => 'integer',
        ];
    }

    /**
     * Get effective hero badge with standard default.
     */
    public function getEffectiveHeroBadgeAttribute(): string
    {
        return $this->hero_badge ?: 'Dubai Cleanroom Laboratory • ISO Class 100 Certified';
    }

    /**
     * Get effective benchmarks array with lab defaults.
     */
    public function getEffectiveBenchmarksAttribute(): array
    {
        $defaults = [
            'standard' => 'ISO Class 100 / FED STD 209E',
            'evaluation' => '100% Free Diagnostic & Quote',
            'guarantee' => 'No Data – No Charge Guarantee',
            'turnaround' => 'Same-Day Emergency or 24-48h Standard',
            'courier' => 'Free Courier Across All 7 Emirates',
        ];

        return array_merge($defaults, is_array($this->benchmarks) ? $this->benchmarks : []);
    }

    /**
     * Scope for published services.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for featured services.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope ordered by custom order index.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Featured image URL accessor.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if ($this->featured_image) {
            $feat = trim($this->featured_image);

            if (str_starts_with($feat, 'http://') || str_starts_with($feat, 'https://')) {
                return $feat;
            }

            if (str_starts_with($feat, 'images/')) {
                if (file_exists(public_path($feat))) {
                    return asset($feat).(str_ends_with($feat, '.svg') ? '?v=2' : '');
                }
            } else {
                $cleanPath = ltrim($feat, '/');
                if (file_exists(public_path('storage/'.$cleanPath)) || file_exists(storage_path('app/public/'.$cleanPath))) {
                    return asset('storage/'.$cleanPath);
                }
            }
        }

        if ($this->slug && file_exists(public_path('images/services/'.$this->slug.'.svg'))) {
            return asset('images/services/'.$this->slug.'.svg?v=2');
        }

        return asset('images/placeholders/default-recovery.svg?v=2');
    }

    /**
     * SEO Title fallback.
     */
    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: ($this->title.' - Recovery Data Dubai');
    }

    /**
     * SEO Description fallback.
     */
    public function getSeoDescriptionAttribute(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        if ($this->summary) {
            return Str::limit(strip_tags($this->summary), 160);
        }

        return Str::limit(strip_tags($this->content ?? ''), 160);
    }

    /**
     * SEO Image fallback.
     */
    public function getSeoImageAttribute(): ?string
    {
        return $this->og_image ?: $this->featured_image_url;
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
     * Extract structured FAQs from service content for Schema.org JSON-LD.
     *
     * @return array<int, array{question: string, answer: string}>
     */
    public function getFaqsAttribute(): array
    {
        if (empty($this->content)) {
            return [];
        }

        preg_match_all('/<h[34][^>]*>([^<]*\?[^<]*)<\/h[34]>\s*<p[^>]*>(.*?)<\/p>/is', $this->content, $matches);

        $faqs = [];
        if (! empty($matches[1])) {
            foreach ($matches[1] as $index => $question) {
                $q = trim(strip_tags($question));
                $a = trim(strip_tags($matches[2][$index] ?? ''));
                if ($q !== '' && $a !== '') {
                    $faqs[] = [
                        'question' => $q,
                        'answer' => $a,
                    ];
                }
            }
        }

        return $faqs;
    }
}
