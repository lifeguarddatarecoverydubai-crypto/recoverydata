@extends('layouts.admin')

@section('title', 'Edit Product: ' . $product->title)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.products.index') }}" class="hover:text-purple-600 transition">Products</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold truncate max-w-xs">{{ $product->title }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Product: {{ $product->title }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="px-3.5 py-2 text-xs font-semibold text-purple-600 bg-purple-50 hover:bg-purple-100 rounded-xl transition flex items-center gap-1.5">
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                <span>View on Site</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 rounded-xl transition">
                &larr; Back to Products
            </a>
        </div>
    </div>

    @include('admin.products.form')
</div>
@endsection
