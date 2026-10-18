@extends('layouts.admin')

@section('title', 'Leads & Client Inquiries')

@section('content')
<div class="space-y-6">
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Leads & Inquiries</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Client Inquiries & Demo Leads</h1>
                @if($counts['unread'] > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-200">
                        {{ $counts['unread'] }} Unread
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-1">Direct inquiries captured from the website contact form, Odoo ERP demo modals, and consultation requests.</p>
        </div>
    </div>

    <!-- Stats & Filters Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.inquiries.index') }}" 
           class="p-4 rounded-2xl border transition {{ !$type && !$status ? 'bg-white border-brand-500 shadow-sm ring-2 ring-brand-500/20' : 'bg-white border-slate-200/80 hover:border-slate-300' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">All Inquiries</div>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $counts['all'] }}</div>
        </a>

        <a href="{{ route('admin.inquiries.index', ['status' => 'unread']) }}" 
           class="p-4 rounded-2xl border transition {{ $status === 'unread' ? 'bg-rose-50/50 border-rose-500 shadow-sm ring-2 ring-rose-500/20' : 'bg-white border-slate-200/80 hover:border-slate-300' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-rose-500 flex items-center justify-between">
                <span>Unread Leads</span>
                @if($counts['unread'] > 0)
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                @endif
            </div>
            <div class="text-2xl font-black text-rose-600 mt-1">{{ $counts['unread'] }}</div>
        </a>

        <a href="{{ route('admin.inquiries.index', ['type' => 'odoo_demo']) }}" 
           class="p-4 rounded-2xl border transition {{ $type === 'odoo_demo' ? 'bg-purple-50/50 border-purple-500 shadow-sm ring-2 ring-purple-500/20' : 'bg-white border-slate-200/80 hover:border-slate-300' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Odoo ERP Demos</div>
            <div class="text-2xl font-black text-purple-700 mt-1">{{ $counts['odoo_demo'] }}</div>
        </a>

        <a href="{{ route('admin.inquiries.index', ['type' => 'demo']) }}" 
           class="p-4 rounded-2xl border transition {{ $type === 'demo' ? 'bg-indigo-50/50 border-indigo-500 shadow-sm ring-2 ring-indigo-500/20' : 'bg-white border-slate-200/80 hover:border-slate-300' }}">
            <div class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">Product Demos</div>
            <div class="text-2xl font-black text-indigo-700 mt-1">{{ $counts['demo'] }}</div>
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between gap-4">
        <form action="{{ route('admin.inquiries.index') }}" method="GET" class="relative flex-1 max-w-md">
            @if($type)
                <input type="hidden" name="type" value="{{ $type }}">
            @endif
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by lead name, email, company, subject..."
                   class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5 pointer-events-none"></i>
        </form>

        @if($search || $type || $status)
            <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-semibold text-rose-600 hover:underline">
                Clear Filters
            </a>
        @endif
    </div>

    <!-- Inquiries Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Contact & Company</th>
                        <th class="py-3.5 px-4">Type</th>
                        <th class="py-3.5 px-4">Subject & Message</th>
                        <th class="py-3.5 px-4">Date</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($inquiries as $inq)
                        <tr class="hover:bg-slate-50/80 transition {{ !$inq->is_read ? 'bg-brand-50/30' : '' }}">
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if(!$inq->is_read)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                                        NEW
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                        Read
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900 text-xs">{{ $inq->name }}</div>
                                <div class="text-[11px] text-slate-500">
                                    <a href="mailto:{{ $inq->email }}" class="text-brand-600 hover:underline">{{ $inq->email }}</a>
                                    @if($inq->phone)
                                        &bull; <a href="tel:{{ $inq->phone }}" class="hover:text-slate-700">{{ $inq->phone }}</a>
                                    @endif
                                </div>
                                @if($inq->company)
                                    <div class="text-[10px] text-slate-400 mt-0.5">🏢 {{ $inq->company }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($inq->type === 'odoo_demo')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                        Odoo ERP Demo
                                    </span>
                                @elseif($inq->type === 'demo')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        Product Demo
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        General Contact
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 max-w-sm">
                                <div class="font-bold text-slate-800 text-xs truncate">{{ $inq->subject }}</div>
                                <div class="text-slate-500 text-[11px] line-clamp-1 mt-0.5">{{ $inq->message ?: 'No message body provided.' }}</div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-slate-400 text-[11px]">
                                {{ $inq->created_at->diffForHumans() }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.inquiries.show', $inq) }}" class="p-1.5 rounded-lg text-slate-600 hover:text-brand-600 hover:bg-slate-100 transition" title="View Details">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.inquiries.toggle-read', $inq) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-600 hover:text-purple-600 hover:bg-slate-100 transition" title="{{ $inq->is_read ? 'Mark as Unread' : 'Mark as Read' }}">
                                            <i data-lucide="{{ $inq->is_read ? 'mail' : 'mail-check' }}" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.inquiries.destroy', $inq) }}" method="POST" class="inline" onsubmit="return confirm('Delete this inquiry?');">
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
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto space-y-2">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 mx-auto"></i>
                                    <p class="font-bold text-slate-700 text-sm">No client inquiries found</p>
                                    <p class="text-xs">When users submit contact or demo forms on the website, they will appear here instantly.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inquiries->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
