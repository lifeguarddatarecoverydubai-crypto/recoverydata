@extends('layouts.admin')

@section('title', 'Navigation Menus')

@section('content')
<div class="space-y-6" x-data="{
    editModalOpen: false,
    editItem: {
        id: null,
        title: '',
        url: '',
        route_name: '',
        type: 'standard',
        target: '_self',
        parent_id: '',
        badge: '',
        order: 0,
        is_active: true
    },
    quickFillUrl(url, type = 'standard') {
        document.getElementById('newItemUrl').value = url;
        document.getElementById('newItemType').value = type;
    },
    openEdit(item) {
        this.editItem = {
            id: item.id,
            title: item.title,
            url: item.url,
            route_name: item.route_name || '',
            type: item.type,
            target: item.target || '_self',
            parent_id: item.parent_id || '',
            badge: item.badge || '',
            order: item.order,
            is_active: Boolean(item.is_active)
        };
        this.editModalOpen = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-2 rounded-xl bg-brand-50 text-brand-600">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </span>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Navigation Menu</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Manage the top navigation bar menu items shown on your website header</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-xs transition">
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-400"></i>
                Preview on Site
            </a>

            <form action="{{ route('admin.menus.reset-defaults') }}" method="POST" onsubmit="return confirm('Reset menu to original 7 default items (Home, About, Odoo Partner, Products, Services, Blogs, Contact us)?');">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-slate-500"></i>
                    Reset to Defaults
                </button>
            </form>
        </div>
    </div>

    <!-- Alert / Notice -->
    <div class="p-4 rounded-2xl bg-gradient-to-r from-brand-900 via-indigo-950 to-slate-900 text-white shadow-sm flex items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-brand-500/20 border border-brand-400/30 flex items-center justify-center shrink-0">
                <i data-lucide="sparkles" class="w-5 h-5 text-brand-300"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-white">Live Website Menu Sync</h4>
                <p class="text-[11px] text-slate-300 mt-0.5">Any additions, edits, reordering, or status toggles here instantly update the public top header navigation bar.</p>
            </div>
        </div>
        <span class="hidden md:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 shrink-0">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> {{ $menuItems->count() }} Root Items
        </span>
    </div>

    <!-- 2-Column WordPress-Style Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left Column: Add New Menu Item Form -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-5">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-brand-600"></i>
                    Add Menu Item
                </h2>
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">New</span>
            </div>

            <!-- Quick Preset Buttons -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1.5">Quick Presets</label>
                <div class="flex flex-wrap gap-1.5 text-xs">
                    <button type="button" @click="quickFillUrl('/', 'standard'); document.getElementById('newItemTitle').value = 'Home';" 
                            class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-[11px] font-medium text-slate-700 transition">
                        Home
                    </button>
                    <button type="button" @click="quickFillUrl('/about', 'standard'); document.getElementById('newItemTitle').value = 'About';" 
                            class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-[11px] font-medium text-slate-700 transition">
                        About
                    </button>
                    <button type="button" @click="quickFillUrl('/odoo', 'odoo_badge'); document.getElementById('newItemTitle').value = 'Odoo Partner';" 
                            class="px-2 py-1 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-700 text-[11px] font-medium transition">
                        Odoo Partner
                    </button>
                    <button type="button" @click="quickFillUrl('/products', 'products_dropdown'); document.getElementById('newItemTitle').value = 'Products';" 
                            class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-[11px] font-medium text-slate-700 transition">
                        Products Dropdown
                    </button>
                    <button type="button" @click="quickFillUrl('/services', 'services_dropdown'); document.getElementById('newItemTitle').value = 'Services';" 
                            class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-[11px] font-medium text-slate-700 transition">
                        Services Dropdown
                    </button>
                    <button type="button" @click="quickFillUrl('/blog', 'standard'); document.getElementById('newItemTitle').value = 'Blogs';" 
                            class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-[11px] font-medium text-slate-700 transition">
                        Blogs
                    </button>
                    <button type="button" @click="quickFillUrl('/contact', 'standard'); document.getElementById('newItemTitle').value = 'Contact us';" 
                            class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-[11px] font-medium text-slate-700 transition">
                        Contact us
                    </button>
                </div>
            </div>

            <!-- Published Pages Dropdown helper -->
            @if($publishedPages->count())
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Link to Published CMS Page</label>
                    <select onchange="if(this.value){ document.getElementById('newItemUrl').value = this.value; document.getElementById('newItemTitle').value = this.options[this.selectedIndex].text; document.getElementById('newItemType').value = 'standard'; }" 
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 bg-white">
                        <option value="">-- Choose a page to auto-fill --</option>
                        @foreach($publishedPages as $pg)
                            <option value="{{ '/page/' . $pg->slug }}">{{ $pg->title }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <form action="{{ route('admin.menus.store') }}" method="POST" class="space-y-4 pt-1">
                @csrf
                <div>
                    <label for="newItemTitle" class="block text-xs font-bold text-slate-700 mb-1">
                        Title / Label <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="newItemTitle" name="title" required placeholder="e.g. Products, About Us, Careers"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    <p class="text-[10px] text-slate-400 mt-1">The text that displays in the header menu bar.</p>
                </div>

                <div>
                    <label for="newItemType" class="block text-xs font-bold text-slate-700 mb-1">Menu Item Type</label>
                    <select id="newItemType" name="type" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 bg-white">
                        <option value="standard">Standard Link</option>
                        <option value="products_dropdown">Products Mega-Menu (Dynamic products list)</option>
                        <option value="services_dropdown">Services Dropdown (Dynamic services list)</option>
                        <option value="odoo_badge">Odoo Partner (Official logo & styling)</option>
                        <option value="dropdown">Custom Dropdown (Shows child menu items)</option>
                    </select>
                </div>

                <div>
                    <label for="newItemUrl" class="block text-xs font-bold text-slate-700 mb-1">Target URL / Path</label>
                    <input type="text" id="newItemUrl" name="url" placeholder="e.g. /about, /page/careers, https://..."
                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                    <p class="text-[10px] text-slate-400 mt-1">Internal path (e.g. <code>/contact</code>) or external URL.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="newItemParent" class="block text-xs font-bold text-slate-700 mb-1">Parent Item</label>
                        <select id="newItemParent" name="parent_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 bg-white">
                            <option value="">None (Top Bar)</option>
                            @foreach($parentCandidates as $pItem)
                                <option value="{{ $pItem->id }}">{{ $pItem->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="newItemTarget" class="block text-xs font-bold text-slate-700 mb-1">Open In</label>
                        <select id="newItemTarget" name="target" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 bg-white">
                            <option value="_self">Same Tab (_self)</option>
                            <option value="_blank">New Tab (_blank)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="newItemBadge" class="block text-xs font-bold text-slate-700 mb-1">Badge (Optional)</label>
                        <input type="text" id="newItemBadge" name="badge" placeholder="e.g. New, Hot"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    </div>

                    <div>
                        <label for="newItemOrder" class="block text-xs font-bold text-slate-700 mb-1">Order</label>
                        <input type="number" id="newItemOrder" name="order" value="{{ ($menuItems->max('order') ?? 0) + 1 }}"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="newItemActive" name="is_active" value="1" checked
                           class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                    <label for="newItemActive" class="text-xs font-bold text-slate-700 cursor-pointer">
                        Active (Visible in navigation)
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-500/20 transition flex items-center justify-center gap-1.5">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add to Navigation
                </button>
            </form>
        </div>

        <!-- Right Column: Menu Items Listing & Ordering Table -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <i data-lucide="list-ordered" class="w-4 h-4 text-brand-600"></i>
                        <h3 class="text-sm font-bold text-slate-800">Current Navigation Structure</h3>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">Reorder items with up/down arrows</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($menuItems as $item)
                        <div class="p-4 hover:bg-slate-50/80 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ !$item->is_active ? 'opacity-50 bg-slate-50/30' : '' }}">
                            
                            <!-- Left: Item info & Type -->
                            <div class="flex items-start sm:items-center gap-3 min-w-0">
                                <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold shrink-0">
                                    {{ $item->order }}
                                </span>

                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-extrabold text-sm text-slate-900 truncate">{{ $item->title }}</span>

                                        @if($item->type === 'products_dropdown')
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 border border-purple-200">
                                                Products Mega-Menu
                                            </span>
                                        @elseif($item->type === 'services_dropdown')
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 border border-indigo-200">
                                                Services Dropdown
                                            </span>
                                        @elseif($item->type === 'odoo_badge')
                                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-purple-600 text-white flex items-center gap-1">
                                                Odoo Partner Logo
                                            </span>
                                        @elseif($item->type === 'dropdown')
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">
                                                Custom Dropdown
                                            </span>
                                        @else
                                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-slate-100 text-slate-600">
                                                Link
                                            </span>
                                        @endif

                                        @if($item->badge)
                                            <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded-full bg-brand-100 text-brand-700">
                                                {{ $item->badge }}
                                            </span>
                                        @endif

                                        @if($item->target === '_blank')
                                            <span class="text-[9px] text-slate-400 font-medium">(New tab)</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2 text-xs text-slate-400 mt-0.5 truncate">
                                        <span class="truncate font-mono text-[11px] text-slate-500">{{ $item->url }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Controls & Actions -->
                            <div class="flex items-center gap-1.5 shrink-0 self-end sm:self-auto">
                                
                                <!-- Active Toggle -->
                                <form action="{{ route('admin.menus.toggle-active', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-2.5 py-1 text-[11px] font-bold rounded-lg transition {{ $item->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}"
                                            title="Click to toggle visibility">
                                        {{ $item->is_active ? 'Active' : 'Hidden' }}
                                    </button>
                                </form>

                                <!-- Move Up -->
                                <form action="{{ route('admin.menus.move-up', $item) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition" title="Move Up">
                                        <i data-lucide="arrow-up" class="w-4 h-4"></i>
                                    </button>
                                </form>

                                <!-- Move Down -->
                                <form action="{{ route('admin.menus.move-down', $item) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition" title="Move Down">
                                        <i data-lucide="arrow-down" class="w-4 h-4"></i>
                                    </button>
                                </form>

                                <!-- Edit Button -->
                                <button type="button" @click="openEdit({{ json_encode($item) }})" 
                                        class="p-1.5 rounded-lg text-brand-600 hover:bg-brand-50 transition" title="Edit Menu Item">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.menus.destroy', $item) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete \'{{ $item->title }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Nested Child items if any -->
                        @if($item->children->count())
                            <div class="pl-10 pr-4 py-2 space-y-1 bg-slate-50/50 border-t border-slate-100">
                                @foreach($item->children as $child)
                                    <div class="p-2.5 rounded-xl bg-white border border-slate-200/60 shadow-2xs flex items-center justify-between text-xs {{ !$child->is_active ? 'opacity-50' : '' }}">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="corner-down-right" class="w-3.5 h-3.5 text-slate-400"></i>
                                            <span class="font-bold text-slate-800">{{ $child->title }}</span>
                                            <span class="text-slate-400 font-mono text-[10px]">{{ $child->url }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <form action="{{ route('admin.menus.toggle-active', $child) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-2 py-0.5 text-[10px] font-bold rounded {{ $child->is_active ? 'text-emerald-700 bg-emerald-50' : 'text-slate-500 bg-slate-100' }}">
                                                    {{ $child->is_active ? 'Active' : 'Hidden' }}
                                                </button>
                                            </form>
                                            <button type="button" @click="openEdit({{ json_encode($child) }})" class="p-1 text-brand-600 hover:bg-brand-50 rounded">
                                                <i data-lucide="pencil" class="w-3 h-3"></i>
                                            </button>
                                            <form action="{{ route('admin.menus.destroy', $child) }}" method="POST" class="inline" onsubmit="return confirm('Delete submenu item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-rose-500 hover:bg-rose-50 rounded">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @empty
                        <div class="p-12 text-center text-slate-400 text-xs">
                            <i data-lucide="menu" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                            <p class="font-semibold text-slate-600">No navigation items found</p>
                            <p class="mt-1">Add items above or click "Reset to Defaults" to restore standard menu.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Help Guide Box -->
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-600 space-y-2">
                <h4 class="font-bold text-slate-900 flex items-center gap-1.5">
                    <i data-lucide="help-circle" class="w-4 h-4 text-brand-600"></i>
                    Menu Customization Guide
                </h4>
                <ul class="list-disc pl-5 space-y-1 text-slate-500 text-[11px] leading-relaxed">
                    <li><strong>Products Mega-Menu:</strong> Dynamically presents your published products with icons and badges.</li>
                    <li><strong>Services Dropdown:</strong> Automatically pulls your active services catalog.</li>
                    <li><strong>Odoo Partner:</strong> Renders with official Odoo branding, logo, and active styling.</li>
                    <li><strong>Order:</strong> Items with lower order numbers appear further to the left.</li>
                    <li><strong>Inactive:</strong> Items marked as hidden will not appear in either the desktop or mobile menus.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Edit Modal Dialog (Alpine) -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="editModalOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="editModalOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="editModalOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                
                <form :action="'{{ url('admin/menus') }}/' + editItem.id" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="bg-white p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <i data-lucide="pencil" class="w-4 h-4 text-brand-600"></i>
                                Edit Menu Item
                            </h3>
                            <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Title / Label</label>
                            <input type="text" name="title" x-model="editItem.title" required
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Item Type</label>
                            <select name="type" x-model="editItem.type" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 bg-white">
                                <option value="standard">Standard Link</option>
                                <option value="products_dropdown">Products Mega-Menu</option>
                                <option value="services_dropdown">Services Dropdown</option>
                                <option value="odoo_badge">Odoo Partner Logo</option>
                                <option value="dropdown">Custom Dropdown</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Target URL / Path</label>
                            <input type="text" name="url" x-model="editItem.url"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Parent Item</label>
                                <select name="parent_id" x-model="editItem.parent_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 bg-white">
                                    <option value="">None (Top Level)</option>
                                    @foreach($parentCandidates as $pItem)
                                        <option value="{{ $pItem->id }}">{{ $pItem->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Target</label>
                                <select name="target" x-model="editItem.target" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 bg-white">
                                    <option value="_self">Same Tab (_self)</option>
                                    <option value="_blank">New Tab (_blank)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Badge</label>
                                <input type="text" name="badge" x-model="editItem.badge" placeholder="e.g. New"
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Order</label>
                                <input type="number" name="order" x-model="editItem.order"
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" id="editItemActive" name="is_active" value="1" :checked="editItem.is_active"
                                   class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                            <label for="editItemActive" class="text-xs font-bold text-slate-700 cursor-pointer">
                                Active in Navigation
                            </label>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 rounded-b-3xl border-t border-slate-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
