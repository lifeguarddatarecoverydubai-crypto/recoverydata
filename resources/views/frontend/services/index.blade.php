@extends('layouts.app')

@section('seo_meta')
    <title>All Data Recovery Services (HDD, SSD, RAID, NAS) | Recovery Data Dubai</title>
    <meta name="title" content="All Data Recovery Services (HDD, SSD, RAID, NAS) | Recovery Data Dubai">
    <meta name="description" content="Explore our full range of laboratory data recovery services in Dubai: Hard Drive, SSD, NVMe, RAID, NAS, CCTV footage, and mobile device recovery with No Data No Charge guarantee.">
    <link rel="canonical" href="{{ route('service.index') }}">
    <meta property="og:title" content="All Data Recovery Services | Recovery Data Dubai">
    <meta property="og:description" content="Certified cleanroom data recovery in Dubai with No Data, No Charge policy. Expert recovery for HDDs, SSDs, RAID, NAS, CCTV, and mobile devices.">
    <meta property="og:url" content="{{ route('service.index') }}">
    <meta property="og:type" content="website">
@endsection

@section('content')
<div class="bg-slate-50 min-h-screen">

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-brand-950 via-slate-900 to-slate-900 text-white pt-24 pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#555cce 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="absolute -top-36 right-0 w-96 h-96 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-600"></i>
                <span class="text-brand-300 font-semibold">Services</span>
            </nav>

            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-500/20 text-brand-300 border border-brand-500/30 mb-6 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>DUBAI CLEANROOM LABORATORY &bull; ISO CLASS 100</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black font-display tracking-tight leading-tight">
                    Professional Data Recovery <span class="bg-gradient-to-r from-brand-300 via-sky-200 to-cyan-300 bg-clip-text text-transparent">Services in Dubai</span>
                </h1>
                <p class="mt-6 text-lg sm:text-xl text-slate-300 leading-relaxed font-light">
                    From clicking hard drives and burned SSD controllers to enterprise multi-disk RAID arrays, multi-bay NAS systems, CCTV footage, and liquid-damaged smartphones. Recovered safely in our Al Barsha 1 laboratory with our strict <strong>No Data, No Charge</strong> policy.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="#services-list" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-gradient-to-r from-brand-600 via-sky-500 to-brand-600 hover:from-brand-500 hover:to-sky-400 text-white shadow-lg shadow-brand-600/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                        <span>Explore Recovery Solutions</span>
                        <i data-lucide="arrow-down" class="w-4 h-4"></i>
                    </a>
                    <a href="tel:+971564101040" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg transition flex items-center gap-2">
                        <i data-lucide="phone-call" class="w-4 h-4 text-emerald-200"></i>
                        <span>Call 24/7: +971 56 410 1040</span>
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="mt-16 pt-10 border-t border-slate-800 grid grid-cols-2 md:grid-cols-4 gap-6 text-slate-300">
                <div>
                    <div class="text-3xl font-black text-white font-display">ISO Class 100</div>
                    <div class="text-xs text-slate-400 mt-1">Dust-Free Cleanroom</div>
                </div>
                <div>
                    <div class="text-3xl font-black text-white font-display">98.7%</div>
                    <div class="text-xs text-slate-400 mt-1">Proven Lab Success Rate</div>
                </div>
                <div>
                    <div class="text-3xl font-black text-white font-display">20,000+</div>
                    <div class="text-xs text-slate-400 mt-1">Drives Successfully Recovered</div>
                </div>
                <div>
                    <div class="text-3xl font-black text-white font-display">15+ Years</div>
                    <div class="text-xs text-slate-400 mt-1">Dubai Data Recovery Excellence</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section id="services-list" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-600">Specialized Practice Areas</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2 font-display">
                Engineered for Every Storage Medium & Failure Type
            </h2>
            <p class="mt-4 text-slate-600 text-base">
                Whether you are facing physical mechanical head crashes, burnt flash controllers, corrupted multi-disk arrays, or accidental deletion, our Dubai laboratory provides guaranteed recovery with zero financial risk.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($services as $svc)
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-brand-300 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                    @if($svc->featured_image_url)
                        <a href="{{ route('service.show', $svc->slug) }}" class="block aspect-[16/9] bg-slate-900 overflow-hidden relative">
                            <img src="{{ $svc->featured_image_url }}" alt="{{ $svc->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/default-recovery.svg') }}';">
                        </a>
                    @endif

                    <div class="p-8 flex-1 flex flex-col justify-between">
                        <div>
                            <!-- Icon & Tag -->
                            <div class="flex items-center justify-between mb-6">

                            <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 group-hover:bg-brand-600 group-hover:text-white transition-all duration-300 flex items-center justify-center shadow-inner">
                                <i data-lucide="{{ $svc->icon ?: 'hard-drive' }}" class="w-7 h-7"></i>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i data-lucide="shield-check" class="w-3 h-3 mr-1 text-emerald-600"></i> No Data, No Fee
                            </span>
                        </div>

                        <!-- Title & Tagline -->
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 group-hover:text-brand-600 transition tracking-tight">
                            <a href="{{ route('service.show', $svc->slug) }}">{{ $svc->title }}</a>
                        </h3>
                        @if($svc->tagline)
                            <p class="text-xs font-semibold text-brand-600 mt-1.5">{{ $svc->tagline }}</p>
                        @endif

                        <!-- Summary -->
                        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mt-4">
                            {{ $svc->summary }}
                        </p>

                        <!-- Features Chips -->
                        @if(!empty($svc->features) && is_array($svc->features))
                            <div class="mt-6 pt-6 border-t border-slate-100">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-2.5">Key Capabilities:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach(array_slice($svc->features, 0, 4) as $feat)
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-50 text-slate-700 border border-slate-200/70">
                                            {{ $feat }}
                                        </span>
                                    @endforeach
                                    @if(count($svc->features) > 4)
                                        <span class="px-2 py-1 rounded-md text-[11px] font-bold text-brand-600 bg-brand-50">
                                            +{{ count($svc->features) - 4 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Bottom Action -->
                    <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('service.show', $svc->slug) }}" class="text-xs font-bold text-brand-600 group-hover:text-brand-700 flex items-center gap-1.5 transition">
                            <span>View Full Details & Process</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 transform group-hover:translate-x-1 transition"></i>
                        </a>
                        <span class="text-[11px] text-slate-400 font-medium">Free Evaluation</span>
                    </div>
                </div>
            </div>

            @empty
                <div class="col-span-3 bg-white rounded-3xl p-12 text-center border border-slate-200">
                    <p class="text-slate-500 text-sm">No services currently published.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- 4-Step Cleanroom Methodology -->
    <section class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-600">Forensic Methodology</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2 font-display">
                    How We Recover Your Inaccessible Files
                </h2>
                <p class="mt-4 text-slate-600 text-base">
                    A strictly non-destructive forensic workflow honed over 15 years in Dubai. Your original media is never modified or put at risk.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-brand-600 text-white font-black text-base flex items-center justify-center mb-4">
                        01
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Free In-Lab Diagnostic</h3>
                    <p class="mt-2 text-xs text-slate-600 leading-relaxed">
                        Comprehensive microscopic hardware and firmware examination in our Al Barsha 1 lab. We determine the exact failure cause and provide a transparent written quotation.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-brand-700 text-white font-black text-base flex items-center justify-center mb-4">
                        02
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">ISO Class 100 Repair</h3>
                    <p class="mt-2 text-xs text-slate-600 leading-relaxed">
                        Drives with mechanical or PCB faults enter our certified cleanroom benches for head assembly transplants, platter cleaning, or micro-soldering component restoration.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-brand-800 text-white font-black text-base flex items-center justify-center mb-4">
                        03
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Low-Level Sector Clone</h3>
                    <p class="mt-2 text-xs text-slate-600 leading-relaxed">
                        Using ACELab PC-3000 hardware systems, we image every accessible sector onto our sterile laboratory storage, bypassing bad blocks with active head control.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-brand-900 text-white font-black text-base flex items-center justify-center mb-4">
                        04
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Verification & Delivery</h3>
                    <p class="mt-2 text-xs text-slate-600 leading-relaxed">
                        You review and verify your recovered file tree before paying. Files are delivered on an encrypted drive or secure cloud download with full NDA confidentiality.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Consultation / Emergency Banner CTA -->
    <section class="py-16 bg-gradient-to-r from-slate-950 via-brand-950 to-slate-950 text-white border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black font-display tracking-tight">Critical Data Loss Emergency?</h2>
                <p class="text-slate-300 text-sm mt-2 max-w-xl">
                    Speak directly with a certified cleanroom recovery engineer in Dubai. Free evaluations, same-day emergency turnaround, and free pickup across all 7 Emirates.
                </p>
            </div>
            <div class="flex flex-wrap gap-4 shrink-0">
                <a href="{{ route('contact') }}" class="px-6 py-3.5 rounded-xl font-bold text-xs sm:text-sm bg-brand-600 hover:bg-brand-500 text-white shadow-xl shadow-brand-600/30 transition">
                    Request Free Evaluation
                </a>
                <a href="tel:+971564101040" class="px-6 py-3.5 rounded-xl font-bold text-xs sm:text-sm bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center gap-2 shadow-lg">
                    <i data-lucide="phone-call" class="w-4 h-4 text-emerald-200"></i>
                    <span>Call +971 56 410 1040</span>
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
