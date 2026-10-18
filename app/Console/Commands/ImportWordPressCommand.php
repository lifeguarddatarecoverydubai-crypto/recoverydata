<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Media;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportWordPressCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:import-wordpress
                            {--url=https://www.recoverydata.ae : The WordPress website URL}
                            {--truncate : Truncate posts, pages, categories, tags before import}
                            {--download-images : Download featured images to local storage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import posts, categories, tags, pages, media, and navigation from a WordPress site REST API';

    /**
     * Cache for user ID mapping: WP User ID => Laravel User ID.
     */
    protected array $userMap = [];

    /**
     * Cache for category ID mapping: WP Cat ID => Laravel Cat ID.
     */
    protected array $categoryMap = [];

    /**
     * Cache for tag ID mapping: WP Tag ID => Laravel Tag ID.
     */
    protected array $tagMap = [];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $baseUrl = rtrim($this->option('url'), '/');
        $this->info('=================================================');
        $this->info("  WordPress Importer for {$baseUrl}");
        $this->info("=================================================\n");

        if ($this->option('truncate')) {
            $this->warn('Truncating existing posts, pages, categories, and tags...');
            Post::query()->delete();
            Page::query()->delete();
            Category::query()->delete();
            Tag::query()->delete();
            MenuItem::query()->delete();
        }

        // 1. Import Users
        $this->importUsers($baseUrl);

        // 2. Import Categories
        $this->importCategories($baseUrl);

        // 3. Import Tags
        $this->importTags($baseUrl);

        // 4. Import Pages
        $this->importPages($baseUrl);

        // 5. Import Posts
        $this->importPosts($baseUrl);

        // 6. Setup Services
        $this->setupServices();

        // 7. Setup Navigation Menu
        $this->setupNavigationMenu();

        $this->info("\n=================================================");
        $this->info('  WordPress Migration Completed Successfully!   ');
        $this->info('=================================================');
        $this->table(
            ['Entity', 'Count in Laravel'],
            [
                ['Posts', Post::count()],
                ['Published Posts', Post::where('status', 'published')->count()],
                ['Categories', Category::count()],
                ['Tags', Tag::count()],
                ['Pages', Page::count()],
                ['Services', Service::count()],
                ['Users', User::count()],
                ['Menu Items', MenuItem::count()],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Helper to fetch JSON from WP REST API with retry and timeout handling.
     */
    protected function fetchApi(string $url, array $params = []): ?array
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) CMS-Migrator/1.0',
            ])->timeout(30)->withoutVerifying()->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            $this->warn("HTTP status {$response->status()} for {$url}");

            return null;
        } catch (\Throwable $e) {
            $this->error("API request failed: {$e->getMessage()} on {$url}");

            return null;
        }
    }

    /**
     * Import users / authors from WordPress.
     */
    protected function importUsers(string $baseUrl): void
    {
        $this->info('--> Fetching authors/users...');
        $url = "{$baseUrl}/wp-json/wp/v2/users";
        $wpUsers = $this->fetchApi($url);

        // Ensure at least one admin exists
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Recovery Data Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        $defaultUserId = $adminUser->id;

        if (! empty($wpUsers) && is_array($wpUsers)) {
            foreach ($wpUsers as $u) {
                $slug = $u['slug'] ?? 'user-'.$u['id'];
                $name = $u['name'] ?? 'Author '.$u['id'];
                $email = $slug === 'admin' ? 'admin@example.com' : "{$slug}@recoverydata.ae";

                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'password' => Hash::make('password'),
                        'role' => $slug === 'admin' ? 'admin' : 'editor',
                        'is_active' => true,
                    ]
                );

                $this->userMap[$u['id']] = $user->id;
                $this->line("  [User] Imported '{$name}' ({$email}) -> ID: {$user->id}");
            }
        }

        $this->userMap[0] = $defaultUserId;
    }

    /**
     * Import all categories.
     */
    protected function importCategories(string $baseUrl): void
    {
        $this->info('--> Fetching categories...');
        $page = 1;
        $totalCats = 0;

        while (true) {
            $items = $this->fetchApi("{$baseUrl}/wp-json/wp/v2/categories", [
                'per_page' => 100,
                'page' => $page,
            ]);

            if (empty($items) || ! is_array($items)) {
                break;
            }

            foreach ($items as $c) {
                $name = html_entity_decode($c['name'] ?? 'Category '.$c['id']);
                $slug = Str::slug($c['slug'] ?? $name);

                $category = Category::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => Str::limit($name, 250, ''),
                        'description' => $c['description'] ?: null,
                    ]
                );

                $this->categoryMap[$c['id']] = $category->id;
                $totalCats++;
            }

            if (count($items) < 100) {
                break;
            }
            $page++;
        }

        $this->info("  Total Categories Imported/Synced: {$totalCats}");
    }

    /**
     * Import all tags.
     */
    protected function importTags(string $baseUrl): void
    {
        $this->info('--> Fetching tags...');
        $page = 1;
        $totalTags = 0;

        while (true) {
            $items = $this->fetchApi("{$baseUrl}/wp-json/wp/v2/tags", [
                'per_page' => 100,
                'page' => $page,
            ]);

            if (empty($items) || ! is_array($items)) {
                break;
            }

            foreach ($items as $t) {
                $rawName = html_entity_decode($t['name'] ?? 'Tag '.$t['id']);
                $name = Str::limit(trim($rawName), 250, '');
                $slug = Str::limit(trim($t['slug'] ?? Str::slug($name)), 250, '');

                $tag = Tag::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'description' => $t['description'] ?: null,
                    ]
                );

                $this->tagMap[$t['id']] = $tag->id;
                $totalTags++;
            }

            if (count($items) < 100) {
                break;
            }
            $page++;
        }

        $this->info("  Total Tags Imported/Synced: {$totalTags}");
    }

    /**
     * Import all pages.
     */
    protected function importPages(string $baseUrl): void
    {
        $this->info('--> Fetching pages...');
        $page = 1;
        $totalPages = 0;
        $defaultUserId = array_values($this->userMap)[0] ?? 1;

        while (true) {
            $items = $this->fetchApi("{$baseUrl}/wp-json/wp/v2/pages", [
                'per_page' => 100,
                'page' => $page,
                '_embed' => 1,
            ]);

            if (empty($items) || ! is_array($items)) {
                break;
            }

            foreach ($items as $p) {
                $title = html_entity_decode($p['title']['rendered'] ?? 'Page '.$p['id']);
                $slug = $p['slug'] ?? Str::slug($title);
                $content = $p['content']['rendered'] ?? '';
                $status = ($p['status'] ?? 'publish') === 'publish' ? 'published' : 'draft';

                // Featured image
                $featuredImage = null;
                if (! empty($p['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                    $featuredImage = $p['_embedded']['wp:featuredmedia'][0]['source_url'];
                } elseif (! empty($p['yoast_head_json']['og_image'][0]['url'])) {
                    $featuredImage = $p['yoast_head_json']['og_image'][0]['url'];
                }

                if ($featuredImage && $this->option('download-images')) {
                    $featuredImage = $this->downloadImage($featuredImage);
                }

                $metaTitle = $p['yoast_head_json']['title'] ?? $title;
                $metaDesc = $p['yoast_head_json']['description'] ?? null;
                $canonicalUrl = $p['yoast_head_json']['canonical'] ?? $p['link'] ?? null;
                $ogTitle = $p['yoast_head_json']['og_title'] ?? $title;
                $ogDesc = $p['yoast_head_json']['og_description'] ?? $metaDesc;
                $ogImage = $p['yoast_head_json']['og_image'][0]['url'] ?? $featuredImage;

                $authorId = $this->userMap[$p['author'] ?? 0] ?? $defaultUserId;

                Page::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'user_id' => $authorId,
                        'title' => Str::limit($title, 250, ''),
                        'content' => $content,
                        'featured_image' => $featuredImage,
                        'template' => 'default',
                        'status' => $status,
                        'meta_title' => Str::limit($metaTitle, 250, ''),
                        'meta_description' => $metaDesc,
                        'canonical_url' => $canonicalUrl,
                        'og_title' => Str::limit($ogTitle, 250, ''),
                        'og_description' => $ogDesc,
                        'og_image' => $ogImage,
                        'is_indexable' => true,
                    ]
                );

                $totalPages++;
                $this->line("  [Page] {$title} (/{$slug})");
            }

            if (count($items) < 100) {
                break;
            }
            $page++;
        }

        $this->info("  Total Pages Imported/Synced: {$totalPages}");
    }

    /**
     * Import all posts (563+ items).
     */
    protected function importPosts(string $baseUrl): void
    {
        $this->info('--> Fetching posts (this may take a few moments for 500+ articles)...');
        $page = 1;
        $totalPosts = 0;
        $defaultUserId = array_values($this->userMap)[0] ?? 1;

        while (true) {
            $this->line("  Fetching posts page {$page}...");
            $items = $this->fetchApi("{$baseUrl}/wp-json/wp/v2/posts", [
                'per_page' => 100,
                'page' => $page,
                '_embed' => 1,
            ]);

            if (empty($items) || ! is_array($items)) {
                break;
            }

            foreach ($items as $p) {
                $rawTitle = html_entity_decode($p['title']['rendered'] ?? 'Post '.$p['id']);
                $title = Str::limit(trim($rawTitle), 250, '');
                $slug = Str::limit($p['slug'] ?? Str::slug($title), 250, '');
                $content = $p['content']['rendered'] ?? '';
                $excerpt = strip_tags(html_entity_decode($p['excerpt']['rendered'] ?? ''));
                $status = ($p['status'] ?? 'publish') === 'publish' ? 'published' : 'draft';
                $publishedAt = ! empty($p['date']) ? Carbon::parse($p['date']) : now();

                // Featured image
                $featuredImage = null;
                if (! empty($p['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                    $featuredImage = $p['_embedded']['wp:featuredmedia'][0]['source_url'];
                } elseif (! empty($p['yoast_head_json']['og_image'][0]['url'])) {
                    $featuredImage = $p['yoast_head_json']['og_image'][0]['url'];
                }

                if ($featuredImage && $this->option('download-images')) {
                    $featuredImage = $this->downloadImage($featuredImage);
                }

                $metaTitle = $p['yoast_head_json']['title'] ?? $title;
                $metaDesc = $p['yoast_head_json']['description'] ?? Str::limit($excerpt, 160, '');
                $canonicalUrl = $p['yoast_head_json']['canonical'] ?? $p['link'] ?? null;
                $ogTitle = $p['yoast_head_json']['og_title'] ?? $title;
                $ogDesc = $p['yoast_head_json']['og_description'] ?? $metaDesc;
                $ogImage = $p['yoast_head_json']['og_image'][0]['url'] ?? $featuredImage;

                $authorId = $this->userMap[$p['author'] ?? 0] ?? $defaultUserId;

                $post = Post::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'user_id' => $authorId,
                        'title' => $title,
                        'excerpt' => Str::limit($excerpt, 500, ''),
                        'content' => $content,
                        'featured_image' => $featuredImage,
                        'status' => $status,
                        'published_at' => $publishedAt,
                        'meta_title' => Str::limit($metaTitle, 250, ''),
                        'meta_description' => $metaDesc,
                        'canonical_url' => $canonicalUrl,
                        'og_title' => Str::limit($ogTitle, 250, ''),
                        'og_description' => $ogDesc,
                        'og_image' => $ogImage,
                        'is_indexable' => true,
                    ]
                );

                // Sync categories
                if (! empty($p['categories']) && is_array($p['categories'])) {
                    $catIds = [];
                    foreach ($p['categories'] as $wpCatId) {
                        if (isset($this->categoryMap[$wpCatId])) {
                            $catIds[] = $this->categoryMap[$wpCatId];
                        }
                    }
                    if (! empty($catIds)) {
                        $post->categories()->sync($catIds);
                    }
                }

                // Sync tags
                if (! empty($p['tags']) && is_array($p['tags'])) {
                    $tagIds = [];
                    foreach ($p['tags'] as $wpTagId) {
                        if (isset($this->tagMap[$wpTagId])) {
                            $tagIds[] = $this->tagMap[$wpTagId];
                        }
                    }
                    if (! empty($tagIds)) {
                        $post->tags()->sync($tagIds);
                    }
                }

                // Also register image in media table if available
                if ($featuredImage) {
                    $this->registerMedia($featuredImage, $authorId);
                }

                $totalPosts++;
            }

            $this->info("    Processed {$totalPosts} posts so far...");

            if (count($items) < 100) {
                break;
            }
            $page++;
        }

        $this->info("  Total Posts Imported/Synced: {$totalPosts}");
    }

    /**
     * Download an image to local public storage and return the relative path.
     */
    protected function downloadImage(string $url): string
    {
        try {
            $parsed = parse_url($url);
            $filename = basename($parsed['path'] ?? 'img_'.uniqid().'.jpg');
            $subDir = 'media/'.date('Y/m');
            $targetDir = storage_path('app/public/'.$subDir);

            if (! File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true, true);
            }

            $localPath = $targetDir.'/'.$filename;
            $relativeStoragePath = $subDir.'/'.$filename;

            if (! File::exists($localPath)) {
                $response = Http::timeout(15)->withoutVerifying()->get($url);
                if ($response->successful()) {
                    File::put($localPath, $response->body());

                    return $relativeStoragePath;
                }
            } else {
                return $relativeStoragePath;
            }
        } catch (\Throwable $e) {
            // Silently fallback to URL
        }

        return $url;
    }

    /**
     * Register an image URL or local path into the media library table.
     */
    protected function registerMedia(string $path, int $userId): void
    {
        $fileName = basename($path);
        Media::firstOrCreate(
            ['file_path' => $path],
            [
                'name' => pathinfo($fileName, PATHINFO_FILENAME),
                'file_name' => $fileName,
                'disk' => str_starts_with($path, 'http') ? 'public' : 'public',
                'mime_type' => 'image/jpeg',
                'size' => 0,
                'user_id' => $userId,
            ]
        );
    }

    /**
     * Populate the services table with authentic Data Recovery services.
     */
    protected function setupServices(): void
    {
        $this->info('--> Configuring Data Recovery services...');

        $services = [
            [
                'title' => 'Hard Drive Data Recovery',
                'slug' => 'hard-drive-recovery',
                'tagline' => 'Advanced mechanical and logical recovery for internal & external HDDs',
                'icon' => 'hard-drive',
                'summary' => 'Comprehensive data recovery for clicking, failing, dropped, corrupted, or unreadable desktop, laptop, and enterprise hard drives with certified Class 100 cleanroom technology.',
                'features' => ['Mechanical head & spindle assembly replacement', 'Bad sector cloning & firmware corruption repair', 'Water, fire, and drop physical shock recovery', 'Support for Western Digital, Seagate, Toshiba, Hitachi'],
                'benefits' => ['Free initial diagnostic and evaluation', 'No Data – No Charge policy', 'Strict confidentiality and NDA protection', 'High success rate over 98%'],
                'order' => 1,
                'is_featured' => true,
            ],
            [
                'title' => 'SSD Data Recovery Dubai',
                'slug' => 'ssd-recovery-dubai',
                'tagline' => 'Specialized NAND Flash chip-off and controller diagnostics',
                'icon' => 'cpu',
                'summary' => 'Expert recovery for solid state drives suffering from dead controller, firmware lock, damaged NAND memory, bad sectors, or sudden death across SATA, mSATA, and M.2 form factors.',
                'features' => ['Controller bypass and direct NAND reading', 'Firmware repair for Samsung, Kingston, Crucial, SanDisk', 'Recovery from 0GB capacity or RAW partition', 'BitLocker and hardware encryption recovery'],
                'benefits' => ['Fast turnaround for critical files', 'Proprietary SSD diagnostic hardware', 'Zero risk of further degradation', 'Certified flash memory engineers'],
                'order' => 2,
                'is_featured' => true,
            ],
            [
                'title' => 'NVMe SSD Recovery',
                'slug' => 'nvme-ssd-recovery-dubai',
                'tagline' => 'High-speed PCIe & NVMe protocol data recovery',
                'icon' => 'zap',
                'summary' => 'Cutting-edge data recovery from high-performance PCIe NVMe M.2 drives used in high-end laptops, workstations, and gaming rigs experiencing thermal failure or controller corruption.',
                'features' => ['PCIe Gen 3, Gen 4, and Gen 5 NVMe support', 'Thermal throttle & short circuit electrical repair', 'NAND flash reconstruction and wear-leveling de-obfuscation', 'macOS & Windows proprietary filesystem support'],
                'benefits' => ['Expert hardware micro-soldering lab', 'Rapid turnaround for business laptops', 'Safe non-destructive extraction', 'Comprehensive data integrity verification'],
                'order' => 3,
                'is_featured' => true,
            ],
            [
                'title' => 'RAID & Server Data Recovery',
                'slug' => 'raid-recovery-dubai',
                'tagline' => 'Enterprise RAID 0, 1, 5, 6, 10, 50, 60 array reconstruction',
                'icon' => 'server',
                'summary' => 'Mission-critical enterprise data recovery for failed RAID arrays, virtualized environments, SAN/NAS enclosures, and corrupt striped volumes with 24/7 priority emergency service.',
                'features' => ['Virtual RAID parity recalculation & array re-striping', 'Multiple disk failure recovery (RAID 5 & 6)', 'Hardware controller malfunction bypass', 'VMware ESXi, Hyper-V, and Proxmox VM recovery'],
                'benefits' => ['Emergency 24/7 response team', 'Onsite and remote assessment available', 'Strict enterprise security & compliance', 'Zero risk of array corruption during rebuild'],
                'order' => 4,
                'is_featured' => true,
            ],
            [
                'title' => 'NAS Data Recovery',
                'slug' => 'nas-recovery-dubai',
                'tagline' => 'Synology, QNAP, Asustor, WD My Cloud network storage recovery',
                'icon' => 'cloud',
                'summary' => 'Full recovery solutions for network-attached storage devices after configuration loss, multiple drive failures, accidental firmware upgrades, or corrupted ext4/Btrfs filesystems.',
                'features' => ['Support for Synology SHR, QNAP QTS, and TrueNAS ZFS', 'Btrfs, ext4, XFS, and ZFS proprietary pool reconstruction', 'Degraded or crashed volume recovery', 'iSCSI LUN extraction'],
                'benefits' => ['Direct disk image reconstruction without original hardware', 'Complete folder structure preservation', 'No alteration to original NAS drives', 'Enterprise confidential data handling'],
                'order' => 5,
                'is_featured' => true,
            ],
            [
                'title' => 'CCTV Footage Recovery',
                'slug' => 'cctv-footage-recovery-dubai',
                'tagline' => 'DVR & NVR surveillance security video footage restoration',
                'icon' => 'video',
                'summary' => 'Specialized forensic recovery of overwritten, formatted, deleted, or corrupted video files from CCTV surveillance systems (Hikvision, Dahua, Uniview, CP Plus, Samsung).',
                'features' => ['Proprietary CCTV filesystem reconstruction (DHFS, WFS, etc.)', 'Fragmented video stream stitching (H.264, H.265, MP4)', 'Overwritten and deleted timestamp search', 'Court-admissible forensic integrity verification'],
                'benefits' => ['Time-stamped exact footage retrieval', 'High recovery success for legal investigations', 'Clean video extraction without artifacts', 'Fast turnaround for urgent incident review'],
                'order' => 6,
                'is_featured' => false,
            ],
            [
                'title' => 'USB & Pen Drive Recovery',
                'slug' => 'usb-data-recovery-dubai',
                'tagline' => 'Bent, snapped, unrecognized, or corrupted USB flash drives',
                'icon' => 'disc',
                'summary' => 'Precision recovery from snapped USB connectors, monolithic thumb drives, SD cards, and microSD cards using advanced micro-soldering and trace repair techniques.',
                'features' => ['Monolithic chip-off pinout probing & soldering', 'Broken USB connector and trace rebuilding', 'Controller fault and raw NAND memory decoding', 'Formatting and accidental file deletion rescue'],
                'benefits' => ['Affordable pricing with No Data No Charge', 'Quick turnaround for personal & student drives', 'Support for all USB brands (SanDisk, Kingston, Sony)', 'Safe preservation of intact original memory'],
                'order' => 7,
                'is_featured' => false,
            ],
            [
                'title' => 'iPhone & Mobile Phone Recovery',
                'slug' => 'iphone-data-recovery-dubai',
                'tagline' => 'Water damage, dead motherboard, and logic board micro-soldering',
                'icon' => 'smartphone',
                'summary' => 'Advanced mobile data recovery for liquid-damaged, dropped, bent, or completely unresponsive iPhones and Android smartphones with chip-level motherboard restoration.',
                'features' => ['Motherboard short circuit detection & repair', 'Power management IC and CPU line reballing', 'Water damage corrosion ultrasonic cleaning', 'Photos, WhatsApp messages, contacts & notes recovery'],
                'benefits' => ['Device privacy guaranteed', 'Highest recovery rate for water-damaged phones', 'Focus on extracting critical files without resetting device', 'Free inspection and diagnostic report'],
                'order' => 8,
                'is_featured' => false,
            ],
            [
                'title' => 'Ransomware Data Recovery',
                'slug' => 'ransomware-data-recovery-dubai',
                'tagline' => 'Decryption assistance, shadow copy analysis & raw data carving',
                'icon' => 'shield-alert',
                'summary' => 'Assistance for businesses hit by ransomware attacks. We identify malware strains, decrypt available keys, restore shadow copies, and carve clean unencrypted database fragments.',
                'features' => ['Malware strain identification & threat analysis', 'Database carving for SQL, ERP, and accounting records', 'Volume shadow copy and snapshot extraction', 'Cleanroom forensic sandboxing to prevent reinfection'],
                'benefits' => ['Alternative to paying cyber extortion', 'Forensic timeline analysis for IT security audits', 'Comprehensive recovery of mission-critical databases', 'Strict confidentiality and security standards'],
                'order' => 9,
                'is_featured' => false,
            ],
        ];

        foreach ($services as $srv) {
            Service::updateOrCreate(
                ['slug' => $srv['slug']],
                array_merge($srv, [
                    'status' => 'published',
                    'meta_title' => $srv['title'].' | Recovery Data Dubai',
                    'meta_description' => Str::limit($srv['summary'], 160, ''),
                    'is_indexable' => true,
                ])
            );
        }

        $this->info('  Configured '.count($services).' Data Recovery Services');
    }

    /**
     * Setup the authentic header navigation menu.
     */
    protected function setupNavigationMenu(): void
    {
        $this->info('--> Setting up navigation menu...');

        MenuItem::query()->delete();

        // 1. Home
        MenuItem::create([
            'title' => 'Home',
            'url' => '/',
            'order' => 1,
            'is_active' => true,
            'location' => 'header',
        ]);

        // 2. About
        MenuItem::create([
            'title' => 'About',
            'url' => '/about',
            'order' => 2,
            'is_active' => true,
            'location' => 'header',
        ]);

        // 3. Services (Parent)
        $servicesParent = MenuItem::create([
            'title' => 'Services',
            'url' => '/services',
            'order' => 3,
            'is_active' => true,
            'location' => 'header',
        ]);

        // Submenu items under Services
        $subServices = [
            ['title' => 'Hard Drive Recovery', 'url' => '/service/hard-drive-recovery'],
            ['title' => 'SSD Data Recovery', 'url' => '/service/ssd-recovery-dubai'],
            ['title' => 'NVMe SSD Recovery', 'url' => '/service/nvme-ssd-recovery-dubai'],
            ['title' => 'RAID Recovery', 'url' => '/service/raid-recovery-dubai'],
            ['title' => 'NAS Recovery', 'url' => '/service/nas-recovery-dubai'],
            ['title' => 'CCTV Footage Recovery', 'url' => '/service/cctv-footage-recovery-dubai'],
            ['title' => 'USB / Pen Drive Recovery', 'url' => '/service/usb-data-recovery-dubai'],
            ['title' => 'iPhone / Mobile Recovery', 'url' => '/service/iphone-data-recovery-dubai'],
            ['title' => 'Ransomware Recovery', 'url' => '/service/ransomware-data-recovery-dubai'],
        ];

        $order = 1;
        foreach ($subServices as $sub) {
            MenuItem::create([
                'parent_id' => $servicesParent->id,
                'title' => $sub['title'],
                'url' => $sub['url'],
                'order' => $order++,
                'is_active' => true,
                'location' => 'header',
            ]);
        }

        // 4. Blogs
        MenuItem::create([
            'title' => 'Blogs',
            'url' => '/blog',
            'order' => 4,
            'is_active' => true,
            'location' => 'header',
        ]);

        // 5. Contact
        MenuItem::create([
            'title' => 'Contact',
            'url' => '/contact',
            'order' => 5,
            'is_active' => true,
            'location' => 'header',
        ]);

        $this->info('  Header Navigation Menu configured with dropdown services');
    }
}
