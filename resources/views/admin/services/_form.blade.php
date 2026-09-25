@csrf
@php
    $inputClass = 'mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 text-sm';
    $labelClass = 'block text-sm font-medium text-slate-700';
    $errorClass = 'mt-1 text-xs text-red-600';
    $existingFaqs = old('question')
        ? array_map(fn ($q, $a) => ['q' => $q, 'a' => $a], old('question', []), old('answer', []))
        : ($service->faqs ?? []);
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
    .note-placeholder { color: rgb(148 163 184); }
    .sn-toast { position: fixed; z-index: 60; right: 20px; bottom: 20px; padding: 10px 14px; border-radius: 6px; font-size: 13px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); max-width: 320px; }
    .sn-toast--ok { background: rgb(22 101 52); color: white; }
    .sn-toast--err { background: rgb(153 27 27); color: white; }
    .faq-row { display: grid; grid-template-columns: 1fr 2fr auto; gap: 0.75rem; align-items: start; }
    @media (max-width: 720px) { .faq-row { grid-template-columns: 1fr; } }
</style>
@endpush

<div class="grid grid-cols-12 gap-6">

    {{-- MAIN COLUMN --}}
    <div class="col-span-12 lg:col-span-8 space-y-6">

        {{-- Section: Service details --}}
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">Service details</h3>
                <p class="text-xs text-slate-500 mt-0.5">Core identity of the service.</p>
            </header>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="title" class="{{ $labelClass }}">Title</label>
                    <input id="title" name="title" type="text" required autofocus
                           value="{{ old('title', $service->title) }}"
                           class="{{ $inputClass }}">
                    @error('title') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="slug" class="{{ $labelClass }}">Slug <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input id="slug" name="slug" type="text"
                           value="{{ old('slug', $service->slug) }}"
                           placeholder="auto-generated from title"
                           class="{{ $inputClass }} font-mono">
                    @error('slug') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="eyebrow" class="{{ $labelClass }}">Eyebrow <span class="text-slate-400 font-normal">(all caps line above title)</span></label>
                    <input id="eyebrow" name="eyebrow" type="text" maxlength="120"
                           value="{{ old('eyebrow', $service->eyebrow) }}"
                           placeholder="e.g. CONFERENCE PLANNING"
                           class="{{ $inputClass }}">
                    @error('eyebrow') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="summary" class="{{ $labelClass }}">Summary / lede <span class="text-slate-400 font-normal">(paragraph shown under h1)</span></label>
                    <textarea id="summary" name="summary" rows="3" maxlength="2000" class="{{ $inputClass }}">{{ old('summary', $service->summary) }}</textarea>
                    @error('summary') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        {{-- Section: Description --}}
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                        Description
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Long-form body rendered directly under the hero.</p>
                </div>
                <button type="button" id="generate-description-btn"
                    class="inline-flex items-center gap-2 px-3 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md disabled:opacity-60 disabled:cursor-wait shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707A1 1 0 004.343 5.757l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/></svg>
                    <span id="generate-description-label">Generate description</span>
                </button>
            </header>
            <div class="p-5 space-y-3">
                <div id="generate-description-error" class="hidden rounded-md bg-red-50 border border-red-200 text-red-800 px-3 py-2 text-sm"></div>
                <textarea id="description" name="description" rows="12">{{ old('description', $service->description) }}</textarea>
                @error('description') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            </div>
        </section>

        {{-- Section: Services included checklist --}}
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">Services checklist</h3>
                <p class="text-xs text-slate-500 mt-0.5">Bulleted list rendered under the hero. One item per line.</p>
            </header>
            <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label for="services_included_label" class="{{ $labelClass }}">Section heading</label>
                    <input id="services_included_label" name="services_included_label" type="text" maxlength="120"
                           value="{{ old('services_included_label', $service->services_included_label) }}"
                           placeholder="e.g. Services included"
                           class="{{ $inputClass }}">
                </div>
                <div class="md:col-span-2">
                    <label for="services_included" class="{{ $labelClass }}">Items <span class="text-slate-400 font-normal">(one per line)</span></label>
                    <textarea id="services_included" name="services_included" rows="8" class="{{ $inputClass }} font-mono text-xs">{{ old('services_included', is_array($service->services_included) ? implode("\n", $service->services_included) : '') }}</textarea>
                </div>
            </div>
        </section>

        {{-- Section: Suitable for --}}
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">Secondary checklist <span class="text-slate-400 font-normal">(optional)</span></h3>
                <p class="text-xs text-slate-500 mt-0.5">Use for &ldquo;Suitable for&rdquo;, &ldquo;Programme formats&rdquo;, etc.</p>
            </header>
            <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label for="suitable_for_label" class="{{ $labelClass }}">Section heading</label>
                    <input id="suitable_for_label" name="suitable_for_label" type="text" maxlength="120"
                           value="{{ old('suitable_for_label', $service->suitable_for_label) }}"
                           placeholder="e.g. Suitable for"
                           class="{{ $inputClass }}">
                </div>
                <div class="md:col-span-2">
                    <label for="suitable_for" class="{{ $labelClass }}">Items <span class="text-slate-400 font-normal">(one per line)</span></label>
                    <textarea id="suitable_for" name="suitable_for" rows="6" class="{{ $inputClass }} font-mono text-xs">{{ old('suitable_for', is_array($service->suitable_for) ? implode("\n", $service->suitable_for) : '') }}</textarea>
                </div>
            </div>
        </section>

        {{-- Section: FAQ repeater --}}
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Frequently asked</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Renders as accordion + generates FAQPage JSON-LD when present.</p>
                </div>
                <button type="button" id="faq-add"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 border border-slate-300 rounded-md hover:bg-slate-50">
                    + Add FAQ
                </button>
            </header>
            <div class="p-5 space-y-3" id="faq-rows">
                @forelse ($existingFaqs as $faq)
                    <div class="faq-row">
                        <input type="text" name="question[]" value="{{ $faq['q'] ?? '' }}" placeholder="Question" class="{{ $inputClass }}">
                        <textarea name="answer[]" rows="2" placeholder="Answer" class="{{ $inputClass }}">{{ $faq['a'] ?? '' }}</textarea>
                        <button type="button" class="faq-remove text-red-600 hover:text-red-800 text-xs font-medium mt-2">Remove</button>
                    </div>
                @empty
                    {{-- one empty row so admins can start typing immediately --}}
                    <div class="faq-row">
                        <input type="text" name="question[]" value="" placeholder="Question" class="{{ $inputClass }}">
                        <textarea name="answer[]" rows="2" placeholder="Answer" class="{{ $inputClass }}"></textarea>
                        <button type="button" class="faq-remove text-red-600 hover:text-red-800 text-xs font-medium mt-2">Remove</button>
                    </div>
                @endforelse
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
                           value="{{ old('seo_title', $service->seo_title) }}"
                           placeholder="Leave blank to use the service title"
                           class="{{ $inputClass }}">
                    <p class="mt-1 text-xs text-slate-500"><span id="seo-title-count">0</span> characters</p>
                </div>

                <div>
                    <label for="seo_description" class="{{ $labelClass }}">Meta description</label>
                    <textarea id="seo_description" name="seo_description" rows="3" maxlength="500" class="{{ $inputClass }}">{{ old('seo_description', $service->seo_description) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500"><span id="seo-desc-count">0</span> characters — aim for 120-160</p>
                </div>

                <div>
                    <label for="seo_keywords" class="{{ $labelClass }}">Keywords <span class="text-slate-400 font-normal">(comma-separated)</span></label>
                    <input id="seo_keywords" name="seo_keywords" type="text" maxlength="500"
                           value="{{ old('seo_keywords', $service->seo_keywords) }}"
                           placeholder="e.g. event planning, supplier coordination, venue sourcing"
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
                        <label for="position" class="{{ $labelClass }}">Order <span class="text-slate-400 font-normal">(lower first)</span></label>
                        <input id="position" name="position" type="number" min="0"
                               value="{{ old('position', $service->position ?? 0) }}"
                               class="{{ $inputClass }}">
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1"
                                   class="mt-0.5 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                                   {{ old('is_published', $service->is_published ?? true) ? 'checked' : '' }}>
                            <span class="text-sm">
                                <span class="block font-medium text-slate-900">Published</span>
                                <span class="block text-xs text-slate-500 mt-0.5">Visible on the public site when checked.</span>
                            </span>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center gap-3">
                        <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
                            {{ $service->exists ? 'Update service' : 'Create service' }}
                        </button>
                        <a href="{{ route('admin.services.index') }}" class="text-sm text-slate-500 hover:text-slate-900">Cancel</a>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900">Hero image</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Right-hand image in the hero.</p>
                </header>
                <div class="p-5 space-y-3">
                    @if ($service->hero_url)
                        <div class="relative rounded-md overflow-hidden border border-slate-200 aspect-[16/10] bg-slate-100">
                            <img src="{{ $service->hero_url }}" alt="" class="absolute inset-0 w-full h-full object-cover">
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
                    <p class="text-[11px] text-slate-500 leading-relaxed">JPG, PNG, WEBP — max 5 MB. Stored on DigitalOcean Spaces under <code class="text-slate-700 font-mono">sob-summit/services/</code>.</p>
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

    // Summernote init with paste sanitiser
    const $desc = jQuery('#description');
    $desc.summernote({
        placeholder: 'Optional long-form body, or click Generate description to draft one…',
        tabsize: 2, height: 320,
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
            onPaste: function (e) {
                const ev = e.originalEvent || e;
                const cb = ev.clipboardData || window.clipboardData;
                if (!cb) return;
                e.preventDefault();
                const html = cb.getData('text/html');
                const text = cb.getData('text/plain') || '';
                if (html) {
                    $desc.summernote('pasteHTML', sanitizePastedHtml(html));
                } else {
                    const safe = text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                    $desc.summernote('pasteHTML', '<p>' + safe.replace(/\r?\n\r?\n+/g,'</p><p>').replace(/\r?\n/g,'<br>') + '</p>');
                }
            },
        },
    });

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
            showToast('Image uploaded');
        } catch (e) { showToast('Upload failed: ' + (e.message || e), false); }
    }

    // FAQ repeater
    const faqRows = document.getElementById('faq-rows');
    document.getElementById('faq-add').addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'faq-row';
        row.innerHTML = `
            <input type="text" name="question[]" placeholder="Question" class="{{ $inputClass }}">
            <textarea name="answer[]" rows="2" placeholder="Answer" class="{{ $inputClass }}"></textarea>
            <button type="button" class="faq-remove text-red-600 hover:text-red-800 text-xs font-medium mt-2">Remove</button>`;
        faqRows.appendChild(row);
    });
    faqRows.addEventListener('click', function (e) {
        if (e.target.classList.contains('faq-remove')) {
            e.target.closest('.faq-row').remove();
        }
    });

    // Character counters
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

    // AI helpers
    async function callAi(url, payload, button, label, errBox) {
        const original = label.textContent;
        button.disabled = true; label.textContent = 'Generating…';
        errBox.classList.add('hidden');
        try {
            const res = await fetch(url, {
                method: 'POST', credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify(payload),
            });
            const json = await res.json();
            if (!res.ok) throw new Error(json.detail || json.error || 'Request failed ('+res.status+')');
            return json;
        } catch (e) {
            errBox.textContent = 'AI generation failed: ' + (e.message || e);
            errBox.classList.remove('hidden');
            return null;
        } finally { button.disabled = false; label.textContent = original; }
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
        const json = await callAi('{{ route('admin.services.generate-seo') }}', {
            title,
            eyebrow: (document.getElementById('eyebrow').value || '').trim(),
            context: ((document.getElementById('summary').value || '') + ' ' + ($desc.summernote('code') || '').replace(/<[^>]+>/g,' ')).trim().slice(0, 1000),
        }, seoBtn, seoLabel, seoErr);
        if (!json) return;
        if (json.seo_title)       document.getElementById('seo_title').value = json.seo_title;
        if (json.summary)         document.getElementById('seo_description').value = json.summary;
        if (json.seo_keywords)    document.getElementById('seo_keywords').value = json.seo_keywords;
        ['seo_title','seo_description'].forEach(id => document.getElementById(id).dispatchEvent(new Event('input')));
    });

    const descBtn = document.getElementById('generate-description-btn');
    const descLabel = document.getElementById('generate-description-label');
    const descErr = document.getElementById('generate-description-error');
    descBtn.addEventListener('click', async function () {
        const title = requireTitle(descErr);
        if (!title) return;
        const json = await callAi('{{ route('admin.services.generate-description') }}', {
            title,
            eyebrow: (document.getElementById('eyebrow').value || '').trim(),
            summary: (document.getElementById('summary').value || '').trim(),
        }, descBtn, descLabel, descErr);
        if (!json) return;
        if (json.description) { $desc.summernote('code', json.description); }
    });
})();
</script>
@endpush
