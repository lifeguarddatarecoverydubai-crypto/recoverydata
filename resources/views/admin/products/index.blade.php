@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span>Enterprise Products</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-700">{{ $counts['all'] }}</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Manage enterprise software products (HRMS, CRM, Cloud ERP, AI Agent Studio) for UAE businesses.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-md shadow-purple-500/20 transition self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i> Add New Product
        </a>
    </div>

    <!-- Status Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3 text-xs font-medium">
        <a href="{{ route('admin.products.index') }}" 
           class="px-3 py-1.5 rounded-lg transition {{ empty($status) ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:bg-slate-200/60' }}">
            All ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'published']) }}" 
           class="px-3 py-1.5 rounded-lg transition {{ $status === 'published' ? 'bg-emerald-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-200/60' }}">
            Published ({{ $counts['published'] }})
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'draft']) }}" 
           class="px-3 py-1.5 rounded-lg transition {{ $status === 'draft' ? 'bg-amber-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-200/60' }}">
            Drafts ({{ $counts['draft'] }})
        </a>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="w-full flex flex-col sm:flex-row items-center gap-3">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif

            @if($categories->count() > 0)
                <select name="category" class="w-full sm:w-48 text-xs rounded-xl border border-slate-200 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            @endif

            <div class="relative w-full sm:w-80">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search products (e.g. HRMS, CRM)..." 
                       class="w-full pl-8 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5"></i>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    Filter
                </button>
                @if($search || $category)
                    <a href="{{ route('admin.products.index') }}" class="text-xs text-rose-600 hover:underline">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3.5 px-4">Product</th>
                        <th class="py-3.5 px-4">Category & Badge</th>
                        <th class="py-3.5 px-4">Modules</th>
                        <th class="py-3.5 px-4">Yoast SEO Score</th>
                        <th class="py-3.5 px-4">Order</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/80 transition group">
                            <!-- Product Title & Slug -->
                            <td class="py-4 px-4 max-w-sm">
                                <div class="font-bold text-slate-900 group-hover:text-purple-600 transition text-sm flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100 shrink-0">
                                        <i data-lucide="{{ $product->icon ?: 'box' }}" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <a href="{{ route('admin.products.edit', $product) }}">{{ $product->title }}</a>
                                    @if($product->is_featured)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Featured</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-400 flex items-center gap-1 mt-1 pl-9">
                                    <span>/product/{{ $product->slug }}</span>
                                </div>
                                @if($product->summary)
                                    <p class="text-slate-500 text-[11px] mt-1 pl-9 line-clamp-1">{{ $product->summary }}</p>
                                @endif
                            </td>

                            <!-- Category & Badge -->
                            <td class="py-4 px-4">
                                <div class="space-y-1">
                                    @if($product->badge)
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">
                                            {{ $product->badge }}
                                        </span>
                                    @endif
                                    <div class="text-slate-500 text-[11px]">{{ $product->category ?: 'Enterprise Suite' }}</div>
                                </div>
                            </td>

                            <!-- Modules Count -->
                            <td class="py-4 px-4">
                                <span class="text-slate-600 text-xs font-semibold">
                                    {{ count($product->modules ?? []) }} Modules
                                </span>
                            </td>

                            <!-- Yoast SEO Score -->
                            <td class="py-4 px-4">
                                @php
                                    $score = $product->seo_score ?? 0;
                                    $rating = $score >= 80 ? 'good' : ($score >= 50 ? 'ok' : 'poor');
                                @endphp
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $rating === 'good' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($rating === 'ok' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200') }}">
                                    <span class="w-2 h-2 rounded-full {{ $rating === 'good' ? 'bg-emerald-500' : ($rating === 'ok' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                    <span>{{ $score }}/100 SEO</span>
                                </div>
                                @if($product->focus_keyword)
                                    <div class="text-[10px] text-slate-400 mt-1 truncate max-w-[140px]" title="Focus: {{ $product->focus_keyword }}">
                                        Key: {{ $product->focus_keyword }}
                                    </div>
                                @endif
                            </td>

                            <!-- Order -->
                            <td class="py-4 px-4">
                                <span class="font-mono text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded">#{{ $product->order }}</span>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-4">
                                @if($product->status === 'published')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('product.show', $product->slug) }}" target="_blank" 
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-purple-600 hover:bg-purple-50 transition" title="Preview Public Page">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition" title="Edit Product">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to remove this product?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i data-lucide="box" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                                <p class="text-sm font-semibold">No products found.</p>
                                <p class="text-xs text-slate-400 mt-1">Get started by creating your first product (e.g. Cleanroom Diagnostics, Enterprise Lab Recovery).</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
