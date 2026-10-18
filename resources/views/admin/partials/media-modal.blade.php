<!-- Media Picker Modal (WordPress-style) -->
<div id="cmsMediaModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/80 backdrop-blur-sm hidden" style="display: none;">
    <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-6">
                <h3 class="font-bold text-slate-800 text-base">Select Media</h3>
                <!-- Tabs: Library vs Upload -->
                <div class="flex items-center bg-slate-200/80 p-0.5 rounded-lg text-xs font-semibold">
                    <button type="button" id="tabLibraryBtn" onclick="switchMediaTab('library')" class="px-3 py-1.5 rounded-md bg-white text-slate-900 shadow-sm transition">Media Library</button>
                    <button type="button" id="tabUploadBtn" onclick="switchMediaTab('upload')" class="px-3 py-1.5 rounded-md text-slate-600 hover:text-slate-900 transition">Upload Files</button>
                </div>
            </div>
            <button type="button" onclick="closeMediaModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Tab 1: Library -->
        <div id="mediaTabLibrary" class="flex-1 flex flex-col overflow-hidden">
            <!-- Search bar -->
            <div class="p-4 border-b border-slate-100 flex items-center justify-between gap-4">
                <div class="relative flex-1 max-w-xs">
                    <input type="text" id="modalMediaSearch" onkeyup="filterMediaModal(this.value)" placeholder="Search media by name..."
                           class="w-full pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5"></i>
                </div>
                <span id="modalMediaCount" class="text-xs text-slate-500">Loading...</span>
            </div>

            <!-- Grid container -->
            <div class="flex-1 overflow-y-auto p-4">
                <div id="modalMediaGrid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                    <!-- Populated via JS -->
                </div>
                <div id="modalMediaEmpty" class="hidden py-12 text-center text-slate-400 text-xs">
                    No media items found. Upload some files first!
                </div>
            </div>
        </div>

        <!-- Tab 2: Upload Files -->
        <div id="mediaTabUpload" class="flex-1 overflow-y-auto p-8 hidden" style="display: none;">
            <div class="border-2 border-dashed border-slate-300 hover:border-brand-500 rounded-2xl p-10 text-center bg-slate-50 transition cursor-pointer" onclick="document.getElementById('modalFileInput').click()">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                </div>
                <p class="text-sm font-semibold text-slate-700">Drop files to upload or <span class="text-brand-600 underline">browse</span></p>
                <p class="text-xs text-slate-400 mt-1">Maximum file size: 20MB. Supports PNG, JPG, GIF, WebP, PDF, DOC.</p>
                <input type="file" id="modalFileInput" multiple class="hidden" onchange="handleModalFileUpload(this.files)">
            </div>
            <div id="modalUploadProgress" class="mt-4 hidden">
                <div class="text-xs text-slate-600 font-semibold mb-1">Uploading...</div>
                <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                    <div class="bg-brand-600 h-full w-0 transition-all duration-300" id="modalProgressBar"></div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-3 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="text-xs text-slate-500 truncate max-w-sm" id="modalSelectedInfo">
                No item selected
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeMediaModal()" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 transition">Cancel</button>
                <button type="button" id="modalSelectBtn" onclick="confirmMediaSelection()" disabled class="px-4 py-2 text-xs font-semibold text-white bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed rounded-lg hover:bg-brand-700 transition">Insert Selected</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentMediaCallback = null;
    let selectedMediaItem = null;
    let loadedMediaItems = [];

    function openMediaModal(callback) {
        currentMediaCallback = callback;
        selectedMediaItem = null;
        document.getElementById('modalSelectBtn').disabled = true;
        document.getElementById('modalSelectedInfo').textContent = 'No item selected';
        document.getElementById('cmsMediaModal').style.display = 'flex';
        switchMediaTab('library');
        fetchModalMedia();
    }

    function closeMediaModal() {
        document.getElementById('cmsMediaModal').style.display = 'none';
        currentMediaCallback = null;
        selectedMediaItem = null;
    }

    function switchMediaTab(tab) {
        const libTab = document.getElementById('mediaTabLibrary');
        const upTab = document.getElementById('mediaTabUpload');
        const libBtn = document.getElementById('tabLibraryBtn');
        const upBtn = document.getElementById('tabUploadBtn');

        if (tab === 'library') {
            libTab.style.display = 'flex';
            upTab.style.display = 'none';
            libBtn.className = 'px-3 py-1.5 rounded-md bg-white text-slate-900 shadow-sm transition';
            upBtn.className = 'px-3 py-1.5 rounded-md text-slate-600 hover:text-slate-900 transition';
        } else {
            libTab.style.display = 'none';
            upTab.style.display = 'block';
            upBtn.className = 'px-3 py-1.5 rounded-md bg-white text-slate-900 shadow-sm transition';
            libBtn.className = 'px-3 py-1.5 rounded-md text-slate-600 hover:text-slate-900 transition';
        }
    }

    function fetchModalMedia(search = '') {
        const url = '{{ route("admin.media.modal-list") }}' + (search ? '?search=' + encodeURIComponent(search) : '');
        fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                loadedMediaItems = data;
                renderModalMedia(data);
            })
            .catch(err => console.error(err));
    }

    function renderModalMedia(items) {
        const grid = document.getElementById('modalMediaGrid');
        const empty = document.getElementById('modalMediaEmpty');
        const count = document.getElementById('modalMediaCount');
        
        count.textContent = items.length + ' items';
        grid.innerHTML = '';

        if (items.length === 0) {
            empty.classList.remove('hidden');
            return;
        }
        empty.classList.add('hidden');

        items.forEach(item => {
            const card = document.createElement('div');
            card.className = 'relative aspect-square rounded-lg border border-slate-200 overflow-hidden cursor-pointer group bg-slate-100 hover:border-brand-500 transition-all';
            card.onclick = () => selectItemInModal(item, card);

            if (item.mime_type && item.mime_type.startsWith('image/')) {
                card.innerHTML = `
                    <img src="${item.url}" alt="${item.alt_text || item.name}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/default-recovery.svg') }}';">
                    <div class="absolute inset-0 bg-brand-600/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    </div>
                `;

            } else {
                card.innerHTML = `
                    <div class="w-full h-full flex flex-col items-center justify-center p-2 text-slate-500">
                        <i data-lucide="file" class="w-6 h-6 mb-1"></i>
                        <span class="text-[9px] text-center line-clamp-2">${item.name}</span>
                    </div>
                `;
            }
            grid.appendChild(card);
        });

        lucide.createIcons();
    }

    function selectItemInModal(item, el) {
        selectedMediaItem = item;
        document.querySelectorAll('#modalMediaGrid > div').forEach(c => {
            c.classList.remove('ring-4', 'ring-brand-500');
        });
        el.classList.add('ring-4', 'ring-brand-500');
        document.getElementById('modalSelectBtn').disabled = false;
        document.getElementById('modalSelectedInfo').textContent = 'Selected: ' + item.name + ' (' + (item.dimensions || item.human_size) + ')';
    }

    function confirmMediaSelection() {
        if (selectedMediaItem && currentMediaCallback) {
            currentMediaCallback(selectedMediaItem);
            closeMediaModal();
        }
    }

    function filterMediaModal(term) {
        fetchModalMedia(term);
    }

    function handleModalFileUpload(files) {
        if (!files || files.length === 0) return;
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        const progress = document.getElementById('modalUploadProgress');
        const bar = document.getElementById('modalProgressBar');
        progress.classList.remove('hidden');
        bar.style.width = '30%';

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route("admin.media.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            bar.style.width = '100%';
            setTimeout(() => {
                progress.classList.add('hidden');
                bar.style.width = '0%';
                switchMediaTab('library');
                fetchModalMedia();
                if (data.media && data.media.length > 0) {
                    selectedMediaItem = data.media[0];
                    document.getElementById('modalSelectBtn').disabled = false;
                }
            }, 600);
        })
        .catch(err => {
            alert('Upload failed: ' + err.message);
            progress.classList.add('hidden');
        });
    }
</script>
