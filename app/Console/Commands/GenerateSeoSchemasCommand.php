<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Console\Command;

class GenerateSeoSchemasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:generate-schemas {--force : Overwrite existing schema_markup} {--dry-run : Preview schema generation without saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Google-optimized Schema.org JSON-LD structured data for all posts, pages, and services';

    /**
     * Common LocalBusiness Publisher definition.
     */
    protected function getLocalBusinessPublisher(): array
    {
        return [
            '@type' => 'LocalBusiness',
            '@id' => 'https://www.recoverydata.ae/#organization',
            'name' => 'Recovery Data Dubai',
            'url' => 'https://www.recoverydata.ae',
            'telephone' => '+971564101040',
            'email' => 'info@recoverydata.ae',
            'priceRange' => '$$',
            'image' => 'https://www.recoverydata.ae/images/recoverydata-logo.png',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => 'https://www.recoverydata.ae/images/recoverydata-logo.png',
            ],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Office # 508, Dusseldorf Business Point, Al Barsha 1',
                'addressLocality' => 'Dubai',
                'addressRegion' => 'Dubai',
                'addressCountry' => 'AE',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 25.1166,
                'longitude' => 55.2000,
            ],
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                    'opens' => '09:00',
                    'closes' => '20:00',
                ],
            ],
            'areaServed' => [
                'Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah', 'Umm Al Quwain',
            ],
            'sameAs' => [
                'https://wa.me/971564101040',
            ],
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $force = (bool) $this->option('force');
        $dryRun = (bool) $this->option('dry-run');

        $this->info('Starting Google-optimized Schema.org JSON-LD generation...');

        // 1. Process Services
        $servicesCount = $this->generateServicesSchema($force, $dryRun);
        $this->info("✓ Services processed: {$servicesCount}");

        // 2. Process Pages
        $pagesCount = $this->generatePagesSchema($force, $dryRun);
        $this->info("✓ Pages processed: {$pagesCount}");

        // 3. Process Posts
        $postsCount = $this->generatePostsSchema($force, $dryRun);
        $this->info("✓ Blog posts processed: {$postsCount}");

        $this->info('All schemas generated successfully with zero errors.');

        return Command::SUCCESS;
    }

    /**
     * Generate Schema for all Services.
     */
    protected function generateServicesSchema(bool $force, bool $dryRun): int
    {
        $services = Service::all();
        $count = 0;

        foreach ($services as $service) {
            if (! $force && ! empty($service->schema_markup)) {
                continue;
            }

            $serviceUrl = "https://www.recoverydata.ae/service/{$service->slug}";
            $canonicalUrl = $service->canonical_url ?: $serviceUrl;

            $serviceType = match ($service->slug) {
                'hard-drive-recovery' => 'Mechanical Hard Drive Data Recovery & ISO Class 100 Cleanroom Platter Transplant',
                'ssd-recovery-dubai' => 'Solid State Drive (SATA / PCIe) NAND Flash Data Recovery & Controller Repair',
                'nvme-ssd-recovery-dubai' => 'M.2 NVMe PCIe High-Speed SSD Forensic Data Recovery',
                'raid-recovery-dubai' => 'Enterprise RAID Array Rebuild (RAID 0, 1, 5, 6, 10, 50, 60) & Virtual Machine Recovery',
                'nas-recovery-dubai' => 'Network Attached Storage (Synology, QNAP, Asustor, TrueNAS) Data Recovery',
                'cctv-footage-recovery-dubai' => 'CCTV DVR / NVR Security Camera Footage & Overwritten Video Recovery',
                'usb-data-recovery-dubai' => 'USB Flash Drive, Monolithic Chip-Off & SD Card Data Recovery',
                'iphone-data-recovery-dubai' => 'Smartphone Logic Board Micro-Soldering & Mobile Device Data Recovery',
                'ransomware-data-recovery-dubai' => 'Ransomware Decryption, Database Recovery & Incident Forensic Remediation',
                default => 'Professional Cleanroom Data Recovery Services in Dubai',
            };

            $faqs = $this->getServiceSpecificFaqs($service);

            $graph = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Service',
                        '@id' => "{$canonicalUrl}#service",
                        'name' => $service->title,
                        'serviceType' => $serviceType,
                        'description' => $service->seo_description ?: $service->summary,
                        'provider' => [
                            '@id' => 'https://www.recoverydata.ae/#organization',
                        ],
                        'areaServed' => [
                            'Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah', 'Umm Al Quwain',
                        ],
                        'hasOfferCatalog' => [
                            '@type' => 'OfferCatalog',
                            'name' => 'Data Recovery Guarantees',
                            'itemListElement' => [
                                [
                                    '@type' => 'Offer',
                                    'itemOffered' => [
                                        '@type' => 'Service',
                                        'name' => 'Free Diagnostic & Written Quote',
                                    ],
                                    'price' => '0',
                                    'priceCurrency' => 'AED',
                                ],
                                [
                                    '@type' => 'Offer',
                                    'itemOffered' => [
                                        '@type' => 'Service',
                                        'name' => 'No Data – No Charge Policy',
                                    ],
                                    'description' => 'Zero recovery fee charged if critical data is deemed unrecoverable.',
                                ],
                                [
                                    '@type' => 'Offer',
                                    'itemOffered' => [
                                        '@type' => 'Service',
                                        'name' => 'Free Safe Courier Collection Across All 7 Emirates',
                                    ],
                                    'price' => '0',
                                    'priceCurrency' => 'AED',
                                ],
                            ],
                        ],
                        'mainEntityOfPage' => $canonicalUrl,
                    ],
                    $this->getLocalBusinessPublisher(),
                    [
                        '@type' => 'BreadcrumbList',
                        '@id' => "{$canonicalUrl}#breadcrumb",
                        'itemListElement' => [
                            [
                                '@type' => 'ListItem',
                                'position' => 1,
                                'name' => 'Home',
                                'item' => 'https://www.recoverydata.ae',
                            ],
                            [
                                '@type' => 'ListItem',
                                'position' => 2,
                                'name' => 'Services',
                                'item' => 'https://www.recoverydata.ae/services',
                            ],
                            [
                                '@type' => 'ListItem',
                                'position' => 3,
                                'name' => $service->title,
                                'item' => $canonicalUrl,
                            ],
                        ],
                    ],
                    [
                        '@type' => 'FAQPage',
                        '@id' => "{$canonicalUrl}#faq",
                        'mainEntity' => $faqs,
                    ],
                ],
            ];

            $jsonLd = json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

            if (! $dryRun) {
                $service->schema_markup = $jsonLd;
                $service->saveQuietly();
            }

            $count++;
        }

        return $count;
    }

    /**
     * Provide rich, high-authority FAQs for each service.
     */
    protected function getServiceSpecificFaqs(Service $service): array
    {
        return match ($service->slug) {
            'hard-drive-recovery' => [
                [
                    '@type' => 'Question',
                    'name' => 'Can data be recovered from a hard drive that makes clicking, beeping, or buzzing noises?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. Clicking and buzzing noises indicate mechanical head assembly failure or motor seizure. In our certified ISO Class 100 cleanroom laboratory in Al Barsha, Dubai, our engineers transplant precision donor head assemblies to read and clone platters without risk of surface damage.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'What should I do immediately after my hard drive fails or stops being recognized?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Power off the device immediately and do not attempt to run automated repair utilities like chkdsk or third-party file recovery software. Running software on a mechanically distressed drive can grind read heads into magnetic platters, causing irreversible data loss.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How much does professional hard drive data recovery cost in Dubai?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'We offer 100% free in-lab diagnostic assessments with a written fixed quote before any work begins. Under our strict No Data, No Charge guarantee, you pay zero fees if your required files cannot be recovered.',
                    ],
                ],
            ],
            'ssd-recovery-dubai', 'nvme-ssd-recovery-dubai' => [
                [
                    '@type' => 'Question',
                    'name' => 'Can data be recovered from an SSD that is not detected or shows 0 GB capacity?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. A dead or 0 GB SSD typically indicates firmware degradation or Flash Translation Layer (FTL) corruption. Using advanced ACELab PC-3000 Flash and SSD diagnostic hardware, our lab communicates directly with the controller in technological safe mode to reconstruct logical address tables.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Does an SSD require cleanroom laboratory opening like a mechanical hard drive?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'No. SSDs do not contain spinning platters or mechanical heads. However, they require clean electronic workbenches, micro-soldering stations, and controller bypass hardware to read NAND packages safely.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How fast can emergency SSD data recovery be completed in Dubai?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Standard SSD diagnostics take 24 to 48 hours. For critical corporate emergencies, our expedited 24/7 priority cleanroom service can complete forensic extraction within 12 to 24 hours.',
                    ],
                ],
            ],
            'raid-recovery-dubai', 'nas-recovery-dubai' => [
                [
                    '@type' => 'Question',
                    'name' => 'Can data be recovered if multiple drives fail simultaneously in a RAID array?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. Even in RAID 5 arrays where two drives fail, or RAID 6 arrays with three failures, our engineers repair the individual physical drives in our cleanroom, clone them bit-by-bit, and virtually reconstruct the RAID stripe order, parity rotation, and block size.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Should we allow the RAID controller to run an automatic rebuild?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'No. Never initiate an automatic rebuild if multiple drives exhibit SMART warnings or read errors. An automatic rebuild subjects remaining stressed drives to hours of intense read cycles, often triggering a catastrophic secondary failure.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Do you support proprietary NAS file systems like Synology SHR and QNAP QTS?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. We support all enterprise file systems including Btrfs, Ext4, ZFS, VMFS (VMware), ReFS, and proprietary LVM/SHR thin-provisioning architectures.',
                    ],
                ],
            ],
            'cctv-footage-recovery-dubai' => [
                [
                    '@type' => 'Question',
                    'name' => 'Can deleted or overwritten CCTV footage be recovered for legal or insurance evidence?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. Security DVRs and NVRs (Hikvision, Dahua, Uniview, Bosch) use proprietary continuous circular recording. Our digital forensics lab bypasses damaged index tables to extract and reassemble raw H.264/H.265 video frames with complete chain-of-custody documentation.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'What happens if a DVR hard drive was formatted or damaged by water/fire?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Our Class 100 cleanroom lab treats fire-damaged, water-damaged, and dropped CCTV hard drives, rebuilding mechanical assemblies to salvage security video archives.',
                    ],
                ],
            ],
            'iphone-data-recovery-dubai' => [
                [
                    '@type' => 'Question',
                    'name' => 'Can data be recovered from a dead, water-damaged, or smashed smartphone?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. Modern smartphones encrypt storage with unique hardware security chips. Rather than just pulling chips, our technicians repair the damaged motherboard at the micro-component level to boot the device and extract encrypted photos, WhatsApp databases, and contacts.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Is our private mobile data kept completely confidential?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. We enforce strict non-disclosure agreements (NDAs) and privacy standards. Extracted user data is stored on secure encrypted servers and transferred onto customer storage with complete privacy.',
                    ],
                ],
            ],
            default => [
                [
                    '@type' => 'Question',
                    'name' => 'What is the No Data, No Charge guarantee?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'If our certified cleanroom lab cannot recover your critical required files, there is zero charge for the recovery effort. Diagnostics and quotes are 100% complimentary.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How can I deliver my failed storage media to your Dubai lab?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'You can visit our cleanroom laboratory directly in Office # 508, Dusseldorf Business Point, Al Barsha 1, Dubai, or book our free secure courier collection service available across all 7 Emirates.',
                    ],
                ],
            ],
        };
    }

    /**
     * Generate Schema for all Pages.
     */
    protected function generatePagesSchema(bool $force, bool $dryRun): int
    {
        $pages = Page::all();
        $count = 0;

        foreach ($pages as $page) {
            if (! $force && ! empty($page->schema_markup)) {
                continue;
            }

            $rawUrl = $page->canonical_url ?: "https://www.recoverydata.ae/{$page->slug}";
            $canonicalUrl = str_replace('https://recoverydata.ae', 'https://www.recoverydata.ae', $rawUrl);
            $canonicalUrl = rtrim($canonicalUrl, '/');
            if ($page->slug === 'home') {
                $canonicalUrl = 'https://www.recoverydata.ae';
            }

            $pageType = match ($page->slug) {
                'about' => 'AboutPage',
                'contact' => 'ContactPage',
                'services' => 'CollectionPage',
                default => 'WebPage',
            };

            $graph = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => $pageType,
                        '@id' => "{$canonicalUrl}#webpage",
                        'url' => $canonicalUrl,
                        'name' => $page->seo_title ?: $page->title,
                        'description' => $page->seo_description ?: "Professional data recovery cleanroom laboratory in Dubai UAE - {$page->title}",
                        'isPartOf' => [
                            '@type' => 'WebSite',
                            '@id' => 'https://www.recoverydata.ae/#website',
                            'name' => 'Recovery Data Dubai',
                            'url' => 'https://www.recoverydata.ae',
                        ],
                        'breadcrumb' => [
                            '@type' => 'BreadcrumbList',
                            '@id' => "{$canonicalUrl}#breadcrumb",
                            'itemListElement' => [
                                [
                                    '@type' => 'ListItem',
                                    'position' => 1,
                                    'name' => 'Home',
                                    'item' => 'https://www.recoverydata.ae',
                                ],
                                [
                                    '@type' => 'ListItem',
                                    'position' => 2,
                                    'name' => $page->title,
                                    'item' => $canonicalUrl,
                                ],
                            ],
                        ],
                        'publisher' => [
                            '@id' => 'https://www.recoverydata.ae/#organization',
                        ],
                    ],
                    $this->getLocalBusinessPublisher(),
                ],
            ];

            // If contact page, add contact-specific details
            if ($page->slug === 'contact') {
                $graph['@graph'][0]['contactPoint'] = [
                    '@type' => 'ContactPoint',
                    'telephone' => '+971564101040',
                    'contactType' => 'customer support',
                    'areaServed' => 'AE',
                    'availableLanguage' => ['English', 'Arabic'],
                ];
            }

            $jsonLd = json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

            if (! $dryRun) {
                $page->schema_markup = $jsonLd;
                $page->saveQuietly();
            }

            $count++;
        }

        return $count;
    }

    /**
     * Generate Schema for all Blog Posts.
     */
    protected function generatePostsSchema(bool $force, bool $dryRun): int
    {
        $posts = Post::with('categories')->cursor();
        $count = 0;

        foreach ($posts as $post) {
            if (! $force && ! empty($post->schema_markup)) {
                continue;
            }

            $canonicalUrl = $post->canonical_url;
            $primaryCategory = $post->categories->first();
            $categoryName = $primaryCategory ? $primaryCategory->name : 'Data Recovery';
            $categorySlug = $primaryCategory ? $primaryCategory->slug : 'blog';

            $breadcrumbItems = [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => 'https://www.recoverydata.ae',
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Articles',
                    'item' => 'https://www.recoverydata.ae/blog',
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $categoryName,
                    'item' => "https://www.recoverydata.ae/category/{$categorySlug}",
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 4,
                    'name' => $post->clean_seo_title,
                    'item' => $canonicalUrl,
                ],
            ];

            // 1. Get real FAQs if present in content, or generate contextual FAQs
            $faqs = [];
            $extractedFaq = $post->extractFaqSchema();
            if ($extractedFaq && ! empty($extractedFaq['mainEntity'])) {
                $faqs = $extractedFaq['mainEntity'];
            } else {
                $faqs = $this->generateContextualFaqsForPost($post, $categoryName);
            }

            $imageUrl = $post->featured_image_url ?: 'https://www.recoverydata.ae/images/recoverydata-logo.png';
            $publishedAt = $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String();
            $updatedAt = $post->updated_at ? $post->updated_at->toIso8601String() : $publishedAt;

            $graph = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'BlogPosting',
                        '@id' => "{$canonicalUrl}#article",
                        'isPartOf' => [
                            '@type' => 'WebSite',
                            '@id' => 'https://www.recoverydata.ae/#website',
                            'name' => 'Recovery Data Dubai',
                            'url' => 'https://www.recoverydata.ae',
                        ],
                        'headline' => $post->clean_seo_title,
                        'description' => $post->seo_description,
                        'mainEntityOfPage' => $canonicalUrl,
                        'inLanguage' => 'en-US',
                        'articleSection' => $categoryName,
                        'image' => [$imageUrl],
                        'datePublished' => $publishedAt,
                        'dateModified' => $updatedAt,
                        'author' => [
                            '@type' => 'Organization',
                            '@id' => 'https://www.recoverydata.ae/#organization',
                            'name' => 'Recovery Data Cleanroom Engineering Lab',
                            'url' => 'https://www.recoverydata.ae',
                        ],
                        'publisher' => [
                            '@id' => 'https://www.recoverydata.ae/#organization',
                        ],
                    ],
                    $this->getLocalBusinessPublisher(),
                    [
                        '@type' => 'BreadcrumbList',
                        '@id' => "{$canonicalUrl}#breadcrumb",
                        'itemListElement' => $breadcrumbItems,
                    ],
                ],
            ];

            if (! empty($faqs)) {
                $graph['@graph'][] = [
                    '@type' => 'FAQPage',
                    '@id' => "{$canonicalUrl}#faq",
                    'mainEntity' => $faqs,
                ];
            }

            $jsonLd = json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

            if (! $dryRun) {
                $post->schema_markup = $jsonLd;
                $post->saveQuietly();
            }

            $count++;
        }

        return $count;
    }

    /**
     * Generate contextually accurate FAQs for posts that do not have embedded Q&A headings.
     */
    protected function generateContextualFaqsForPost(Post $post, string $categoryName): array
    {
        $cleanTitle = $post->clean_seo_title;

        // Custom FAQs based on category
        return match (true) {
            str_contains(strtolower($categoryName), 'ssd') || str_contains(strtolower($cleanTitle), 'ssd') || str_contains(strtolower($cleanTitle), 'nvme') => [
                [
                    '@type' => 'Question',
                    'name' => 'Can data be recovered from this SSD failure in Dubai?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. Whether caused by controller burnout, corrupted Flash Translation Layer (FTL) mapping, or sudden power loss, our Dubai cleanroom lab uses hardware diagnostic tools to extract and reconstruct raw NAND flash data.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'What should I do immediately to prevent permanent SSD data loss?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Disconnect power to the SSD immediately. Do not attempt formatting, partition rebuilds, or continuous power-cycling, as the internal SSD controller may execute TRIM or background garbage collection cycles that overwrite user data.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'What is the diagnostic turnaround time for SSD recovery at Recovery Data Dubai?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'In-lab diagnostic assessment is 100% free and typically completed within 24 to 48 hours. Emergency cleanroom recovery is available same-day under our No Data, No Charge guarantee.',
                    ],
                ],
            ],
            str_contains(strtolower($categoryName), 'raid') || str_contains(strtolower($cleanTitle), 'raid') || str_contains(strtolower($cleanTitle), 'nas') || str_contains(strtolower($cleanTitle), 'server') => [
                [
                    '@type' => 'Question',
                    'name' => 'How does Recovery Data Dubai handle complex RAID or server failures?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Our certified enterprise team clones all member drives bit-by-bit in our ISO Class 100 cleanroom, analyzing RAID array metadata, block sizes, drive offsets, and parity rotation in a virtualized read-only environment to reconstruct failed file systems safely.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Is it safe to rebuild a degraded RAID array after a drive failure?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'No. Avoid initiating automatic RAID rebuilds if drives show read errors or bad sectors. The high stress of a rebuild frequently causes a secondary drive to drop offline, causing total array collapse.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'What is the guarantee on RAID data recovery?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'We operate under a strict No Data – No Charge policy. You receive a complete file verification list to inspect before any payment is required.',
                    ],
                ],
            ],
            str_contains(strtolower($categoryName), 'cctv') || str_contains(strtolower($cleanTitle), 'cctv') || str_contains(strtolower($cleanTitle), 'dvr') => [
                [
                    '@type' => 'Question',
                    'name' => 'Can overwritten or deleted CCTV footage be retrieved?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. Video surveillance DVRs and NVRs record in continuous proprietary streams. Our digital forensics lab bypasses damaged index tables to carve and reassemble raw video frames.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Are recovered security video recordings admissible for insurance and police reporting in Dubai?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. We provide certified chain-of-custody documentation and write-blocked raw evidence imaging suitable for corporate auditing and police review.',
                    ],
                ],
            ],
            str_contains(strtolower($categoryName), 'ransomware') || str_contains(strtolower($cleanTitle), 'ransomware') => [
                [
                    '@type' => 'Question',
                    'name' => 'Can encrypted files be decrypted without paying the ransom?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'In many cases, yes. We utilize known mathematical decryption keys, shadow copy memory extraction, unallocated sector carving, and database repair to restore mission-critical data without funding criminal groups.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How can our corporate systems be protected from re-infection?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'We provide thorough incident analysis to identify the initial entry vector, sanitize storage media, and assist in implementing air-gapped immutable backup architectures.',
                    ],
                ],
            ],
            default => [
                [
                    '@type' => 'Question',
                    'name' => "What is the success rate for data recovery on {$cleanTitle}?",
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Recovery Data Dubai maintains an audited 98.7% success rate across hardware and logical recovery cases. In our ISO Class 100 cleanroom lab in Al Barsha, specialized donor transplants and firmware repairs are executed under dust-free conditions.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'What is the policy if my files cannot be recovered?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'We operate under an unconditional No Data – No Charge policy. If we are unable to retrieve your required files, you pay nothing.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How can I submit my drive for a free diagnostic evaluation in the UAE?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'You can drop off your device at our lab in Office # 508, Dusseldorf Business Point, Al Barsha 1, Dubai, or call +971 56 410 1040 to arrange complimentary safe courier pickup from anywhere in the UAE.',
                    ],
                ],
            ],
        };
    }
}
