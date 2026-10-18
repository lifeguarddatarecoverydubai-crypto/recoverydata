@extends('layouts.app')

@section('seo_meta')
    <title>About Recovery Data Dubai | Certified Class 100 Cleanroom Lab UAE</title>
    <meta name="description" content="Recovery Data is Dubai's premier data recovery laboratory in Al Barsha. Certified Class 100 cleanroom, 15+ years experience, and No Data No Charge policy.">
    <link rel="canonical" href="{{ route('about') }}">
    <meta property="og:title" content="About Recovery Data Dubai | Certified Cleanroom Laboratory">
    <meta property="og:description" content="Leading data recovery specialists in Dubai with certified Class 100 cleanroom facilities and 15+ years of laboratory recovery success.">
    <meta property="og:url" content="{{ route('about') }}">
    <meta property="og:type" content="website">
@endsection

@section('content')
<div class="bg-slate-50 min-h-screen space-y-20">

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white pt-24 pb-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-600"></i>
                <span class="text-brand-300 font-semibold">About Us</span>
            </nav>

            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-brand-500/20 text-brand-300 border border-brand-500/30 mb-6 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>CERTIFIED DUBAI DATA RECOVERY LABORATORY</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black font-display tracking-tight leading-tight">
                    Rescuing Critical Data When <span class="bg-gradient-to-r from-brand-300 via-sky-200 to-cyan-300 bg-clip-text text-transparent">Every Second Counts</span>
                </h1>
                <p class="mt-6 text-lg sm:text-xl text-slate-300 leading-relaxed font-light">
                    Located in <strong>Al Barsha, Dubai</strong>, Recovery Data operates state-of-the-art ISO 5 Class 100 cleanroom facilities and advanced forensic extraction hardware to safely recover lost files from damaged hard drives, SSDs, RAID servers, and smartphones.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('service.index') }}" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-brand-600 hover:bg-brand-500 text-white shadow-lg shadow-brand-600/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                        <span>Explore Recovery Services</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-sm transition">
                        Visit Al Barsha Lab
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="mt-16 pt-10 border-t border-slate-800 grid grid-cols-2 md:grid-cols-4 gap-6 text-slate-300">
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-white font-display">15+ Years</div>
                    <div class="text-xs text-slate-400 mt-1">Specialized Lab Heritage</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-white font-display">98.7%</div>
                    <div class="text-xs text-slate-400 mt-1">Proven Recovery Success Rate</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-white font-display">Class 100</div>
                    <div class="text-xs text-slate-400 mt-1">Certified Cleanroom Environment</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-emerald-400 font-display">100%</div>
                    <div class="text-xs text-slate-400 mt-1">No Data, No Charge Guarantee</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="p-8 sm:p-10 rounded-3xl bg-white border border-slate-200/90 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center">
                    <i data-lucide="eye" class="w-6 h-6"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 font-display">Our Vision</h3>
                <p class="text-slate-600 leading-relaxed text-sm">
                    To become the most trusted leader in data recovery across the UAE and Middle East by delivering secure, reliable, and innovative solutions that protect what matters most to individuals and enterprises.
                </p>
            </div>

            <div class="p-8 sm:p-10 rounded-3xl bg-white border border-slate-200/90 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="target" class="w-6 h-6"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 font-display">Our Mission</h3>
                <p class="text-slate-600 leading-relaxed text-sm">
                    To provide fast, confidential, and high-success data recovery services using advanced laboratory technology, certified cleanroom benches, and deep engineering knowledge with complete price transparency.
                </p>
            </div>
        </div>
    </section>

    <!-- Lab Highlights & Commitments -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-12">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">Laboratory Standards</span>
            <h2 class="text-3xl font-extrabold text-slate-900 font-display">Why UAE Businesses Trust Recovery Data</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-2xl bg-white border border-slate-200 space-y-3">
                <i data-lucide="shield" class="w-8 h-8 text-brand-600"></i>
                <h4 class="font-bold text-slate-900 text-base">Absolute Confidentiality</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    All client data is treated under strict non-disclosure agreements (NDA). Files are processed in an air-gapped, offline laboratory network to guarantee zero exposure.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-slate-200 space-y-3">
                <i data-lucide="cpu" class="w-8 h-8 text-brand-600"></i>
                <h4 class="font-bold text-slate-900 text-base">Forensic Hardware Tools</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Equipped with PC-3000 Flash, PC-3000 Express, high-magnification stereo microscopes, and precision micro-soldering stations for NAND chip-off recovery.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-slate-200 space-y-3">
                <i data-lucide="badge-percent" class="w-8 h-8 text-brand-600"></i>
                <h4 class="font-bold text-slate-900 text-base">Guaranteed No Data, No Charge</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    If your device is determined unrecoverable, or if our team is unable to retrieve your requested target files, you pay zero recovery fee.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Strip -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="rounded-3xl bg-slate-900 text-white p-8 sm:p-12 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h3 class="text-2xl font-bold font-display">Have a Media Device That Needs Recovery?</h3>
                <p class="text-xs sm:text-sm text-slate-300 mt-1">Visit our Al Barsha facility or call our engineers for free phone consultation.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('contact') }}" class="px-6 py-3 rounded-full bg-brand-600 hover:bg-brand-500 font-bold text-xs shadow-md transition">
                    Contact Us Today
                </a>
                <a href="tel:+971564101040" class="px-5 py-3 rounded-full bg-emerald-600 hover:bg-emerald-500 font-bold text-xs flex items-center gap-1.5 transition">
                    <i data-lucide="phone-call" class="w-3.5 h-3.5"></i>
                    <span>+971 56 410 1040</span>
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
