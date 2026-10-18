@extends('layouts.admin')

@section('title', 'Add New Product')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.products.index') }}" class="hover:text-purple-600 transition">Products</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">New Product</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Create Enterprise Product</h1>
        </div>
        <a href="{{ route('admin.products.index') }}" class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 rounded-xl transition">
            &larr; Back to Products
        </a>
    </div>

    @include('admin.products.form')
</div>
@endsection
