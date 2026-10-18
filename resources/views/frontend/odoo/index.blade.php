@extends('layouts.app')

@section('seo_meta')
    <title>{{ $odoo->meta_title ?? 'Official Odoo Partner Dubai & UAE | Certified ERP Implementation | Recovery Data' }}</title>
    <meta name="description" content="{{ $odoo->meta_description ?? 'Recovery Data Dubai is an Official Odoo Partner in UAE. Certified Odoo ERP implementation, FTA-compliant VAT accounting, UAE WPS payroll, and customization in Dubai & Abu Dhabi.' }}">
    <link rel="canonical" href="{{ route('odoo.index') }}">
    <meta property="og:title" content="{{ $odoo->meta_title ?? 'Official Odoo Partner Dubai & UAE | Certified ERP Implementation | Recovery Data' }}">
    <meta property="og:description" content="{{ $odoo->meta_description ?? 'Accelerate your business with certified Odoo ERP implementation in the UAE. 100% localized for UAE FTA VAT, WPS payroll, and multi-warehouse operations.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('odoo.index') }}">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {!! json_encode([
      '@@context' => 'https://schema.org',
      '@type' => 'Service',
      'name' => 'Official Odoo ERP Implementation Partner UAE',
      'serviceType' => 'Enterprise Resource Planning Implementation',
      'description' => 'Certified Odoo Partner providing end-to-end ERP implementation, customization, data migration, and UAE regulatory localization.',
      'provider' => [
        '@type' => 'Organization',
        'name' => 'Recovery Data Dubai',
        'url' => 'https://recoverydata.ae',
        'telephone' => '+971569763386',
        'address' => [
          '@type' => 'PostalAddress',
          'streetAddress' => 'Office 402, DasselDorf Business Point, Al Barsha 1',
          'addressLocality' => 'Dubai',
          'addressCountry' => 'AE',
        ],
      ],
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endsection

@section('content')
<div class="bg-slate-50 min-h-screen" x-data="{ odooDemoModal: false, activeTab: 'accounting' }">

    <!-- Hero Section (Official Odoo Partner UAE Showcase) -->
    <section class="relative bg-gradient-to-b from-[#1c1236] via-[#120b24] to-slate-950 text-white pt-24 pb-20 overflow-hidden border-b border-purple-900/30">
        <div class="absolute inset-0 opacity-25 pointer-events-none" style="background-image: radial-gradient(#7c4dff 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="absolute -top-36 right-0 w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 left-10 w-96 h-96 bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-600"></i>
                <span class="text-purple-300 font-semibold">Odoo ERP UAE Partner</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Hero Information (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Official Partner Badge -->
                    <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-white/10 border border-purple-400/30 backdrop-blur-md">
                        <div class="flex items-center gap-2 border-r border-white/20 pr-3">
                            <img src="{{ asset('images/odoo-logo.svg') }}" alt="Odoo" class="h-4 w-auto">
                            <span class="text-xs font-black tracking-wide text-purple-200 uppercase">{{ $odoo->hero_badge ?? 'Official Partner' }}</span>
                        </div>
                        <span class="text-[11px] font-bold text-emerald-400 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            {{ $odoo->hero_country ?? 'United Arab Emirates' }}
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black font-display tracking-tight text-white leading-tight">
                        {!! $odoo->hero_title ?? 'Transform Your Business with <span class="bg-gradient-to-r from-purple-300 via-indigo-200 to-cyan-300 bg-clip-text text-transparent">Official Odoo ERP</span> in Dubai' !!}
                    </h1>

                    <p class="text-base sm:text-lg text-slate-300 font-light leading-relaxed max-w-2xl">
                        {!! $odoo->hero_subtitle ?? 'Unify your Accounting, HR & WPS Payroll, CRM, Inventory, and Manufacturing into one intuitive platform. Fully pre-configured for UAE FTA 5% VAT, Central Bank WPS SIF files, and bilingual operations by Recovery Data Dubai.' !!}
                    </p>

                    <!-- CTAs -->
                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <button @click="odooDemoModal = true" class="px-7 py-3.5 rounded-xl font-bold text-sm bg-purple-600 hover:bg-purple-500 text-white shadow-xl shadow-purple-600/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                            <i data-lucide="play" class="w-4 h-4 fill-white"></i>
                            <span>Schedule Live Odoo Demo</span>
                        </button>
                        <a href="#estimator" class="px-6 py-3.5 rounded-xl font-bold text-sm bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-sm transition flex items-center gap-2">
                            <i data-lucide="calculator" class="w-4 h-4 text-purple-300"></i>
                            <span>Estimate Implementation Cost</span>
                        </a>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $odoo->contact_phone ?? '+971569763386') }}" class="px-4 py-3.5 text-xs sm:text-sm font-semibold text-slate-300 hover:text-white transition flex items-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4 text-emerald-400"></i>
                            <span>{{ $odoo->contact_phone ?? '+971 56 976 3386' }}</span>
                        </a>
                    </div>

                    <!-- Trust Checklist Bar -->
                    <div class="pt-6 border-t border-purple-900/50 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs text-slate-300">
                        @if(!empty($odoo->trust_items) && is_array($odoo->trust_items))
                            @foreach($odoo->trust_items as $tItem)
                                <div class="flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    <span>{{ $tItem }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                <span>FTA 5% VAT Ready</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                <span>UAE WPS SIF Export</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                <span>Arabic & English UI</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                <span>5 UAE Branch Offices</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Visual Partner Card (5 cols) -->
                <div class="lg:col-span-5">
                    <div class="rounded-3xl bg-gradient-to-br from-[#231548] to-[#0f091e] border border-purple-500/30 p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-44 h-44 bg-purple-500/20 rounded-full blur-2xl pointer-events-none"></div>

                        <!-- Top Header in Mockup -->
                        <div class="flex items-center justify-between border-b border-purple-500/20 pb-4 mb-6">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ asset('images/odoo-logo.svg') }}" alt="Odoo" class="h-5 w-auto">
                                <span class="text-xs font-bold text-white tracking-wide">ENTERPRISE SUITE</span>
                            </div>
                            <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                UAE CERTIFIED
                            </span>
                        </div>

                        <!-- Highlights List in Card -->
                        <div class="space-y-3.5 text-xs text-slate-200">
                            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-300 flex items-center justify-center">
                                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-xs">Official UAE Partner Status</div>
                                        <div class="text-[11px] text-slate-400">Direct escalation & official Odoo support</div>
                                    </div>
                                </div>
                                <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-300 flex items-center justify-center">
                                        <i data-lucide="layers" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-xs">Custom Python / Studio Modules</div>
                                        <div class="text-[11px] text-slate-400">Tailored to your industry workflows</div>
                                    </div>
                                </div>
                                <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-300 flex items-center justify-center">
                                        <i data-lucide="database" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-xs">Zero-Downtime Data Migration</div>
                                        <div class="text-[11px] text-slate-400">From Tally, SAP, Zoho, QuickBooks & Excel</div>
                                    </div>
                                </div>
                                <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-300 flex items-center justify-center">
                                        <i data-lucide="server" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-xs">UAE In-Country Hosting</div>
                                        <div class="text-[11px] text-slate-400">Strict UAE Sovereign Data Protection</div>
                                    </div>
                                </div>
                                <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i>
                            </div>
                        </div>

                        <!-- Bottom Fast Action -->
                        <div class="mt-6 pt-4 border-t border-purple-500/20 text-center">
                            <button @click="odooDemoModal = true" class="w-full py-2.5 rounded-xl text-xs font-bold bg-white text-slate-900 hover:bg-slate-100 transition shadow">
                                Request Certified Partner Proposal
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Interactive Odoo Modules Tabs Section (Darwinbox Style) -->
    <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-widest text-purple-600">The Complete Odoo Suite</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2 font-display">
                Odoo Apps Localized for UAE Regulatory Standards
            </h2>
            <p class="mt-3 text-slate-600 text-sm sm:text-base">
                Eliminate disconnected spreadsheets and siloed legacy systems. Choose the modular Odoo applications you need today, and scale seamlessly tomorrow.
            </p>
        </div>

        <!-- Module Selector Tabs -->
        <div class="flex flex-wrap items-center justify-center gap-2 mb-10 pb-4 border-b border-slate-200">
            @foreach($modules as $mod)
                <button type="button" 
                        @click="activeTab = '{{ $mod['id'] }}'"
                        :class="activeTab === '{{ $mod['id'] }}' 
                            ? 'bg-purple-600 text-white shadow-md shadow-purple-600/30' 
                            : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <i data-lucide="{{ $mod['icon'] }}" class="w-4 h-4"></i>
                    <span>{{ $mod['name'] }}</span>
                    <span class="text-[9px] px-1.5 py-0.5 rounded-md"
                          :class="activeTab === '{{ $mod['id'] }}' ? 'bg-white/20 text-white' : 'bg-purple-50 text-purple-700'">
                        {{ $mod['badge'] }}
                    </span>
                </button>
            @endforeach
        </div>

        <!-- Module Details Panels -->
        @foreach($modules as $mod)
            <div x-show="activeTab === '{{ $mod['id'] }}'" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-3"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="bg-white rounded-3xl border border-slate-200/90 shadow-xl p-8 sm:p-12 overflow-hidden">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    
                    <!-- Left Details (7 cols) -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5 text-purple-600"></i>
                            <span>{{ $mod['badge'] }}</span>
                        </div>

                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                            {{ $mod['headline'] }}
                        </h3>

                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                            {{ $mod['desc'] }}
                        </p>

                        <!-- Key Capabilities Checklist -->
                        <div class="space-y-3 pt-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">UAE Localization Highlights:</span>
                            @foreach($mod['features'] as $feat)
                                <div class="flex items-start gap-3 text-xs sm:text-sm text-slate-700 font-medium">
                                    <div class="w-5 h-5 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <span>{{ $feat }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- CTA -->
                        <div class="pt-6 flex flex-wrap items-center gap-4">
                            <button @click="odooDemoModal = true" class="px-6 py-2.5 rounded-xl font-bold text-xs sm:text-sm bg-purple-600 hover:bg-purple-500 text-white shadow-md shadow-purple-600/20 transition flex items-center gap-2">
                                <span>See {{ $mod['name'] }} in Action</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                            <a href="{{ route('contact') }}" class="text-xs font-bold text-slate-600 hover:text-purple-600 transition">
                                Request Module Customization Scoping &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Right Visual Mockup (5 cols) -->
                    <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 via-[#1d1238] to-slate-950 rounded-2xl p-6 text-white border border-purple-500/20 shadow-xl space-y-4">
                        <div class="flex items-center justify-between border-b border-white/10 pb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                <span class="text-[11px] font-mono text-slate-400 ml-2">odoo.recoverydata.ae/{{ $mod['id'] }}</span>
                            </div>
                            <span class="text-[10px] font-bold text-purple-300 bg-purple-950 px-2 py-0.5 rounded border border-purple-700/50">LIVE V17/V18</span>
                        </div>

                        <div class="space-y-3">
                            <div class="p-4 rounded-xl bg-white/5 border border-white/10 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-purple-600 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $mod['icon'] }}" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-white">{{ $mod['name'] }}</div>
                                    <div class="text-xs text-purple-300">Certified UAE Deployment</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 pt-2">
                                <div class="p-3 rounded-xl bg-white/5 text-center">
                                    <div class="text-[10px] text-slate-400 uppercase">Audit Status</div>
                                    <div class="text-xs font-bold text-emerald-400 mt-0.5">100% FTA Verified</div>
                                </div>
                                <div class="p-3 rounded-xl bg-white/5 text-center">
                                    <div class="text-[10px] text-slate-400 uppercase">Support SLA</div>
                                    <div class="text-xs font-bold text-purple-300 mt-0.5">&lt; 15 Min Urgent</div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-slate-400">
                            <span>Dubai Data Center Hosted</span>
                            <span class="font-bold text-white">Arabic & English</span>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </section>

    <!-- Recovery Data's Odoo Services Grid -->
    <section class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-purple-600">Certified Practice</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2 font-display">
                    Full-Lifecycle Odoo Services Delivered in Dubai
                </h2>
                <p class="mt-3 text-slate-600 text-base">
                    From business blueprint and data cleansing to custom Python development and 24/7 post-launch support.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $srv)
                    <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/90 hover:border-purple-300 hover:shadow-lg transition-all flex flex-col justify-between group">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 group-hover:bg-purple-600 group-hover:text-white transition flex items-center justify-center mb-6">
                                <i data-lucide="{{ $srv['icon'] }}" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-purple-600 transition">{{ $srv['title'] }}</h3>
                            <p class="text-xs sm:text-sm text-slate-600 mt-3 leading-relaxed">
                                {{ $srv['desc'] }}
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-200 text-xs font-bold text-purple-600 flex items-center gap-1">
                            <span>UAE Certified Practice</span>
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Legacy ERP vs Odoo with Recovery Data Comparison -->
    <section class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-purple-600">Comparison</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2 font-display">
                    Why Upgrade from Tally / Legacy ERP to Odoo?
                </h2>
                <p class="mt-3 text-slate-600 text-base">
                    Legacy systems trap your business in manual entry and multiple disconnected tools. See how Odoo transforms operations:
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full bg-white rounded-3xl border border-slate-200 shadow-sm text-left text-sm overflow-hidden">
                    <thead class="bg-slate-900 text-white text-xs font-bold uppercase tracking-wider">
                        <tr>
                            <th class="p-5">Capability / Requirement</th>
                            <th class="p-5 text-slate-400">Legacy Systems (Tally, QuickBooks)</th>
                            <th class="p-5 bg-purple-900 text-purple-100">Odoo Enterprise with Recovery Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm text-slate-700">
                        <tr>
                            <td class="p-5 font-bold text-slate-900">UAE FTA 5% VAT & E-Invoicing</td>
                            <td class="p-5 text-slate-500">Requires manual adjustments & external Excel templates</td>
                            <td class="p-5 bg-purple-50/50 font-semibold text-purple-900">Automated 1-click FTA Return, bilingual QR invoices</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-bold text-slate-900">UAE Wages Protection System (WPS)</td>
                            <td class="p-5 text-slate-500">Not supported natively; manual bank file creation</td>
                            <td class="p-5 bg-purple-50/50 font-semibold text-purple-900">Automatic SIF format export for all UAE Central Bank banks</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-bold text-slate-900">End of Service Gratuity (EOSB)</td>
                            <td class="p-5 text-slate-500">Manual calculation prone to labor dispute penalties</td>
                            <td class="p-5 bg-purple-50/50 font-semibold text-purple-900">Exact real-time accrual per UAE Labour Law Decree 33</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-bold text-slate-900">Integrated CRM & WhatsApp Cloud</td>
                            <td class="p-5 text-slate-500">Disconnected software with no synchronization</td>
                            <td class="p-5 bg-purple-50/50 font-semibold text-purple-900">Native WhatsApp messaging, pipeline tracking & orders</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-bold text-slate-900">Cloud Access & Mobile App</td>
                            <td class="p-5 text-slate-500">Requires slow remote desktop (RDP) or on-prem VPN</td>
                            <td class="p-5 bg-purple-50/50 font-semibold text-purple-900">Native iOS & Android apps with real-time biometric sync</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-bold text-slate-900">Local Dubai Engineering Team</td>
                            <td class="p-5 text-slate-500">Overseas ticketing or freelance individual resellers</td>
                            <td class="p-5 bg-purple-50/50 font-semibold text-purple-900">5 UAE Offices, 32-Yr Lifeguard Technology backing & SLAs</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Implementation Cost & Scope Estimator Form (Darwinbox Lead Gen) -->
    <section id="estimator" class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-slate-950 via-[#180e30] to-purple-950 rounded-3xl p-8 sm:p-12 text-white shadow-2xl border border-purple-500/30">
                <div class="text-center max-w-2xl mx-auto mb-10">
                    <span class="text-xs font-bold uppercase tracking-widest text-purple-300">Fast-Track Implementation</span>
                    <h2 class="text-3xl font-black text-white tracking-tight mt-1 font-display">
                        Request a Personalized Odoo Proposal
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 mt-2">
                        Get a detailed scope of work, timeline, and pricing estimate tailored to your UAE headcount and required modules.
                    </p>
                </div>

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="subject" value="Odoo ERP Implementation Request UAE">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Full Name</label>
                            <input type="text" name="name" required placeholder="Tariq Al Mansoori" class="w-full px-4 py-3 text-xs rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:bg-white/15">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Work Email</label>
                            <input type="email" name="email" required placeholder="tariq@company.ae" class="w-full px-4 py-3 text-xs rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:bg-white/15">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">UAE Mobile Number</label>
                            <input type="tel" name="phone" placeholder="+971 50 123 4567" class="w-full px-4 py-3 text-xs rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:bg-white/15">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Company Name</label>
                            <input type="text" name="company" placeholder="e.g. Al Futtaim Trading LLC" class="w-full px-4 py-3 text-xs rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:bg-white/15">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Modules Required (Select all that apply):</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs text-slate-200">
                            <label class="flex items-center gap-2 p-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 cursor-pointer">
                                <input type="checkbox" name="message" value="Accounting & VAT" class="rounded text-purple-600 focus:ring-purple-500">
                                <span>Accounting & 5% VAT</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 cursor-pointer">
                                <input type="checkbox" name="message" value="HRMS & WPS Payroll" class="rounded text-purple-600 focus:ring-purple-500">
                                <span>HRMS & WPS Payroll</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 cursor-pointer">
                                <input type="checkbox" name="message" value="CRM & WhatsApp" class="rounded text-purple-600 focus:ring-purple-500">
                                <span>CRM & WhatsApp</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 cursor-pointer">
                                <input type="checkbox" name="message" value="Inventory & Barcode" class="rounded text-purple-600 focus:ring-purple-500">
                                <span>Inventory & Warehouse</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 cursor-pointer">
                                <input type="checkbox" name="message" value="Manufacturing MRP" class="rounded text-purple-600 focus:ring-purple-500">
                                <span>Manufacturing (MRP)</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 cursor-pointer">
                                <input type="checkbox" name="message" value="Point of Sale POS" class="rounded text-purple-600 focus:ring-purple-500">
                                <span>Point of Sale (POS)</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Current Software / Notes</label>
                        <textarea name="message" rows="3" placeholder="Tell us if you are migrating from Tally, QuickBooks, Excel, or need custom Python modules..." class="w-full px-4 py-3 text-xs rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:bg-white/15"></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl font-bold text-sm bg-purple-600 hover:bg-purple-500 text-white shadow-xl shadow-purple-600/30 transition transform hover:-translate-y-0.5">
                        Submit Request for Odoo Proposal
                    </button>
                    <p class="text-[11px] text-center text-slate-400">
                        An official Odoo certified consultant from our Dubai office will connect with you within 2 business hours.
                    </p>
                </form>
            </div>
        </div>
    </section>

    <!-- FAQ Accordion -->
    <section class="py-20 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-widest text-purple-600">Frequently Asked Questions</span>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight mt-1 font-display">
                Everything You Need to Know About Odoo in the UAE
            </h2>
        </div>

        <div class="space-y-4" x-data="{ openFaq: null }">
            @php
                $faqList = !empty($odoo->faqs) && is_array($odoo->faqs) ? $odoo->faqs : [
                    ['q' => 'Is Odoo fully compliant with UAE FTA 5% VAT and upcoming e-invoicing?', 'a' => 'Yes. Recovery Data configures Odoo with the official UAE Chart of Accounts, automatic 5% VAT calculation on taxable supplies, exempt and zero-rated categories, bilingual Arabic/English tax invoices with cryptographic QR codes, and generation of the FTA Audit File (FAF).'],
                    ['q' => 'How does Odoo handle the UAE Wages Protection System (WPS)?', 'a' => 'Our UAE payroll package for Odoo generates valid Salary Information Files (.SIF) strictly formatted per UAE Central Bank requirements. The file can be uploaded directly to Emirates NBD, FAB, ADCB, Mashreq, Dubai Islamic Bank, or any exchange house.'],
                    ['q' => 'Can Recovery Data migrate our historical data from Tally or Zoho?', 'a' => 'Yes. We have completed over 100 enterprise migrations across the GCC. We extract, clean, map, and import your chart of accounts, customers, vendors, opening balances, historical transactions, and inventory serials with zero data loss.'],
                    ['q' => 'How long does an average Odoo implementation take in Dubai?', 'a' => 'A standard Quickstart deployment (Accounting, Invoicing & CRM) can go live within 2 to 4 weeks. Full enterprise deployments encompassing HRMS WPS, Multi-Warehouse Inventory, and Manufacturing typically take between 6 and 10 weeks under our milestone-driven methodology.']
                ];
            @endphp

            @foreach($faqList as $idx => $f)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <button type="button" @click="openFaq = openFaq === {{ $idx + 1 }} ? null : {{ $idx + 1 }}" class="w-full p-5 text-left font-bold text-slate-900 text-sm flex items-center justify-between">
                        <span>{{ $f['q'] }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="openFaq === {{ $idx + 1 }} ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openFaq === {{ $idx + 1 }}" x-cloak class="px-5 pb-5 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        {{ $f['a'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Modal for Scheduling an Odoo Demo -->
    <div x-show="odooDemoModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
            <div x-show="odooDemoModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="odooDemoModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="odooDemoModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 p-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/odoo-logo.svg') }}" alt="Odoo" class="h-6 w-auto">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-purple-600 block">Official UAE Partner</span>
                            <h3 class="text-lg font-black text-slate-900">Book Odoo Live Demo</h3>
                        </div>
                    </div>
                    <button @click="odooDemoModal = false" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('contact.submit') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="odoo_demo">
                    <input type="hidden" name="subject" value="Live Odoo ERP Walkthrough Request">
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Your Name</label>
                        <input type="text" name="name" required placeholder="Tariq Al Mansoori" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Work Email</label>
                            <input type="email" name="email" required placeholder="tariq@company.ae" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">UAE Phone</label>
                            <input type="tel" name="phone" placeholder="+971 50 123 4567" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Company & Headcount</label>
                        <input type="text" name="company" placeholder="e.g. 25-100 Employees in Dubai" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Primary Modules of Interest</label>
                        <input type="text" name="message" placeholder="e.g. Accounting, WPS Payroll, Multi-Warehouse" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 rounded-xl font-bold text-xs bg-purple-600 hover:bg-purple-500 text-white shadow-lg shadow-purple-600/30 transition">
                            Confirm Demo Booking
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
