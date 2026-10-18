@extends('layouts.admin')

@section('title', 'Add New Page')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Add New Page</h1>
            <p class="text-xs text-slate-500 mt-1">Create a custom CMS page with SEO metadata</p>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            ← Back to Pages
        </a>
    </div>

    @include('admin.pages.form')
</div>
@endsection
