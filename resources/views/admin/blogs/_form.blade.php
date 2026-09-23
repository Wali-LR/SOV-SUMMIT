@csrf
@php
    $inputClass = 'mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 text-sm';
    $labelClass = 'block text-sm font-medium text-slate-700';
    $errorClass = 'mt-1 text-xs text-red-600';
@endphp

@push('head')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame { border-color: rgb(203 213 225); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); }
    .note-editor.note-frame .note-toolbar { border-bottom: 1px solid rgb(226 232 240); background: rgb(248 250 252); border-radius: 0.375rem 0.375rem 0 0; padding: 6px 8px; }
    .note-editor.note-frame:focus-within { border-color: rgb(15 23 42); box-shadow: 0 0 0 1px rgb(15 23 42); }
    .note-btn-group .note-btn { border-color: transparent; background: transparent; color: rgb(51 65 85); }
    .note-btn-group .note-btn:hover { background: rgb(226 232 240); }
    .note-editable { min-height: 320px; padding: 14px 18px; font-size: 14px; line-height: 1.65; color: rgb(15 23 42); }
    .note-editable h3 { font-size: 18px; font-weight: 600; margin: 16px 0 8px; color: rgb(15 23 42); }
    .note-editable h4 { font-size: 16px; font-weight: 600; margin: 14px 0 6px; color: rgb(15 23 42); }
    .note-editable p { margin: 0 0 12px; }
    .note-editable ul, .note-editable ol { margin: 0 0 12px 20px; }
    .note-editable li { margin: 4px 0; }
    .note-editable blockquote { border-left: 3px solid rgb(203 213 225); padding-left: 12px; margin: 12px 0; color: rgb(71 85 105); }
    .note-placeholder { color: rgb(148 163 184); }
    .sn-upload-placeholder {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 12px; margin: 4px 0;
        background: rgb(241 245 249); border: 1px dashed rgb(148 163 184);
        border-radius: 6px; font-size: 12px; color: rgb(71 85 105);
        user-select: none;
    }
    .sn-upload-placeholder::before {
        content: ""; width: 12px; height: 12px; border-radius: 999px;
        border: 2px solid rgb(148 163 184); border-top-color: transparent;
        animation: snspin 0.7s linear infinite;
    }
    @keyframes snspin { to { transform: rotate(360deg); } }
    .sn-toast {
        position: fixed; z-index: 60; right: 20px; bottom: 20px;
        padding: 10px 14px; border-radius: 6px; font-size: 13px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        max-width: 320px;
    }
    .sn-toast--ok { background: rgb(22 101 52); color: white; }
    .sn-toast--err { background: rgb(153 27 27); color: white; }
</style>
@endpush

