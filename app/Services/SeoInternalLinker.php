<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class SeoInternalLinker
{
    /**
     * Map a blog post to its most relevant commercial recovery service.
     */
    public function matchServiceForPost(Post $post): ?Service
    {
        $services = Service::published()->get();

        if ($services->isEmpty()) {
            return null;
        }

        $categoriesText = $post->relationLoaded('categories')
            ? $post->categories->pluck('name')->join(' ')
            : $post->categories()->pluck('name')->join(' ');

        $haystack = strtolower(
            $post->title.' '.
            $post->slug.' '.
            $categoriesText.' '.
            Str::limit(strip_tags((string) $post->content), 300)
        );

        if (str_contains($haystack, 'nvme') || str_contains($haystack, 'm.2')) {
            $service = $services->firstWhere('slug', 'nvme-ssd-recovery-dubai');
            if ($service) {
                return $service;
            }
        }

        if (str_contains($haystack, 'ssd') || str_contains($haystack, 'solid state')) {
            $service = $services->firstWhere('slug', 'ssd-recovery-dubai');
            if ($service) {
                return $service;
            }
        }

        if (str_contains($haystack, 'raid') || str_contains($haystack, 'server')) {
            $service = $services->firstWhere('slug', 'raid-recovery-dubai');
            if ($service) {
                return $service;
            }
        }

        if (str_contains($haystack, 'nas') || str_contains($haystack, 'synology') || str_contains($haystack, 'qnap')) {
            $service = $services->firstWhere('slug', 'nas-recovery-dubai');
            if ($service) {
                return $service;
            }
        }

        if (str_contains($haystack, 'cctv') || str_contains($haystack, 'dvr') || str_contains($haystack, 'nvr') || str_contains($haystack, 'footage') || str_contains($haystack, 'surveillance')) {
            $service = $services->firstWhere('slug', 'cctv-footage-recovery-dubai');
            if ($service) {
                return $service;
            }
        }

        if (str_contains($haystack, 'usb') || str_contains($haystack, 'flash drive') || str_contains($haystack, 'thumb') || str_contains($haystack, 'pen drive') || str_contains($haystack, 'sd card') || str_contains($haystack, 'microsd')) {
            $service = $services->firstWhere('slug', 'usb-data-recovery-dubai');
            if ($service) {
                return $service;
            }
        }

        if (str_contains($haystack, 'iphone') || str_contains($haystack, 'mobile') || str_contains($haystack, 'android') || str_contains($haystack, 'smartphone') || str_contains($haystack, 'phone')) {
            $service = $services->firstWhere('slug', 'iphone-data-recovery-dubai');
            if ($service) {
                return $service;
            }
        }

        if (str_contains($haystack, 'ransomware') || str_contains($haystack, 'malware') || str_contains($haystack, 'encrypted') || str_contains($haystack, 'decrypt')) {
            $service = $services->firstWhere('slug', 'ransomware-data-recovery-dubai');
            if ($service) {
                return $service;
            }
        }

        if (str_contains($haystack, 'hard drive') || str_contains($haystack, 'hdd') || str_contains($haystack, 'hard disk') || str_contains($haystack, 'clicking')) {
            $service = $services->firstWhere('slug', 'hard-drive-recovery');
            if ($service) {
                return $service;
            }
        }

        return $services->firstWhere('slug', 'hard-drive-recovery') ?? $services->first();
    }

    /**
     * Get related case studies and technical articles for a specific recovery service.
     *
     * @return Collection<int, Post>
     */
    public function getRelatedPostsForService(Service $service, int $limit = 3): Collection
    {
        $keywords = match ($service->slug) {
            'nvme-ssd-recovery-dubai' => ['nvme', 'm.2', 'pcie'],
            'ssd-recovery-dubai' => ['ssd', 'solid state', 'nand'],
            'raid-recovery-dubai' => ['raid', 'server', 'array'],
            'nas-recovery-dubai' => ['nas', 'synology', 'qnap'],
            'cctv-footage-recovery-dubai' => ['cctv', 'dvr', 'nvr', 'footage'],
            'usb-data-recovery-dubai' => ['usb', 'flash', 'pen drive', 'sd card', 'microsd'],
            'iphone-data-recovery-dubai' => ['iphone', 'mobile', 'smartphone', 'phone', 'android'],
            'ransomware-data-recovery-dubai' => ['ransomware', 'malware', 'decrypt', 'encrypted'],
            default => ['hard drive', 'hdd', 'hard disk'],
        };

        $query = Post::published()
            ->with(['user', 'categories'])
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $kw) {
                    $q->orWhere('title', 'like', "%{$kw}%");
                }
            })
            ->latest('published_at');

        $posts = $query->take($limit)->get();

        if ($posts->count() < $limit) {
            $missing = $limit - $posts->count();
            $fallbackPosts = Post::published()
                ->whereNotIn('id', $posts->pluck('id'))
                ->with(['user', 'categories'])
                ->latest('published_at')
                ->take($missing)
                ->get();

            $posts = $posts->merge($fallbackPosts);
        }

        return $posts;
    }
}
