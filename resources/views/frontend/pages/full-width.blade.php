@extends('layouts.app')

@section('seo_meta')
    <title>{{ $page->seo_title }} - {{ config('app.name', 'Recovery Data Dubai') }}</title>
    <meta name="description" content="{{ $page->seo_description }}">
    <link rel="canonical" href="{{ $page->canonical_url ?: url()->current() }}">
    <meta name="robots" content="{{ $page->is_indexable ? 'index, follow' : 'noindex, nofollow' }}">
    <meta property="og:title" content="{{ $page->og_title ?: $page->seo_title }}">
    <meta property="og:description" content="{{ $page->og_description ?: $page->seo_description }}">
@endsection

@section('content')
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <header class="space-y-4 border-b border-slate-100 pb-8">
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">
                {{ $page->title }}
            </h1>
        </header>

        @if($page->featured_image_url)
            <div class="rounded-3xl overflow-hidden aspect-[21/9] shadow-md bg-slate-100">
                <img src="{{ $page->featured_image_url }}" alt="{{ $page->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/default-recovery.svg') }}';">
            </div>
        @endif


        <div class="prose prose-slate prose-xl max-w-none leading-relaxed">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