<div class="grid grid-cols-12 gap-6">

    {{-- MAIN COLUMN --}}
    <div class="col-span-12 lg:col-span-8 space-y-6">

        {{-- Section: Post details --}}
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">Post details</h3>
                <p class="text-xs text-slate-500 mt-0.5">Core information about the blog post.</p>
            </header>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="title" class="{{ $labelClass }}">Title</label>
                    <input id="title" name="title" type="text" required autofocus
                           value="{{ old('title', $blog->title) }}"
                           class="{{ $inputClass }}">
                    @error('title') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="slug" class="{{ $labelClass }}">Slug <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input id="slug" name="slug" type="text"
                           value="{{ old('slug', $blog->slug) }}"
                           placeholder="auto-generated from title"
                           class="{{ $inputClass }} font-mono">
                    @error('slug') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="category" class="{{ $labelClass }}">Category</label>
                    <input id="category" name="category" type="text"
                           value="{{ old('category', $blog->category) }}"
                           placeholder="e.g. Coordination, Events, Travel"
                           class="{{ $inputClass }}">
                    @error('category') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="author" class="{{ $labelClass }}">Author</label>
                    <input id="author" name="author" type="text"
                           value="{{ old('author', $blog->author) }}"
                           placeholder="e.g. The SOV SUMMIT Desk"
                           class="{{ $inputClass }}">
                    @error('author') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="reading_time" class="{{ $labelClass }}">Reading time <span class="text-slate-400 font-normal">(minutes)</span></label>
                    <input id="reading_time" name="reading_time" type="number" min="1" max="180"
                           value="{{ old('reading_time', $blog->reading_time) }}"
                           placeholder="e.g. 6"
                           class="{{ $inputClass }}">
                    @error('reading_time') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        {{-- Section: Body --}}
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                        Body
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">The main article body. Extra sections can be added on the public page after saving.</p>
                </div>
                <button type="button" id="generate-description-btn"
                    class="inline-flex items-center gap-2 px-3 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md disabled:opacity-60 disabled:cursor-wait shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707A1 1 0 004.343 5.757l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/></svg>
                    <span id="generate-description-label">Generate description</span>
                </button>
            </header>
            <div class="p-5 space-y-3">
                <div id="generate-description-error" class="hidden rounded-md bg-red-50 border border-red-200 text-red-800 px-3 py-2 text-sm"></div>
                <textarea id="description" name="description" rows="12">{{ old('description', $blog->description) }}</textarea>
                @error('description') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>
        </section>

        {{-- Section: SEO --}}
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                        SEO
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Meta title, description, and keywords for search engines.</p>
                </div>
                <button type="button" id="generate-seo-btn"
                    class="inline-flex items-center gap-2 px-3 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md disabled:opacity-60 disabled:cursor-wait shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707A1 1 0 004.343 5.757l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/></svg>
                    <span id="generate-seo-label">Generate SEO info</span>
                </button>
            </header>
            <div class="p-5 space-y-5">
                <div id="generate-seo-error" class="hidden rounded-md bg-red-50 border border-red-200 text-red-800 px-3 py-2 text-sm"></div>
                <div>
                    <label for="seo_title" class="{{ $labelClass }}">SEO title <span class="text-slate-400 font-normal">(50-60 chars ideal)</span></label>
                    <input id="seo_title" name="seo_title" type="text" maxlength="160"
                           value="{{ old('seo_title', $blog->seo_title) }}"
                           placeholder="Leave blank to use the post title"
                           class="{{ $inputClass }}">
                    <p class="mt-1 text-xs text-slate-500"><span id="seo-title-count">0</span> characters</p>
                    @error('seo_title') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="summary" class="{{ $labelClass }}">Meta description <span class="text-slate-400 font-normal">(also shown as the summary on public pages)</span></label>
                    <textarea id="summary" name="summary" rows="3" maxlength="500"
                              class="{{ $inputClass }}">{{ old('summary', $blog->summary) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500"><span id="summary-count">0</span> characters — aim for 120-160 for search snippets</p>
                    @error('summary') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="seo_keywords" class="{{ $labelClass }}">Keywords <span class="text-slate-400 font-normal">(comma-separated)</span></label>
                    <input id="seo_keywords" name="seo_keywords" type="text" maxlength="500"
                           value="{{ old('seo_keywords', $blog->seo_keywords) }}"
                           placeholder="e.g. event coordination, delegations, protocol"
                           class="{{ $inputClass }}">
                    @error('seo_keywords') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

    </div>

    {{-- SIDEBAR --}}
    <aside class="col-span-12 lg:col-span-4 space-y-5">
        <div class="lg:sticky lg:top-24 space-y-5">

            {{-- Publish card --}}
            <div class="bg-white border border-slate-200 rounded-lg">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900">Publish</h3>
                </header>
                <div class="p-5 space-y-4">
                    <div>
                        <label for="published_at" class="{{ $labelClass }}">Publish date</label>
                        <input id="published_at" name="published_at" type="datetime-local"
                               value="{{ old('published_at', $blog->published_at?->format('Y-m-d\TH:i')) }}"
                               class="{{ $inputClass }}">
                        @error('published_at') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1"
                                   class="mt-0.5 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                                   {{ old('is_published', $blog->is_published ?? true) ? 'checked' : '' }}>
                            <span class="text-sm">
                                <span class="block font-medium text-slate-900">Published</span>
                                <span class="block text-xs text-slate-500 mt-0.5">Visible on the public blog when checked.</span>
                            </span>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center gap-3">
                        <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
                            {{ $blog->exists ? 'Update post' : 'Create post' }}
                        </button>
                        <a href="{{ route('admin.blogs.index') }}" class="text-sm text-slate-500 hover:text-slate-900">Cancel</a>
                    </div>
                </div>
            </div>

            {{-- Cover image card --}}
            <div class="bg-white border border-slate-200 rounded-lg">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900">Cover image</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Displayed as the article hero and on cards.</p>
                </header>
                <div class="p-5 space-y-3">
                    @if ($blog->cover_url)
                        <div class="relative rounded-md overflow-hidden border border-slate-200 aspect-[16/10] bg-slate-100">
                            <img src="{{ $blog->cover_url }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <p class="text-xs text-slate-500">Upload a new file below to replace.</p>
                    @else
                        <div class="rounded-md border border-dashed border-slate-300 aspect-[16/10] bg-slate-50 flex flex-col items-center justify-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                            <span class="text-xs">No image uploaded</span>
                        </div>
                    @endif
                    <input id="cover_image" name="cover_image" type="file" accept="image/jpeg,image/png,image/webp" data-auto-compress
                           class="block w-full text-xs text-slate-700 file:mr-3 file:px-3 file:py-1.5 file:rounded-md file:border-0 file:bg-slate-900 file:text-white file:cursor-pointer file:text-xs file:font-semibold hover:file:bg-black">
                    <p class="text-[11px] text-slate-500 leading-relaxed">JPG, PNG, WEBP — max 5 MB. Stored on DigitalOcean Spaces under <code class="text-slate-700 font-mono">sob-summit/blogs/</code>.</p>
                    @error('cover_image') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- SEO health --}}
            <div class="bg-white border border-slate-200 rounded-lg">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900">SEO health</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Live status of key SEO signals.</p>
                </header>
                <ul class="p-5 space-y-2.5 text-sm" id="seo-health-list">
                    <li class="flex items-center gap-2.5" data-check="title">
                        <span class="seo-dot"></span>
                        <span class="flex-1 text-slate-700">SEO title <span class="text-slate-400 text-xs">(50-60 chars)</span></span>
                    </li>
                    <li class="flex items-center gap-2.5" data-check="summary">
                        <span class="seo-dot"></span>
                        <span class="flex-1 text-slate-700">Meta description <span class="text-slate-400 text-xs">(120-160 chars)</span></span>
                    </li>
                    <li class="flex items-center gap-2.5" data-check="keywords">
                        <span class="seo-dot"></span>
                        <span class="flex-1 text-slate-700">Keywords set</span>
                    </li>
                    <li class="flex items-center gap-2.5" data-check="cover">
                        <span class="seo-dot"></span>
                        <span class="flex-1 text-slate-700">Cover image</span>
                    </li>
                    <li class="flex items-center gap-2.5" data-check="description">
                        <span class="seo-dot"></span>
                        <span class="flex-1 text-slate-700">Body content</span>
                    </li>
                </ul>
            </div>

        </div>
    </aside>

