@extends('layouts.admin')

@section('title', 'Inquiry Details: ' . $inquiry->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.inquiries.index') }}" class="hover:text-brand-600 transition">&larr; Back to Inquiries</a>
            <span>&rsaquo;</span>
            <span class="text-slate-800 font-semibold">{{ $inquiry->subject }}</span>
        </div>

        <div class="flex items-center gap-2">
            <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Permanently delete this inquiry?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Main Inquiry Detail Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-100 gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600">
                        {{ strtoupper($inquiry->type) }}
                    </span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-xs text-slate-400">{{ $inquiry->created_at->format('M d, Y - h:i A') }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $inquiry->subject }}</h1>
            </div>

            <div class="flex items-center gap-2">
                <a href="mailto:{{ $inquiry->email }}?subject=Re: {{ urlencode($inquiry->subject) }}" 
                   class="px-4 py-2 text-xs font-bold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-sm shadow-brand-500/20 transition flex items-center gap-1.5">
                    <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                    <span>Reply via Email</span>
                </a>
                @if($inquiry->phone)
                    <a href="tel:{{ $inquiry->phone }}" 
                       class="px-4 py-2 text-xs font-bold rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm shadow-emerald-500/20 transition flex items-center gap-1.5">
                        <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                        <span>Call</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Sender Profile Info -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 p-5 bg-slate-50/80 rounded-2xl border border-slate-100 text-xs">
            <div>
                <span class="block text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Contact Name</span>
                <span class="font-bold text-slate-900 text-sm">{{ $inquiry->name }}</span>
            </div>
            <div>
                <span class="block text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Email Address</span>
                <a href="mailto:{{ $inquiry->email }}" class="font-bold text-brand-600 hover:underline">{{ $inquiry->email }}</a>
            </div>
            <div>
                <span class="block text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Company / Organization</span>
                <span class="font-bold text-slate-900">{{ $inquiry->company ?: 'Not provided' }}</span>
            </div>
        </div>

        <!-- Message Body -->
        <div class="space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Message Content</h4>
            <div class="p-6 bg-white rounded-2xl border border-slate-200 text-slate-700 text-sm leading-relaxed whitespace-pre-line">
                {{ $inquiry->message ?: 'No additional message text provided.' }}
            </div>
        </div>
    </div>
</div>
@endsection
