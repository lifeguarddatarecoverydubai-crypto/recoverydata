@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span>Users</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-700">{{ $users->total() }}</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Manage user accounts, roles, authors, and publishing privileges</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition self-start sm:self-auto">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Add New User
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2 w-full md:w-auto text-xs">
            <a href="{{ route('admin.users.index') }}" 
               class="px-3 py-1.5 rounded-lg transition {{ empty($role) ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">All</a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ $role === 'admin' ? 'bg-brand-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">Administrators</a>
            <a href="{{ route('admin.users.index', ['role' => 'editor']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ $role === 'editor' ? 'bg-brand-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">Editors</a>
            <a href="{{ route('admin.users.index', ['role' => 'author']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ $role === 'author' ? 'bg-brand-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">Authors</a>
        </div>

        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
            <div class="relative w-full sm:w-60">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email..."
                       class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5"></i>
            </div>
            <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">Search</button>
            @if($search)
                <a href="{{ route('admin.users.index') }}" class="text-xs text-rose-600 hover:underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <th class="py-3 px-4">User</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-center">Posts Written</th>
                    <th class="py-3 px-4">Joined Date</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-200">
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">{{ $user->name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @if($user->role === 'admin')
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-purple-100 text-purple-700 border border-purple-200">Admin</span>
                            @elseif($user->role === 'editor')
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-sky-100 text-brand-700 border border-sky-200">Editor</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">Author</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium text-rose-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Disabled
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold">
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px]">{{ $user->posts_count }}</span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 text-slate-400 hover:text-brand-600 hover:bg-slate-100 rounded-lg transition" title="Edit User">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete user {{ $user->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete User">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
