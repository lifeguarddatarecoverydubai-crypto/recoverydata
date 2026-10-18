@props([
    'model' => null,
    'titleInputId' => 'titleInput',
    'slugInputId' => 'slugInput',
    'contentInputId' => 'contentInput',
    'urlPrefix' => url('/'),
    'type' => 'post'
])

@php
    $focusKeyword = old('focus_keyword', $model->focus_keyword ?? '');
    $metaTitle = old('meta_title', $model->meta_title ?? '');
    $metaDescription = old('meta_description', $model->meta_description ?? '');
    $metaKeywords = old('meta_keywords', $model->meta_keywords ?? '');
    $canonicalUrl = old('canonical_url', $model->canonical_url ?? '');
    $ogTitle = old('og_title', $model->og_title ?? '');
    $ogDescription = old('og_description', $model->og_description ?? '');
    $ogImage = old('og_image', $model->og_image ?? '');
    $isIndexable = old('is_indexable', $model->is_indexable ?? true);
    $seoScore = old('seo_score', $model->seo_score ?? 0);
    $readabilityScore = old('readability_score', $model->readability_score ?? 0);
    $schemaMarkup = old('schema_markup', $model->schema_markup ?? '');
@endphp

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" 
     x-data="yoastSeoAnalyzer({
        titleInputId: '{{ $titleInputId }}',
        slugInputId: '{{ $slugInputId }}',
        contentInputId: '{{ $contentInputId }}',
        urlPrefix: '{{ $urlPrefix }}',
        initialFocusKeyword: '{{ addslashes($focusKeyword) }}',
        initialTitle: '{{ addslashes($metaTitle) }}',
        initialDescription: '{{ addslashes($metaDescription) }}',
        initialSeoScore: {{ (int) $seoScore }},
        initialReadabilityScore: {{ (int) $readabilityScore }},
        initialSchemaMarkup: @js($schemaMarkup)
     })" 
     x-init="initAnalyzer()">
    
    <!-- Header with Yoast Traffic Light Badges -->
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-purple-500/20">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-slate-800 text-sm">Yoast SEO & Content Signals</h3>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">Live AI Assistant</span>
                </div>
                <p class="text-xs text-slate-400">Optimize search rankings, social preview cards & keyword density in real-time.</p>
            </div>
        </div>

        <!-- Yoast Traffic Light Badges -->
        <div class="flex items-center gap-3">
            <!-- SEO Score -->
            <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl border text-xs font-semibold"
                 :class="{
                     'bg-emerald-50 text-emerald-700 border-emerald-200': seoScoreRating === 'good',
                     'bg-amber-50 text-amber-700 border-amber-200': seoScoreRating === 'ok',
                     'bg-rose-50 text-rose-700 border-rose-200': seoScoreRating === 'poor'
                 }">
                <span class="w-2.5 h-2.5 rounded-full"
                      :class="{
                          'bg-emerald-500 animate-pulse': seoScoreRating === 'good',
                          'bg-amber-500': seoScoreRating === 'ok',
                          'bg-rose-500': seoScoreRating === 'poor'
                      }"></span>
                <span>SEO: <strong x-text="seoScore + '/100'"></strong></span>
                <span class="capitalize text-[11px] opacity-80" x-text="'(' + seoScoreRating + ')'"></span>
            </div>

            <!-- Readability Score -->
            <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl border text-xs font-semibold"
                 :class="{
                     'bg-emerald-50 text-emerald-700 border-emerald-200': readabilityRating === 'good',
                     'bg-amber-50 text-amber-700 border-amber-200': readabilityRating === 'ok',
                     'bg-rose-50 text-rose-700 border-rose-200': readabilityRating === 'poor'
                 }">
                <span class="w-2.5 h-2.5 rounded-full"
                      :class="{
                          'bg-emerald-500': readabilityRating === 'good',
                          'bg-amber-500': readabilityRating === 'ok',
                          'bg-rose-500': readabilityRating === 'poor'
                      }"></span>
                <span>Readability: <strong x-text="readabilityScore + '/100'"></strong></span>
            </div>
        </div>
    </div>

    <!-- Hidden form fields to store calculated scores -->
    <input type="hidden" name="seo_score" :value="seoScore">
    <input type="hidden" name="readability_score" :value="readabilityScore">

    <!-- Navigation Tabs -->
    <div class="px-6 pt-3 border-b border-slate-200 flex items-center justify-between bg-white flex-wrap gap-2">
        <div class="flex items-center gap-1 text-xs font-bold">
            <button type="button" @click="activeTab = 'general'" 
                    :class="activeTab === 'general' ? 'border-purple-600 text-purple-600 bg-purple-50/50' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    class="px-4 py-2.5 border-b-2 rounded-t-lg transition flex items-center gap-2">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span>General SEO</span>
            </button>
            <button type="button" @click="activeTab = 'analysis'" 
                    :class="activeTab === 'analysis' ? 'border-purple-600 text-purple-600 bg-purple-50/50' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    class="px-4 py-2.5 border-b-2 rounded-t-lg transition flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                <span>Yoast Signals & Audit</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full bg-slate-200 text-slate-700" x-text="passedChecksCount + '/' + totalChecksCount"></span>
            </button>
            <button type="button" @click="activeTab = 'social'" 
                    :class="activeTab === 'social' ? 'border-purple-600 text-purple-600 bg-purple-50/50' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    class="px-4 py-2.5 border-b-2 rounded-t-lg transition flex items-center gap-2">
                <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                <span>Social (Open Graph)</span>
            </button>
            <button type="button" @click="activeTab = 'advanced'" 
                    :class="activeTab === 'advanced' ? 'border-purple-600 text-purple-600 bg-purple-50/50' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    class="px-4 py-2.5 border-b-2 rounded-t-lg transition flex items-center gap-2">
                <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                <span>Robots & Indexing</span>
            </button>
            <button type="button" @click="activeTab = 'schema'" 
                    :class="activeTab === 'schema' ? 'border-purple-600 text-purple-600 bg-purple-50/50' : 'border-transparent text-slate-500 hover:text-slate-800'"
                    class="px-4 py-2.5 border-b-2 rounded-t-lg transition flex items-center gap-2">
                <i data-lucide="boxes" class="w-3.5 h-3.5"></i>
                <span>Schema (Structured Data)</span>
                <span x-show="customSchema && customSchema.trim().length > 0" class="w-2 h-2 rounded-full bg-emerald-500" title="Custom Schema Configured"></span>
            </button>
        </div>

        <!-- Preview Mode Toggle (Desktop / Mobile) -->
        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-lg text-xs" x-show="activeTab === 'general' || activeTab === 'social'">
            <button type="button" @click="previewMode = 'desktop'" 
                    :class="previewMode === 'desktop' ? 'bg-white shadow-xs text-slate-800 font-bold' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded transition flex items-center gap-1.5">
                <i data-lucide="monitor" class="w-3 h-3"></i>
                <span>Desktop</span>
            </button>
            <button type="button" @click="previewMode = 'mobile'" 
                    :class="previewMode === 'mobile' ? 'bg-white shadow-xs text-slate-800 font-bold' : 'text-slate-500 hover:text-slate-800'"
                    class="px-2.5 py-1 rounded transition flex items-center gap-1.5">
                <i data-lucide="smartphone" class="w-3 h-3"></i>
                <span>Mobile</span>
            </button>
        </div>
    </div>

    <!-- Body Contents -->
    <div class="p-6 space-y-6">

        <!-- ================= TAB 1: GENERAL SEO ================= -->
        <div x-show="activeTab === 'general'" class="space-y-6">

            <!-- Focus Keyword Input -->
            <div class="p-4 rounded-xl border border-purple-200 bg-purple-50/40 space-y-2">
                <div class="flex items-center justify-between">
                    <label for="focusKeywordInput" class="text-xs font-bold uppercase tracking-wider text-purple-900 flex items-center gap-1.5">
                        <i data-lucide="key" class="w-3.5 h-3.5 text-purple-600"></i>
                        Focus Keyphrase
                    </label>
                    <span class="text-[11px] text-purple-700 font-medium">The main phrase you want to rank for</span>
                </div>
                <input type="text" id="focusKeywordInput" name="focus_keyword" x-model="focusKeyword" @input="reanalyze()"
                       placeholder="e.g. HRMS Software Dubai, AI Automation UAE"
                       class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-purple-200 bg-white focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition">
            </div>

            <!-- Live Google Snippet Preview -->
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="globe" class="w-3.5 h-3.5 text-purple-600"></i> Google Search Snippet Preview
                    </span>
                    <span class="text-[11px] font-normal text-slate-400" x-text="previewMode === 'desktop' ? 'Simulating Desktop SERP' : 'Simulating Mobile SERP'"></span>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 transition-all"
                     :class="previewMode === 'mobile' ? 'max-w-md mx-auto shadow-md' : 'w-full shadow-xs'">
                    <div class="bg-white p-4 rounded-lg border border-slate-200/80 space-y-1.5 font-sans">
                        <!-- URL Bar -->
                        <div class="flex items-center gap-2 text-xs text-slate-600">
                            <span class="w-4 h-4 rounded-full bg-brand-600 text-white flex items-center justify-center text-[10px] font-bold">R</span>
                            <div class="flex items-center gap-1 truncate text-[11px]">
                                <span class="font-medium text-slate-900">{{ config('app.name', 'Recovery Data') }}</span>
                                <span class="text-slate-400">&rsaquo;</span>
                                <span class="text-slate-500 truncate" x-text="currentSlug ? urlPrefix + '/' + currentSlug : urlPrefix"></span>
                            </div>
                        </div>

                        <!-- Snippet Title -->
                        <h4 class="text-blue-700 font-medium hover:underline cursor-pointer leading-snug"
                            :class="previewMode === 'mobile' ? 'text-sm' : 'text-base font-medium'"
                            x-text="metaTitle || effectiveTitle || 'Title Placeholder'"></h4>

                        <!-- Snippet Description -->
                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed"
                           x-text="metaDescription || effectiveDescription || 'Please provide a meta description by editing the snippet below. If you don’t, Google will pick relevant text from your page.'"></p>
                    </div>
                </div>
            </div>

            <!-- Meta Title Field with Length Meter -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="metaTitleInput" class="text-xs font-bold text-slate-700">SEO Title</label>
                    <span class="text-[11px] font-mono" :class="titleLengthClass" x-text="titleLength + ' / 60 characters'"></span>
                </div>
                <input type="text" id="metaTitleInput" name="meta_title" x-model="metaTitle" @input="reanalyze()"
                       placeholder="Leave blank to use main page title"
                       class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                <!-- Title Meter Bar -->
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-300" 
                         :class="titleMeterColor"
                         :style="'width: ' + Math.min(100, (titleLength / 60) * 100) + '%'"></div>
                </div>
                <p class="text-[11px] text-slate-400">Aim for 40 to 60 characters to avoid truncation on Google search pages.</p>
            </div>

            <!-- Meta Description Field with Length Meter -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="metaDescriptionInput" class="text-xs font-bold text-slate-700">Meta Description</label>
                    <span class="text-[11px] font-mono" :class="descLengthClass" x-text="descLength + ' / 160 characters'"></span>
                </div>
                <textarea id="metaDescriptionInput" name="meta_description" rows="3" x-model="metaDescription" @input="reanalyze()"
                          placeholder="Write a clear, compelling summary (120-160 characters) with your focus keyword to encourage click-throughs..."
                          class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600"></textarea>
                <!-- Description Meter Bar -->
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-300" 
                         :class="descMeterColor"
                         :style="'width: ' + Math.min(100, (descLength / 160) * 100) + '%'"></div>
                </div>
                <p class="text-[11px] text-slate-400">Recommended length: 120 - 160 characters.</p>
            </div>

            <!-- Meta Keywords -->
            <div class="space-y-1.5">
                <label for="metaKeywordsInput" class="block text-xs font-bold text-slate-700">Meta Keywords</label>
                <input type="text" id="metaKeywordsInput" name="meta_keywords" value="{{ $metaKeywords }}"
                       placeholder="separated, by, commas"
                       class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
            </div>
        </div>

        <!-- ================= TAB 2: YOAST SIGNALS & AUDIT ================= -->
        <div x-show="activeTab === 'analysis'" class="space-y-6">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4 text-purple-600"></i>
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Yoast SEO Audit Checklist</h4>
                    </div>
                    <span class="text-xs font-bold" :class="seoScoreRating === 'good' ? 'text-emerald-600' : (seoScoreRating === 'ok' ? 'text-amber-600' : 'text-rose-600')"
                          x-text="passedChecksCount + ' out of ' + totalChecksCount + ' checks passing'"></span>
                </div>
                <p class="text-xs text-slate-500">Live checks evaluate title, meta description, keyword presence, density, content length, and readability.</p>
            </div>

            <!-- Signal List -->
            <div class="space-y-2.5">
                <template x-for="(check, index) in checksList" :key="index">
                    <div class="flex items-start gap-3 p-3 rounded-xl border transition-colors"
                         :class="{
                             'bg-emerald-50/40 border-emerald-200/80': check.status === 'good',
                             'bg-amber-50/40 border-amber-200/80': check.status === 'ok',
                             'bg-rose-50/40 border-rose-200/80': check.status === 'poor'
                         }">
                        <!-- Status Dot -->
                        <span class="mt-0.5 w-3 h-3 rounded-full shrink-0 flex items-center justify-center"
                              :class="{
                                  'bg-emerald-500 text-white': check.status === 'good',
                                  'bg-amber-500 text-white': check.status === 'ok',
                                  'bg-rose-500 text-white': check.status === 'poor'
                              }">
                        </span>
                        <!-- Content -->
                        <div class="flex-1 text-xs">
                            <span class="font-bold block"
                                  :class="{
                                      'text-emerald-900': check.status === 'good',
                                      'text-amber-900': check.status === 'ok',
                                      'text-rose-900': check.status === 'poor'
                                  }" x-text="check.title"></span>
                            <span class="text-slate-600 text-[11px] leading-relaxed" x-text="check.message"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- ================= TAB 3: SOCIAL OPEN GRAPH ================= -->
        <div x-show="activeTab === 'social'" class="space-y-6">
            <p class="text-xs text-slate-500">Preview and customize how this page looks when shared on LinkedIn, Facebook, WhatsApp, and Twitter.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Fields -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Social Title</label>
                        <input type="text" name="og_title" x-model="ogTitle"
                               placeholder="Defaults to SEO title if blank"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Social Description</label>
                        <textarea name="og_description" rows="3" x-model="ogDescription"
                                  placeholder="Defaults to meta description if blank"
                                  class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Social Image URL</label>
                        <input type="text" name="og_image" x-model="ogImage"
                               placeholder="https://... or image URL"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                    </div>
                </div>

                <!-- Live Social Card Preview -->
                <div class="space-y-2">
                    <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <i data-lucide="share" class="w-3.5 h-3.5 text-purple-600"></i> Social Card Preview
                    </span>
                    <div class="rounded-xl border border-slate-200 overflow-hidden shadow-xs bg-white">
                        <div class="h-36 bg-slate-100 flex items-center justify-center relative overflow-hidden">
                            <template x-if="ogImage">
                                <img :src="ogImage" alt="Social Preview" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/default-recovery.svg') }}';">
                            </template>

                            <template x-if="!ogImage">
                                <div class="text-center text-slate-400 p-4">
                                    <i data-lucide="image" class="w-8 h-8 mx-auto mb-1 opacity-50"></i>
                                    <span class="text-[11px] block">No Social Image Selected</span>
                                </div>
                            </template>
                        </div>
                        <div class="p-3.5 space-y-1 bg-slate-50 border-t border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">RECOVERYDATA.AE</span>
                            <h5 class="text-xs font-bold text-slate-900 truncate" x-text="ogTitle || metaTitle || effectiveTitle || 'Shared Title'"></h5>
                            <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed" x-text="ogDescription || metaDescription || effectiveDescription || 'Shared description snippet.'"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB 4: ADVANCED ROBOTS & INDEXING ================= -->
        <div x-show="activeTab === 'advanced'" class="space-y-5">
            <div>
                <label for="canonicalUrlInput" class="block text-xs font-bold text-slate-700 mb-1.5">Canonical URL</label>
                <input type="url" id="canonicalUrlInput" name="canonical_url" value="{{ $canonicalUrl }}"
                       placeholder="https://recoverydata.ae/original-page"
                       class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                <p class="text-[11px] text-slate-400 mt-1">Leave empty to use this item's default permalink.</p>
            </div>

            <div class="pt-3 border-t border-slate-100">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_indexable" value="1" {{ $isIndexable ? 'checked' : '' }}
                           class="rounded border-slate-300 text-purple-600 focus:ring-purple-500 h-4 w-4">
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">Allow search engines to show this in search results (Index)</span>
                        <span class="text-[11px] text-slate-400">Uncheck to apply <code class="bg-slate-100 px-1 py-0.5 rounded">noindex, nofollow</code> robot tags.</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- ================= TAB 5: SCHEMA (STRUCTURED DATA) ================= -->
        <div x-show="activeTab === 'schema'" class="space-y-6">
            <!-- Automated Schema Status Card -->
            <div class="p-5 rounded-2xl bg-gradient-to-r from-purple-50/80 via-indigo-50/60 to-blue-50/60 border border-purple-200/80 space-y-3">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center shadow-sm">
                            <i data-lucide="boxes" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 flex items-center gap-2">
                                <span>Google Rich Snippets & Structured Data</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Auto-Generated Active</span>
                            </h4>
                            <p class="text-[11px] text-slate-500">Every published post is automatically equipped with Schema.org JSON-LD structured data for Google search indexing.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="https://search.google.com/test/rich-results" target="_blank" rel="noopener" class="px-2.5 py-1 text-[11px] font-semibold text-purple-700 bg-white hover:bg-purple-50 rounded-lg border border-purple-200 transition inline-flex items-center gap-1 shadow-2xs">
                            <span>Google Rich Test</span>
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                        </a>
                        <a href="https://validator.schema.org/" target="_blank" rel="noopener" class="px-2.5 py-1 text-[11px] font-semibold text-slate-600 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition inline-flex items-center gap-1 shadow-2xs">
                            <span>Schema Validator</span>
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>

                <!-- Automatic Schema Tags Pill List -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-2">
                    <div class="p-3 rounded-xl bg-white/90 border border-purple-100 shadow-2xs">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-purple-600 flex items-center gap-1">
                            <i data-lucide="file-text" class="w-3 h-3"></i>
                            <span>{{ $type === 'post' ? 'BlogPosting / Article' : ($type === 'service' ? 'Service / Offer' : 'WebPage') }}</span>
                        </div>
                        <p class="text-[11px] text-slate-600 mt-1">Author, dates, featured images, canonical URL & publisher.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/90 border border-purple-100 shadow-2xs">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 flex items-center gap-1">
                            <i data-lucide="git-commit" class="w-3 h-3"></i>
                            <span>BreadcrumbList</span>
                        </div>
                        <p class="text-[11px] text-slate-600 mt-1">Hierarchical site navigation breadcrumbs for search results.</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/90 border border-purple-100 shadow-2xs">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-blue-600 flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-3 h-3"></i>
                            <span>LocalBusiness Publisher</span>
                        </div>
                        <p class="text-[11px] text-slate-600 mt-1">Recovery Data Dubai Cleanroom Lab NAP & contact schema.</p>
                    </div>
                </div>
            </div>

            <!-- Custom Schema Markup Editor Card -->
            <div class="space-y-3">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div>
                        <label for="schemaMarkupInput" class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                            <i data-lucide="code" class="w-3.5 h-3.5 text-purple-600"></i>
                            <span>Custom Schema (JSON-LD Code)</span>
                        </label>
                        <p class="text-[11px] text-slate-400 mt-0.5">Paste custom JSON-LD schema (e.g. FAQ, HowTo, Service, Review). Overrides or supplements default markup.</p>
                    </div>

                    <!-- JSON Syntax Status Badge -->
                    <div class="flex items-center gap-2">
                        <template x-if="schemaStatus === 'valid'">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Valid JSON-LD Syntax</span>
                            </span>
                        </template>
                        <template x-if="schemaStatus === 'invalid'">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200" :title="schemaError">
                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                                <span>Invalid JSON (<span x-text="schemaError"></span>)</span>
                            </span>
                        </template>
                        <template x-if="schemaStatus === 'empty'">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">
                                <span>No Custom Schema (Using Default)</span>
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Template Inserters & Action Toolbar -->
                <div class="flex items-center justify-between flex-wrap gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[11px] font-bold text-slate-500 mr-1">Insert Template:</span>
                        <button type="button" @click="insertTemplate('faq')" 
                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white hover:bg-purple-50 text-purple-700 border border-slate-200 hover:border-purple-300 shadow-2xs transition flex items-center gap-1">
                            <i data-lucide="help-circle" class="w-3 h-3 text-purple-600"></i>
                            <span>+ FAQ Schema</span>
                        </button>
                        <button type="button" @click="insertTemplate('howto')" 
                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white hover:bg-purple-50 text-purple-700 border border-slate-200 hover:border-purple-300 shadow-2xs transition flex items-center gap-1">
                            <i data-lucide="list-ordered" class="w-3 h-3 text-purple-600"></i>
                            <span>+ How-To Schema</span>
                        </button>
                        <button type="button" @click="insertTemplate('service')" 
                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white hover:bg-purple-50 text-purple-700 border border-slate-200 hover:border-purple-300 shadow-2xs transition flex items-center gap-1">
                            <i data-lucide="briefcase" class="w-3 h-3 text-purple-600"></i>
                            <span>+ Service Schema</span>
                        </button>
                        <button type="button" @click="insertTemplate('article')" 
                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white hover:bg-purple-50 text-purple-700 border border-slate-200 hover:border-purple-300 shadow-2xs transition flex items-center gap-1">
                            <i data-lucide="newspaper" class="w-3 h-3 text-purple-600"></i>
                            <span>+ Custom Article</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <button type="button" @click="formatSchema()" 
                                :disabled="!customSchema || schemaStatus === 'invalid'"
                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 shadow-2xs transition disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1">
                            <i data-lucide="wand-2" class="w-3 h-3 text-purple-600"></i>
                            <span>Format / Prettify</span>
                        </button>
                        <button type="button" @click="clearSchema()" 
                                x-show="customSchema && customSchema.length > 0"
                                class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 hover:border-rose-300 shadow-2xs transition flex items-center gap-1">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                            <span>Clear</span>
                        </button>
                    </div>
                </div>

                <!-- Code Editor Textarea -->
                <div class="relative">
                    <textarea id="schemaMarkupInput" 
                              name="schema_markup" 
                              rows="12" 
                              x-model="customSchema" 
                              @input="validateSchema()"
                              placeholder='{
  "@@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "How long does data recovery take in Dubai?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Standard diagnostics take 24-48 hours. Emergency cleanroom recovery is available same-day."
      }
    }
  ]
}'
                              class="w-full bg-slate-950 text-emerald-400 p-4 rounded-xl border border-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/30 font-mono text-xs leading-relaxed transition shadow-inner"></textarea>
                </div>

                <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>Accepts raw JSON (<code class="bg-slate-100 text-slate-700 px-1 py-0.5 rounded">{ ... }</code>) or wrapped <code class="bg-slate-100 text-slate-700 px-1 py-0.5 rounded">&lt;script type="application/ld+json"&gt;</code> tags.</span>
                    <span x-text="(customSchema ? customSchema.length : 0) + ' characters'"></span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('yoastSeoAnalyzer', (config) => ({
        activeTab: 'general',
        previewMode: 'desktop',
        titleInputId: config.titleInputId,
        slugInputId: config.slugInputId,
        contentInputId: config.contentInputId,
        urlPrefix: config.urlPrefix,

        focusKeyword: config.initialFocusKeyword || '',
        metaTitle: config.initialTitle || '',
        metaDescription: config.initialDescription || '',
        ogTitle: '',
        ogDescription: '',
        ogImage: '',

        effectiveTitle: '',
        effectiveDescription: '',
        currentSlug: '',
        contentHtml: '',

        seoScore: config.initialSeoScore || 0,
        readabilityScore: config.initialReadabilityScore || 0,
        seoScoreRating: 'poor',
        readabilityRating: 'poor',

        customSchema: config.initialSchemaMarkup || '',
        schemaStatus: 'empty',
        schemaError: '',

        checksList: [],

        initAnalyzer() {
            this.validateSchema();

            // Bind to title input
            const titleEl = document.getElementById(this.titleInputId);
            if (titleEl) {
                this.effectiveTitle = titleEl.value;
                titleEl.addEventListener('input', () => {
                    this.effectiveTitle = titleEl.value;
                    this.reanalyze();
                });
            }

            // Bind to slug input
            const slugEl = document.getElementById(this.slugInputId);
            if (slugEl) {
                this.currentSlug = slugEl.value;
                slugEl.addEventListener('input', () => {
                    this.currentSlug = slugEl.value;
                    this.reanalyze();
                });
            }

            // Bind to Quill or textarea
            const contentEl = document.getElementById(this.contentInputId);
            if (contentEl) {
                this.contentHtml = contentEl.value;
                contentEl.addEventListener('input', () => {
                    this.contentHtml = contentEl.value;
                    this.reanalyze();
                });
            }

            // Listen for Quill text-change if available
            if (window.quill) {
                window.quill.on('text-change', () => {
                    this.contentHtml = window.quill.root.innerHTML;
                    this.reanalyze();
                });
            }

            this.reanalyze();
        },

        get titleLength() {
            return (this.metaTitle || this.effectiveTitle || '').length;
        },

        get descLength() {
            return (this.metaDescription || '').length;
        },

        get titleLengthClass() {
            const l = this.titleLength;
            if (l >= 40 && l <= 60) return 'text-emerald-600 font-bold';
            if (l > 60) return 'text-rose-600 font-bold';
            return 'text-amber-600 font-medium';
        },

        get titleMeterColor() {
            const l = this.titleLength;
            if (l >= 40 && l <= 60) return 'bg-emerald-500';
            if (l > 60) return 'bg-rose-500';
            return 'bg-amber-400';
        },

        get descLengthClass() {
            const l = this.descLength;
            if (l >= 120 && l <= 160) return 'text-emerald-600 font-bold';
            if (l > 160) return 'text-rose-600 font-bold';
            return 'text-amber-600 font-medium';
        },

        get descMeterColor() {
            const l = this.descLength;
            if (l >= 120 && l <= 160) return 'bg-emerald-500';
            if (l > 160) return 'bg-rose-500';
            return 'bg-amber-400';
        },

        get passedChecksCount() {
            return this.checksList.filter(c => c.status === 'good').length;
        },

        get totalChecksCount() {
            return this.checksList.length;
        },

        reanalyze() {
            const keyword = (this.focusKeyword || '').trim().toLowerCase();
            const fullTitle = (this.metaTitle || this.effectiveTitle || '').trim();
            const fullDesc = (this.metaDescription || '').trim();
            const slug = (this.currentSlug || '').trim();
            
            // Extract raw text from HTML content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = this.contentHtml || '';
            const plainContent = tempDiv.textContent || tempDiv.innerText || '';
            const words = plainContent.trim().split(/\s+/).filter(w => w.length > 0);
            const wordCount = words.length;

            const checks = [];
            let earnedSeoPoints = 0;
            let totalSeoPoints = 0;

            let earnedReadPoints = 0;
            let totalReadPoints = 0;

            // 1. Focus Keyword set
            totalSeoPoints += 10;
            if (keyword.length > 0) {
                earnedSeoPoints += 10;
                checks.push({
                    title: 'Focus Keyphrase Defined',
                    status: 'good',
                    message: `Focus keyword "${this.focusKeyword}" is specified.`
                });
            } else {
                checks.push({
                    title: 'Focus Keyphrase Missing',
                    status: 'poor',
                    message: 'Add a focus keyphrase to enable full Yoast SEO ranking audit.'
                });
            }

            // 2. Keyphrase in SEO Title
            totalSeoPoints += 15;
            if (keyword && fullTitle.toLowerCase().includes(keyword)) {
                earnedSeoPoints += 15;
                checks.push({
                    title: 'Keyphrase in SEO Title',
                    status: 'good',
                    message: `The focus keyphrase appears in the SEO title.`
                });
            } else if (keyword) {
                checks.push({
                    title: 'Keyphrase Missing from SEO Title',
                    status: 'poor',
                    message: `Include "${this.focusKeyword}" in your SEO title.`
                });
            }

            // 3. SEO Title Length
            totalSeoPoints += 15;
            if (fullTitle.length >= 40 && fullTitle.length <= 60) {
                earnedSeoPoints += 15;
                checks.push({
                    title: 'SEO Title Length',
                    status: 'good',
                    message: `Title length (${fullTitle.length} characters) is ideal for Google results.`
                });
            } else if (fullTitle.length > 60) {
                earnedSeoPoints += 5;
                checks.push({
                    title: 'SEO Title Too Long',
                    status: 'poor',
                    message: `Title is ${fullTitle.length} characters. Google will likely truncate it beyond 60.`
                });
            } else {
                earnedSeoPoints += 5;
                checks.push({
                    title: 'SEO Title Too Short',
                    status: 'ok',
                    message: `Title is only ${fullTitle.length} characters. Consider adding more descriptive details.`
                });
            }

            // 4. Keyphrase in Meta Description
            totalSeoPoints += 15;
            if (keyword && fullDesc.toLowerCase().includes(keyword)) {
                earnedSeoPoints += 15;
                checks.push({
                    title: 'Keyphrase in Meta Description',
                    status: 'good',
                    message: 'The meta description contains the focus keyphrase.'
                });
            } else if (keyword && fullDesc.length > 0) {
                checks.push({
                    title: 'Keyphrase Missing in Meta Description',
                    status: 'ok',
                    message: `The meta description does not contain the keyphrase "${this.focusKeyword}".`
                });
            } else {
                checks.push({
                    title: 'Meta Description Missing',
                    status: 'poor',
                    message: 'No meta description set. Search engines will generate an arbitrary snippet.'
                });
            }

            // 5. Meta Description Length
            totalSeoPoints += 15;
            if (fullDesc.length >= 120 && fullDesc.length <= 160) {
                earnedSeoPoints += 15;
                checks.push({
                    title: 'Meta Description Length',
                    status: 'good',
                    message: `Description length (${fullDesc.length} characters) is optimal.`
                });
            } else if (fullDesc.length > 160) {
                earnedSeoPoints += 5;
                checks.push({
                    title: 'Meta Description Too Long',
                    status: 'ok',
                    message: `Description is ${fullDesc.length} characters. May be truncated on search results.`
                });
            } else {
                checks.push({
                    title: 'Meta Description Length',
                    status: 'poor',
                    message: `Description is only ${fullDesc.length} characters. Target between 120 and 160.`
                });
            }

            // 6. Keyphrase in Slug / URL
            totalSeoPoints += 15;
            const normalizedKeywordSlug = keyword.replace(/\s+/g, '-');
            if (keyword && slug.toLowerCase().includes(normalizedKeywordSlug)) {
                earnedSeoPoints += 15;
                checks.push({
                    title: 'Keyphrase in URL Slug',
                    status: 'good',
                    message: 'The URL slug contains your focus keyphrase.'
                });
            } else if (keyword) {
                earnedSeoPoints += 5;
                checks.push({
                    title: 'Keyphrase in Slug',
                    status: 'ok',
                    message: 'Consider including the keyphrase in the permalink slug.'
                });
            }

            // 7. Keyword Density & Content Length
            totalSeoPoints += 15;
            if (wordCount >= 250) {
                earnedSeoPoints += 15;
                checks.push({
                    title: 'Text Word Count',
                    status: 'good',
                    message: `Content length is ${wordCount} words (above 250 word minimum threshold).`
                });
            } else {
                earnedSeoPoints += 5;
                checks.push({
                    title: 'Text Word Count',
                    status: 'ok',
                    message: `Content has ${wordCount} words. 250+ words recommended for high search ranking.`
                });
            }

            // Readability Checks
            totalReadPoints += 50;
            const hasHeadings = tempDiv.querySelectorAll('h1, h2, h3, h4').length > 0;
            if (hasHeadings) {
                earnedReadPoints += 50;
                checks.push({
                    title: 'Subheadings Distribution',
                    status: 'good',
                    message: 'Content contains structured subheadings (H2/H3) for easy reading.'
                });
            } else {
                earnedReadPoints += 20;
                checks.push({
                    title: 'Subheadings Missing',
                    status: 'ok',
                    message: 'Add H2 or H3 section titles to make the page easier to scan.'
                });
            }

            totalReadPoints += 50;
            if (wordCount > 50) {
                earnedReadPoints += 50;
                checks.push({
                    title: 'Paragraph Structure',
                    status: 'good',
                    message: 'Content is well-proportioned for reading.'
                });
            } else {
                earnedReadPoints += 20;
                checks.push({
                    title: 'Thin Content',
                    status: 'poor',
                    message: 'Add more informative text to improve reader engagement.'
                });
            }

            // Calculate percentage scores
            this.seoScore = Math.min(100, Math.round((earnedSeoPoints / totalSeoPoints) * 100));
            this.readabilityScore = Math.min(100, Math.round((earnedReadPoints / totalReadPoints) * 100));

            // Ratings
            this.seoScoreRating = this.seoScore >= 80 ? 'good' : (this.seoScore >= 50 ? 'ok' : 'poor');
            this.readabilityRating = this.readabilityScore >= 80 ? 'good' : (this.readabilityScore >= 50 ? 'ok' : 'poor');

            this.checksList = checks;
        },

        validateSchema() {
            const raw = (this.customSchema || '').trim();
            if (!raw) {
                this.schemaStatus = 'empty';
                this.schemaError = '';
                return;
            }

            // Strip script tags if user pasted full script block
            let clean = raw.replace(/^<script[^>]*>/i, '').replace(/<\/script>$/i, '').trim();

            try {
                JSON.parse(clean);
                this.schemaStatus = 'valid';
                this.schemaError = '';
            } catch (e) {
                this.schemaStatus = 'invalid';
                this.schemaError = e.message ? e.message.substring(0, 50) : 'Invalid JSON';
            }
        },

        formatSchema() {
            const raw = (this.customSchema || '').trim();
            if (!raw) return;

            let isWrapped = /^<script[^>]*>/i.test(raw);
            let clean = raw.replace(/^<script[^>]*>/i, '').replace(/<\/script>$/i, '').trim();

            try {
                const parsed = JSON.parse(clean);
                const pretty = JSON.stringify(parsed, null, 2);
                if (isWrapped) {
                    this.customSchema = '<script type="application/ld+json">\n' + pretty + '\n</' + 'script>';
                } else {
                    this.customSchema = pretty;
                }
                this.validateSchema();
            } catch (e) {
                alert('Could not format: ' + e.message);
            }
        },

        clearSchema() {
            if (confirm('Are you sure you want to clear custom schema markup?')) {
                this.customSchema = '';
                this.validateSchema();
            }
        },

        insertTemplate(type) {
            let template = '';
            const title = (this.metaTitle || this.effectiveTitle || 'Data Recovery Dubai').trim();
            const desc = (this.metaDescription || this.effectiveDescription || 'Professional data recovery lab in Dubai').trim();
            const canonical = this.currentSlug ? this.urlPrefix + '/' + this.currentSlug : this.urlPrefix;

            if (type === 'faq') {
                template = JSON.stringify({
                    "@@context": "https://schema.org",
                    "@type": "FAQPage",
                    "mainEntity": [
                        {
                            "@type": "Question",
                            "name": "Can data be recovered from a physically damaged drive?",
                            "acceptedAnswer": {
                                "@type": "Answer",
                                "text": "Yes, our certified ISO Class 100 cleanroom lab in Dubai performs mechanical head and platter transplants with high recovery rates."
                            }
                        },
                        {
                            "@type": "Question",
                            "name": "How much does data recovery cost in Dubai?",
                            "acceptedAnswer": {
                                "@type": "Answer",
                                "text": "Diagnostics are 100% free with a written fixed quote. We operate under a strict No Data, No Charge policy."
                            }
                        }
                    ]
                }, null, 2);
            } else if (type === 'howto') {
                template = JSON.stringify({
                    "@@context": "https://schema.org",
                    "@type": "HowTo",
                    "name": "How to Handle Storage Failure Before Professional Data Recovery",
                    "description": "Critical emergency steps to preserve data integrity before bringing your device to a cleanroom lab.",
                    "step": [
                        {
                            "@type": "HowToStep",
                            "name": "Step 1: Power Down the Device Immediately",
                            "text": "Disconnect power instantly to prevent mechanical drive heads from scratching platters or NAND wear."
                        },
                        {
                            "@type": "HowToStep",
                            "name": "Step 2: Do Not Run Automated Chkdsk or Format Tools",
                            "text": "Automated tools write over bad sectors and destroy crucial partition and filesystem trees."
                        },
                        {
                            "@type": "HowToStep",
                            "name": "Step 3: Consult a Certified Cleanroom Lab",
                            "text": "Call Recovery Data Dubai (+971 56 410 1040) for safe courier pickup across all 7 Emirates and free diagnostic analysis."
                        }
                    ]
                }, null, 2);
            } else if (type === 'service') {
                template = JSON.stringify({
                    "@@context": "https://schema.org",
                    "@type": "Service",
                    "name": title,
                    "description": desc,
                    "provider": {
                        "@type": "LocalBusiness",
                        "name": "Recovery Data Dubai",
                        "url": "https://www.recoverydata.ae",
                        "telephone": "+971564101040",
                        "address": {
                            "@type": "PostalAddress",
                            "streetAddress": "Office # 508, Dusseldorf Business Point, Al Barsha 1",
                            "addressLocality": "Dubai",
                            "addressCountry": "AE"
                        }
                    },
                    "areaServed": ["Dubai", "Abu Dhabi", "Sharjah", "Ajman", "Ras Al Khaimah", "Fujairah", "Umm Al Quwain"]
                }, null, 2);
            } else if (type === 'article') {
                template = JSON.stringify({
                    "@@context": "https://schema.org",
                    "@type": "BlogPosting",
                    "headline": title,
                    "description": desc,
                    "mainEntityOfPage": {
                        "@type": "WebPage",
                        "@id": canonical
                    },
                    "author": {
                        "@type": "Organization",
                        "name": "Recovery Data Cleanroom Engineering Lab",
                        "url": "https://www.recoverydata.ae"
                    },
                    "publisher": {
                        "@type": "LocalBusiness",
                        "name": "Recovery Data Dubai",
                        "url": "https://www.recoverydata.ae",
                        "telephone": "+971564101040"
                    }
                }, null, 2);
            }

            if (this.customSchema && this.customSchema.trim().length > 0) {
                if (!confirm('This will replace your current custom schema. Continue?')) {
                    return;
                }
            }

            this.customSchema = template;
            this.validateSchema();
        }
    }));
});
</script>
