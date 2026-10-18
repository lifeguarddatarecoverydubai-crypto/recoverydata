@extends('layouts.admin')

@section('title', 'Media Library')

@section('content')
<div class="space-y-6" x-data="{ 
    selectedItem: null, 
    detailModalOpen: false, 
    uploadOpen: false,
    viewMode: 'grid' 
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span>Media Library</span>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-700">{{ $media->total() }}</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Upload and manage images, illustrations, and documents</p>
        </div>
        <button type="button" @click="uploadOpen = !uploadOpen" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition self-start sm:self-auto">
            <i data-lucide="upload-cloud" class="w-4 h-4"></i>
            <span x-text="uploadOpen ? 'Hide Uploader' : 'Add New Media'"></span>
        </button>
    </div>

    <!-- Drag & Drop Upload Zone -->
    <div x-show="uploadOpen" x-cloak class="bg-white p-6 rounded-2xl border-2 border-dashed border-slate-300 hover:border-brand-500 transition shadow-sm">
        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-center">
            @csrf
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto">
                <i data-lucide="cloud-arrow-up" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800">Select files to upload</p>
                <p class="text-xs text-slate-400 mt-0.5">Maximum file size: 20MB. Images, SVGs, PDFs, and Documents accepted.</p>
            </div>
            <div class="flex items-center justify-center gap-3">
                <input type="file" name="files[]" multiple required class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold transition">Upload Now</button>
            </div>
        </form>
    </div>

    <!-- Toolbar: Filter & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Filters -->
        <div class="flex items-center gap-2 w-full md:w-auto text-xs">
            <a href="{{ route('admin.media.index') }}" 
               class="px-3 py-1.5 rounded-lg transition {{ empty($type) ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">All Media</a>
            <a href="{{ route('admin.media.index', ['type' => 'image']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ $type === 'image' ? 'bg-brand-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">Images</a>
            <a href="{{ route('admin.media.index', ['type' => 'document']) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ $type === 'document' ? 'bg-brand-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' }}">Documents</a>
        </div>

        <!-- Search & View Mode -->
        <div class="flex items-center gap-3 w-full md:w-auto justify-end">
            <form action="{{ route('admin.media.index') }}" method="GET" class="flex items-center gap-2">
                @if($type)
                    <input type="hidden" name="type" value="{{ $type }}">
                @endif
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search files..."
                           class="w-48 sm:w-60 pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5"></i>
                </div>
                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">Search</button>
            </form>

            <div class="flex items-center bg-slate-100 p-0.5 rounded-xl border border-slate-200">
                <button type="button" @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-400 hover:text-slate-700'" class="p-1 rounded-lg">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                </button>
                <button type="button" @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-400 hover:text-slate-700'" class="p-1 rounded-lg">
                    <i data-lucide="list" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Media Grid View -->
    <div x-show="viewMode === 'grid'" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($media as $item)
            <div @click="selectedItem = {{ json_encode($item) }}; detailModalOpen = true"
                 class="group relative aspect-square bg-slate-100 rounded-2xl border border-slate-200 overflow-hidden cursor-pointer hover:shadow-md hover:border-brand-500 transition-all flex flex-col justify-between p-1">
                @if($item->is_image)
                    <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition duration-300" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/default-recovery.svg') }}';">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center p-3 text-slate-400 group-hover:text-brand-600 transition">
                        <i data-lucide="file-text" class="w-8 h-8 mb-2"></i>
                        <span class="text-[10px] text-center font-semibold text-slate-600 line-clamp-2">{{ $item->name }}</span>
                    </div>
                @endif
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/80 to-transparent p-2 opacity-0 group-hover:opacity-100 transition text-[10px] text-white truncate font-medium">
                    {{ $item->name }}
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-slate-400 text-xs">
                <i data-lucide="image-off" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                No media items found. Click "Add New Media" to upload images.
            </div>
        @endforelse
    </div>

    <!-- Media List View -->
    <div x-show="viewMode === 'list'" x-cloak class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <th class="py-3 px-4">Preview</th>
                    <th class="py-3 px-4">File Name</th>
                    <th class="py-3 px-4">Type</th>
                    <th class="py-3 px-4">Size & Dimensions</th>
                    <th class="py-3 px-4">Uploaded</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($media as $item)
                    <tr class="hover:bg-slate-50/80 transition cursor-pointer" @click="selectedItem = {{ json_encode($item) }}; detailModalOpen = true">
                        <td class="py-3 px-4">
                            <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                @if($item->is_image)
                                    <img src="{{ $item->url }}" alt="{{ $item->name }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/default-recovery.svg') }}';">
                                @else
                                    <i data-lucide="file" class="w-5 h-5 text-slate-400"></i>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $item->name }}</td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-500">{{ $item->mime_type }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $item->human_size }} {{ $item->dimensions ? '('.$item->dimensions.')' : '' }}</td>
                        <td class="py-3 px-4 text-slate-500">{{ $item->created_at->format('M d, Y') }}</td>
                        <td class="py-3 px-4 text-right">
                            <button type="button" class="p-1.5 text-slate-400 hover:text-brand-600"><i data-lucide="info" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">No media found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($media->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200">
            {{ $media->links() }}
        </div>
    @endif

    <!-- Media Details Inspector Modal -->
    <div x-show="detailModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden flex flex-col md:flex-row max-h-[90vh]" @click.outside="detailModalOpen = false">
            <!-- Left: Big Preview -->
            <div class="md:w-1/2 bg-slate-900 flex items-center justify-center p-6 min-h-[260px]">
                <template x-if="selectedItem && selectedItem.is_image">
                    <img :src="selectedItem.url" :alt="selectedItem.name" class="max-h-[380px] max-w-full object-contain rounded-lg" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/default-recovery.svg') }}';">
                </template>

                <template x-if="selectedItem && !selectedItem.is_image">
                    <div class="text-center text-slate-400">
                        <i data-lucide="file-text" class="w-16 h-16 mx-auto mb-2 opacity-60"></i>
                        <span x-text="selectedItem.name" class="text-xs text-white font-semibold"></span>
                    </div>
                </template>
            </div>

            <!-- Right: Metadata & Edit Form -->
            <div class="md:w-1/2 p-6 flex flex-col justify-between overflow-y-auto space-y-4">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900 text-sm">Attachment Details</h3>
                        <button type="button" @click="detailModalOpen = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
                    </div>

                    <div class="py-3 text-[11px] text-slate-500 space-y-1 border-b border-slate-100">
                        <div><strong>File Name:</strong> <span class="font-mono text-slate-800" x-text="selectedItem ? selectedItem.file_name : ''"></span></div>
                        <div><strong>File Type:</strong> <span x-text="selectedItem ? selectedItem.mime_type : ''"></span></div>
                        <div><strong>File Size:</strong> <span x-text="selectedItem ? selectedItem.human_size : ''"></span></div>
                        <template x-if="selectedItem && selectedItem.dimensions">
                            <div><strong>Dimensions:</strong> <span x-text="selectedItem.dimensions + ' pixels'"></span></div>
                        </template>
                    </div>

                    <!-- URL Copy Field -->
                    <div class="pt-3">
                        <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">File URL</label>
                        <div class="flex items-center gap-1.5">
                            <input type="text" readonly :value="selectedItem ? selectedItem.url : ''" id="detailUrlInput"
                                   class="flex-1 px-2.5 py-1.5 text-xs font-mono rounded-lg border border-slate-200 bg-slate-50">
                            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('detailUrlInput').value); alert('URL copied to clipboard!');"
                                    class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg">Copy</button>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <form :action="'{{ url('admin/media') }}/' + (selectedItem ? selectedItem.id : '')" method="POST" class="space-y-3 pt-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Title</label>
                            <input type="text" name="name" :value="selectedItem ? selectedItem.name : ''" required
                                   class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Alternative Text (Alt)</label>
                            <input type="text" name="alt_text" :value="selectedItem ? selectedItem.alt_text : ''" placeholder="Describe the image for accessibility & SEO"
                                   class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Caption</label>
                            <textarea name="caption" rows="2" :value="selectedItem ? selectedItem.caption : ''"
                                      class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20"></textarea>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <button type="submit" class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-xs font-bold transition">Update Meta</button>
                        </div>
                    </form>
                </div>

                <!-- Delete permanently form -->
                <div class="pt-3 border-t border-slate-100">
                    <form :action="'{{ url('admin/media') }}/' + (selectedItem ? selectedItem.id : '')" method="POST" onsubmit="return confirm('Permanently delete this file? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-rose-600 hover:underline font-semibold">Delete Permanently</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
