@php
    $isEdit = isset($product);
    $actionUrl = $isEdit ? route('admin.products.update', $product) : route('admin.products.store');
@endphp

<form action="{{ $actionUrl }}" method="POST" id="productForm" class="space-y-8">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Left Column (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Title & Slug Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label for="productTitle" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Product Title</label>
                        <input type="text" id="productTitle" name="title" required value="{{ old('title', $product->title ?? '') }}" 
                               placeholder="e.g. Cleanroom Diagnostics, Enterprise Lab Recovery"
                               class="w-full px-4 py-3 text-lg font-bold rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition">
                    </div>
                    <div>
                        <label for="productBadge" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Badge / Tag</label>
                        <input type="text" id="productBadge" name="badge" value="{{ old('badge', $product->badge ?? '') }}" 
                               placeholder="e.g. Flagship HCM, AI-Native"
                               class="w-full px-3.5 py-3 text-sm font-semibold rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                    </div>
                </div>

                <!-- Slug / Permalink Box -->
                <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 p-2.5 rounded-xl border border-slate-200/60" x-data="{ editingSlug: false }">
                    <span class="font-semibold text-slate-600">Permalink:</span>
                    <span class="text-slate-400">{{ url('/product') }}/</span>
                    <span x-show="!editingSlug" id="slugDisplay" class="font-mono text-purple-600 font-bold">{{ old('slug', $product->slug ?? 'your-product-slug') }}</span>
                    <input type="text" id="productSlug" name="slug" value="{{ old('slug', $product->slug ?? '') }}" x-show="editingSlug"
                           class="px-2 py-0.5 rounded border border-slate-300 font-mono text-xs focus:outline-none focus:border-purple-600 w-64">
                    <button type="button" @click="editingSlug = !editingSlug" class="ml-auto text-xs text-purple-600 font-semibold hover:underline">
                        <span x-text="editingSlug ? 'Done' : 'Edit'"></span>
                    </button>
                </div>

                <!-- Tagline & Category -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="productTagline" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Tagline / Subheadline</label>
                        <input type="text" id="productTagline" name="tagline" value="{{ old('tagline', $product->tagline ?? '') }}" 
                               placeholder="e.g. New-Age AI-Native HCM for UAE Enterprises"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                    </div>
                    <div>
                        <label for="productCategory" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Product Category</label>
                        <input type="text" id="productCategory" name="category" value="{{ old('category', $product->category ?? '') }}" 
                               placeholder="e.g. Human Resources & Payroll, Sales CRM"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">
                    </div>
                </div>
            </div>

            <!-- WYSIWYG Editor Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Product Overview & Architecture</label>
                    <span class="text-xs text-slate-400">Rich HTML formatted overview</span>
                </div>

                <!-- Hidden Input for Form Submission -->
                <textarea name="content" id="productContentInput" class="hidden">{{ old('content', $product->content ?? '') }}</textarea>

                <!-- Quill Container -->
                <div id="productQuillEditor" class="min-h-[340px] bg-white"></div>
            </div>

            <!-- Short Summary Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-2">
                <label for="productSummary" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Short Summary</label>
                <p class="text-xs text-slate-400">Used on navigation mega menu, cards, and product showcase switcher.</p>
                <textarea id="productSummary" name="summary" rows="3" placeholder="Brief summary of the software product..."
                          class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition">{{ old('summary', $product->summary ?? '') }}</textarea>
            </div>

            <!-- Modules & Highlights -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Modules Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-2">
                    <label for="modulesInput" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Product Modules (Format: Title | Description)</label>
                    <p class="text-[11px] text-slate-400">One per line. Example: <code class="bg-slate-100 px-1 py-0.5 rounded">WPS Payroll | Instant SIF file generation</code></p>
                    <textarea id="modulesInput" name="modules_input" rows="6" 
                              placeholder="WPS Payroll & Gratuity | Instant UAE WPS SIF file generation&#10;Smart Attendance | Geofenced mobile check-in&#10;Employee Self Service | Mobile-first leaves & claims"
                              class="w-full px-3.5 py-2.5 text-xs font-mono rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">{{ old('modules_input', $modulesString ?? '') }}</textarea>
                </div>

                <!-- Highlights Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-2">
                    <label for="highlightsInput" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Key Highlights & Stats</label>
                    <p class="text-[11px] text-slate-400">Bullet points for high-converting sales points. One per line.</p>
                    <textarea id="highlightsInput" name="highlights_input" rows="6" 
                              placeholder="100% UAE Labour Law & WPS Compliant&#10;Zero Payroll Calculation Errors&#10;95% Faster Leave Approvals&#10;Bilingual Arabic & English UI"
                              class="w-full px-3.5 py-2.5 text-xs font-mono rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600">{{ old('highlights_input', $highlightsString ?? '') }}</textarea>
                </div>
            </div>

            <!-- Yoast SEO Box -->
            @include('admin.partials.yoast-seo-box', [
                'model' => $product ?? null,
                'titleInputId' => 'productTitle',
                'slugInputId' => 'productSlug',
                'contentInputId' => 'productContentInput',
                'urlPrefix' => url('/product'),
                'type' => 'product'
            ])

        </div>

        <!-- Right Sidebar (1 Col) -->
        <div class="space-y-6">

            <!-- Publish Settings Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3 flex items-center justify-between">
                    <span>Publish Settings</span>
                    @if($isEdit)
                        <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="text-xs font-semibold text-purple-600 hover:underline">Preview ↗</a>
                    @endif
                </h3>

                <!-- Status Selector -->
                <div>
                    <label for="productStatus" class="block text-xs font-semibold text-slate-700 mb-1.5">Status</label>
                    <select id="productStatus" name="status" class="w-full text-xs rounded-xl border border-slate-200 p-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                        <option value="published" {{ old('status', $product->status ?? 'published') === 'published' ? 'selected' : '' }}>Published (Active)</option>
                        <option value="draft" {{ old('status', $product->status ?? '') === 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                    </select>
                </div>

                <!-- Display Order -->
                <div>
                    <label for="productOrder" class="block text-xs font-semibold text-slate-700 mb-1.5">Display Order</label>
                    <input type="number" id="productOrder" name="order" value="{{ old('order', $product->order ?? 0) }}"
                           class="w-full text-xs rounded-xl border border-slate-200 p-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                    <p class="text-[11px] text-slate-400 mt-1">Lower numbers appear first on product tab switcher.</p>
                </div>

                <!-- Featured Toggle -->
                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? true) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-purple-600 focus:ring-purple-500 h-4 w-4">
                        <span class="text-xs font-semibold text-slate-700">Feature on Homepage Switcher</span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex flex-col gap-2 border-t border-slate-100">
                    <button type="submit" 
                            class="w-full py-2.5 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md shadow-purple-500/20 transition flex items-center justify-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>{{ $isEdit ? 'Update Product' : 'Save & Publish Product' }}</span>
                    </button>
                    @if($isEdit)
                        <button type="button" onclick="if(confirm('Are you sure you want to delete this product?')) { document.getElementById('deleteProductForm').submit(); }" 
                                class="w-full py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition">
                            Delete Product
                        </button>
                    @endif
                </div>
            </div>

            <!-- Demo & Brochure Links -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">CTA & Demo Links</h3>
                
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Demo Request URL</label>
                    <input type="text" name="demo_url" value="{{ old('demo_url', $product->demo_url ?? '/contact') }}"
                           placeholder="/contact or demo link"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Brochure PDF / Link</label>
                    <input type="text" name="brochure_url" value="{{ old('brochure_url', $product->brochure_url ?? '') }}"
                           placeholder="https://... or PDF path"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                </div>
            </div>

            <!-- Icon Picker Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3" x-data="{ selectedIcon: '{{ old('icon', $product->icon ?? 'box') }}' }">
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Lucide Icon</h3>
                
                <input type="text" id="iconInput" name="icon" x-model="selectedIcon"
                       placeholder="e.g. users-round, target, layers-3, cpu, box"
                       class="w-full px-3 py-2 text-xs font-mono rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20">

                <div class="grid grid-cols-5 gap-2 pt-1">
                    @php
                        $presetIcons = ['users-round', 'target', 'layers-3', 'cpu', 'box', 'sparkles', 'briefcase', 'bar-chart-2', 'shield-check', 'zap'];
                    @endphp
                    @foreach($presetIcons as $ico)
                        <button type="button" @click="selectedIcon = '{{ $ico }}'"
                                :class="selectedIcon === '{{ $ico }}' ? 'border-purple-600 bg-purple-50 text-purple-700' : 'border-slate-200 hover:border-slate-300 text-slate-600'"
                                class="p-2 border rounded-xl flex items-center justify-center transition" title="{{ $ico }}">
                            <i data-lucide="{{ $ico }}" class="w-4 h-4"></i>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Featured Image / Screenshot Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Product Mockup / Image</h3>
                <p class="text-[11px] text-slate-400">Image URL or path for software UI preview.</p>
                <input type="text" name="featured_image" value="{{ old('featured_image', $product->featured_image ?? '') }}"
                       placeholder="https://... or image path"
                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                @if(isset($product) && $product->featured_image_url)
                    <div class="rounded-xl overflow-hidden border border-slate-200 aspect-video">
                        <img src="{{ $product->featured_image_url }}" alt="{{ $product->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/product-software.svg') }}';">
                    </div>
                @endif

            </div>

        </div>
    </div>
</form>

@if($isEdit)
    <form id="deleteProductForm" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endif

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quillEl = document.getElementById('productQuillEditor');
    const contentInput = document.getElementById('productContentInput');
    const titleInput = document.getElementById('productTitle');
    const slugInput = document.getElementById('productSlug');
    const slugDisplay = document.getElementById('slugDisplay');

    if (quillEl && contentInput) {
        window.quill = new Quill(quillEl, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'clean']
                ]
            }
        });

        if (contentInput.value) {
            window.quill.root.innerHTML = contentInput.value;
        }

        window.quill.on('text-change', function() {
            contentInput.value = window.quill.root.innerHTML;
        });
    }

    @if(! $isEdit)
    if (titleInput && slugInput && slugDisplay) {
        titleInput.addEventListener('input', function() {
            const raw = titleInput.value.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = raw;
            slugDisplay.textContent = raw || 'your-product-slug';
        });
    }
    @endif
});
</script>
@endpush
