@extends('layouts.app')

@section('seo_meta')
    <title>{{ $service->seo_title }}</title>
    <meta name="description" content="{{ $service->seo_description }}">
    <link rel="canonical" href="{{ $service->canonical_url ?: route('service.show', $service->slug) }}">
    <meta property="og:title" content="{{ $service->og_title ?: $service->seo_title }}">
    <meta property="og:description" content="{{ $service->og_description ?: $service->seo_description }}">
    <meta property="og:type" content="service">
    <meta property="og:url" content="{{ route('service.show', $service->slug) }}">
    @if($service->seo_image)
        <meta property="og:image" content="{{ $service->seo_image }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">

    @php
        $hasCustomSchema = !empty($service->schema_markup);
        $hasCustomService = $hasCustomSchema && str_contains($service->schema_markup, 'Service');
        $hasCustomBreadcrumbs = $hasCustomSchema && str_contains($service->schema_markup, 'BreadcrumbList');
        $hasCustomFaq = $hasCustomSchema && str_contains($service->schema_markup, 'FAQPage');
    @endphp

    @if(!$hasCustomService)
    <!-- Schema.org Service JSON-LD -->
    <script type="application/ld+json">
    {!! json_encode([
      '@@context' => 'https://schema.org',
      '@type' => 'Service',
      'name' => $service->title,
      'description' => $service->seo_description,
      'provider' => [
        '@type' => 'LocalBusiness',
        'name' => 'Recovery Data Dubai',
        'url' => 'https://www.recoverydata.ae',
        'telephone' => '+971564101040',
        'address' => [
          '@type' => 'PostalAddress',
          'streetAddress' => 'Office # 508, Dusseldorf Business Point, Al Barsha 1',
          'addressLocality' => 'Dubai',
          'addressRegion' => 'Dubai',
          'addressCountry' => 'AE',
        ],
      ],
      'areaServed' => [
        'Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Fujairah', 'Umm Al Quwain'
      ],
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    @if(!$hasCustomBreadcrumbs)
    <!-- BreadcrumbList Schema -->
    <script type="application/ld+json">
    {!! json_encode([
      '@@context' => 'https://schema.org',
      '@type' => 'BreadcrumbList',
      'itemListElement' => [
        [
          '@type' => 'ListItem',
          'position' => 1,
          'name' => 'Home',
          'item' => route('home'),
        ],
        [
          '@type' => 'ListItem',
          'position' => 2,
          'name' => 'Services',
          'item' => route('service.index'),
        ],
        [
          '@type' => 'ListItem',
          'position' => 3,
          'name' => $service->title,
          'item' => route('service.show', $service->slug),
        ],
      ],
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    @if(!$hasCustomFaq && !empty($service->faqs) && count($service->faqs) > 0)
    <!-- FAQPage Schema -->
    <script type="application/ld+json">
    {!! json_encode([
      '@@context' => 'https://schema.org',
      '@type' => 'FAQPage',
      'mainEntity' => array_map(function($faq) {
          return [
              '@type' => 'Question',
              'name' => $faq['question'],
              'acceptedAnswer' => [
                  '@type' => 'Answer',
                  'text' => $faq['answer'],
              ],
          ];
      }, $service->faqs),
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    @if($hasCustomSchema)
    <!-- Custom / Pre-Generated Schema.org JSON-LD -->
    @if(str_contains($service->schema_markup, '<script'))
    {!! $service->schema_markup !!}
    @else
    <script type="application/ld+json">
    {!! $service->schema_markup !!}
    </script>
    @endif
    @endif
@endsection

@section('content')
<div class="bg-slate-50 min-h-screen">

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-brand-950 via-slate-900 to-slate-900 text-white pt-20 pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#555cce 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="absolute -top-32 right-10 w-96 h-96 bg-brand-600/25 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-600"></i>
                <a href="{{ route('service.index') }}" class="hover:text-white transition">Services</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-600"></i>
                <span class="text-brand-300 font-semibold">{{ $service->title }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-8 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-500/20 text-brand-300 border border-brand-400/30 text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>{{ $service->effective_hero_badge }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black font-display tracking-tight text-white leading-tight">
                        {{ $service->title }}
                    </h1>

                    @if($service->tagline)
                        <p class="text-lg sm:text-xl font-medium text-brand-300">
                            {{ $service->tagline }}
                        </p>
                    @endif

                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl">
                        {{ $service->summary }}
                    </p>

                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <a href="#evaluation-box" class="px-7 py-3.5 rounded-xl font-bold text-sm bg-gradient-to-r from-brand-600 via-sky-500 to-brand-600 hover:from-brand-500 hover:to-sky-400 text-white shadow-xl shadow-brand-600/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                            <span>Request Free Lab Evaluation</span>
                            <i data-lucide="arrow-down" class="w-4 h-4"></i>
                        </a>
                        <a href="tel:+971564101040" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg transition flex items-center gap-2">
                            <i data-lucide="phone-call" class="w-4 h-4 text-emerald-200"></i>
                            <span>Call 24/7: +971 56 410 1040</span>
                        </a>
                        <a href="https://wa.me/971564101040?text={{ urlencode('Hello Recovery Data Dubai, I need urgent assistance with ' . $service->title) }}" target="_blank" class="px-5 py-3.5 rounded-xl font-bold text-sm bg-white/10 hover:bg-white/20 text-white border border-white/20 transition flex items-center gap-2">
                            <i data-lucide="message-circle" class="w-4 h-4 text-emerald-400"></i>
                            <span>WhatsApp Us</span>
                        </a>
                    </div>
                </div>

                <!-- Right Quick Metrics Badge -->
                <div class="lg:col-span-4 bg-slate-900/90 border border-brand-500/30 rounded-3xl p-6 sm:p-8 backdrop-blur-md space-y-4 shadow-2xl">
                    <div class="flex items-center justify-between border-b border-white/10 pb-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-brand-300">Cleanroom Lab Benchmarks</h4>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Verified</span>
                    </div>
                    
                    @php $benchmarks = $service->effective_benchmarks; @endphp
                    <div class="space-y-3">
                        <div class="p-3 rounded-xl bg-white/5 border border-white/5">
                            <div class="text-[11px] text-slate-400">Cleanroom Standard</div>
                            <div class="text-sm font-bold text-white mt-0.5">{{ $benchmarks['standard'] ?? 'ISO Class 100 / FED STD 209E' }}</div>
                        </div>

                        <div class="p-3 rounded-xl bg-white/5 border border-white/5">
                            <div class="text-[11px] text-slate-400">Evaluation Policy</div>
                            <div class="text-sm font-bold text-emerald-400 mt-0.5">{{ $benchmarks['evaluation'] ?? '100% Free Diagnostic & Quote' }}</div>
                        </div>

                        <div class="p-3 rounded-xl bg-white/5 border border-white/5">
                            <div class="text-[11px] text-slate-400">Payment Guarantee</div>
                            <div class="text-sm font-bold text-white mt-0.5">{{ $benchmarks['guarantee'] ?? 'No Data – No Charge Guarantee' }}</div>
                        </div>

                        <div class="p-3 rounded-xl bg-white/5 border border-white/5">
                            <div class="text-[11px] text-slate-400">Turnaround Options</div>
                            <div class="text-sm font-bold text-white mt-0.5">{{ $benchmarks['turnaround'] ?? 'Same-Day Emergency or 24-48h Standard' }}</div>
                        </div>

                        <div class="p-3 rounded-xl bg-white/5 border border-white/5">
                            <div class="text-[11px] text-slate-400">UAE Pickup & Drop</div>
                            <div class="text-sm font-bold text-sky-300 mt-0.5">{{ $benchmarks['courier'] ?? 'Free Courier Across All 7 Emirates' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Service Details -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left Details Column (8 cols) -->
            <div class="lg:col-span-8 space-y-12">

                <!-- Service Featured Banner -->
                @if($service->featured_image_url)
                    <div class="rounded-3xl overflow-hidden aspect-[21/9] sm:aspect-[16/7] border border-slate-200 shadow-sm bg-slate-900">
                        <img src="{{ $service->featured_image_url }}" alt="{{ $service->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/default-recovery.svg') }}';">
                    </div>
                @endif

                <!-- Detailed Service Content -->
                @if($service->content)

                    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-sm">
                        <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-sm sm:text-base">
                            {!! str_contains($service->content, '<') ? $service->content : nl2br(e($service->content)) !!}
                        </div>
                    </div>
                @endif

                <!-- Capabilities & Features Grid -->
                @if(!empty($service->features) && is_array($service->features))
                    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Core Technical Capabilities Included</h3>
                                <p class="text-xs text-slate-500">Forensic engineering and specialized laboratory deliverables</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($service->features as $feature)
                                <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100/80">
                                    <div class="w-6 h-6 rounded-lg bg-brand-100 text-brand-600 flex items-center justify-center shrink-0 mt-0.5">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <span class="text-xs sm:text-sm font-semibold text-slate-800">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Business Benefits / Why Choose Us -->
                @if(!empty($service->benefits) && is_array($service->benefits))
                    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i data-lucide="shield-check" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Why UAE Clients Choose Recovery Data Dubai</h3>
                                <p class="text-xs text-slate-500">Proven protection, guarantees, and certified laboratory standards</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @foreach($service->benefits as $benefit)
                                <div class="flex items-start gap-3 p-4 rounded-xl bg-emerald-50/50 border border-emerald-100 text-slate-800 text-xs sm:text-sm font-medium">
                                    <i data-lucide="arrow-right-circle" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
                                    <span>{{ $benefit }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Cleanroom Lab Trust Callout -->
                <div class="p-8 sm:p-10 rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-brand-950 text-white shadow-2xl border border-slate-800 flex flex-col sm:flex-row items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-brand-500/20 border border-brand-500/30 flex items-center justify-center shrink-0 text-brand-300 shadow-lg">
                        <i data-lucide="shield-check" class="w-8 h-8 text-emerald-400"></i>
                    </div>
                    <div class="space-y-1 text-center sm:text-left">
                        <h4 class="text-xl font-bold text-white">15+ Years of Certified Cleanroom Excellence in Dubai</h4>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                            Located in Al Barsha 1, Dubai, our laboratory combines ISO Class 100 clean benches, hardware-level ACELab PC-3000 diagnostics, and over 10,000 in-stock donor drives. We operate under strict legal Non-Disclosure Agreements (NDAs) to guarantee total confidentiality for personal photos, legal cases, and enterprise records.
                        </p>
                    </div>
                </div>

                <!-- Related Technical Guides & Lab Case Studies -->
                @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Related Technical Guides & Lab Case Studies</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Forensic recovery procedures, failure diagnostics, and real lab cases from our engineers</p>
                            </div>
                            <a href="{{ route('blog.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 hidden sm:inline-flex items-center gap-1">
                                <span>Browse All Articles</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            @foreach($relatedPosts as $rPost)
                                <article class="flex flex-col justify-between p-4 rounded-2xl bg-slate-50 hover:bg-brand-50/50 border border-slate-100 hover:border-brand-200 transition group space-y-3">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                                            <span class="text-brand-600 font-bold">Case Study</span>
                                            <span>&bull;</span>
                                            <span>{{ $rPost->reading_time }} min read</span>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-900 group-hover:text-brand-600 transition line-clamp-2 leading-snug">
                                            <a href="{{ route('post.show', $rPost->slug) }}">{{ $rPost->title }}</a>
                                        </h4>
                                        @if($rPost->excerpt)
                                            <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">
                                                {{ $rPost->excerpt }}
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{ route('post.show', $rPost->slug) }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-brand-600 group-hover:translate-x-0.5 transition">
                                        <span>Read Procedure</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Right Sticky Column: Form & Other Services (4 cols) -->
            <div class="lg:col-span-4 lg:sticky lg:top-24">
                @include('frontend.partials.service-sidebar', [
                    'service' => $service,
                    'otherServices' => $otherServices ?? null
                ])
            </div>
        </div>
    </section>

</div>
@endsection
