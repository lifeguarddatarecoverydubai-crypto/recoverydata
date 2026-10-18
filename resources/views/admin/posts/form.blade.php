@php
    $isEdit = isset($post);
    $actionUrl = $isEdit ? route('admin.posts.update', $post) : route('admin.posts.store');
@endphp

<form action="{{ $actionUrl }}" method="POST" id="postForm" class="space-y-8">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Left Column (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Title & Slug Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div>
                    <label for="postTitle" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Post Title</label>
                    <input type="text" id="postTitle" name="title" required value="{{ old('title', $post->title ?? '') }}" placeholder="Enter an engaging post title..."
                           class="w-full px-4 py-3 text-lg font-bold rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>

                <!-- Slug / Permalink Box -->
                <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 p-2.5 rounded-xl border border-slate-200/60" x-data="{ editingSlug: false }">
                    <span class="font-semibold text-slate-600">Permalink:</span>
                    <span class="text-slate-400">{{ url('/post') }}/</span>
                    <span x-show="!editingSlug" id="slugDisplay" class="font-mono text-brand-600 font-bold">{{ old('slug', $post->slug ?? 'your-post-slug') }}</span>
                    <input type="text" id="postSlug" name="slug" value="{{ old('slug', $post->slug ?? '') }}" x-show="editingSlug"
                           class="px-2 py-0.5 rounded border border-slate-300 font-mono text-xs focus:outline-none focus:border-brand-500 w-64">
                    <button type="button" @click="editingSlug = !editingSlug" class="ml-auto text-xs text-brand-600 font-semibold hover:underline">
                        <span x-text="editingSlug ? 'Done' : 'Edit'"></span>
                    </button>
                </div>
            </div>

            <!-- WYSIWYG Editor Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Content</label>
                    <button type="button" onclick="insertMediaToQuill()" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                        <i data-lucide="image-plus" class="w-3.5 h-3.5 text-brand-600"></i>
                        <span>Add Media</span>
                    </button>
                </div>

                <!-- Hidden Input for Form Submission -->
                <textarea name="content" id="postContentInput" class="hidden">{{ old('content', $post->content ?? '') }}</textarea>

                <!-- Quill Container -->
                <div id="quillEditor" class="min-h-[380px] bg-white"></div>
            </div>

            <!-- Excerpt Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-2">
                <label for="postExcerpt" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Excerpt</label>
                <p class="text-xs text-slate-400">Write an optional short summary of your post. Used in search results and archive cards.</p>
                <textarea id="postExcerpt" name="excerpt" rows="3" placeholder="Brief summary of the article..."
                          class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
            </div>

            <!-- Yoast SEO Box Component -->
            @include('admin.partials.yoast-seo-box', [
                'model' => $post ?? null,
                'titleInputId' => 'postTitle',
                'slugInputId' => 'postSlug',
                'contentInputId' => 'postContentInput',
                'urlPrefix' => url('/post'),
                'type' => 'post'
            ])
        </div>

        <!-- Right Sidebar (1 Col) -->
        <div class="space-y-6">
            <!-- Publish Action Box -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3 flex items-center justify-between">
                    <span>Publish Settings</span>
                    @if($isEdit)
                        <a href="{{ route('post.show', $post->slug) }}" target="_blank" class="text-xs font-semibold text-brand-600 hover:underline">Preview ↗</a>
                    @endif
                </h3>

                <!-- Status Selector -->
                <div>
                    <label for="postStatus" class="block text-xs font-semibold text-slate-700 mb-1.5">Status</label>
                    <select id="postStatus" name="status" class="w-full text-xs rounded-xl border border-slate-200 p-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        <option value="draft" {{ old('status', $post->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="scheduled" {{ old('status', $post->status ?? '') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                </div>

                <!-- Published At Date -->
                <div>
                    <label for="publishedAt" class="block text-xs font-semibold text-slate-700 mb-1.5">Publish Date</label>
                    <input type="datetime-local" id="publishedAt" name="published_at" 
                           value="{{ old('published_at', isset($post->published_at) ? $post->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                           class="w-full text-xs rounded-xl border border-slate-200 p-2 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                </div>

                <!-- Buttons -->
                <div class="pt-2 flex flex-col gap-2">
                    <button type="submit" 
                            class="w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-500/20 transition flex items-center justify-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>{{ $isEdit ? 'Update Post' : 'Publish / Save Post' }}</span>
                    </button>
                    @if($isEdit)
                        <button type="button" onclick="if(confirm('Are you sure you want to trash this post?')) { document.getElementById('deletePostForm').submit(); }" 
                                class="w-full py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition">
                            Move to Trash
                        </button>
                    @endif
                </div>
            </div>

            <!-- Categories Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3" x-data="{ newCatOpen: false }">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Categories</h3>
                    <button type="button" @click="newCatOpen = !newCatOpen" class="text-xs text-brand-600 font-semibold hover:underline">+ Add New</button>
                </div>

                <!-- Inline New Category Form -->
                <div x-show="newCatOpen" x-cloak class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <input type="text" id="inlineCatName" placeholder="New category name" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200">
                    <button type="button" onclick="createInlineCategory()" class="w-full py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-semibold transition">Add Category</button>
                </div>

                <!-- Category Checkboxes -->
                <div class="max-h-48 overflow-y-auto space-y-2 pr-1" id="categoriesCheckboxList">
                    @php
                        $selectedCats = old('categories', $selectedCategories ?? []);
                    @endphp
                    @forelse($categories as $category)
                        <label class="flex items-center gap-2 text-xs text-slate-700 hover:text-slate-900 cursor-pointer">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, $selectedCats) ? 'checked' : '' }}
                                   class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 h-3.5 w-3.5">
                            <span>{{ $category->name }}</span>
                        </label>
                    @empty
                        <p class="text-xs text-slate-400">No categories found.</p>
                    @endforelse
                </div>
            </div>

            <!-- Tags Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Tags</h3>
                <p class="text-[11px] text-slate-400">Separate tags with commas.</p>
                <input type="text" id="tagsInput" name="tags_input" value="{{ old('tags_input', $tagsString ?? '') }}" placeholder="technology, web design, ai"
                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                
                @if($tags->count())
                    <div class="pt-1">
                        <span class="text-[10px] font-semibold text-slate-400 block mb-1">Choose from popular tags:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($tags->take(10) as $t)
                                <button type="button" onclick="addTagToInput('{{ $t->name }}')" class="text-[10px] bg-slate-100 hover:bg-brand-50 hover:text-brand-600 px-2 py-0.5 rounded text-slate-600 transition">
                                    + {{ $t->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Featured Image Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Featured Image</h3>
                
                <input type="hidden" id="featuredImageInput" name="featured_image" value="{{ old('featured_image', $post->featured_image ?? '') }}">

                <div id="featuredImageContainer" class="space-y-3">
                    @php
                        $featImg = old('featured_image', $post->featured_image ?? null);
                        $featUrl = null;
                        if ($featImg) {
                            if (isset($post) && $featImg === ($post->featured_image ?? null)) {
                                $featUrl = $post->featured_image_url;
                            } elseif (str_starts_with($featImg, 'http')) {
                                $featUrl = $featImg;
                            } elseif (str_starts_with($featImg, 'images/')) {
                                $featUrl = asset($featImg);
                            } else {
                                $featUrl = asset('storage/' . ltrim($featImg, '/'));
                            }
                        } elseif (isset($post) && $post->id) {
                            $featUrl = $post->featured_image_url;
                        }
                        $fallbackPlaceholder = isset($post) && method_exists($post, 'getTopicPlaceholderUrl')
                            ? $post->getTopicPlaceholderUrl()
                            : asset('images/placeholders/blog-default.svg');
                    @endphp

                    <div id="featuredImagePreview" class="relative aspect-video rounded-xl overflow-hidden bg-slate-100 border border-slate-200 {{ $featUrl ? '' : 'hidden' }}">
                        <img src="{{ $featUrl }}" alt="Featured Image" id="featuredImgTag" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ $fallbackPlaceholder }}';">
                    </div>


                    <div id="noFeaturedImageText" class="p-6 text-center border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 {{ $featUrl ? 'hidden' : '' }}">
                        <i data-lucide="image" class="w-8 h-8 text-slate-300 mx-auto mb-1"></i>
                        <p class="text-xs text-slate-400">No featured image set</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="chooseFeaturedImage()" class="flex-1 py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-xl transition text-center">
                            Select from Media
                        </button>
                        <button type="button" id="removeFeaturedBtn" onclick="removeFeaturedImage()" class="py-2 px-3 text-rose-600 hover:bg-rose-50 text-xs font-semibold rounded-xl transition {{ $featUrl ? '' : 'hidden' }}">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@if($isEdit)
    <form id="deletePostForm" action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endif

