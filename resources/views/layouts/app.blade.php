<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 text-slate-900 antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @hasSection('seo_meta')
        @yield('seo_meta')
    @else
        <title>{{ config('app.name', 'Recovery Data Dubai') }} | Data Recovery Services in Dubai | No Data, No Charge</title>
        <meta name="description" content="Need professional data recovery in Dubai? Recover lost files from hard drives, SSDs, RAID systems, NAS, CCTV footage, and smartphones with certified Class 100 cleanroom laboratory.">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta property="og:title" content="Data Recovery Services in Dubai | Recovery Data Dubai">
        <meta property="og:description" content="Professional data recovery specialists in Dubai. Hard drive, SSD, RAID, NAS, CCTV, and smartphone data recovery with No Data No Charge policy.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif

    <!-- Global LocalBusiness & Organization Schema.org JSON-LD -->
    <script type="application/ld+json">
    {!! json_encode([
      '@@context' => 'https://schema.org',
      '@type' => 'ProfessionalService',
      'name' => 'Recovery Data Dubai',
      'legalName' => 'Recovery Data Computer Systems & Data Recovery LLC',
      'alternateName' => ['Recovery Data', 'Data Recovery Dubai'],
      'url' => 'https://www.recoverydata.ae',
      'logo' => asset('images/recoverydata-logo.png'),
      'image' => asset('images/recoverydata-logo.png'),
      'telephone' => '+971564101040',
      'email' => 'info@recoverydata.ae',
      'priceRange' => '$$',
      'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => 'Office # 508, Dusseldorf Business Point, Al Barsha 1',
        'addressLocality' => 'Dubai',
        'addressRegion' => 'Dubai',
        'postalCode' => '00000',
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
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Red+Hat+Display:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Tailwind CSS CDN Fallback -->
        <script src="https://cdn.tailwindcss.com?plugins=typography,forms,aspect-ratio"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                            display: ['"Red Hat Display"', '"Plus Jakarta Sans"', 'sans-serif'],
                        },
                        colors: {
                            brand: {
                                50: '#f0f9ff',
                                100: '#e0f2fe',
                                200: '#bae6fd',
                                300: '#7dd3fc',
                                400: '#38bdf8',
                                500: '#0ea5e9',
                                600: '#0284c7', /* Cleanroom Cyber Blue */
                                700: '#0369a1',
                                800: '#075985',
                                900: '#0c4a6e',
                                950: '#082f49',
                            },
                            obsidian: {
                                900: '#090d16',
                                950: '#05070c',
                            }
                        }
                    }
                }
            }
        </script>
    @endif

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <!-- Lucide Icons (Pinned for stability) -->
    <script src="https://unpkg.com/lucide@0.469.0/dist/umd/lucide.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .prose pre { background-color: #090d16; color: #f8fafc; border-radius: 0.75rem; padding: 1.25rem; overflow-x: auto; }
        .prose img { border-radius: 0.75rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); margin: 2rem auto; }
    </style>
    @stack('styles')
