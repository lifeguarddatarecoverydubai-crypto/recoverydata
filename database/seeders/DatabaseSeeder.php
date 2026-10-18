<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with users, categories, tags, pages, and posts.
     */
    public function run(): void
    {
        // 1. Create Default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'bio' => 'Lead administrator and platform architect. Managing content and team contributors.',
                'is_active' => true,
            ]
        );

        // 2. Create Default Editor User
        $editor = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Sarah Jenkins',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'bio' => 'Senior Tech Editor and content strategist specializing in modern web ecosystems and developer tools.',
                'is_active' => true,
            ]
        );

        // 3. Create Categories
        $categoriesData = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Breakthroughs, developer workflows, and cutting-edge software tooling.',
            ],
            [
                'name' => 'Artificial Intelligence',
                'slug' => 'artificial-intelligence',
                'description' => 'Machine learning, autonomous agentic systems, and neural frameworks.',
            ],
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Architecture, performance, Laravel ecosystems, and modern frontend practices.',
            ],
            [
                'name' => 'Productivity & Leadership',
                'slug' => 'productivity-leadership',
                'description' => 'Best practices for software delivery, product mindset, and engineering culture.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 4. Create Tags
        $tagsData = [
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'PHP', 'slug' => 'php'],
            ['name' => 'Blade', 'slug' => 'blade'],
            ['name' => 'SEO', 'slug' => 'seo'],
            ['name' => 'Web Design', 'slug' => 'web-design'],
            ['name' => 'Full Stack', 'slug' => 'full-stack'],
            ['name' => 'Architecture', 'slug' => 'architecture'],
            ['name' => 'AI Agents', 'slug' => 'ai-agents'],
        ];

        $tags = [];
        foreach ($tagsData as $t) {
            $tags[$t['slug']] = Tag::firstOrCreate(['slug' => $t['slug']], $t);
        }

        // 5. Create Static Pages
        Page::firstOrCreate(
            ['slug' => 'about-us'],
            [
                'user_id' => $admin->id,
                'title' => 'About Our Platform',
                'slug' => 'about-us',
                'template' => 'default',
                'status' => 'published',
                'featured_image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1400&q=80',
                'content' => '
                    <h2>Empowering Modern Creators and Developers</h2>
                    <p>Welcome to <strong>Recovery Data CMS</strong>, a high-performance content management and publishing platform engineered natively on Laravel with classic Blade architecture. Our goal is to blend the intuitive, familiar workflow of WordPress with the pristine elegance, speed, and maintainability of modern Laravel.</p>
                    
                    <h3>Why Choose Recovery Data CMS?</h3>
                    <ul>
                        <li><strong>Pure Blade & Tailwind CSS:</strong> Zero friction, no heavy SPA hydration overhead, lightning-fast rendering.</li>
                        <li><strong>Built-in SEO Meta Suite:</strong> Fine-tuned Open Graph previews, live Google search snippet previews, canonical tags, and granular indexing controls.</li>
                        <li><strong>WordPress-style Familiarity:</strong> Intuitive media library, hierarchical taxonomy, categories, tags, and rich WYSIWYG editing.</li>
                        <li><strong>Role-Based Access Control:</strong> Seamless multi-author management with Administrators, Editors, and Authors.</li>
                    </ul>

                    <blockquote>
                        "Simplicity is the prerequisite for reliability. Great software gets out of your way and lets you ship."
                    </blockquote>

                    <p>Whether you are running an editorial blog, corporate documentation hub, or high-traffic news site, Recovery Data CMS is engineered for speed, stability, and search engine prominence.</p>
                ',
                'meta_title' => 'About Recovery Data CMS - The Modern Laravel Publishing System',
                'meta_description' => 'Discover Recovery Data CMS, a fast, SEO-optimized publishing platform built natively on Laravel with rich WYSIWYG editing and WordPress-style taxonomies.',
                'meta_keywords' => 'laravel cms, blade cms, publishing platform, seo cms',
                'og_title' => 'About Recovery Data CMS - Modern Publishing on Laravel',
                'og_description' => 'Empowering content creators with a blazing fast, SEO-ready CMS.',
                'og_image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1400&q=80',
                'is_indexable' => true,
            ]
        );

        Page::firstOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'user_id' => $admin->id,
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'template' => 'default',
                'status' => 'published',
                'content' => '
                    <h2>Your Privacy Matters</h2>
                    <p>This Privacy Policy outlines how Recovery Data collects, uses, and protects personal information gathered through your use of our website and services.</p>
                    
                    <h3>1. Information We Collect</h3>
                    <p>When you register or browse our website, we may collect information including your name, email address, IP address, and browser session details for security and authentication purposes.</p>

                    <h3>2. How We Use Information</h3>
                    <p>We use this information to maintain your authenticated session, deliver requested content, improve user experience, and ensure security across our platform.</p>

                    <h3>3. Cookies and Analytics</h3>
                    <p>Session cookies are used to keep you securely signed in. We do not sell or monetize personal data to third parties.</p>
                ',
                'meta_title' => 'Privacy Policy - Recovery Data',
                'meta_description' => 'Read our privacy policy regarding data collection, protection, and user rights.',
                'is_indexable' => true,
            ]
        );

        // 6. Create Sample Posts with SEO metadata
        $postsData = [
            [
                'user_id' => $editor->id,
                'title' => 'Building High-Performance Websites with Laravel and Pure Blade',
                'slug' => 'building-high-performance-websites-with-laravel-and-pure-blade',
                'excerpt' => 'Discover why pure Blade templates combined with Tailwind CSS deliver unbeatable performance, simplicity, and superior SEO outcomes for modern content applications.',
                'featured_image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1400&q=80',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'content' => '
                    <p class="lead">In an era of increasingly bloated frontend build pipelines, returning to fast, server-rendered Blade templates delivers profound advantages in speed, SEO discoverability, and developer joy.</p>
                    
                    <h2>The Return of Server-Driven Architecture</h2>
                    <p>When building content-driven platforms, blogs, and marketing websites, every millisecond counts. Single-Page Application (SPA) architectures introduce JavaScript hydration overhead, large bundles, and complicated server-side rendering setups.</p>
                    
                    <p>With <strong>Laravel Blade</strong>, HTML is rendered instantaneously on the server. Search engines receive pristine, pre-rendered markup with complete Open Graph and Twitter Card tags in the initial response.</p>

                    <h3>Key Architectural Advantages</h3>
                    <ul>
                        <li><strong>Zero Hydration Lag:</strong> First Contentful Paint (FCP) and Largest Contentful Paint (LCP) scores are consistently in the 95-100 range.</li>
                        <li><strong>Effortless SEO:</strong> Complete control over title tags, JSON-LD schemas, and robots meta tags directly in your Blade layout.</li>
                        <li><strong>Simpler Tooling:</strong> No separate Node server required for SSR. Deploy effortlessly onto standard web servers like Laragon, Apache, or Nginx.</li>
                    </ul>

                    <blockquote>
                        "Server-rendered HTML is the ultimate resilient format for the web. It works everywhere, loads fast, and is indexed immediately by every search crawler."
                    </blockquote>

                    <h2>Writing Clean, Maintainable Blade Views</h2>
                    <p>By leveraging Blade components and layouts, you maintain total separation of concerns while keeping your codebase cohesive and enjoyable to evolve.</p>
                ',
                'categories' => ['web-development', 'technology'],
                'tags' => ['laravel', 'php', 'blade', 'seo'],
                'meta_title' => 'High-Performance Websites with Laravel Blade - SEO & Speed Guide',
                'meta_description' => 'Learn how building websites with pure Laravel Blade and Tailwind CSS delivers unbeatable performance, simple architecture, and top SEO rankings.',
                'meta_keywords' => 'laravel blade, pure blade, laravel seo, web performance',
                'og_title' => 'High-Performance Websites with Laravel and Pure Blade',
                'og_description' => 'Unbeatable speed and pristine SEO using server-rendered Laravel Blade.',
                'og_image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1400&q=80',
                'is_indexable' => true,
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Mastering Technical SEO: The Complete Checklist for Modern Publishers',
                'slug' => 'mastering-technical-seo-complete-checklist-for-modern-publishers',
                'excerpt' => 'A comprehensive guide to optimizing metadata, Open Graph cards, canonical URLs, and indexability controls for maximum Google visibility.',
                'featured_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1400&q=80',
                'status' => 'published',
                'published_at' => now()->subDay(),
                'content' => '
                    <p>Search Engine Optimization is not just about keywords; it is about providing clear, structured signals that assist search engines in indexing and highlighting your best material.</p>

                    <h2>1. The Power of Meta Titles & Descriptions</h2>
                    <p>Your <strong>Title Tag</strong> remains one of the strongest organic ranking signals. Aim for 50-60 characters with your core topic near the front. Your <strong>Meta Description</strong> serves as your advertisement in search results—keep it between 140-160 characters with a compelling call to action.</p>

                    <h2>2. Open Graph & Social Sharing</h2>
                    <p>When readers share your articles across Facebook, LinkedIn, Twitter, or Slack, Open Graph tags determine whether you get a rich card with an eye-catching photo or an unstyled link. Always specify:</p>
                    <ul>
                        <li><code>og:title</code> & <code>og:description</code></li>
                        <li><code>og:image</code> (ideally 1200x630 pixels)</li>
                        <li><code>og:type</code> set to "article"</li>
                    </ul>

                    <h2>3. Canonical URLs</h2>
                    <p>Canonical tags prevent duplicate content issues when an article is shared across multiple query strings, categories, or syndicated channels.</p>
                ',
                'categories' => ['technology', 'web-development'],
                'tags' => ['seo', 'web-design', 'laravel'],
                'meta_title' => 'Technical SEO Checklist for Modern Publishers - Recovery Data',
                'meta_description' => 'Master technical SEO: titles, meta descriptions, Open Graph cards, and canonical tags to dominate search engine results.',
                'meta_keywords' => 'technical seo, open graph, meta tags, search rankings',
                'og_title' => 'Mastering Technical SEO: The Complete Publisher Checklist',
                'og_description' => 'Optimize metadata, social cards, and canonical tags for peak Google rankings.',
                'og_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1400&q=80',
                'is_indexable' => true,
            ],
            [
                'user_id' => $editor->id,
                'title' => 'The Evolution of AI Coding Assistants and Autonomous Agents',
                'slug' => 'evolution-of-ai-coding-assistants-and-autonomous-agents',
                'excerpt' => 'How next-generation agentic workflows are changing software engineering from line-by-line autocompletion to end-to-end full stack execution.',
                'featured_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1400&q=80',
                'status' => 'published',
                'published_at' => now()->subHours(8),
                'content' => '
                    <p>The transition from passive code completion to proactive, tool-equipped autonomous agents represents the most significant shift in software development in decades.</p>

                    <h2>From Chatbots to Autonomous Agents</h2>
                    <p>Traditional AI tools acted primarily as interactive question-and-answer engines. Modern agents, by contrast, possess the capability to inspect environments, invoke specialized CLI tools, run migrations, and test systems in real time.</p>

                    <h3>What Makes an Agent Powerful?</h3>
                    <ul>
                        <li><strong>Deep Context Integration:</strong> Access to codebase architecture, schema rules, and conventions.</li>
                        <li><strong>Multi-step Verification:</strong> The capacity to execute tests, diagnose compiler errors, and refine code before finalizing.</li>
                        <li><strong>Domain Tools:</strong> Specialized interfaces for database inspection, asset compilation, and deployment pipelines.</li>
                    </ul>

                    <p>By pairing skilled developers with agentic pair programmers, product development cycles accelerate by orders of magnitude.</p>
                ',
                'categories' => ['artificial-intelligence', 'technology'],
                'tags' => ['ai-agents', 'architecture', 'full-stack'],
                'meta_title' => 'The Evolution of AI Coding Assistants & Autonomous Agents',
                'meta_description' => 'Explore the leap from code completion to autonomous agentic software development and pair programming.',
                'meta_keywords' => 'ai agents, autonomous coding, artificial intelligence, software engineering',
                'og_title' => 'AI Coding Assistants & Autonomous Agents: The Future of Software',
                'og_description' => 'How autonomous agents are transforming full-stack development.',
                'og_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1400&q=80',
                'is_indexable' => true,
            ],
        ];

        foreach ($postsData as $pData) {
            $catSlugs = $pData['categories'];
            $tagSlugs = $pData['tags'];
            unset($pData['categories'], $pData['tags']);

            $post = Post::firstOrCreate(['slug' => $pData['slug']], $pData);

            $catIds = Category::whereIn('slug', $catSlugs)->pluck('id');
            $post->categories()->sync($catIds);

            $tagIds = Tag::whereIn('slug', $tagSlugs)->pluck('id');
            $post->tags()->sync($tagIds);
        }

        // 6. Seed Services and Products
        $this->call(ServicesAndProductsSeeder::class);
    }
}