</div>

@push('head')
<style>
    .seo-dot { display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 9999px; background: rgb(241 245 249); color: rgb(148 163 184); position: relative; flex-shrink: 0; }
    .seo-dot::before { content: ""; width: 6px; height: 6px; border-radius: 9999px; background: rgb(203 213 225); }
    li[data-status="ok"] .seo-dot { background: rgb(220 252 231); }
    li[data-status="ok"] .seo-dot::before { background: rgb(22 163 74); }
    li[data-status="warn"] .seo-dot { background: rgb(254 249 195); }
    li[data-status="warn"] .seo-dot::before { background: rgb(202 138 4); }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const hasExistingCover = {{ $blog->cover_url ? 'true' : 'false' }};

    // Auto slug from title
    const titleEl = document.getElementById('title');
    const slugEl = document.getElementById('slug');
    function slugify(s) {
        return (s || '')
            .toString()
            .toLowerCase()
            .normalize('NFKD')
            .replace(/[̀-ͯ]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }
    let slugTouched = false;
    slugEl.addEventListener('input', function () { slugTouched = true; });
    titleEl.addEventListener('input', function (e) {
        if (slugTouched) return;
        slugEl.value = slugify(e.target.value);
    });

    // Summernote init
    const $desc = jQuery('#description');
    $desc.summernote({
        placeholder: 'Write the article body, or click Generate description to draft one…',
        tabsize: 2,
        height: 360,
        styleTags: ['p', 'blockquote', 'h3', 'h4'],
        toolbar: [
            ['style', ['style']],
            ['format', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview', 'fullscreen']],
        ],
        callbacks: {
            onChange: function () { updateSeoHealth(); },
            onImageUpload: function (files) {
                for (let i = 0; i < files.length; i++) uploadInlineImage(files[i]);
            },
            onPaste: function (e) {
                // Strip inline font/color/size junk from pasted content — keeps
                // only paragraph breaks, headings, lists, links, bold, italic.
                const ev = e.originalEvent || e;
                const cb = ev.clipboardData || window.clipboardData;
                if (!cb) return;
                e.preventDefault();
                const html = cb.getData('text/html');
                const text = cb.getData('text/plain') || '';
                if (html) {
                    $desc.summernote('pasteHTML', sanitizePastedHtml(html));
                } else {
                    // Preserve line breaks when plain-text pasting.
                    const safe = text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                    $desc.summernote('pasteHTML', '<p>' + safe.replace(/\r?\n\r?\n+/g,'</p><p>').replace(/\r?\n/g,'<br>') + '</p>');
                }
            },
        },
    });

    // Allowlist-based sanitiser: keeps semantic tags, drops style/class/id
    // attributes and unwraps <span>/<font> wrappers that carry inline styles.
    function sanitizePastedHtml(html) {
        const allowedTags = new Set(['P','BR','STRONG','B','EM','I','U','H3','H4','UL','OL','LI','BLOCKQUOTE','A']);
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;

        (function walk(node) {
            const children = Array.from(node.childNodes);
            children.forEach(walk);
            if (node.nodeType !== 1) return;

            const tag = node.tagName;
            if (!allowedTags.has(tag)) {
                while (node.firstChild) node.parentNode.insertBefore(node.firstChild, node);
                node.parentNode.removeChild(node);
                return;
            }
            for (const attr of Array.from(node.attributes)) {
                if (tag === 'A' && (attr.name === 'href' || attr.name === 'title')) continue;
                node.removeAttribute(attr.name);
            }
        })(wrapper);

        return wrapper.innerHTML;
    }

    function showToast(message, ok = true) {
        const t = document.createElement('div');
        t.className = 'sn-toast ' + (ok ? 'sn-toast--ok' : 'sn-toast--err');
        t.textContent = message;
        document.body.appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity 0.3s'; }, 2200);
        setTimeout(() => { t.remove(); }, 2600);
    }

    async function uploadInlineImage(file) {
        const placeholderId = 'sn-up-' + Math.random().toString(36).slice(2);
        const placeholder = '<span class="sn-upload-placeholder" id="' + placeholderId + '" contenteditable="false">Uploading ' + (file.name || 'image') + '…</span>';
        $desc.summernote('pasteHTML', placeholder);
        const removePlaceholder = () => {
            const el = document.getElementById(placeholderId);
            if (el && el.parentNode) el.parentNode.removeChild(el);
        };
        if (window.compressImage) {
            try { file = await window.compressImage(file); } catch (_) {}
        }
        const fd = new FormData();
        fd.append('file', file);
        try {
            const res = await fetch('{{ route('admin.media.upload') }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: fd,
            });
            const json = await res.json();
            if (!res.ok) throw new Error(json.message || json.error || 'Upload failed');
            removePlaceholder();
            const img = document.createElement('img');
            img.src = json.url;
            img.style.maxWidth = '100%';
            img.setAttribute('data-uploaded', '1');
            $desc.summernote('insertNode', img);
            showToast('Image uploaded');
        } catch (e) {
            removePlaceholder();
            showToast('Upload failed: ' + (e.message || e), false);
        }
    }

    // Character counters
    function attachCounter(inputId, counterId) {
        const el = document.getElementById(inputId);
        const c = document.getElementById(counterId);
        if (!el || !c) return;
        const update = () => { c.textContent = (el.value || '').length; updateSeoHealth(); };
        el.addEventListener('input', update);
        update();
    }
    attachCounter('seo_title', 'seo-title-count');
    attachCounter('summary', 'summary-count');
    document.getElementById('seo_keywords').addEventListener('input', updateSeoHealth);
    document.getElementById('cover_image').addEventListener('change', updateSeoHealth);

    // SEO health checklist
    function setStatus(check, status) {
        const li = document.querySelector('#seo-health-list li[data-check="'+check+'"]');
        if (li) li.setAttribute('data-status', status);
    }
    function updateSeoHealth() {
        const titleLen = (document.getElementById('seo_title').value || '').trim().length;
        setStatus('title', titleLen >= 30 && titleLen <= 65 ? 'ok' : (titleLen > 0 ? 'warn' : 'pending'));
        const summaryLen = (document.getElementById('summary').value || '').trim().length;
        setStatus('summary', summaryLen >= 100 && summaryLen <= 170 ? 'ok' : (summaryLen > 0 ? 'warn' : 'pending'));
        const kwLen = (document.getElementById('seo_keywords').value || '').trim().length;
        setStatus('keywords', kwLen > 3 ? 'ok' : 'pending');
        const coverInput = document.getElementById('cover_image');
        const hasNewFile = coverInput.files && coverInput.files.length > 0;
        setStatus('cover', (hasExistingCover || hasNewFile) ? 'ok' : 'pending');
        const descText = ($desc.summernote('code') || '').replace(/<[^>]+>/g,'').trim();
        setStatus('description', descText.length >= 80 ? 'ok' : (descText.length > 0 ? 'warn' : 'pending'));
    }
    updateSeoHealth();

    // AI helpers
    async function callAi(url, payload, button, label, errBox) {
        const original = label.textContent;
        button.disabled = true;
        label.textContent = 'Generating…';
        errBox.classList.add('hidden');
        try {
            const res = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify(payload),
            });
            const json = await res.json();
            if (!res.ok) throw new Error(json.detail || json.error || 'Request failed ('+res.status+')');
            return json;
        } catch (e) {
            errBox.textContent = 'AI generation failed: ' + (e.message || e);
            errBox.classList.remove('hidden');
            return null;
        } finally {
            button.disabled = false;
            label.textContent = original;
        }
    }

    function requireTitle(errBox) {
        const t = (document.getElementById('title').value || '').trim();
        if (!t) {
            errBox.textContent = 'Enter a title first.';
            errBox.classList.remove('hidden');
            document.getElementById('title').focus();
            return null;
        }
        return t;
    }

    const seoBtn = document.getElementById('generate-seo-btn');
    const seoLabel = document.getElementById('generate-seo-label');
    const seoErr = document.getElementById('generate-seo-error');
    seoBtn.addEventListener('click', async function () {
        const title = requireTitle(seoErr);
        if (!title) return;
        const json = await callAi('{{ route('admin.blogs.generate-seo') }}', {
            title,
            category: (document.getElementById('category').value || '').trim(),
            context: ($desc.summernote('code') || '').replace(/<[^>]+>/g,' ').trim().slice(0, 800),
        }, seoBtn, seoLabel, seoErr);
        if (!json) return;
        if (json.seo_title) document.getElementById('seo_title').value = json.seo_title;
        if (json.summary) document.getElementById('summary').value = json.summary;
        if (json.seo_keywords) document.getElementById('seo_keywords').value = json.seo_keywords;
        ['seo_title','summary','seo_keywords'].forEach(id => document.getElementById(id).dispatchEvent(new Event('input')));
    });

    const descBtn = document.getElementById('generate-description-btn');
    const descLabel = document.getElementById('generate-description-label');
    const descErr = document.getElementById('generate-description-error');
    descBtn.addEventListener('click', async function () {
        const title = requireTitle(descErr);
        if (!title) return;
        const json = await callAi('{{ route('admin.blogs.generate-description') }}', {
            title,
            category: (document.getElementById('category').value || '').trim(),
            summary: (document.getElementById('summary').value || '').trim(),
        }, descBtn, descLabel, descErr);
        if (!json) return;
        if (json.description) {
            $desc.summernote('code', json.description);
            updateSeoHealth();
        }
    });
})();
</script>
@endpush
