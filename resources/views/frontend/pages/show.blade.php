@extends('layouts.app')

@section('seo_meta')
    <!-- Primary Meta Tags -->
    <title>{{ $page->seo_title }} - {{ config('app.name', 'Recovery Data Dubai') }}</title>
    <meta name="title" content="{{ $page->seo_title }} - {{ config('app.name', 'Recovery Data Dubai') }}">
    <meta name="description" content="{{ $page->seo_description }}">
    <link rel="canonical" href="{{ $page->canonical_url ?: url()->current() }}">
    <meta name="robots" content="{{ $page->is_indexable ? 'index, follow' : 'noindex, nofollow' }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $page->og_title ?: $page->seo_title }}">
    <meta property="og:description" content="{{ $page->og_description ?: $page->seo_description }}">
    @if($page->seo_image)
        <meta property="og:image" content="{{ $page->seo_image }}">
    @endif
    <meta property="og:site_name" content="{{ config('app.name', 'Recovery Data Dubai') }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page->og_title ?: $page->seo_title }}">
    <meta name="twitter:description" content="{{ $page->og_description ?: $page->seo_description }}">
    @if($page->seo_image)
        <meta name="twitter:image" content="{{ $page->seo_image }}">
    @endif

    @php
        $hasCustomSchema = !empty($page->schema_markup);
        $hasCustomWebPage = $hasCustomSchema && (str_contains($page->schema_markup, 'WebPage') || str_contains($page->schema_markup, 'AboutPage') || str_contains($page->schema_markup, 'ContactPage') || str_contains($page->schema_markup, 'CollectionPage'));
    @endphp

    @if(!$hasCustomWebPage)
    <!-- Schema.org WebPage JSON-LD -->
    <script type="application/ld+json">
    {!! json_encode([
      '@@context' => 'https://schema.org',
      '@type' => 'WebPage',
      '@id' => $page->canonical_url ?: url()->current(),
      'name' => $page->seo_title,
      'description' => $page->seo_description,
      'publisher' => [
        '@type' => 'LocalBusiness',
        'name' => 'Recovery Data Dubai',
        'url' => 'https://www.recoverydata.ae',
        'telephone' => '+971564101040',
        'logo' => [
          '@type' => 'ImageObject',
          'url' => asset('images/recoverydata-logo.png'),
        ],
      ],
      'breadcrumb' => [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
          [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => route('home'),
          ],
          [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => $page->title,
            'item' => $page->canonical_url ?: url()->current(),
          ],
        ],
      ],
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    @if($hasCustomSchema)
    <!-- Custom / Pre-Generated Schema.org JSON-LD -->
    @if(str_contains($page->schema_markup, '<script'))
    {!! $page->schema_markup !!}
    @else
    <script type="application/ld+json">
    {!! $page->schema_markup !!}
    </script>
    @endif
    @endif
@endsection

@section('content')
<div class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Page Header -->
        <header class="space-y-4 border-b border-slate-100 pb-8">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                {{ $page->title }}
            </h1>
            @if($page->updated_at)
                <div class="text-xs text-slate-400">
                    Last updated on {{ $page->updated_at->format('F d, Y') }}
                </div>
            @endif
        </header>

        <!-- Featured Image -->
        @if($page->featured_image_url)
            <div class="rounded-3xl overflow-hidden aspect-[21/9] shadow-md bg-slate-100">
                <img src="{{ $page->featured_image_url }}" alt="{{ $page->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/default-recovery.svg') }}';">
            </div>
        @endif


        <!-- Page WYSIWYG Content Body -->
        <div class="prose prose-slate prose-lg max-w-none leading-relaxed">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
