<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'template',
        'status',
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
     * Scope for published pages.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
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
                if (preg_match('#^wp-content/uploads/(.*)$#i', $cleanPath, $matches)) {
                    $cleanPath = $matches[1];
                }

                if (file_exists(public_path('storage/'.$cleanPath)) || file_exists(storage_path('app/public/'.$cleanPath))) {
                    return asset('storage/'.$cleanPath);
                }
            }
        }

        if ($this->slug && file_exists(public_path('images/pages/'.$this->slug.'.svg'))) {
            return asset('images/pages/'.$this->slug.'.svg?v=2');
        }

        return $this->getTopicPlaceholderUrl();
    }

    /**
     * Resolve a context-aware topic placeholder for pages.
     */
    public function getTopicPlaceholderPath(): string
    {
        $text = strtolower(($this->title ?? '').' '.($this->slug ?? ''));

        if (str_contains($text, 'ssd') || str_contains($text, 'nvme')) {
            return 'images/placeholders/ssd-nvme.svg';
        }

        if (str_contains($text, 'hard-disk') || str_contains($text, 'hard-drive') || str_contains($text, 'hdd')) {
            return 'images/placeholders/hard-drive.svg';
        }

        if (str_contains($text, 'raid') || str_contains($text, 'server')) {
            return 'images/placeholders/raid-server.svg';
        }

        if (str_contains($text, 'nas')) {
            return 'images/placeholders/nas-storage.svg';
        }

        if (str_contains($text, 'cctv') || str_contains($text, 'footage')) {
            return 'images/placeholders/cctv-dvr.svg';
        }

        if (str_contains($text, 'iphone') || str_contains($text, 'android') || str_contains($text, 'mobile')) {
            return 'images/placeholders/mobile-iphone.svg';
        }

        if (str_contains($text, 'usb') || str_contains($text, 'sd-card') || str_contains($text, 'pen-drive')) {
            return 'images/placeholders/usb-sd.svg';
        }

        if (str_contains($text, 'ransomware')) {
            return 'images/placeholders/ransomware.svg';
        }

        if (str_contains($text, 'mac') || str_contains($text, 'laptop') || str_contains($text, 'windows')) {
            return 'images/placeholders/macbook-laptop.svg';
        }

        if (str_contains($text, 'cloud') || str_contains($text, 'whatsapp')) {
            return 'images/placeholders/cloud-backup.svg';
        }

        if (str_contains($text, 'emergency') || str_contains($text, 'cleanroom')) {
            return 'images/placeholders/cleanroom-lab.svg';
        }

        if (str_contains($text, 'about')) {
            return 'images/placeholders/about-banner.svg';
        }

        if (str_contains($text, 'contact')) {
            return 'images/placeholders/contact-banner.svg';
        }

        if (str_contains($text, 'service')) {
            return 'images/placeholders/services-banner.svg';
        }

        return 'images/placeholders/default-recovery.svg';
    }

    public function getTopicPlaceholderUrl(): string
    {
        return asset($this->getTopicPlaceholderPath()).'?v=2';
    }

    /**
     * SEO Title fallback.
     */
    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->title;
    }

    /**
     * SEO Description fallback.
     */
    public function getSeoDescriptionAttribute(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
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
}
