@extends('layouts.admin')

@section('title', 'Add New Post')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Add New Post</h1>
            <p class="text-xs text-slate-500 mt-1">Create a new blog article with rich content and SEO meta</p>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            ← Back to Posts
        </a>
    </div>

    @include('admin.posts.form')
</div>
@endsection
