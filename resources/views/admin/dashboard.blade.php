@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner (WordPress-style) -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-slate-800 to-brand-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-slate-900/10">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-brand-500/20 text-brand-300 border border-brand-400/30 mb-3">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Recovery Data Dubai &bull; Lab CMS
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="mt-2 text-sm text-slate-300 leading-relaxed">
                Directly manage your cleanroom data recovery services, 560+ technical blog articles, client lab evaluation requests, and website pages.
            </p>
            <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.services.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold shadow-lg shadow-brand-600/30 transition">
                    <i data-lucide="layers" class="w-4 h-4"></i> Manage Services
                </a>
                <a href="{{ route('admin.inquiries.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-600/30 transition">
                    <i data-lucide="inbox" class="w-4 h-4"></i> View Leads & Inquiries
                </a>
                <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-sm transition">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> New Blog Article
                </a>
                <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-sm transition">
                    <i data-lucide="file-text" class="w-4 h-4"></i> Website Pages
                </a>
                <a href="{{ route('admin.menus.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-sm transition">
                    <i data-lucide="menu" class="w-4 h-4"></i> Header Menu
                </a>
                <a href="{{ route('admin.redirects.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600/30 hover:bg-teal-600/50 text-white text-xs font-semibold backdrop-blur-sm border border-teal-400/30 transition">
                    <i data-lucide="arrow-left-right" class="w-4 h-4"></i> 301 Redirections
                </a>
                <form action="{{ route('admin.sitemap.generate') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600/40 hover:bg-purple-600/60 text-white text-xs font-bold backdrop-blur-sm border border-purple-400/40 shadow-sm transition" title="Generate XML Sitemaps for Google Search Console">
                        <i data-lucide="network" class="w-4 h-4 text-purple-200"></i> Generate Sitemap
                    </button>
                </form>
                <form action="{{ route('admin.cache.clear') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500/40 hover:bg-amber-500/60 text-white text-xs font-bold backdrop-blur-sm border border-amber-400/40 shadow-sm transition" title="Clear all compiled Blade views, routes, config, and application caches">
                        <i data-lucide="zap" class="w-4 h-4 text-amber-200"></i> Clear Cache
                    </button>
                </form>
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white text-xs font-medium transition">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Visit Website
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Leads & Inquiries -->
        <a href="{{ route('admin.inquiries.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition group sm:col-span-1">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Leads</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-lucide="inbox" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-xl font-black text-slate-900">{{ $stats['total_inquiries'] }}</span>
                    @if($stats['unread_inquiries'] > 0)
                        <span class="text-[10px] text-rose-600 font-extrabold">{{ $stats['unread_inquiries'] }} new</span>
                    @endif
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5">Emergency & Intake Leads</p>
            </div>
        </a>

        <!-- Services -->
        <a href="{{ route('admin.services.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Services</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-brand-600 flex items-center justify-center">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-xl font-black text-slate-900">{{ $stats['total_services'] }}</span>
                <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">{{ $stats['published_services'] }} active lab pages</p>
            </div>
        </a>

        <!-- Blog Posts -->
        <a href="{{ route('admin.posts.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Articles</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i data-lucide="pin" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-xl font-black text-slate-900">{{ $stats['total_posts'] }}</span>
                <p class="text-[10px] text-slate-500 font-medium mt-0.5">{{ $stats['published_posts'] }} published</p>
            </div>
        </a>

        <!-- Pages -->
        <a href="{{ route('admin.pages.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pages</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-xl font-black text-slate-900">{{ $stats['total_pages'] }}</span>
                <p class="text-[10px] text-slate-400 mt-0.5">CMS Pages</p>
            </div>
        </a>

        <!-- Navigation Menu -->
        <a href="{{ route('admin.menus.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Navigation</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="menu" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-xl font-black text-slate-900">{{ $stats['total_menu_items'] }}</span>
                <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">{{ $stats['active_menu_items'] }} active links</p>
            </div>
        </a>

        <!-- 301 Redirections -->
        <a href="{{ route('admin.redirects.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Redirects</span>
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                    <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-xl font-black text-slate-900">{{ $stats['total_redirects'] }}</span>
                <p class="text-[10px] text-teal-600 font-semibold mt-0.5">{{ $stats['redirect_hits'] }} hits rescued</p>
            </div>
        </a>
    </div>

    <!-- PROMINENT SERVICES MANAGEMENT SECTION ON DASHBOARD -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-100">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-900">Data Recovery Services ({{ $services->count() }})</h2>
                    <p class="text-[11px] text-slate-400">Directly edit content, cleanroom benchmarks, failure tables, and SEO for all recovery services</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.services.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold transition shadow-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Add Service</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 px-2 py-1">
                    View All &rarr;
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-6">Service Name & Link</th>
                        <th class="py-3 px-4">Icon & Features</th>
                        <th class="py-3 px-4">SEO Score</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($services as $svc)
                        <tr class="hover:bg-slate-50/80 transition group">
                            <td class="py-3.5 px-6 max-w-xs">
                                <div class="font-bold text-slate-900 group-hover:text-brand-600 transition text-xs sm:text-sm flex items-center gap-2">
                                    <a href="{{ route('admin.services.edit', $svc) }}">{{ $svc->title }}</a>
                                    @if($svc->is_featured)
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800">Featured</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                                    /service/{{ $svc->slug }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-100">
                                        <i data-lucide="{{ $svc->icon ?: 'hard-drive' }}" class="w-3 h-3"></i>
                                    </div>
                                    <span class="text-slate-600 text-[11px]">
                                        {{ count($svc->features ?? []) }} Features listed
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @php
                                    $score = $svc->seo_score ?? 0;
                                    $rating = $score >= 80 ? 'good' : ($score >= 50 ? 'ok' : 'poor');
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $rating === 'good' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($rating === 'ok' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200') }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $rating === 'good' ? 'bg-emerald-500' : ($rating === 'ok' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                    <span>{{ $score }}/100</span>
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($svc->status === 'published')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('service.show', $svc->slug) }}" target="_blank" 
                                       class="px-2.5 py-1 rounded-lg text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 text-[11px] font-semibold transition flex items-center gap-1">
                                        <i data-lucide="external-link" class="w-3 h-3 text-slate-400"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="{{ route('admin.services.edit', $svc) }}" 
                                       class="px-3 py-1 rounded-lg text-white bg-brand-600 hover:bg-brand-500 text-[11px] font-bold shadow-sm transition flex items-center gap-1">
                                        <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        <span>Edit Page</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">No services created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($recentInquiries->isNotEmpty())
        <!-- Recent Leads & Inquiries Banner -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <i data-lucide="inbox" class="w-4 h-4 text-rose-600"></i>
                    <h2 class="text-sm font-bold text-slate-800">Recent Leads & Inquiries</h2>
                </div>
                <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">View All Leads &rarr;</a>
            </div>
            <div class="divide-y divide-slate-100 text-xs">
                @foreach($recentInquiries as $inq)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/60 transition {{ !$inq->is_read ? 'bg-rose-50/20' : '' }}">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full {{ !$inq->is_read ? 'bg-rose-500 animate-pulse' : 'bg-slate-300' }}"></span>
                            <div>
                                <div class="font-bold text-slate-900">{{ $inq->name }} &bull; <span class="font-normal text-slate-500">{{ $inq->email }}</span></div>
                                <div class="text-[11px] text-slate-500 font-medium">{{ $inq->subject }} {{ $inq->company ? '('.$inq->company.')' : '' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] text-slate-400">{{ $inq->created_at->diffForHumans() }}</span>
                            <a href="{{ route('admin.inquiries.show', $inq) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px]">View</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 2-Column Section: Recent Posts (Left) & Pages + Quick Draft (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left 2 Cols: Recent Posts -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-check-2" class="w-4 h-4 text-brand-600"></i>
                    <h2 class="text-sm font-bold text-slate-800">Recent Blog Activity</h2>
                </div>
                <a href="{{ route('admin.posts.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">View All Posts &rarr;</a>
            </div>

            <div class="flex-1 divide-y divide-slate-100">
                @forelse($recentPosts as $post)
                    <div class="p-4 sm:p-5 flex items-center justify-between hover:bg-slate-50/80 transition group">
                        <div class="space-y-1 pr-4 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="text-sm font-bold text-slate-900 group-hover:text-brand-600 transition truncate block">
                                    {{ $post->title }}
                                </a>
                                @if($post->status === 'published')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Published</span>
                                @elseif($post->status === 'scheduled')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-sky-50 text-sky-700 border border-sky-200">Scheduled</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Draft</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-4 text-xs text-slate-500">
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                @if($post->categories->count())
                                    <span>•</span>
                                    <span class="text-brand-600 font-medium">{{ $post->categories->pluck('name')->implode(', ') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-1 shrink-0">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="p-2 text-slate-400 hover:text-brand-600 hover:bg-slate-100 rounded-lg transition" title="Edit Post">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('post.show', $post->slug) }}" target="_blank" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition" title="Preview">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400 text-xs">
                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                        No posts created yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right 1 Col: Website Pages & Quick Draft -->
        <div class="space-y-6">
            <!-- XML Sitemap & Search Console Box -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100">
                            <i data-lucide="network" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">XML Sitemaps</h3>
                            <p class="text-[10px] text-slate-400">Search Engine Indexing & Crawl</p>
                        </div>
                    </div>
                    @if(isset($sitemapInfo) && $sitemapInfo['exists'] && $sitemapInfo['last_generated'])
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Generated
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            Dynamic
                        </span>
                    @endif
                </div>

                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="text-slate-400 text-[11px]">Canonical Host:</span>
                        <span class="font-mono text-[11px] font-semibold text-slate-800">{{ $sitemapInfo['canonical_domain'] ?? config('app.canonical_domain') }}</span>
                    </div>
                    @if(isset($sitemapInfo) && $sitemapInfo['last_generated'])
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="text-slate-400 text-[11px]">Last Generated:</span>
                            <span class="text-[11px] text-slate-700 font-medium">
                                {{ $sitemapInfo['last_generated']->diffForHumans() }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Generate Action Form -->
                <form action="{{ route('admin.sitemap.generate') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-3 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold shadow-md shadow-purple-600/20 transition flex items-center justify-center gap-2 group">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5 group-hover:rotate-180 transition-transform duration-500"></i>
                        <span>Generate XML Sitemaps</span>
                    </button>
                </form>

                <!-- Direct Links to Sitemaps -->
                <div class="pt-2 border-t border-slate-100 flex flex-wrap gap-2 text-[11px]">
                    <a href="{{ route('sitemap') }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 font-medium transition inline-flex items-center gap-1" title="Open master sitemap">
                        <span>sitemap.xml</span>
                        <i data-lucide="external-link" class="w-3 h-3 text-slate-400"></i>
                    </a>
                    <a href="{{ route('sitemap.index') }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 font-medium transition inline-flex items-center gap-1" title="Open sitemap index">
                        <span>sitemap-index.xml</span>
                        <i data-lucide="external-link" class="w-3 h-3 text-slate-400"></i>
                    </a>
                    <a href="{{ route('robots') }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 font-medium transition inline-flex items-center gap-1" title="Open robots.txt">
                        <span>robots.txt</span>
                        <i data-lucide="external-link" class="w-3 h-3 text-slate-400"></i>
                    </a>
                </div>
            </div>

            <!-- System Cache & Performance Box -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                            <i data-lucide="zap" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">System Cache</h3>
                            <p class="text-[10px] text-slate-400">Application, Views & Routes</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        Active
                    </span>
                </div>

                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Clears compiled Blade templates, cached routes, configuration, and application data cache.
                </p>

                <!-- Clear Cache Action Form -->
                <form action="{{ route('admin.cache.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow-md shadow-amber-500/20 transition flex items-center justify-center gap-2 group">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:scale-110"></i>
                        <span>Clear All System Caches</span>
                    </button>
                </form>
            </div>

            <!-- Website Pages Quick Box -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-4 h-4 text-emerald-600"></i>
                        Website Pages
                    </h3>
                    <a href="{{ route('admin.pages.index') }}" class="text-[11px] font-semibold text-brand-600 hover:underline">All Pages &rarr;</a>
                </div>

                <div class="space-y-2 text-xs">
                    @forelse($pages as $pg)
                        <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition group">
                            <div class="min-w-0 pr-2">
                                <a href="{{ route('admin.pages.edit', $pg) }}" class="font-semibold text-slate-800 group-hover:text-brand-600 transition truncate block">
                                    {{ $pg->title }}
                                </a>
                                <span class="text-[10px] text-slate-400 font-mono">/page/{{ $pg->slug }}</span>
                            </div>
                            <a href="{{ route('admin.pages.edit', $pg) }}" class="p-1 rounded text-slate-400 hover:text-brand-600 transition shrink-0" title="Edit">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    @empty
                        <p class="text-slate-400 text-center py-2">No pages found.</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Draft Box -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="feather" class="w-4 h-4 text-brand-600"></i>
                        Quick Draft
                    </h3>
                    <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-medium">Idea jotter</span>
                </div>

                <form action="{{ route('admin.quick-draft') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <input type="text" name="title" required placeholder="Title of your post idea"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    </div>
                    <div>
                        <textarea name="content" rows="3" placeholder="What's on your mind?..."
                                  class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                        Save as Draft
                    </button>
                </form>
            </div>

            <!-- Recent Media Thumbnails -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="image" class="w-4 h-4 text-amber-600"></i>
                        Recent Media Uploads
                    </h3>
                    <a href="{{ route('admin.media.index') }}" class="text-[11px] font-semibold text-brand-600 hover:underline">Library &rarr;</a>
                </div>

                @if($recentMedia->count())
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($recentMedia as $med)
                            <div class="aspect-square rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                @if($med->is_image)
                                    <img src="{{ $med->url }}" alt="{{ $med->name }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/default-recovery.svg') }}';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <i data-lucide="file" class="w-5 h-5"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 text-center py-4">No media uploaded yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
