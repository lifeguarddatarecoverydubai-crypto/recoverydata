@extends('layouts.app')

@section('seo_meta')
    @php
        $pageSuffix = ($products instanceof \Illuminate\Contracts\Pagination\Paginator && $products->currentPage() > 1) ? " - Page {$products->currentPage()}" : '';
        $canonicalParams = array_filter([
            'category' => $category,
            'page' => ($products instanceof \Illuminate\Contracts\Pagination\Paginator && $products->currentPage() > 1) ? $products->currentPage() : null,
        ]);
        $canonicalUrl = !empty($canonicalParams) ? route('product.index', $canonicalParams) : route('product.index');
        $titlePrefix = $category ? (ucfirst($category) . ' Enterprise Products & Software') : 'Enterprise Products & Software Suite Dubai';
        $pageTitle = $titlePrefix . $pageSuffix . ' | ' . config('app.name', 'Recovery Data');
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="Discover Recovery Data's enterprise software solutions tailored for UAE businesses. Cleanroom diagnostic software, laboratory management, and enterprise data solutions.">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="Next-gen enterprise data systems and cleanroom laboratory software solutions tailored for UAE organizations.">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="website">
@endsection

@section('content')
<div class="bg-slate-50 min-h-screen" x-data="{ demoModalOpen: false, selectedProductTitle: 'Recovery Data Enterprise Suite' }">

    <!-- Hero Header (Darwinbox Enterprise Style) -->
    <section class="relative bg-gradient-to-b from-brand-950 via-slate-900 to-slate-900 text-white pt-24 pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#555cce 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-600"></i>
                <span class="text-brand-300 font-semibold">Products</span>
            </nav>

            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-500/20 text-brand-300 border border-brand-500/30 mb-6 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>ENTERPRISE SAAS ECOSYSTEM</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black font-display tracking-tight leading-tight">
                    Intelligent Software Built for the <span class="bg-gradient-to-r from-brand-300 via-indigo-200 to-cyan-300 bg-clip-text text-transparent">Modern Enterprise</span>
                </h1>
                <p class="mt-6 text-lg sm:text-xl text-slate-300 leading-relaxed font-light">
                    Transform your data resilience, diagnostic workflows, systems, and enterprise storage operations with Recovery Data’s specialized software platform — built for UAE organizations.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="#products-list" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-brand-600 hover:bg-brand-500 text-white shadow-lg shadow-brand-600/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                        <span>Explore All Products</span>
                        <i data-lucide="arrow-down" class="w-4 h-4"></i>
                    </a>
                    <button @click="demoModalOpen = true; selectedProductTitle = 'Recovery Data Enterprise Suite'" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-sm transition flex items-center gap-2">
                        <i data-lucide="play-circle" class="w-4 h-4 text-brand-300"></i>
                        <span>Request Custom Demo</span>
                    </button>
                </div>
            </div>

            <!-- Quick Trust Bar -->
            <div class="mt-16 pt-10 border-t border-slate-800/80 grid grid-cols-2 md:grid-cols-4 gap-6 text-slate-300">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-500/20 border border-brand-500/30 flex items-center justify-center text-brand-300">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white">100% UAE Compliant</div>
                        <div class="text-xs text-slate-400">MOHRE & WPS Certified</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-500/20 border border-brand-500/30 flex items-center justify-center text-brand-300">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white">Gen-AI Native</div>
                        <div class="text-xs text-slate-400">Autonomous Workflows</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-500/20 border border-brand-500/30 flex items-center justify-center text-brand-300">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white">32-Year Legacy</div>
                        <div class="text-xs text-slate-400">Lifeguard Technology</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-500/20 border border-brand-500/30 flex items-center justify-center text-brand-300">
                        <i data-lucide="server" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white">UAE Cloud Hosting</div>
                        <div class="text-xs text-slate-400">In-Country Data Residency</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Filter Bar & Products List -->
    <section id="products-list" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Pills -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-10 border-b border-slate-200">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Our Enterprise Applications</h2>
                <p class="text-sm text-slate-500 mt-1">Select a category or explore our full platform catalog</p>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('product.index') }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !$category ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    All Products ({{ $products->count() }})
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('product.index', ['category' => $cat]) }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $category === $cat ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Product Cards Showcase (Darwinbox Inspired Card Layout) -->
        <div class="mt-12 space-y-12">
            @forelse($products as $prod)
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-0 items-stretch">
                        
                        <!-- Left Details (7 cols) -->
                        <div class="p-8 sm:p-10 lg:col-span-7 flex flex-col justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    @if($prod->badge)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5 mr-1.5 text-brand-600"></i>
                                            {{ $prod->badge }}
                                        </span>
                                    @endif
                                    @if($prod->category)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            {{ $prod->category }}
                                        </span>
                                    @endif
                                    @if($prod->is_featured)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <i data-lucide="star" class="w-3 h-3 mr-1 fill-amber-500 text-amber-500"></i> Featured
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight group-hover:text-brand-600 transition">
                                    <a href="{{ route('product.show', $prod->slug) }}">{{ $prod->title }}</a>
                                </h3>

                                @if($prod->tagline)
                                    <p class="text-sm font-semibold text-brand-600 mt-1">{{ $prod->tagline }}</p>
                                @endif

                                <p class="text-slate-600 text-sm sm:text-base leading-relaxed mt-4">
                                    {{ $prod->summary }}
                                </p>

                                <!-- Core Modules Chips -->
                                @if(!empty($prod->modules) && is_array($prod->modules))
                                    <div class="mt-6">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-2.5">Key Capability Modules:</span>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($prod->modules as $module)
                                                @php
                                                    $moduleName = is_array($module) ? ($module['name'] ?? '') : $module;
                                                @endphp
                                                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200/80 hover:border-brand-300 transition">
                                                    {{ $moduleName }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Key Highlights Checklist -->
                                @if(!empty($prod->highlights) && is_array($prod->highlights))
                                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        @foreach(array_slice($prod->highlights, 0, 4) as $highlight)
                                            @php
                                                $highlightText = is_array($highlight) ? ($highlight['text'] ?? ($highlight['name'] ?? implode(' ', $highlight))) : $highlight;
                                            @endphp
                                            <div class="flex items-start gap-2 text-xs sm:text-sm text-slate-700 font-medium">
                                                <i data-lucide="check" class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5"></i>
                                                <span>{{ $highlightText }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- CTA Actions -->
                            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center gap-4">
                                <a href="{{ route('product.show', $prod->slug) }}" class="px-6 py-2.5 rounded-xl font-bold text-xs sm:text-sm bg-brand-600 hover:bg-brand-500 text-white shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                                    <span>Product Details & Specs</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                                <button @click="demoModalOpen = true; selectedProductTitle = '{{ $prod->title }}'" class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm bg-slate-100 hover:bg-slate-200 text-slate-800 transition flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4 text-brand-600"></i>
                                    <span>Schedule 1-on-1 Demo</span>
                                </button>
                                @if($prod->demo_url)
                                    <a href="{{ $prod->demo_url }}" target="_blank" class="text-xs font-bold text-slate-500 hover:text-brand-600 transition flex items-center gap-1">
                                        <span>Interactive Walkthrough</span>
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Right Visual Panel (5 cols) -->
                        <div class="lg:col-span-5 bg-gradient-to-br from-brand-950 via-slate-900 to-brand-900 text-white p-8 sm:p-10 flex flex-col justify-between relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-60 h-60 bg-brand-500/20 rounded-full blur-2xl pointer-events-none"></div>

                            <div class="relative z-10 space-y-6">
                                <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md flex items-center justify-center text-brand-300 shadow-inner">
                                    <i data-lucide="{{ $prod->icon ?: 'layers' }}" class="w-7 h-7"></i>
                                </div>

                                <div>
                                    <span class="text-[11px] font-bold uppercase tracking-widest text-brand-300 block">Enterprise Feature Matrix</span>
                                    <h4 class="text-xl font-bold text-white mt-1">Built to Scale with High-Performance Teams</h4>
                                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                                        Designed for rapid onboarding with zero disruption to existing core banking, ERP, or communication workflows.
                                    </p>
                                </div>

                                <!-- UI Mockup Box -->
                                <div class="bg-slate-950/70 border border-white/10 rounded-2xl p-4 backdrop-blur-sm space-y-3 shadow-2xl">
                                    <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div>
                                            <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                            <span class="text-[10px] text-slate-400 font-mono ml-2">{{ $prod->slug }}.app.recoverydata.ae</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950/80 px-2 py-0.5 rounded border border-emerald-500/30">Live 99.99%</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="h-2 bg-brand-500/40 rounded w-3/4 animate-pulse"></div>
                                        <div class="h-2 bg-slate-700/60 rounded w-1/2"></div>
                                        <div class="h-2 bg-slate-700/40 rounded w-5/6"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 pt-2">
                                        <div class="bg-white/5 rounded-lg p-2 text-center">
                                            <div class="text-[10px] text-slate-400">Response SLA</div>
                                            <div class="text-xs font-bold text-white">&lt; 15 mins</div>
                                        </div>
                                        <div class="bg-white/5 rounded-lg p-2 text-center">
                                            <div class="text-[10px] text-slate-400">Deployment</div>
                                            <div class="text-xs font-bold text-brand-300">UAE Sovereign Cloud</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative z-10 pt-6 mt-6 border-t border-white/10 flex items-center justify-between text-xs text-slate-400">
                                <span>Dubai Data Center Hosted</span>
                                <span class="font-semibold text-white">ISO 27001 Certified</span>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
                    <i data-lucide="inbox" class="w-12 h-12 text-slate-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-bold text-slate-800">No products found in this category</h3>
                    <p class="text-sm text-slate-500 mt-1">Please browse another category or view all products.</p>
                    <a href="{{ route('product.index') }}" class="inline-block mt-4 px-5 py-2.5 rounded-xl bg-brand-600 text-white font-bold text-xs">
                        View All Products
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Why Recovery Data vs Legacy Enterprise Software -->
    <section class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-600">The Recovery Data Advantage</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2 font-display">
                    Why UAE Enterprises Choose Recovery Data Over Legacy Suites
                </h2>
                <p class="mt-4 text-slate-600 text-base">
                    Unlike rigid global systems that require months of third-party customization, Recovery Data software solutions are built specifically for UAE enterprise standards and bilingual operations.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 hover:shadow-lg transition">
                    <div class="w-12 h-12 rounded-2xl bg-brand-600 text-white flex items-center justify-center mb-6 shadow-md shadow-brand-600/30">
                        <i data-lucide="scale" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">UAE Labor Law & WPS Native</h3>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                        Automatic calculation of UAE End-of-Service Gratuity (Federal Decree-Law No. 33), SIF file generation for all UAE Central Bank authorized banks, and MOHRE compliance audits built in.
                    </p>
                    <div class="mt-6 pt-4 border-t border-slate-200 text-xs font-bold text-brand-600 flex items-center gap-1">
                        <span>Zero manual SIF corrections</span>
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </div>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 hover:shadow-lg transition">
                    <div class="w-12 h-12 rounded-2xl bg-brand-900 text-white flex items-center justify-center mb-6 shadow-md shadow-brand-900/30">
                        <i data-lucide="bot" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Embedded Autonomous AI</h3>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                        AI isn’t a bolt-on feature. From automated CV resume parsing to smart payroll anomaly alerts, proactive lead scoring, and automated customer follow-ups on WhatsApp Cloud API.
                    </p>
                    <div class="mt-6 pt-4 border-t border-slate-200 text-xs font-bold text-brand-600 flex items-center gap-1">
                        <span>Save 20+ hours per HR & Sales rep/week</span>
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </div>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 hover:border-brand-300 hover:shadow-lg transition">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center mb-6 shadow-md shadow-emerald-600/30">
                        <i data-lucide="map-pin" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Local Dubai Engineers & 5 Offices</h3>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                        No overseas ticketing queues. Backed by Lifeguard Technology’s 32-year UAE legacy with on-ground consultants across Al Barsha, Sheikh Zayed Rd, Bur Dubai, Abu Dhabi, and Sharjah.
                    </p>
                    <div class="mt-6 pt-4 border-t border-slate-200 text-xs font-bold text-emerald-600 flex items-center gap-1">
                        <span>Dedicated UAE Account Manager</span>
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom Enterprise Consultation CTA -->
    <section class="py-16 bg-gradient-to-r from-brand-950 via-slate-950 to-brand-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-black font-display tracking-tight">Ready to see our products in action?</h2>
            <p class="mt-4 text-slate-300 max-w-2xl mx-auto text-base">
                Book a personalized, 30-minute demonstration tailored to your company’s headcount, industry, and UAE operational workflow.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <button @click="demoModalOpen = true; selectedProductTitle = 'Recovery Data Enterprise Suite'" class="px-8 py-3.5 rounded-xl font-bold text-sm bg-brand-600 hover:bg-brand-500 text-white shadow-xl shadow-brand-600/30 transition transform hover:-translate-y-0.5">
                    Schedule Live Interactive Demo
                </button>
                <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-xl font-bold text-sm bg-white/10 hover:bg-white/20 text-white border border-white/20 transition">
                    Contact Enterprise Sales
                </a>
            </div>
        </div>
    </section>

    <!-- Demo Request Modal (Alpine.js) -->
    <div x-show="demoModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
            <div x-show="demoModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="demoModalOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="demoModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 p-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-600 block">Recovery Data Enterprise Demo</span>
                        <h3 class="text-xl font-black text-slate-900" x-text="'Request Demo: ' + selectedProductTitle"></h3>
                    </div>
                    <button @click="demoModalOpen = false" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('contact.submit') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <input type="hidden" name="subject" :value="'Product Demo Request: ' + selectedProductTitle">
                    
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
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Requirements / Headcount</label>
                        <textarea name="message" rows="3" placeholder="Tell us about your team size, current software, and specific requirements..." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 rounded-xl font-bold text-xs bg-brand-600 hover:bg-brand-500 text-white shadow-lg shadow-brand-600/30 transition">
                            Submit Demo Request
                        </button>
                        <p class="text-[11px] text-center text-slate-400 mt-2">
                            A UAE solutions architect will contact you within 2 hours.
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
