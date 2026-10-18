@extends('layouts.app')

@section('seo_meta')
    <title>{{ $product->seo_title }}</title>
    <meta name="description" content="{{ $product->seo_description }}">
    <link rel="canonical" href="{{ $product->canonical_url ?: route('product.show', $product->slug) }}">
    <meta property="og:title" content="{{ $product->og_title ?: $product->seo_title }}">
    <meta property="og:description" content="{{ $product->og_description ?: $product->seo_description }}">
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ route('product.show', $product->slug) }}">
    @if($product->seo_image)
        <meta property="og:image" content="{{ $product->seo_image }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">

    <!-- Schema.org SoftwareApplication JSON-LD -->
    <script type="application/ld+json">
    {!! json_encode([
      '@@context' => 'https://schema.org',
      '@type' => 'SoftwareApplication',
      'name' => $product->title,
      'headline' => $product->tagline,
      'description' => $product->seo_description,
      'applicationCategory' => $product->category ?: 'BusinessApplication',
      'operatingSystem' => 'Cloud-based SaaS',
      'author' => [
        '@type' => 'Organization',
        'name' => 'Recovery Data Dubai',
        'url' => 'https://recoverydata.ae',
      ],
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endsection

@section('content')
<div class="bg-slate-50 min-h-screen" x-data="{ demoModalOpen: false }">

    <!-- Hero Section (Darwinbox Product Showcase Style) -->
    <section class="relative bg-gradient-to-b from-brand-950 via-slate-900 to-slate-900 text-white pt-24 pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#555cce 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="absolute -top-32 right-10 w-96 h-96 bg-brand-600/25 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-600"></i>
                <a href="{{ route('product.index') }}" class="hover:text-white transition">Products</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-600"></i>
                <span class="text-brand-300 font-semibold">{{ $product->title }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Hero Text (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="flex flex-wrap items-center gap-3">
                        @if($product->badge)
                            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-brand-500/20 text-brand-300 border border-brand-500/30 backdrop-blur-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-2"></span>
                                {{ $product->badge }}
                            </span>
                        @endif
                        @if($product->category)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-slate-300 border border-white/15">
                                {{ $product->category }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black font-display tracking-tight text-white leading-tight">
                        {{ $product->title }}
                    </h1>

                    @if($product->tagline)
                        <p class="text-lg sm:text-xl font-medium text-brand-300">
                            {{ $product->tagline }}
                        </p>
                    @endif

                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl">
                        {{ $product->summary }}
                    </p>

                    <!-- CTAs -->
                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <button @click="demoModalOpen = true" class="px-7 py-3.5 rounded-xl font-bold text-sm bg-brand-600 hover:bg-brand-500 text-white shadow-xl shadow-brand-600/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span>Schedule Live 1-on-1 Demo</span>
                        </button>

                        @if($product->demo_url)
                            <a href="{{ $product->demo_url }}" target="_blank" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-white/10 hover:bg-white/20 text-white border border-white/20 transition flex items-center gap-2">
                                <i data-lucide="play" class="w-4 h-4 text-brand-300"></i>
                                <span>Interactive Walkthrough</span>
                            </a>
                        @endif

                        <a href="tel:+971569763386" class="px-5 py-3.5 rounded-xl font-bold text-sm text-slate-300 hover:text-white transition flex items-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4 text-emerald-400"></i>
                            <span>Talk to Dubai Consultant</span>
                        </a>
                    </div>

                    <!-- Trust Stats Mini Row -->
                    <div class="pt-6 border-t border-slate-800 flex flex-wrap gap-8 text-xs text-slate-400">
                        <div class="flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                            <span>100% UAE Compliant</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="globe-2" class="w-4 h-4 text-brand-300"></i>
                            <span>Arabic & English Bilingual</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="cloud" class="w-4 h-4 text-cyan-300"></i>
                            <span>UAE In-Country Cloud</span>
                        </div>
                    </div>
                </div>

                <!-- Right Hero Dashboard Mockup Card (5 cols) -->
                <div class="lg:col-span-5">
                    <div class="relative rounded-3xl bg-gradient-to-br from-slate-900 via-brand-950 to-slate-950 p-6 sm:p-8 border border-brand-500/30 shadow-2xl overflow-hidden">
                        <div class="absolute top-0 right-0 w-48 h-48 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>

                        <!-- Top Simulated Window Controls -->
                        <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-6">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                <span class="text-xs font-mono text-slate-400 ml-2">portal.recoverydata.ae/{{ $product->slug }}</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                ACTIVE INSTANCE
                            </span>
                        </div>

                        <!-- Product Highlights Inside Mockup -->
                        <div class="space-y-4">
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-brand-600 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $product->icon ?: 'layers' }}" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">{{ $product->title }}</h4>
                                    <p class="text-xs text-slate-300">{{ $product->tagline ?: 'Enterprise Core Solution' }}</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800 space-y-3">
                                <div class="flex justify-between text-xs text-slate-300">
                                    <span class="font-medium">Onboarding Velocity</span>
                                    <span class="font-bold text-emerald-400">Under 14 Days</span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-brand-500 to-emerald-400 h-2 rounded-full w-4/5"></div>
                                </div>
                                <div class="flex justify-between text-[11px] text-slate-400 pt-1">
                                    <span>Legacy ERP: 6+ months</span>
                                    <span>Recovery Data: Fast Track</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3.5 rounded-xl bg-white/5 border border-white/5 text-center">
                                    <div class="text-lg font-black text-white">99.99%</div>
                                    <div class="text-[11px] text-slate-400">Uptime SLA</div>
                                </div>
                                <div class="p-3.5 rounded-xl bg-white/5 border border-white/5 text-center">
                                    <div class="text-lg font-black text-brand-300">32+ Years</div>
                                    <div class="text-[11px] text-slate-400">Lifeguard Trust</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-800/80 text-center">
                            <button @click="demoModalOpen = true" class="w-full py-2.5 rounded-xl text-xs font-bold bg-white text-slate-900 hover:bg-slate-100 transition shadow">
                                Request Private Sandbox Access
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Main Content Area: Modules & Highlights Grid -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Modules Grid (Darwinbox Style Product Architecture) -->
        @if(!empty($product->modules) && is_array($product->modules))
            <div class="mb-20">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <span class="text-xs font-bold uppercase tracking-widest text-brand-600">Product Architecture</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-2 font-display">
                        Core Modules &amp; Functionality
                    </h2>
                    <p class="text-slate-600 text-sm mt-2">
                        Everything your organization needs, fully integrated into a single cohesive platform.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($product->modules as $index => $module)
                        @php
                            $moduleName = is_array($module) ? ($module['name'] ?? '') : $module;
                            $moduleDesc = is_array($module) ? ($module['desc'] ?? '') : 'Seamlessly configured with automated triggers, role-based access, and enterprise audit logging for continuous compliance.';
                        @endphp
                        <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-sm hover:shadow-md hover:border-brand-300 transition group">
                            <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm mb-4 group-hover:bg-brand-600 group-hover:text-white transition">
                                {{ sprintf("%02d", $index + 1) }}
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition">{{ $moduleName }}</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                {{ $moduleDesc }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Deep Dive & Highlights -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left: Full Description & Features (8 cols) -->
            <div class="lg:col-span-8 space-y-12">
                
                <!-- Detailed Content -->
                @if($product->content)
                    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm">
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight mb-6">Overview & Capability Breakdown</h3>
                        <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-sm sm:text-base">
                            {!! str_contains($product->content, '<') ? $product->content : nl2br(e($product->content)) !!}
                        </div>
                    </div>
                @endif

                <!-- Highlights Checklist -->
                @if(!empty($product->highlights) && is_array($product->highlights))
                    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Key Technical Highlights & Standards</h3>
                                <p class="text-xs text-slate-500">Enterprise security, SLA commitments, and compliance benchmarks</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($product->highlights as $highlight)
                                @php
                                    $highlightText = is_array($highlight) ? ($highlight['text'] ?? ($highlight['name'] ?? implode(' ', $highlight))) : $highlight;
                                @endphp
                                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-brand-600 shrink-0 mt-0.5"></i>
                                    <span class="text-xs sm:text-sm font-semibold text-slate-800">{{ $highlightText }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- UAE Localization & Regulatory Assurance -->
                <div class="bg-gradient-to-br from-brand-900 to-slate-900 text-white rounded-3xl p-8 sm:p-10 shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-300 block mb-2">UAE Regional Excellence</span>
                        <h3 class="text-2xl font-bold text-white mb-3">Designed Exclusively for UAE Organizations</h3>
                        <p class="text-sm text-slate-300 leading-relaxed mb-6">
                            From MOHRE labor contract rules to UAE Central Bank SIF validation, our systems guarantee that your company stays 100% audit-proof and compliant without hiring external consultancies.
                        </p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                            <div class="p-3 rounded-xl bg-white/10 backdrop-blur-sm">
                                <span class="font-bold text-white block">WPS SIF Export</span>
                                <span class="text-slate-300 text-[11px]">Direct upload to ENBD, FAB, ADCB, DIB, and all UAE banks.</span>
                            </div>
                            <div class="p-3 rounded-xl bg-white/10 backdrop-blur-sm">
                                <span class="font-bold text-white block">Gratuity Calculator</span>
                                <span class="text-slate-300 text-[11px]">Real-time accruals per UAE Decree Law 33.</span>
                            </div>
                            <div class="p-3 rounded-xl bg-white/10 backdrop-blur-sm">
                                <span class="font-bold text-white block">Local Data Center</span>
                                <span class="text-slate-300 text-[11px]">Data never leaves UAE sovereign borders.</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Sticky Sidebar: Consultation Form & Direct Contact (4 cols) -->
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                
                <!-- Quick Demo Form Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase text-brand-600 mb-2">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        <span>Book Live Demo</span>
                    </div>
                    <h4 class="text-xl font-black text-slate-900">Experience {{ $product->title }}</h4>
                    <p class="text-xs text-slate-500 mt-1 mb-6">Get a personalized walkthrough with a senior UAE solutions architect.</p>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="subject" value="Demo Request for {{ $product->title }}">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Your Name</label>
                            <input type="text" name="name" required placeholder="Tariq Al Mansoori" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Work Email</label>
                            <input type="email" name="email" required placeholder="tariq@company.ae" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">UAE Phone</label>
                            <input type="tel" name="phone" placeholder="+971 50 123 4567" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Organization & Headcount</label>
                            <input type="text" name="company" placeholder="e.g. 50-250 Employees" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl font-bold text-xs bg-brand-600 hover:bg-brand-500 text-white shadow-lg shadow-brand-600/30 transition">
                            Request Demo Session
                        </button>
                    </form>
                </div>

                <!-- Direct Contact Card -->
                <div class="bg-slate-900 text-white rounded-3xl p-6 border border-slate-800 space-y-4">
                    <h5 class="text-sm font-bold text-white">Need Immediate Technical Answers?</h5>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Speak directly with our Dubai engineering team. We are available Sunday to Friday.
                    </p>
                    <div class="space-y-2 text-xs">
                        <a href="tel:+971569763386" class="flex items-center gap-2 text-brand-300 hover:text-white font-semibold transition">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                            <span>+971 56 976 3386</span>
                        </a>
                        <a href="mailto:info@recoverydata.ae" class="flex items-center gap-2 text-brand-300 hover:text-white font-semibold transition">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            <span>info@recoverydata.ae</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Related Products Section -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="mt-24 pt-16 border-t border-slate-200">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-brand-600">Enterprise Ecosystem</span>
                        <h3 class="text-2xl font-bold text-slate-900 mt-1">Explore Other Recovery Data Solutions</h3>
                    </div>
                    <a href="{{ route('product.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 transition flex items-center gap-1">
                        <span>View All Products</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedProducts as $rel)
                        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-brand-300 transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                                        <i data-lucide="{{ $rel->icon ?: 'layers' }}" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-slate-900">{{ $rel->title }}</h4>
                                        <span class="text-[11px] text-slate-400">{{ $rel->category }}</span>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 line-clamp-2 mt-2 leading-relaxed">{{ $rel->summary }}</p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                                <a href="{{ route('product.show', $rel->slug) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                                    <span>Learn more</span>
                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </a>
                                @if($rel->badge)
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $rel->badge }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </section>

    <!-- Modal for Scheduling a Demo -->
    <div x-show="demoModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
            <div x-show="demoModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="demoModalOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="demoModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 p-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-600 block">Schedule 1-on-1 Walkthrough</span>
                        <h3 class="text-xl font-black text-slate-900">{{ $product->title }} Demo</h3>
                    </div>
                    <button @click="demoModalOpen = false" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('contact.submit') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <input type="hidden" name="subject" value="Demo Request for {{ $product->title }}">
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Full Name</label>
                        <input type="text" name="name" required placeholder="e.g. Tariq Al Mansoori" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Work Email</label>
                            <input type="email" name="email" required placeholder="tariq@company.ae" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">UAE Phone</label>
                            <input type="tel" name="phone" placeholder="+971 50 123 4567" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Company / Organization</label>
                        <input type="text" name="company" placeholder="e.g. Al Habtoor Enterprises" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Questions / Specific Use Case</label>
                        <textarea name="message" rows="3" placeholder="Tell us about your current tools, team size, or implementation timeline..." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 rounded-xl font-bold text-xs bg-brand-600 hover:bg-brand-500 text-white shadow-lg shadow-brand-600/30 transition">
                            Confirm Demo Request
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
