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
    .note-editable { min-height: 260px; padding: 14px 18px; font-size: 14px; line-height: 1.65; color: rgb(15 23 42); }
    .note-editable h3 { font-size: 18px; font-weight: 600; margin: 16px 0 8px; color: rgb(15 23 42); }
    .note-editable h4 { font-size: 16px; font-weight: 600; margin: 14px 0 6px; color: rgb(15 23 42); }
    .note-editable p { margin: 0 0 12px; }
    .note-editable ul, .note-editable ol { margin: 0 0 12px 20px; }
    .note-editable li { margin: 4px 0; }
    .note-placeholder { color: rgb(148 163 184); }
</style>
@endpush

<div class="grid grid-cols-12 gap-6">

    {{-- MAIN COLUMN --}}
    <div class="col-span-12 lg:col-span-8 space-y-6">

        {{-- Section: Page details --}}
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">Page details</h3>
                <p class="text-xs text-slate-500 mt-0.5">Core identity of the page. Public URL: <code class="font-mono text-slate-700">/{slug}</code></p>
            </header>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="title" class="{{ $labelClass }}">Title</label>
                    <input id="title" name="title" type="text" required autofocus
                           value="{{ old('title', $page->title) }}"
                           class="{{ $inputClass }}">
                    @error('title') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="slug" class="{{ $labelClass }}">Slug <span class="text-slate-400 font-normal">(auto if blank)</span></label>
                    <input id="slug" name="slug" type="text"
                           value="{{ old('slug', $page->slug) }}"
                           placeholder="auto-generated from title"
                           class="{{ $inputClass }} font-mono">
                    <p class="mt-1 text-[11px] text-slate-500">Reserved: about, services, events, blog, contact, admin, dashboard…</p>
                    @error('slug') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="eyebrow" class="{{ $labelClass }}">Eyebrow <span class="text-slate-400 font-normal">(small line above title)</span></label>
                    <input id="eyebrow" name="eyebrow" type="text" maxlength="120"
                           value="{{ old('eyebrow', $page->eyebrow) }}"
                           class="{{ $inputClass }}">
                    @error('eyebrow') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="summary" class="{{ $labelClass }}">Summary / lede <span class="text-slate-400 font-normal">(paragraph shown under h1)</span></label>
                    <textarea id="summary" name="summary" rows="3" maxlength="2000" class="{{ $inputClass }}">{{ old('summary', $page->summary) }}</textarea>
                    @error('summary') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        {{-- Section: Description --}}
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">Description</h3>
                <p class="text-xs text-slate-500 mt-0.5">Long-form body rendered directly under the hero. Optional — leave blank if you plan to use only sections.</p>
            </header>
            <div class="p-5 space-y-3">
                <textarea id="description" name="description" rows="12">{{ old('description', $page->description) }}</textarea>
                @error('description') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>
        </section>

        {{-- Section: SEO --}}
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">SEO</h3>
                <p class="text-xs text-slate-500 mt-0.5">Meta title, description, and keywords for search engines.</p>
            </header>
            <div class="p-5 space-y-5">
                <div>
                    <label for="seo_title" class="{{ $labelClass }}">SEO title <span class="text-slate-400 font-normal">(50-60 chars ideal)</span></label>
                    <input id="seo_title" name="seo_title" type="text" maxlength="160"
                           value="{{ old('seo_title', $page->seo_title) }}"
                           placeholder="Leave blank to use the page title"
                           class="{{ $inputClass }}">
                    <p class="mt-1 text-xs text-slate-500"><span id="seo-title-count">0</span> characters</p>
                </div>

                <div>
                    <label for="seo_description" class="{{ $labelClass }}">Meta description</label>
                    <textarea id="seo_description" name="seo_description" rows="3" maxlength="500" class="{{ $inputClass }}">{{ old('seo_description', $page->seo_description) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500"><span id="seo-desc-count">0</span> characters — aim for 120-160</p>
                </div>

                <div>
                    <label for="seo_keywords" class="{{ $labelClass }}">Keywords <span class="text-slate-400 font-normal">(comma-separated)</span></label>
                    <input id="seo_keywords" name="seo_keywords" type="text" maxlength="500"
                           value="{{ old('seo_keywords', $page->seo_keywords) }}"
                           class="{{ $inputClass }}">
                </div>
            </div>
        </section>

    </div>

    {{-- SIDEBAR --}}
    <aside class="col-span-12 lg:col-span-4 space-y-5">
        <div class="lg:sticky lg:top-24 space-y-5">

            <div class="bg-white border border-slate-200 rounded-lg">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900">Publish</h3>
                </header>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1"
                                   class="mt-0.5 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                                   {{ old('is_published', $page->is_published ?? true) ? 'checked' : '' }}>
                            <span class="text-sm">
                                <span class="block font-medium text-slate-900">Published</span>
                                <span class="block text-xs text-slate-500 mt-0.5">Visible on the public site when checked.</span>
                            </span>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center gap-3">
                        <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
                            {{ $page->exists ? 'Update page' : 'Create page' }}
                        </button>
                        <a href="{{ route('admin.pages.index') }}" class="text-sm text-slate-500 hover:text-slate-900">Cancel</a>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900">Navigation</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Show this page in the site header.</p>
                </header>
                <div class="p-5 space-y-4">
                    <label class="flex items-start gap-2.5 cursor-pointer">
                        <input type="hidden" name="show_in_nav" value="0">
                        <input type="checkbox" name="show_in_nav" value="1"
                               class="mt-0.5 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                               {{ old('show_in_nav', $page->show_in_nav ?? false) ? 'checked' : '' }}>
                        <span class="text-sm">
                            <span class="block font-medium text-slate-900">Show in navbar</span>
                            <span class="block text-xs text-slate-500 mt-0.5">Adds a link in the primary site nav.</span>
                        </span>
                    </label>

                    <div>
                        <label for="nav_label" class="{{ $labelClass }}">Nav label <span class="text-slate-400 font-normal">(falls back to title)</span></label>
                        <input id="nav_label" name="nav_label" type="text" maxlength="60"
                               value="{{ old('nav_label', $page->nav_label) }}"
                               placeholder="e.g. Our Team"
                               class="{{ $inputClass }}">
                        @error('nav_label') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nav_order" class="{{ $labelClass }}">Nav order <span class="text-slate-400 font-normal">(lower first)</span></label>
                        <input id="nav_order" name="nav_order" type="number" min="0"
                               value="{{ old('nav_order', $page->nav_order ?? 0) }}"
                               class="{{ $inputClass }}">
                        @error('nav_order') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900">Hero image</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Optional image displayed in the hero.</p>
                </header>
                <div class="p-5 space-y-3">
                    @if ($page->hero_url)
                        <div class="relative rounded-md overflow-hidden border border-slate-200 aspect-[16/10] bg-slate-100">
                            <img src="{{ $page->hero_url }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <p class="text-xs text-slate-500">Upload a new file below to replace.</p>
                    @else
                        <div class="rounded-md border border-dashed border-slate-300 aspect-[16/10] bg-slate-50 flex flex-col items-center justify-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                            <span class="text-xs">No image uploaded</span>
                        </div>
                    @endif
                    <input id="hero_image" name="hero_image" type="file" accept="image/jpeg,image/png,image/webp" data-auto-compress
                           class="block w-full text-xs text-slate-700 file:mr-3 file:px-3 file:py-1.5 file:rounded-md file:border-0 file:bg-slate-900 file:text-white file:cursor-pointer file:text-xs file:font-semibold hover:file:bg-black">
                    <p class="text-[11px] text-slate-500 leading-relaxed">JPG, PNG, WEBP — max 5 MB.</p>
                    @error('hero_image') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </aside>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    // Auto slug from title
    const titleEl = document.getElementById('title');
    const slugEl = document.getElementById('slug');
    function slugify(s) {
        return (s || '').toString().toLowerCase().normalize('NFKD')
            .replace(/[̀-ͯ]/g, '').replace(/[^a-z0-9\s-]/g, '')
            .trim().replace(/\s+/g, '-').replace(/-+/g, '-');
    }
    let slugTouched = !!slugEl.value;
    slugEl.addEventListener('input', () => { slugTouched = true; });
    titleEl.addEventListener('input', (e) => {
        if (slugTouched) return;
        slugEl.value = slugify(e.target.value);
    });

    // Summernote
    const $desc = jQuery('#description');
    $desc.summernote({
        placeholder: 'Optional long-form body…',
        tabsize: 2, height: 260,
        styleTags: ['p', 'blockquote', 'h3', 'h4'],
        toolbar: [
            ['style', ['style']],
            ['format', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview', 'fullscreen']],
        ],
        callbacks: {
            onImageUpload: function (files) { for (let i=0;i<files.length;i++) uploadInlineImage(files[i]); },
        },
    });

    async function uploadInlineImage(file) {
        if (window.compressImage) { try { file = await window.compressImage(file); } catch (_) {} }
        const fd = new FormData();
        fd.append('file', file);
        try {
            const res = await fetch('{{ route('admin.media.upload') }}', {
                method: 'POST', credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: fd,
            });
            const json = await res.json();
            if (!res.ok) throw new Error(json.message || json.error || 'Upload failed');
            const img = document.createElement('img');
            img.src = json.url; img.style.maxWidth = '100%'; img.setAttribute('data-uploaded', '1');
            $desc.summernote('insertNode', img);
        } catch (e) { alert('Upload failed: ' + (e.message || e)); }
    }

    function attachCounter(inputId, counterId) {
        const el = document.getElementById(inputId);
        const c  = document.getElementById(counterId);
        if (!el || !c) return;
        const update = () => { c.textContent = (el.value || '').length; };
        el.addEventListener('input', update);
        update();
    }
    attachCounter('seo_title', 'seo-title-count');
    attachCounter('seo_description', 'seo-desc-count');
})();
</script>
@endpush