</head>
<body class="min-h-full flex flex-col font-sans selection:bg-brand-600 selection:text-white bg-slate-50" x-data="{ mobileMenuOpen: false }">

    <!-- Top Hello Bar / Announcement -->
    <div class="bg-gradient-to-r from-slate-950 via-brand-950 to-slate-950 text-white text-xs py-2 px-4 border-b border-brand-800/40 relative z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 overflow-hidden truncate">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-500/30 text-brand-300 border border-brand-400/30 shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse mr-1.5"></span> DUBAI CLEANROOM LAB
                </span>
                <span class="text-slate-200 truncate">
                    Certified Class 100 Cleanroom • 15+ Years Experience • <strong>No Data – No Charge</strong> Policy
                </span>
            </div>
            <div class="hidden sm:flex items-center gap-4 shrink-0 text-slate-300 font-medium">
                <a href="tel:+971564101040" class="flex items-center gap-1.5 hover:text-white transition font-semibold text-emerald-400">
                    <i data-lucide="phone-call" class="w-3.5 h-3.5"></i>
                    <span>+971 56 410 1040</span>
                </a>
                <span class="text-slate-700">|</span>
                <a href="{{ route('contact') }}" class="text-brand-300 hover:text-white font-semibold transition flex items-center gap-1">
                    <span>Emergency Lab Intake</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all shadow-xs" x-data="{ servicesOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand Logo (Recovery Data) -->
                <div class="flex items-center gap-6 xl:gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 py-1">
                        <img src="{{ asset('images/recoverydata-logo.png') }}?v={{ file_exists(public_path('images/recoverydata-logo.png')) ? filemtime(public_path('images/recoverydata-logo.png')) : time() }}" alt="Recovery Data Dubai" class="h-12 sm:h-14 w-auto object-contain">
                    </a>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden lg:flex items-center gap-5 xl:gap-6 text-sm font-semibold text-slate-600">
                        @if(isset($headerMenuItems) && $headerMenuItems->isNotEmpty())
                            @foreach($headerMenuItems as $item)
                                @if($item->activeChildren->isNotEmpty())
                                    <!-- Dropdown Menu Item -->
                                    <div class="relative" @mouseenter="servicesOpen = true" @mouseleave="servicesOpen = false">
                                        <button type="button" @click="servicesOpen = !servicesOpen" 
                                                class="inline-flex items-center gap-1 transition hover:text-brand-600 py-2 {{ request()->routeIs('service.*') ? 'text-brand-600 font-bold' : '' }}">
                                            <span>{{ $item->title }}</span>
                                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200" :class="servicesOpen ? 'rotate-180' : ''"></i>
                                        </button>

                                        <!-- Dropdown Panel (2-column grid for clean view of all services) -->
                                        <div x-show="servicesOpen" x-cloak 
                                             x-transition:enter="transition ease-out duration-150"
                                             x-transition:enter-start="opacity-0 translate-y-2"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-100"
                                             x-transition:leave-start="opacity-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 translate-y-2"
                                             class="absolute -left-10 top-full w-[480px] bg-white rounded-2xl border border-slate-200/90 shadow-2xl p-4 grid grid-cols-2 gap-2 z-50">
                                            @foreach($item->activeChildren as $child)
                                                <a href="{{ $child->getUrl() }}" class="flex items-center gap-2.5 p-2 rounded-xl hover:bg-slate-50 transition group">
                                                    <div class="w-7 h-7 rounded-lg bg-brand-50 group-hover:bg-brand-600 text-brand-600 group-hover:text-white flex items-center justify-center shrink-0 transition-colors">
                                                        <i data-lucide="hard-drive" class="w-3.5 h-3.5"></i>
                                                    </div>
                                                    <span class="text-xs font-semibold text-slate-700 group-hover:text-brand-600 transition truncate">{{ $child->title }}</span>
                                                </a>
                                            @endforeach

                                            <div class="col-span-2 pt-2 border-t border-slate-100 text-center">
                                                <a href="{{ route('service.index') }}" class="text-xs font-bold text-brand-600 hover:underline">
                                                    View All Data Recovery Solutions &rarr;
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Standard Menu Link -->
                                    <a href="{{ $item->getUrl() }}" target="{{ $item->target }}" 
                                       class="transition hover:text-brand-600 py-2 {{ $item->isActive() ? 'text-brand-600 font-bold' : '' }}">
                                        {{ $item->title }}
                                        @if($item->badge)
                                            <span class="ml-1 text-[9px] font-extrabold px-1.5 py-0.5 rounded-full bg-brand-100 text-brand-700">{{ $item->badge }}</span>
                                        @endif
                                    </a>
                                @endif
                            @endforeach
                        @else
                            <!-- Fallback Links -->
                            <a href="{{ route('home') }}" class="transition hover:text-brand-600 py-2">Home</a>
                            <a href="{{ route('about') }}" class="transition hover:text-brand-600 py-2">About</a>
                            <a href="{{ route('service.index') }}" class="transition hover:text-brand-600 py-2">Services</a>
                            <a href="{{ route('blog.index') }}" class="transition hover:text-brand-600 py-2">Blogs</a>
                            <a href="{{ route('contact') }}" class="transition hover:text-brand-600 py-2">Contact</a>
                        @endif
                    </nav>
                </div>

                <!-- Right Side: Emergency Hotline & Quote CTA -->
                <div class="hidden lg:flex items-center gap-3 xl:gap-4 shrink-0">
                    <!-- Search Input -->
                    <form action="{{ route('blog.index') }}" method="GET" class="relative hidden xl:block">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search articles..." 
                            class="w-36 2xl:w-44 pl-8 pr-3 py-1.5 text-xs rounded-full border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition-all">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2 pointer-events-none"></i>
                    </form>

                    <!-- WhatsApp / Call Hotline -->
                    <a href="tel:+971564101040" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 hover:text-brand-600 px-3 py-2 rounded-xl hover:bg-slate-50 transition">
                        <i data-lucide="phone" class="w-4 h-4 text-emerald-600"></i>
                        <span>+971 56 410 1040</span>
                    </a>

                    @auth
                        <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                            @if(auth()->user()->canManageContent())
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm">
                                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i>
                                    Admin CMS
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 transition rounded-lg hover:bg-slate-100" title="Logout">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Action CTA Button -->
                        <a href="{{ route('contact') }}" 
                           class="inline-flex items-center gap-2 px-4 xl:px-5 py-2 xl:py-2.5 rounded-full bg-gradient-to-r from-brand-600 to-sky-600 hover:from-brand-700 hover:to-sky-700 text-white text-xs font-bold shadow-md shadow-brand-600/30 hover:shadow-lg hover:shadow-brand-600/40 transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                            <span>Get Free Evaluation</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden flex items-center gap-3">
                    <a href="tel:+971564101040" class="px-3 py-1.5 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center gap-1">
                        <i data-lucide="phone" class="w-3 h-3"></i>
                        <span>Call</span>
                    </a>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl">
                        <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenuOpen"></i>
                        <i data-lucide="x" class="w-6 h-6" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-cloak class="lg:hidden border-t border-slate-200 bg-white px-5 py-6 space-y-5 shadow-xl max-h-[85vh] overflow-y-auto">
            <form action="{{ route('blog.index') }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search articles..." 
                    class="w-full pl-9 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none"></i>
            </form>

            <div class="flex flex-col space-y-3 font-semibold text-slate-700 text-sm">
                @if(isset($headerMenuItems) && $headerMenuItems->isNotEmpty())
                    @foreach($headerMenuItems as $item)
                        @if($item->activeChildren->isNotEmpty())
                            <div class="py-1">
                                <div class="flex items-center justify-between py-1">
                                    <span class="text-brand-600 font-bold">{{ $item->title }}</span>
                                    <a href="{{ route('service.index') }}" class="text-xs text-brand-600 font-semibold">View all &rarr;</a>
                                </div>
                                <div class="pl-3 py-1 space-y-1.5 border-l-2 border-slate-100 text-xs text-slate-500">
                                    @foreach($item->activeChildren as $child)
                                        <a href="{{ $child->getUrl() }}" class="block hover:text-brand-600 py-0.5">&bull; {{ $child->title }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $item->getUrl() }}" class="py-1 hover:text-brand-600 {{ $item->isActive() ? 'text-brand-600 font-bold' : '' }}">
                                {{ $item->title }}
                            </a>
                        @endif
                    @endforeach
                @else
                    <a href="{{ route('home') }}" class="py-1 hover:text-brand-600">Home</a>
                    <a href="{{ route('about') }}" class="py-1 hover:text-brand-600">About</a>
                    <a href="{{ route('service.index') }}" class="py-1 hover:text-brand-600">Services</a>
                    <a href="{{ route('blog.index') }}" class="py-1 hover:text-brand-600">Blogs</a>
                    <a href="{{ route('contact') }}" class="py-1 hover:text-brand-600">Contact</a>
                @endif
            </div>

            <div class="pt-4 border-t border-slate-100 space-y-3">
                <a href="{{ route('contact') }}" class="block w-full py-3 rounded-xl bg-brand-600 text-white text-center font-bold text-xs shadow-md">
                    Request Free Data Evaluation
                </a>
                <a href="tel:+971564101040" class="block w-full py-3 rounded-xl bg-emerald-600 text-white text-center font-bold text-xs">
                    Call 24/7 Hotline: +971 56 410 1040
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="bg-slate-950 text-white border-t border-slate-800 mt-20">
        <!-- Pre-footer CTA Strip -->
        <div class="border-b border-slate-800/80 bg-gradient-to-r from-brand-950 via-slate-900 to-brand-950 py-10 px-4">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold font-display text-white">Lost Critical Data? Do Not Panic.</h3>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl">
                        Power off the device to avoid further damage and bring it to our Al Barsha cleanroom laboratory for an instant free evaluation.
                    </p>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('contact') }}" class="px-6 py-3 rounded-full bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold shadow-lg shadow-brand-600/30 transition">
                        Get Free Lab Evaluation
                    </a>
                    <a href="tel:+971564101040" class="px-6 py-3 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-2">
                        <i data-lucide="phone-call" class="w-4 h-4"></i>
                        <span>+971 56 410 1040</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                
                <!-- Col 1: Brand & Heritage -->
                <div class="space-y-4 lg:col-span-2">
                    <a href="{{ route('home') }}" class="inline-block">
                        <img src="{{ asset('images/recoverydata-logo-white.png') }}" alt="Recovery Data Dubai" class="h-11 w-auto object-contain">
                    </a>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                        Recovery Data is Dubai's trusted data recovery laboratory. We provide advanced recovery solutions for physically damaged, corrupted, or failed hard drives, SSDs, RAID arrays, NAS, and mobile devices under certified Class 100 cleanroom conditions with a strict <strong>No Data – No Charge</strong> policy.
                    </p>
                    <div class="space-y-2 text-xs text-slate-400 pt-2">
                        <div class="flex items-center gap-2">
                            <i data-lucide="phone-call" class="w-4 h-4 text-brand-400"></i>
                            <span class="text-slate-200 font-semibold">+971 56 410 1040 / +971 43 933 082</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4 text-brand-400"></i>
                            <a href="mailto:info@recoverydata.ae" class="text-slate-200 hover:underline">info@recoverydata.ae</a>
                        </div>
                        <div class="flex items-start gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-brand-400 shrink-0 mt-0.5"></i>
                            <span class="text-slate-300">Office # 508, Dusseldorf Business Point – Al Barsha – Dubai</span>
                        </div>
                    </div>
                </div>

                <!-- Col 2: Data Recovery Services -->
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-800 pb-2">Recovery Services</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        @foreach(\App\Models\Service::published()->ordered()->get() as $s)
                            <li>
                                <a href="{{ route('service.show', $s->slug) }}" class="hover:text-white transition flex items-center gap-1.5">
                                    <span class="text-brand-400">&rsaquo;</span>
                                    <span>{{ $s->title }}</span>
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('service.index') }}" class="text-brand-400 font-semibold hover:underline block pt-1">
                                View All Services &rarr;
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Col 3: Latest Knowledge Articles (from 563 posts) -->
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-800 pb-2">Knowledge Base</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        @foreach(\App\Models\Post::published()->latest('published_at')->take(5)->get() as $p)
                            <li>
                                <a href="{{ route('post.show', $p->slug) }}" class="hover:text-white transition flex items-start gap-1.5">
                                    <span class="text-brand-400 shrink-0">&rsaquo;</span>
                                    <span class="line-clamp-1">{{ $p->title }}</span>
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('blog.index') }}" class="text-brand-400 font-semibold hover:underline block pt-1">
                                All 560+ Articles &rarr;
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Lab & Office Location -->
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-800 pb-2">Laboratory & Hours</h4>
                    <ul class="space-y-3 text-[11px] text-slate-400">
                        <li class="space-y-0.5">
                            <strong class="text-slate-200 block">Cleanroom Lab:</strong>
                            <span>Office # 508, Dusseldorf Business Point, Al Barsha, Dubai (Near Mall of the Emirates)</span>
                        </li>
                        <li class="space-y-0.5">
                            <strong class="text-slate-200 block">Working Hours:</strong>
                            <span>Monday – Saturday: 9:00 AM – 8:00 PM</span>
                        </li>
                        <li class="space-y-0.5">
                            <strong class="text-emerald-400 block font-bold">Emergency 24/7 Line:</strong>
                            <span>+971 56 410 1040</span>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Legal & Compliance -->
            <div class="pt-10 mt-12 border-t border-slate-900 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <div class="flex items-center gap-4 flex-wrap">
                    <p>&copy; {{ date('Y') }} Recovery Data Dubai. All rights reserved.</p>
                    <span class="text-slate-700 hidden sm:inline">&bull;</span>
                    <span class="text-slate-400">Certified Class 100 ISO 5 Cleanroom Laboratory</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ route('about') }}" class="hover:text-slate-300">About Recovery Data</a>
                    <a href="{{ route('blog.index') }}" class="hover:text-slate-300">Blog Hub</a>
                    <a href="{{ route('contact') }}" class="hover:text-slate-300">Contact Lab</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