@push('scripts')
<script>
    // 1. Initialize Quill Editor
    const quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Write your story here with rich formatting...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['blockquote', 'code-block'],
                ['link', 'clean']
            ]
        }
    });

    // Populate initial content
    const existingContent = document.getElementById('postContentInput').value;
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }

    // Sync content on form submission
    document.getElementById('postForm').addEventListener('submit', function() {
        document.getElementById('postContentInput').value = quill.root.innerHTML;
    });

    // 2. Custom Media insertion into Quill
    function insertMediaToQuill() {
        openMediaModal(function(item) {
            const range = quill.getSelection(true);
            if (item.mime_type && item.mime_type.startsWith('image/')) {
                quill.insertEmbed(range.index, 'image', item.url);
            } else {
                quill.insertText(range.index, item.name, 'link', item.url);
            }
        });
    }

    // 3. Featured Image Selection via Media Modal
    function chooseFeaturedImage() {
        openMediaModal(function(item) {
            document.getElementById('featuredImageInput').value = item.file_path || item.url;
            document.getElementById('featuredImgTag').src = item.url;
            document.getElementById('featuredImagePreview').classList.remove('hidden');
            document.getElementById('noFeaturedImageText').classList.add('hidden');
            document.getElementById('removeFeaturedBtn').classList.remove('hidden');

            // If OG Image is empty, auto-populate it!
            const ogInput = document.getElementById('ogImageInput');
            if (!ogInput.value) {
                ogInput.value = item.url;
            }
        });
    }

    function removeFeaturedImage() {
        document.getElementById('featuredImageInput').value = '';
        document.getElementById('featuredImagePreview').classList.add('hidden');
        document.getElementById('noFeaturedImageText').classList.remove('hidden');
        document.getElementById('removeFeaturedBtn').classList.add('hidden');
    }

    function chooseOgImage() {
        openMediaModal(function(item) {
            document.getElementById('ogImageInput').value = item.url;
        });
    }

    // 4. Automatic Slug Generation & Live SEO Preview
    const titleInput = document.getElementById('postTitle');
    const slugInput = document.getElementById('postSlug');
    const slugDisplay = document.getElementById('slugDisplay');
    const previewTitle = document.getElementById('previewTitle');
    const previewSlug = document.getElementById('previewSlug');
    const metaTitleInput = document.getElementById('metaTitle');
    const metaDescInput = document.getElementById('metaDescription');
    const previewDesc = document.getElementById('previewDescription');
    const metaTitleCount = document.getElementById('metaTitleCount');
    const metaDescCount = document.getElementById('metaDescCount');

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }

    @if(!$isEdit)
    titleInput.addEventListener('input', function() {
        const slug = slugify(this.value);
        if (!slugInput.dataset.manual) {
            slugInput.value = slug;
            slugDisplay.textContent = slug || 'your-post-slug';
            previewSlug.textContent = slug || 'your-post';
        }
        updateSeoPreview();
    });
    @endif

    slugInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
        slugDisplay.textContent = this.value;
        previewSlug.textContent = this.value;
    });

    function updateSeoPreview() {
        const title = metaTitleInput.value.trim() || titleInput.value.trim() || 'Post Title';
        previewTitle.textContent = title + ' - {{ config('app.name') }}';

        const desc = metaDescInput.value.trim() || document.getElementById('postExcerpt').value.trim() || 'Provide a meta description to see how your article appears in Google search engine rankings.';
        previewDesc.textContent = desc;

        // Counters
        const tLen = metaTitleInput.value.length;
        metaTitleCount.textContent = tLen + ' / 60';
        metaTitleCount.className = tLen > 60 ? 'text-[11px] font-mono text-rose-600 font-bold' : (tLen >= 40 ? 'text-[11px] font-mono text-emerald-600 font-bold' : 'text-[11px] font-mono text-slate-400');

        const dLen = metaDescInput.value.length;
        metaDescCount.textContent = dLen + ' / 160';
        metaDescCount.className = dLen > 160 ? 'text-[11px] font-mono text-rose-600 font-bold' : (dLen >= 120 ? 'text-[11px] font-mono text-emerald-600 font-bold' : 'text-[11px] font-mono text-slate-400');
    }

    metaTitleInput.addEventListener('input', updateSeoPreview);
    metaDescInput.addEventListener('input', updateSeoPreview);
    document.getElementById('postExcerpt').addEventListener('input', updateSeoPreview);
    updateSeoPreview();

    // 5. Inline Category Creation
    function createInlineCategory() {
        const catNameInput = document.getElementById('inlineCatName');
        const name = catNameInput.value.trim();
        if (!name) return;

        fetch('{{ route("admin.categories.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: name })
        })
        .then(res => {
            // Reload category checkboxes or insert
            location.reload();
        })
        .catch(err => alert('Error creating category'));
    }

    // 6. Tag Quick Add
    function addTagToInput(tagName) {
        const input = document.getElementById('tagsInput');
        let current = input.value.split(',').map(s => s.trim()).filter(Boolean);
        if (!current.includes(tagName)) {
            current.push(tagName);
            input.value = current.join(', ');
        }
    }
</script>
@endpush
