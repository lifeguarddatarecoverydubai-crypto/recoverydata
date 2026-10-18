@extends('layouts.admin')

@section('title', 'Add New User')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Add New User</h1>
            <p class="text-xs text-slate-500 mt-1">Create a user account and grant publishing or administrative rights</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            ← Back to Users
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Full Name</label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="e.g. Jane Smith"
                       class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="jane@example.com"
                       class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                       class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div>
                <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Role & Permissions</label>
                <select id="role" name="role" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role', 'editor') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Editors can post and manage categories, pages, tags, posts, and media just like WordPress.</p>
            </div>

            <div>
                <label for="bio" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Biographical Info</label>
                <textarea id="bio" name="bio" rows="3" placeholder="Author biography..."
                          class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">{{ old('bio') }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 h-4 w-4">
                    <span class="text-xs font-semibold text-slate-700">Account is Active (Can sign in to CMS)</span>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-500/20 transition">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
