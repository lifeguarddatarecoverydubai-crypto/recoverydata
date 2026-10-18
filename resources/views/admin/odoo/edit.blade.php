@extends('layouts.admin')

@section('title', 'Edit Odoo Partner Landing Page')

@section('content')
<div class="space-y-6" x-data="odooEditor()">
    <!-- Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Odoo ERP UAE Partner Page</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Odoo Partner Landing Page</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-purple-100 text-purple-800 border border-purple-200">Official UAE Partner</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Manage marketing headlines, VAT accounting & WPS payroll modules, services, FAQs, and Yoast SEO signals.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('odoo.index') }}" target="_blank" class="px-3.5 py-2 text-xs font-semibold text-purple-600 bg-purple-50 hover:bg-purple-100 rounded-xl transition flex items-center gap-1.5 shadow-xs">
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                <span>View Live Landing Page</span>
            </a>
            <button type="button" @click="$refs.odooForm.submit()" class="px-4 py-2 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl transition flex items-center gap-1.5 shadow-md shadow-purple-500/20">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Save All Changes</span>
            </button>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.odoo.update') }}" method="POST" x-ref="odooForm" class="space-y-8" @submit="syncJsonFields()">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Main Column (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Hero Section Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center">
                                <i data-lucide="presentation" class="w-4 h-4"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm">Hero Banner & Main Value Proposition</h3>
                        </div>
                        <span class="text-xs text-slate-400">Section 1</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Hero Badge</label>
                            <input type="text" name="hero_badge" value="{{ old('hero_badge', $odoo->hero_badge) }}" required
                                   placeholder="Official Partner"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Region / Country Label</label>
                            <input type="text" name="hero_country" value="{{ old('hero_country', $odoo->hero_country) }}" required
                                   placeholder="United Arab Emirates"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Main Headline (H1)</label>
                        <input type="text" id="heroTitle" name="hero_title" value="{{ old('hero_title', $odoo->hero_title) }}" required
                               placeholder="Transform Your Business with Official Odoo ERP in Dubai"
                               class="w-full px-4 py-3 text-base font-bold rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Hero Subtitle & Introduction</label>
                        <textarea id="heroSubtitle" name="hero_subtitle" rows="3" required
                                  placeholder="Detailed explanation of Odoo ERP capabilities in the UAE..."
                                  class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 leading-relaxed">{{ old('hero_subtitle', $odoo->hero_subtitle) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Direct Phone / WhatsApp</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $odoo->contact_phone) }}" required
                                   placeholder="+971 56 976 3386"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Inquiry Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $odoo->contact_email) }}" required
                                   placeholder="info@recoverydata.ae"
                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Trust & Compliance Bullet Points (One per line)</label>
                        @php
                            $trustString = is_array($odoo->trust_items) ? implode("\n", $odoo->trust_items) : '';
                        @endphp
                        <textarea name="trust_items_input" rows="4"
                                  placeholder="FTA 5% VAT Ready&#10;UAE WPS SIF Export&#10;Arabic & English UI&#10;5 UAE Branch Offices"
                                  class="w-full px-3.5 py-2.5 text-xs font-mono rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">{{ old('trust_items_input', $trustString) }}</textarea>
                    </div>
                </div>

                <!-- Modules Customizer (Accounting, HRMS, CRM, etc.) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                <i data-lucide="layers" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Odoo Modules & UAE Localizations</h3>
                                <p class="text-xs text-slate-400">Configure tabs for Accounting, WPS Payroll, CRM, Inventory, MRP, and POS.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden input to store updated modules JSON -->
                    <input type="hidden" name="modules_json" :value="JSON.stringify(modules)">

                    <div class="space-y-4">
                        <template x-for="(mod, index) in modules" :key="index">
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-purple-700 uppercase" x-text="'Module ' + (index + 1) + ': ' + mod.name"></span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 font-bold" x-text="mod.badge"></span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Module Name</label>
                                        <input type="text" x-model="mod.name" class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Badge</label>
                                        <input type="text" x-model="mod.badge" class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-white">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Headline</label>
                                    <input type="text" x-model="mod.headline" class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-white font-semibold">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Description</label>
                                    <textarea x-model="mod.desc" rows="2" class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-white"></textarea>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- FAQs Customizer -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center">
                                <i data-lucide="help-circle" class="w-4 h-4"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm">UAE Business FAQs Accordion</h3>
                        </div>
                    </div>

                    <!-- Hidden input to store updated faqs JSON -->
                    <input type="hidden" name="faqs_json" :value="JSON.stringify(faqs)">

                    <div class="space-y-4">
                        <template x-for="(faq, fIndex) in faqs" :key="fIndex">
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 space-y-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Question</label>
                                    <input type="text" x-model="faq.q" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-white font-semibold">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Answer</label>
                                    <textarea x-model="faq.a" rows="3" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-white"></textarea>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Yoast SEO Box -->
                @include('admin.partials.yoast-seo-box', [
                    'model' => $odoo,
                    'titleInputId' => 'heroTitle',
                    'slugInputId' => 'heroTitle',
                    'contentInputId' => 'heroSubtitle',
                    'urlPrefix' => url('/odoo'),
                    'type' => 'odoo'
                ])

            </div>

            <!-- Right Sidebar Column (1 Col) -->
            <div class="space-y-6">

                <!-- Save & Publish Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4 sticky top-24">
                    <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3 flex items-center justify-between">
                        <span>Save & Publish</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-extrabold uppercase">Live on Site</span>
                    </h3>

                    <p class="text-xs text-slate-500">Any modifications made here are updated instantly on the live <a href="{{ route('odoo.index') }}" target="_blank" class="text-purple-600 font-bold underline">/odoo</a> landing page.</p>

                    <!-- Indexable Toggle -->
                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_indexable" value="1" {{ old('is_indexable', $odoo->is_indexable) ? 'checked' : '' }}
                                   class="rounded border-slate-300 text-purple-600 focus:ring-purple-500 h-4 w-4">
                            <span class="text-xs font-semibold text-slate-700">Index on Google & Search Engines</span>
                        </label>
                    </div>

                    <div class="pt-4 flex flex-col gap-2 border-t border-slate-100">
                        <button type="submit" 
                                class="w-full py-3 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md shadow-purple-500/20 transition flex items-center justify-center gap-2">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span>Update Odoo Landing Page</span>
                        </button>
                        <a href="{{ route('odoo.index') }}" target="_blank" class="w-full py-2.5 text-center text-xs font-semibold text-slate-600 hover:bg-slate-50 border border-slate-200 rounded-xl transition flex items-center justify-center gap-1.5">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            <span>Preview Frontend (/odoo)</span>
                        </a>
                    </div>
                </div>

                <!-- Certified Partner Quick Details -->
                <div class="bg-gradient-to-br from-[#1c1236] to-[#0f091e] p-6 rounded-2xl border border-purple-900 text-white shadow-xl space-y-3">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/odoo-logo.svg') }}" alt="Odoo" class="h-5 w-auto">
                        <span class="text-xs font-bold tracking-wide uppercase text-purple-300">UAE Official Status</span>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        This landing page highlights Recovery Data's official partnership accreditation for Odoo Enterprise implementation across the UAE.
                    </p>
                    <div class="pt-2 border-t border-purple-800/60 text-[11px] text-purple-200 space-y-1">
                        <div>&bull; <strong>Accounting:</strong> UAE 5% VAT & FAF files</div>
                        <div>&bull; <strong>Payroll:</strong> Central Bank WPS .SIF</div>
                        <div>&bull; <strong>Supply Chain:</strong> Freezone & Mainland routing</div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function odooEditor() {
        return {
            modules: {!! json_encode($odoo->modules ?? []) !!},
            faqs: {!! json_encode($odoo->faqs ?? []) !!},
            syncJsonFields() {
                // Alpine will automatically bind to inputs before submission
            }
        };
    }
</script>
@endpush
