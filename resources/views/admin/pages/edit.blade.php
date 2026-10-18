@extends('layouts.admin')

@section('title', 'Edit Page')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Page</h1>
            <p class="text-xs text-slate-500 mt-1">Editing "{{ $page->title }}"</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition">
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                <span>View Live</span>
            </a>
            <a href="{{ route('admin.pages.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
                ← Back to Pages
            </a>
        </div>
    </div>

    @include('admin.pages.form')
</div>
@endsection
