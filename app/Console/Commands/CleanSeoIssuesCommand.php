<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\RedirectRule;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanSeoIssuesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:clean-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean all SEO audit issues in the database: canonicals, Lifeguard brand text, phone numbers in titles, duplicate HDD posts, and junk tags';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting SEO Database Cleanup...');

        $this->fixLifeguardOccurrences();
        $this->fixCanonicalUrls();
        $this->fixNonWwwContentUrls();
        $this->fixPhoneNumbersInTitles();
        $this->fixWaterDamageDescriptionOnPowerOutagePost();
        $this->consolidateDuplicateHddPosts();
        $this->cleanJunkTags();
        $this->reorganizeCategories();

        $this->newLine();
        $this->info('SEO Database Cleanup completed successfully!');

        return self::SUCCESS;
    }

    /**
     * S03: Replace "Lifeguard" with "Recovery Data Dubai".
     */
    protected function fixLifeguardOccurrences(): void
    {
        $this->info('Fixing Lifeguard references in meta_description and content...');

        $updatedMeta = DB::table('posts')
            ->where('meta_description', 'like', '%Lifeguard%')
            ->get(['id', 'meta_description']);

        foreach ($updatedMeta as $post) {
            $cleaned = str_replace(
                ['by Lifeguard Data Recovery', 'Lifeguard Data Recovery', 'Lifeguard'],
                ['by Recovery Data Dubai', 'Recovery Data Dubai', 'Recovery Data Dubai'],
                $post->meta_description
            );

            DB::table('posts')->where('id', $post->id)->update([
                'meta_description' => $cleaned,
            ]);
        }

        $this->info("  Updated {$updatedMeta->count()} posts with Lifeguard in meta_description.");

        $updatedContent = DB::table('posts')
            ->where('content', 'like', '%Lifeguard%')
            ->get(['id', 'content']);

        foreach ($updatedContent as $post) {
            $cleaned = str_replace(
                ['Lifeguard Data Recovery', 'Lifeguard'],
                ['Recovery Data Dubai', 'Recovery Data Dubai'],
                $post->content
            );

            DB::table('posts')->where('id', $post->id)->update([
                'content' => $cleaned,
            ]);
        }

        $this->info("  Updated {$updatedContent->count()} posts with Lifeguard in content.");
    }

    /**
     * S01: Fix canonical_url to self-referencing https://www.recoverydata.ae/post/{slug}.
     */
    protected function fixCanonicalUrls(): void
    {
        $this->info('Updating canonical URLs to self-referencing https://www.recoverydata.ae/post/{slug}...');

        $posts = Post::all(['id', 'slug']);
        $count = 0;

        foreach ($posts as $post) {
            $canonical = "https://www.recoverydata.ae/post/{$post->slug}";
            DB::table('posts')->where('id', $post->id)->update([
                'canonical_url' => $canonical,
            ]);
            $count++;
        }

        $this->info("  Updated {$count} posts canonical_url to self-referencing www URL.");
    }

    /**
     * S02: Update in-post links from https://recoverydata.ae to https://www.recoverydata.ae.
     */
    protected function fixNonWwwContentUrls(): void
    {
        $this->info('Fixing non-www links and wp-content paths in post content...');

        $posts = DB::table('posts')
            ->where('content', 'like', '%https://recoverydata.ae%')
            ->orWhere('content', 'like', '%wp-content/uploads%')
            ->get(['id', 'content']);

        $count = 0;
        foreach ($posts as $post) {
            $content = $post->content;

            // Replace wp-content/uploads/
            $content = str_replace(
                ['https://recoverydata.ae/wp-content/uploads/', 'http://recoverydata.ae/wp-content/uploads/', '/wp-content/uploads/'],
                'https://www.recoverydata.ae/storage/',
                $content
            );

            // Replace non-www links
            $content = str_replace(
                'https://recoverydata.ae',
                'https://www.recoverydata.ae',
                $content
            );

            DB::table('posts')->where('id', $post->id)->update([
                'content' => $content,
            ]);
            $count++;
        }

        $this->info("  Cleaned non-www and wp-content URLs in {$count} posts.");
    }

    /**
     * S09: Remove phone numbers from meta_title and excerpts.
     */
    protected function fixPhoneNumbersInTitles(): void
    {
        $this->info('Cleaning phone numbers and duplicate brands from post titles...');

        $posts = DB::table('posts')
            ->whereNotNull('meta_title')
            ->get(['id', 'title', 'meta_title']);

        $count = 0;
        foreach ($posts as $post) {
            $title = $post->meta_title;

            // Strip phone number patterns
            $cleaned = preg_replace('/\s*-\s*\+?971[\s0-9]+/i', '', $title);
            $cleaned = preg_replace('/\s*-\s*05[0-9]{8}/i', '', $cleaned);
            $cleaned = preg_replace('/\s*-\s*Call\s+Now.*$/i', '', $cleaned);
            $cleaned = preg_replace('/\s*\|\s*Recovery Data Dubai\s*-\s*Recovery Data Dubai/i', ' | Recovery Data Dubai', $cleaned);
            $cleaned = trim($cleaned, " -\t\n\r\0\x0B");

            if ($cleaned !== $title) {
                DB::table('posts')->where('id', $post->id)->update([
                    'meta_title' => $cleaned ?: $post->title,
                ]);
                $count++;
            }
        }

        $this->info("  Cleaned phone numbers from {$count} post meta_titles.");
    }

    /**
     * S08: Fix water damage description mistakenly copied to power outage post (ID 8).
     */
    protected function fixWaterDamageDescriptionOnPowerOutagePost(): void
    {
        $this->info('Fixing water damage description on power outage post (ID 8)...');

        $post = Post::where('slug', 'hdd-damaged-after-power-outage-dubai')->first();
        if ($post) {
            $post->update([
                'meta_description' => 'Hard drive damaged after power outage in Dubai? Emergency cleanroom recovery for burnt PCBs, spindle seizure, and firmware faults. Call Recovery Data Dubai.',
            ]);
            $this->info('  Fixed meta_description on hdd-damaged-after-power-outage-dubai.');
        }
    }

    /**
     * S04: Consolidate duplicate HDD posts with 301 redirects.
     */
    protected function consolidateDuplicateHddPosts(): void
    {
        $this->info('Consolidating duplicate HDD cannibalization posts (S04)...');

        $duplicatePairs = [
            [
                'loser' => 'hdd-damaged-after-power-outage-dubai',
                'winner' => 'hard-drive-damaged-after-power-outage-dubai',
                'description' => '301 redirect for duplicate power outage post (S04)',
            ],
            [
                'loser' => 'hdd-power-surge-recovery-dubai',
                'winner' => 'hard-drive-power-surge-data-recovery-dubai',
                'description' => '301 redirect for duplicate power surge post (S04)',
            ],
            [
                'loser' => 'hdd-file-system-corruption-recovery-dubai',
                'winner' => 'hard-drive-file-system-corruption-recovery-dubai',
                'description' => '301 redirect for duplicate file system corruption post (S04)',
            ],
        ];

        foreach ($duplicatePairs as $pair) {
            $loserPost = Post::where('slug', $pair['loser'])->first();
            $winnerPost = Post::where('slug', $pair['winner'])->first();

            if ($loserPost && $winnerPost) {
                // Set loser to noindex
                $loserPost->update([
                    'is_indexable' => false,
                    'status' => 'archived',
                ]);

                // Register 301 redirect in RedirectRule
                RedirectRule::updateOrCreate(
                    ['source_url' => "/post/{$pair['loser']}"],
                    [
                        'target_url' => "/post/{$pair['winner']}",
                        'status_code' => 301,
                        'match_type' => 'exact',
                        'query_param_option' => 'pass',
                        'group' => 'SEO Cannibalization',
                        'description' => $pair['description'],
                        'is_active' => true,
                    ]
                );

                // Also redirect the old WordPress root permalink
                RedirectRule::updateOrCreate(
                    ['source_url' => "/{$pair['loser']}"],
                    [
                        'target_url' => "/post/{$pair['winner']}",
                        'status_code' => 301,
                        'match_type' => 'exact',
                        'query_param_option' => 'pass',
                        'group' => 'SEO Cannibalization',
                        'description' => "Old WP slug {$pair['description']}",
                        'is_active' => true,
                    ]
                );

                $this->info("  Consolidated /post/{$pair['loser']} -> /post/{$pair['winner']} (301)");
            }
        }
    }

    /**
     * S31: Clean junk and typo tags, reassign posts, and register 301 redirects.
     */
    protected function cleanJunkTags(): void
    {
        $this->info('Cleaning junk tags and registering 301 redirects (S31)...');

        $usbTag = Tag::firstOrCreate(['slug' => 'usb-pen-drive-recovery'], ['name' => 'USB / Pen Drive Recovery']);
        $nasTag = Tag::firstOrCreate(['slug' => 'nas-recovery'], ['name' => 'NAS Recovery']);

        $junkTagMappings = [
            'sb-pen-drive-recovery' => $usbTag->id,
            'vusb-pen-drive-recovery' => $usbTag->id,
            'vnas-recovery' => $nasTag->id,
        ];

        foreach ($junkTagMappings as $slug => $targetTagId) {
            $junkTag = Tag::where('slug', $slug)->first();
            if ($junkTag) {
                foreach ($junkTag->posts as $post) {
                    $post->tags()->syncWithoutDetaching([$targetTagId]);
                }
                $junkTag->posts()->detach();
                $junkTag->delete();

                // Add 301 redirect to target tag
                $targetTag = Tag::find($targetTagId);
                RedirectRule::updateOrCreate(
                    ['source_url' => "/tag/{$slug}"],
                    [
                        'target_url' => "/tag/{$targetTag->slug}",
                        'status_code' => 301,
                        'match_type' => 'exact',
                        'query_param_option' => 'ignore',
                        'group' => 'Tag Normalization',
                        'description' => "Redirect junk typo tag /tag/{$slug} to /tag/{$targetTag->slug}",
                        'is_active' => true,
                    ]
                );

                $this->info("  Deleted junk tag /tag/{$slug} and redirected to /tag/{$targetTag->slug}");
            }
        }

        // Mega-slug tag
        $megaSlug = 'data-recovery-ssd-recovery-nvme-ssd-recovery-raid-recovery-nas-recovery-cctv-recovery-usb-pen-drive-recovery-iphone-recovery-ransomware-recovery';
        $megaTag = Tag::where('slug', $megaSlug)->first();
        if ($megaTag) {
            $megaTag->posts()->detach();
            $megaTag->delete();

            RedirectRule::updateOrCreate(
                ['source_url' => "/tag/{$megaSlug}"],
                [
                    'target_url' => '/blog',
                    'status_code' => 301,
                    'match_type' => 'exact',
                    'query_param_option' => 'ignore',
                    'group' => 'Tag Normalization',
                    'description' => 'Redirect mega junk tag to /blog',
                    'is_active' => true,
                ]
            );

            $this->info("  Deleted mega junk tag /tag/{$megaSlug} and redirected to /blog");
        }
    }

    /**
     * S33: Reorganize categories and fix uncategorized posts.
     */
    protected function reorganizeCategories(): void
    {
        $this->info('Reorganizing categories (S33)...');

        $categories = [
            'hard-drive-recovery' => ['name' => 'Hard Drive Recovery', 'description' => 'Expert guides on mechanical HDD repair, head crashes, PCB replacement, and firmware recovery.'],
            'ssd-recovery' => ['name' => 'SSD & NVMe Recovery', 'description' => 'Specialized articles on NAND flash memory, controller failure, raw partition recovery, and NVMe SSD repair.'],
            'raid-recovery' => ['name' => 'RAID & Server Recovery', 'description' => 'Enterprise storage recovery guides for RAID 0, 1, 5, 6, 10 arrays, NAS servers, and virtualized systems.'],
            'nas-recovery' => ['name' => 'NAS Data Recovery', 'description' => 'Network attached storage recovery solutions for Synology, QNAP, Buffalo, and enterprise NAS enclosures.'],
            'cctv-recovery' => ['name' => 'CCTV Footage Recovery', 'description' => 'Forensic recovery of deleted, overwritten, or corrupted surveillance video from DVR and NVR systems.'],
            'usb-recovery' => ['name' => 'USB & Flash Recovery', 'description' => 'Techniques for recovering files from broken USB pen drives, SD cards, and microSD storage devices.'],
            'mobile-recovery' => ['name' => 'Mobile & iPhone Recovery', 'description' => 'Advanced forensic recovery for water-damaged, boot-looping, and broken iPhones and Android devices.'],
            'ransomware-recovery' => ['name' => 'Ransomware Recovery', 'description' => 'Incident response and data restoration procedures for systems affected by ransomware encryption.'],
        ];

        $categoryModels = [];
        foreach ($categories as $slug => $data) {
            $categoryModels[$slug] = Category::firstOrCreate(
                ['slug' => $slug],
                ['name' => $data['name'], 'description' => $data['description']]
            );
        }

        // Reassign the 5 uncategorized posts
        $uncategorized = Category::where('slug', 'uncategorized')->first();
        if ($uncategorized) {
            foreach ($uncategorized->posts as $post) {
                $slug = $post->slug;
                if (str_contains($slug, 'cctv')) {
                    $post->categories()->syncWithoutDetaching([$categoryModels['cctv-recovery']->id]);
                } elseif (str_contains($slug, 'nvme') || str_contains($slug, 'ssd')) {
                    $post->categories()->syncWithoutDetaching([$categoryModels['ssd-recovery']->id]);
                } elseif (str_contains($slug, 'raid') || str_contains($slug, 'server')) {
                    $post->categories()->syncWithoutDetaching([$categoryModels['raid-recovery']->id]);
                } else {
                    $post->categories()->syncWithoutDetaching([$categoryModels['hard-drive-recovery']->id]);
                }
                $post->categories()->detach($uncategorized->id);
            }

            // Redirect /category/uncategorized to /blog
            RedirectRule::updateOrCreate(
                ['source_url' => '/category/uncategorized'],
                [
                    'target_url' => '/blog',
                    'status_code' => 301,
                    'match_type' => 'exact',
                    'query_param_option' => 'ignore',
                    'group' => 'Category Normalization',
                    'description' => 'Redirect empty uncategorized category to /blog',
                    'is_active' => true,
                ]
            );

            $uncategorized->delete();
            $this->info('  Reassigned all uncategorized posts and deleted uncategorized category.');
        }

        // Assign all posts in generic "Blogs" category to their specific topical categories based on title/slug
        $posts = Post::all();
        $assignedCount = 0;
        foreach ($posts as $post) {
            $haystack = strtolower($post->slug.' '.$post->title);

            $targetCat = null;
            if (str_contains($haystack, 'nvme') || str_contains($haystack, 'm.2') || str_contains($haystack, 'ssd') || str_contains($haystack, 'solid-state')) {
                $targetCat = $categoryModels['ssd-recovery'];
            } elseif (str_contains($haystack, 'raid') || str_contains($haystack, 'server') || str_contains($haystack, 'array')) {
                $targetCat = $categoryModels['raid-recovery'];
            } elseif (str_contains($haystack, 'nas') || str_contains($haystack, 'synology') || str_contains($haystack, 'qnap')) {
                $targetCat = $categoryModels['nas-recovery'];
            } elseif (str_contains($haystack, 'cctv') || str_contains($haystack, 'dvr') || str_contains($haystack, 'nvr') || str_contains($haystack, 'footage') || str_contains($haystack, 'surveillance')) {
                $targetCat = $categoryModels['cctv-recovery'];
            } elseif (str_contains($haystack, 'usb') || str_contains($haystack, 'pen-drive') || str_contains($haystack, 'sd-card') || str_contains($haystack, 'flash')) {
                $targetCat = $categoryModels['usb-recovery'];
            } elseif (str_contains($haystack, 'iphone') || str_contains($haystack, 'phone') || str_contains($haystack, 'mobile') || str_contains($haystack, 'android')) {
                $targetCat = $categoryModels['mobile-recovery'];
            } elseif (str_contains($haystack, 'ransomware') || str_contains($haystack, 'malware') || str_contains($haystack, 'encrypted') || str_contains($haystack, 'decrypt')) {
                $targetCat = $categoryModels['ransomware-recovery'];
            } else {
                $targetCat = $categoryModels['hard-drive-recovery'];
            }

            if ($targetCat) {
                $post->categories()->syncWithoutDetaching([$targetCat->id]);
                $assignedCount++;
            }
        }

        $this->info("  Topical categories created and categorized {$assignedCount} posts.");
    }
}
