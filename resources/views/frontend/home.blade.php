@extends('layouts.app')

@section('seo_meta')
    <title>Data Recovery Dubai | Recovery Data - Certified Cleanroom Lab</title>
    <meta name="title" content="Data Recovery Dubai | Recovery Data - Certified Cleanroom Lab">
    <meta name="description" content="Professional data recovery services in Dubai. Hard drive, SSD, NVMe, RAID, and NAS recovery with certified ISO Class 100 cleanroom lab. No Data, No Charge.">
    <link rel="canonical" href="{{ route('home') }}">
    <meta property="og:title" content="Data Recovery Dubai | Recovery Data Cleanroom Lab">
    <meta property="og:description" content="Professional data recovery services in Dubai. Hard drive, SSD, NVMe, RAID, and NAS recovery with certified ISO Class 100 cleanroom lab. No Data, No Charge.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('home') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Data Recovery Dubai | Recovery Data Cleanroom Lab">
    <meta name="twitter:description" content="Professional data recovery in Dubai with certified ISO Class 100 cleanroom. No Data, No Charge guarantee.">
@endsection

@section('content')
<div class="space-y-24 sm:space-y-32">

    <!-- ================= 1. HERO SECTION ================= -->
    <section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white">
        <!-- Ambient Cleanroom Glows -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-brand-600/20 blur-[130px] rounded-full pointer-events-none"></div>
        <div class="absolute top-10 left-10 w-72 h-72 bg-sky-500/10 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Hero Top Badge -->
            <div class="text-center space-y-6 max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold text-brand-300 shadow-lg shadow-brand-950/40">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Dubai's Certified Data Recovery Laboratory</span>
                    <span class="text-white/40">|</span>
                    <span class="text-slate-300">ISO Class 100 Cleanroom</span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold font-display tracking-tight leading-[1.1] text-white">
                    Professional Data Recovery in Dubai with <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 via-sky-300 to-cyan-200">No Data, No Charge.</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-xl text-slate-300 max-w-2xl mx-auto font-normal leading-relaxed">
                    Recover critical files from mechanically damaged <strong>Hard Drives</strong>, corrupted <strong>SSDs & NVMe</strong>, failed <strong>RAID Arrays</strong>, <strong>NAS</strong>, <strong>CCTV Footage</strong>, and <strong>iPhones</strong>. Fast, secure, and completely confidential.
                </p>

                <!-- Dual CTAs -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('contact') }}" 
                       class="w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-brand-600 via-sky-500 to-brand-600 hover:from-brand-500 hover:to-sky-400 text-white font-bold text-sm shadow-xl shadow-brand-600/40 hover:shadow-brand-500/50 hover:scale-105 transition-all flex items-center justify-center gap-2 group">
                        <span>Request Free Lab Evaluation</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="tel:+971564101040" 
                       class="w-full sm:w-auto px-7 py-4 rounded-full bg-emerald-600/90 hover:bg-emerald-500 text-white font-semibold text-sm border border-emerald-400/30 backdrop-blur-md transition flex items-center justify-center gap-2 shadow-lg">
                        <i data-lucide="phone-call" class="w-4 h-4 text-emerald-200"></i>
                        <span>Call 24/7: +971 56 410 1040</span>
                    </a>
                </div>

                <!-- Trust Micro-Badges -->
                <div class="pt-8 flex flex-wrap items-center justify-center gap-6 text-xs text-slate-400 font-medium">
                    <div class="flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                        <span>100% Confidentiality & Non-Disclosure</span>
                    </div>
                    <span class="text-slate-700 hidden sm:inline">&bull;</span>
                    <div class="flex items-center gap-2">
                        <i data-lucide="award" class="w-4 h-4 text-sky-400"></i>
                        <span>98.7% Proven Lab Success Rate</span>
                    </div>
                    <span class="text-slate-700 hidden sm:inline">&bull;</span>
                    <div class="flex items-center gap-2">
                        <i data-lucide="zap" class="w-4 h-4 text-amber-400"></i>
                        <span>Same-Day Emergency Diagnostic</span>
                    </div>
                </div>

                <!-- Quick Lab Notification Strip -->
                <div class="mt-8 p-4 rounded-2xl bg-gradient-to-r from-slate-900 via-brand-950/70 to-slate-900 border border-brand-500/30 flex flex-col sm:flex-row items-center justify-between gap-4 text-left shadow-xl">
                    <div class="flex items-center gap-3.5">
                        <div class="p-2.5 rounded-xl bg-brand-500/20 text-brand-300 shrink-0 border border-brand-500/30">
                            <i data-lucide="map-pin" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black text-white">Al Barsha Cleanroom Lab — Dubai, UAE</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Open 6 Days</span>
                            </div>
                            <p class="text-[11px] text-slate-300 mt-0.5">Office # 508, Dusseldorf Business Point, Al Barsha 1 (Behind Mall of Emirates). Free drop-off inspection.</p>
                        </div>
                    </div>
                    <a href="{{ route('contact') }}" class="shrink-0 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                        <span>Get Directions</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

            <!-- Device Diagnostic Selector Cards -->
            <div class="mt-14 max-w-5xl mx-auto grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <a href="{{ route('service.show', 'hard-drive-recovery') }}" class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-brand-500 text-center transition group hover:-translate-y-1">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-brand-500/10 text-brand-400 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition">
                        <i data-lucide="hard-drive" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 mt-2.5 group-hover:text-white">Hard Drive</h4>
                    <span class="text-[10px] text-slate-400">Clicking / Dead</span>
                </a>
                <a href="{{ route('service.show', 'ssd-recovery-dubai') }}" class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-brand-500 text-center transition group hover:-translate-y-1">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition">
                        <i data-lucide="cpu" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 mt-2.5 group-hover:text-white">SSD & NVMe</h4>
                    <span class="text-[10px] text-slate-400">0GB / Not Detected</span>
                </a>
                <a href="{{ route('service.show', 'raid-recovery-dubai') }}" class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-brand-500 text-center transition group hover:-translate-y-1">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition">
                        <i data-lucide="server" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 mt-2.5 group-hover:text-white">RAID & Server</h4>
                    <span class="text-[10px] text-slate-400">Arrays 0, 1, 5, 10</span>
                </a>
                <a href="{{ route('service.show', 'nas-recovery-dubai') }}" class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-brand-500 text-center transition group hover:-translate-y-1">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition">
                        <i data-lucide="cloud" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 mt-2.5 group-hover:text-white">NAS Storage</h4>
                    <span class="text-[10px] text-slate-400">Synology & QNAP</span>
                </a>
                <a href="{{ route('service.show', 'cctv-footage-recovery-dubai') }}" class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-brand-500 text-center transition group hover:-translate-y-1">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition">
                        <i data-lucide="video" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 mt-2.5 group-hover:text-white">CCTV Video</h4>
                    <span class="text-[10px] text-slate-400">DVR / Overwritten</span>
                </a>
                <a href="{{ route('service.show', 'iphone-data-recovery-dubai') }}" class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-brand-500 text-center transition group hover:-translate-y-1">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition">
                        <i data-lucide="smartphone" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-xs font-bold text-slate-200 mt-2.5 group-hover:text-white">iPhone & Phone</h4>
                    <span class="text-[10px] text-slate-400">Water Damage / Dead</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ================= 2. CORE RECOVERY SERVICES GRID ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-4 max-w-3xl mx-auto">
            <h2 class="text-xs font-extrabold text-brand-600 uppercase tracking-widest">Specialized Recovery Solutions</h2>
            <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-display tracking-tight">
                Complete Laboratory Recovery Capabilities
            </h3>
            <p class="text-sm sm:text-base text-slate-600">
                We handle logical corruption, mechanical read-write head crashes, electronic board fires, liquid submersion, and encrypted ransomware attacks.
            </p>
        </div>

        <div class="mt-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $srv)
                <div class="p-7 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-brand-300 transition duration-300 flex flex-col justify-between group">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-brand-50 group-hover:bg-brand-600 text-brand-600 group-hover:text-white flex items-center justify-center transition shadow-xs">
                            <i data-lucide="{{ $srv->icon ?: 'hard-drive' }}" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition">
                            {{ $srv->title }}
                        </h4>
                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                            {{ $srv->summary }}
                        </p>

                        @if(!empty($srv->features) && is_array($srv->features))
                            <ul class="space-y-1.5 pt-2 text-xs text-slate-600">
                                @foreach(array_slice($srv->features, 0, 3) as $feat)
                                    <li class="flex items-center gap-2">
                                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500 shrink-0"></i>
                                        <span class="truncate">{{ $feat }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                            No Data, No Charge
                        </span>
                        <a href="{{ route('service.show', $srv->slug) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                            <span>Learn Details</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- ================= 3. FOUR-STEP RECOVERY PROCESS ================= -->
    <section class="bg-slate-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 max-w-2xl mx-auto">
                <span class="text-xs font-bold text-brand-400 uppercase tracking-widest">Transparent Procedure</span>
                <h3 class="text-3xl font-extrabold font-display">How Data Recovery Works at Our Dubai Lab</h3>
                <p class="text-sm text-slate-400">Zero surprises. You receive a guaranteed quote and file list preview before committing to any charge.</p>
            </div>

            <div class="mt-14 grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="relative p-6 rounded-2xl bg-slate-800/80 border border-slate-700/80 space-y-3">
                    <span class="w-8 h-8 rounded-full bg-brand-500 text-white font-bold text-xs flex items-center justify-center">1</span>
                    <h4 class="font-bold text-white text-base">Device Intake</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">Drop off your drive at our Al Barsha facility or arrange an urgent courier pickup throughout Dubai & Sharjah.</p>
                </div>
                <!-- Step 2 -->
                <div class="relative p-6 rounded-2xl bg-slate-800/80 border border-slate-700/80 space-y-3">
                    <span class="w-8 h-8 rounded-full bg-brand-500 text-white font-bold text-xs flex items-center justify-center">2</span>
                    <h4 class="font-bold text-white text-base">Free Diagnostic</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">Our certified engineers analyze the drive in our cleanroom and provide a detailed diagnostic report & fixed cost.</p>
                </div>
                <!-- Step 3 -->
                <div class="relative p-6 rounded-2xl bg-slate-800/80 border border-slate-700/80 space-y-3">
                    <span class="w-8 h-8 rounded-full bg-brand-500 text-white font-bold text-xs flex items-center justify-center">3</span>
                    <h4 class="font-bold text-white text-base">Cleanroom Extraction</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">Using PC-3000 hardware systems and micro-soldering rigs, we extract raw sector data without stressing the drive.</p>
                </div>
                <!-- Step 4 -->
                <div class="relative p-6 rounded-2xl bg-slate-800/80 border border-slate-700/80 space-y-3">
                    <span class="w-8 h-8 rounded-full bg-brand-500 text-white font-bold text-xs flex items-center justify-center">4</span>
                    <h4 class="font-bold text-white text-base">Verify & Deliver</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">You inspect the file list to verify your critical data is intact. Data is transferred securely to your destination media.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= 4. RECENT KNOWLEDGE BASE & BLOG ARTICLES ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200 pb-6">
            <div class="space-y-2">
                <span class="text-xs font-extrabold text-brand-600 uppercase tracking-widest">Expert Guides & Case Studies</span>
                <h3 class="text-3xl font-extrabold text-slate-900 font-display">Latest Data Recovery Knowledge Base</h3>
                <p class="text-xs sm:text-sm text-slate-500">Over 560 technical insights, symptoms guides, and troubleshooting tips from our laboratory.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="px-5 py-2.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
                <span>View All 560+ Articles</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestPosts as $post)
                <article class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-brand-300 transition overflow-hidden flex flex-col group">
                    @if($post->featured_image_url)
                        <a href="{{ route('post.show', $post->slug) }}" class="block aspect-[16/9] bg-slate-100 overflow-hidden relative">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/blog-default.svg') }}';">

                        </a>
                    @else
                        <div class="aspect-[16/9] bg-gradient-to-br from-brand-950 via-slate-900 to-slate-950 flex items-center justify-center p-6 text-center">
                            <i data-lucide="hard-drive" class="w-10 h-10 text-brand-400"></i>
                        </div>
                    @endif

                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-[11px] text-slate-400">
                                <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Recent' }}</span>
                                <span>&bull;</span>
                                <span>{{ $post->reading_time }} min read</span>
                            </div>

                            <h4 class="font-bold text-slate-900 group-hover:text-brand-600 transition text-base line-clamp-2 leading-snug">
                                <a href="{{ route('post.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h4>

                            <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                                {{ $post->excerpt }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end text-xs">
                            <a href="{{ route('post.show', $post->slug) }}" class="font-bold text-brand-600 hover:underline flex items-center gap-1">
                                Read Guide &rarr;
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <!-- ================= 5. EMERGENCY CONTACT & INTAKE FORM ================= -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-brand-950 p-8 sm:p-12 text-white border border-slate-800 shadow-2xl relative overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="space-y-6">
                    <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        Immediate Response
                    </span>
                    <h3 class="text-3xl font-extrabold font-display leading-tight">
                        Need Immediate Data Recovery in Dubai?
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Fill in device details below and our lab director will review your case immediately with emergency diagnostic instructions.
                    </p>

                    <div class="space-y-3 pt-2 text-xs text-slate-300">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-brand-300">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="block text-slate-400 text-[10px]">Direct Phone & WhatsApp:</span>
                                <strong class="text-white text-sm">+971 56 410 1040</strong>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-brand-300">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="block text-slate-400 text-[10px]">Laboratory Address:</span>
                                <strong class="text-white">Office # 508, Dusseldorf Business Point – Al Barsha – Dubai</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('contact.submit') }}" method="POST" class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/10 space-y-4 text-xs">
                    @csrf
                    {{-- Anti-Bot Silent Honeypot --}}
                    <div class="hidden opacity-0 pointer-events-none absolute -left-[9999px]" aria-hidden="true">
                        <label for="_hp_website_home">Leave this field blank</label>
                        <input type="text" id="_hp_website_home" name="_hp_website" tabindex="-1" autocomplete="off">
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">Your Full Name *</label>
                        <input type="text" name="name" required placeholder="e.g. John Doe" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700 text-white focus:outline-none focus:border-brand-400">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-300 font-semibold mb-1">Email Address *</label>
                            <input type="email" name="email" required placeholder="name@domain.com" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700 text-white focus:outline-none focus:border-brand-400">
                        </div>
                        <div>
                            <label class="block text-slate-300 font-semibold mb-1">Phone / WhatsApp *</label>
                            <input type="tel" name="phone" required placeholder="+971 50 ..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700 text-white focus:outline-none focus:border-brand-400">
                        </div>
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">Device Type & Problem *</label>
                        <select name="subject" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700 text-white focus:outline-none focus:border-brand-400">
                            <option value="Hard Drive Failure (Clicking / Not spinning)">Hard Drive Failure (Clicking / Not spinning)</option>
                            <option value="SSD / NVMe Failure (0GB / RAW / Unrecognized)">SSD / NVMe Failure (0GB / RAW / Unrecognized)</option>
                            <option value="RAID / Server Crash (Array degraded / corrupted)">RAID / Server Crash (Array degraded / corrupted)</option>
                            <option value="NAS Network Storage (Synology / QNAP)">NAS Network Storage (Synology / QNAP)</option>
                            <option value="CCTV Video Footage Recovery">CCTV Video Footage Recovery</option>
                            <option value="USB / Flash Drive / SD Card">USB / Flash Drive / SD Card</option>
                            <option value="iPhone / Mobile Phone Liquid Damage">iPhone / Mobile Phone Liquid Damage</option>
                            <option value="Ransomware Encrypted Files">Ransomware Encrypted Files</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-300 font-semibold mb-1">What happened to the device?</label>
                        <textarea name="message" rows="3" placeholder="Explain what happened: dropped, formatted, water damage, stopped detecting..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700 text-white focus:outline-none focus:border-brand-400"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-brand-600 hover:bg-brand-500 font-bold text-white text-xs shadow-lg transition flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Submit for Free Diagnostic Evaluation</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

</div>
@endsection
