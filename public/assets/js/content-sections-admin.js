(function () {
    'use strict';

    const root = document.querySelector('.cs-root[data-editor]');
    if (!root) return;

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.content : '';

    const sectionableType = root.dataset.sectionableType;
    const sectionableId = parseInt(root.dataset.sectionableId, 10);
    const pageType = root.dataset.pageType || '';
    const list = root.querySelector('.cs-list');

    const picker = document.querySelector('.cs-adm-picker');
    const pickerGrid = picker ? picker.querySelector('[data-picker-grid]') : null;
    const panel = document.querySelector('.cs-adm-panel');
    const panelBody = panel ? panel.querySelector('[data-panel-body]') : null;
    const panelTitle = panel ? panel.querySelector('[data-panel-title]') : null;
    const panelPublished = panel ? panel.querySelector('[data-panel-published]') : null;
    const panelSaveBtn = panel ? panel.querySelector('[data-panel-save]') : null;

    let currentSectionId = null;
    let templatesLoaded = false;
    let templatesCache = [];

    // ---------- utilities ----------

    function toast(message, ok = true) {
        const t = document.createElement('div');
        t.className = 'cs-adm-toast ' + (ok ? 'cs-adm-toast--ok' : 'cs-adm-toast--err');
        t.textContent = message;
        document.body.appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity 0.3s'; }, 2200);
        setTimeout(() => { t.remove(); }, 2600);
    }

    async function api(url, options = {}) {
        const opts = Object.assign({
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
        }, options);
        if (opts.body && !(opts.body instanceof FormData)) {
            opts.headers['Content-Type'] = 'application/json';
            if (typeof opts.body !== 'string') opts.body = JSON.stringify(opts.body);
        }
        const res = await fetch(url, opts);
        let json = null;
        try { json = await res.json(); } catch (_) { }
        if (!res.ok) {
            const message = (json && (json.message || json.error)) || ('Request failed (' + res.status + ')');
            throw new Error(message);
        }
        return json;
    }

    function openDialog(el) {
        if (!el) return;
        el.hidden = false;
        document.body.style.overflow = 'hidden';
    }
    function closeDialog(el) {
        if (!el) return;
        el.hidden = true;
        // if no other dialogs open, restore scroll
        if (!document.querySelector('.cs-adm-picker:not([hidden]), .cs-adm-panel:not([hidden])')) {
            document.body.style.overflow = '';
        }
    }

    // ---------- picker ----------

    async function ensureTemplatesLoaded() {
        if (templatesLoaded) return;
        try {
            const json = await api('/admin/sections/templates');
            templatesCache = json.types || [];
            renderTemplates();
            templatesLoaded = true;
        } catch (e) {
            pickerGrid.innerHTML = '<p class="cs-adm-picker__loading" style="color:#991b1b">Failed to load templates: ' + e.message + '</p>';
        }
    }

    function renderTemplates() {
        if (!pickerGrid) return;
        pickerGrid.innerHTML = '';
        templatesCache.forEach(function (t) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'cs-adm-picker__card';
            btn.dataset.type = t.key;
            btn.innerHTML =
                (t.preview ? '<img src="' + t.preview + '" alt="">' : '') +
                '<span class="cs-adm-picker__card-label">' + escapeHtml(t.label) + '</span>' +
                '<p class="cs-adm-picker__card-desc">' + escapeHtml(t.description || '') + '</p>';
            btn.addEventListener('click', () => createSection(t.key));
            pickerGrid.appendChild(btn);
        });
    }

    async function createSection(type) {
        try {
            closeDialog(picker);
            const json = await api('/admin/sections', {
                method: 'POST',
                body: {
                    sectionable_type: sectionableType,
                    sectionable_id: sectionableId,
                    page_type: pageType,
                    type: type,
                },
            });
            const tmp = document.createElement('div');
            tmp.innerHTML = json.html.trim();
            const newNode = tmp.firstElementChild;
            list.appendChild(newNode);
            bindWrapper(newNode);
            toast('Section added');
            openEditor(json.id);
        } catch (e) {
            toast('Add failed: ' + e.message, false);
        }
    }

    // ---------- overlay actions ----------

    function bindWrapper(wrap) {
        wrap.querySelectorAll('[data-action="edit"]').forEach(b => b.addEventListener('click', () => openEditor(wrap.dataset.sectionId)));
        wrap.querySelectorAll('[data-action="delete"]').forEach(b => b.addEventListener('click', () => deleteSection(wrap)));
        wrap.querySelectorAll('[data-action="visibility"]').forEach(b => b.addEventListener('click', () => toggleVisibility(wrap)));
    }

    async function deleteSection(wrap) {
        if (!confirm('Delete this section? This cannot be undone.')) return;
        try {
            await api('/admin/sections/' + wrap.dataset.sectionId, { method: 'DELETE' });
            wrap.style.transition = 'opacity 0.2s';
            wrap.style.opacity = '0';
            setTimeout(() => wrap.remove(), 220);
            toast('Section deleted');
        } catch (e) {
            toast('Delete failed: ' + e.message, false);
        }
    }

    async function toggleVisibility(wrap) {
        const willPublish = wrap.classList.contains('cs-wrap--hidden');
        try {
            const json = await api('/admin/sections/' + wrap.dataset.sectionId, {
                method: 'PATCH',
                body: { data: {}, is_published: willPublish },
            });
            swapWrapper(wrap, json.html);
            toast(willPublish ? 'Section published' : 'Section hidden');
        } catch (e) {
            toast('Update failed: ' + e.message, false);
        }
    }

    function swapWrapper(oldWrap, html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html.trim();
        const newWrap = tmp.firstElementChild;
        oldWrap.replaceWith(newWrap);
        bindWrapper(newWrap);
    }

    // ---------- editor panel ----------

    async function openEditor(sectionId) {
        currentSectionId = sectionId;
        panelBody.innerHTML = '<div class="cs-adm-panel__loading">Loading…</div>';
        panelTitle.textContent = 'Edit';
        openDialog(panel);
        try {
            const json = await api('/admin/sections/' + sectionId + '/edit');
            panelTitle.textContent = json.label || 'Edit';
            panelPublished.checked = !!json.is_published;
            panelBody.innerHTML = json.form;
            initEditorFields(panelBody);
        } catch (e) {
            panelBody.innerHTML = '<p class="cs-adm-picker__loading" style="color:#991b1b">Failed to load: ' + escapeHtml(e.message) + '</p>';
        }
    }

    function initEditorFields(scope) {
        // Init Summernote on any [data-rich="1"] textarea
        scope.querySelectorAll('textarea[data-rich="1"]').forEach(initRich);
        // Init image uploaders
        scope.querySelectorAll('[data-image-field]').forEach(initImage);
        // Init repeaters
        scope.querySelectorAll('[data-repeater]').forEach(initRepeater);
    }

    function initRich(textarea) {
        if (!window.jQuery || !jQuery.fn.summernote) return;
        const $t = jQuery(textarea);
        if ($t.data('cs-rich')) return;
        $t.data('cs-rich', true);
        $t.summernote({
            placeholder: 'Write here…',
            tabsize: 2,
            height: 220,
            styleTags: ['p', 'blockquote', 'h3', 'h4'],
            toolbar: [
                ['style', ['style']],
                ['format', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['codeview']],
            ],
            callbacks: {
                onImageUpload: function (files) {
                    for (let i = 0; i < files.length; i++) {
                        uploadInlineImage($t, files[i]);
                    }
                },
            },
        });
    }

    async function uploadInlineImage($t, file) {
        const placeholderId = 'cs-up-' + Math.random().toString(36).slice(2);
        const placeholder = '<span id="' + placeholderId + '" style="padding:4px 8px;background:#f1f5f9;border-radius:4px;font-size:12px" contenteditable="false">Uploading…</span>';
        $t.summernote('pasteHTML', placeholder);
        try {
            const url = await uploadFile(file);
            const el = document.getElementById(placeholderId);
            if (el) el.remove();
            const img = document.createElement('img');
            img.src = url;
            img.style.maxWidth = '100%';
            $t.summernote('insertNode', img);
        } catch (e) {
            const el = document.getElementById(placeholderId);
            if (el) el.remove();
            toast('Upload failed: ' + e.message, false);
        }
    }

    async function uploadFile(file) {
        if (window.compressImage) {
            try { file = await window.compressImage(file); } catch (_) { /* keep original */ }
        }
        const fd = new FormData();
        fd.append('file', file);
        const res = await fetch('/admin/media/upload', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: fd,
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || json.error || 'Upload failed');
        return json.url;
    }

    function initImage(field) {
        const input = field.querySelector('[data-image-input]');
        const hidden = field.querySelector('[data-value]');
        const preview = field.querySelector('.cs-adm-image__preview');
        const clearBtn = field.querySelector('[data-image-clear]');

        if (input && !input.dataset.bound) {
            input.dataset.bound = '1';
            input.addEventListener('change', async () => {
                if (!input.files || !input.files[0]) return;
                const original = preview.innerHTML;
                preview.innerHTML = '<span>Uploading…</span>';
                try {
                    const url = await uploadFile(input.files[0]);
                    hidden.value = url;
                    preview.innerHTML = '<img src="' + url + '" alt="">';
                    input.value = '';
                    ensureClearButton(field);
                } catch (e) {
                    preview.innerHTML = original;
                    toast('Upload failed: ' + e.message, false);
                }
            });
        }
        if (clearBtn && !clearBtn.dataset.bound) {
            clearBtn.dataset.bound = '1';
            clearBtn.addEventListener('click', () => {
                hidden.value = '';
                preview.innerHTML = '<span>No image</span>';
                clearBtn.remove();
            });
        }
    }
    function ensureClearButton(field) {
        if (field.querySelector('[data-image-clear]')) return;
        const actions = field.querySelector('.cs-adm-image__actions');
        if (!actions) return;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'cs-adm-btn cs-adm-btn--ghost cs-adm-btn--sm';
        btn.dataset.imageClear = '1';
        btn.textContent = 'Remove';
        actions.appendChild(btn);
        initImage(field);
    }

    function initRepeater(rep) {
        if (rep.dataset.bound) return;
        rep.dataset.bound = '1';
        const list = rep.querySelector('[data-repeater-list]');
        const tmpl = rep.querySelector('template[data-repeater-template]');
        const addBtn = rep.querySelector('[data-repeater-add]');

        function bindRemove(row) {
            const btn = row.querySelector('[data-repeater-remove]');
            if (btn && !btn.dataset.bound) {
                btn.dataset.bound = '1';
                btn.addEventListener('click', () => row.remove());
            }
        }
        list.querySelectorAll('[data-repeater-row]').forEach(bindRemove);

        if (addBtn && !addBtn.dataset.bound) {
            addBtn.dataset.bound = '1';
            addBtn.addEventListener('click', () => {
                const frag = tmpl.content.cloneNode(true);
                list.appendChild(frag);
                const row = list.lastElementChild;
                bindRemove(row);
                initEditorFields(row);
            });
        }
    }

    // ---------- save ----------

    function collectFields(scope) {
        const data = {};

        // 1) Image fields at this level
        scope.querySelectorAll('[data-image-field]').forEach(field => {
            if (fieldInDescendantRepeater(field, scope)) return;
            const key = field.dataset.field;
            const hidden = field.querySelector('[data-value]');
            if (key && hidden) data[key] = hidden.value || null;
        });

        // 2) Plain [data-field] inputs at this level
        scope.querySelectorAll('[data-field]').forEach(el => {
            // Skip hidden image-value inputs (handled above)
            if (el.tagName === 'INPUT' && el.type === 'hidden' && el.hasAttribute('data-value')) return;
            // Skip image field wrappers (they are collected above)
            if (el.hasAttribute('data-image-field')) return;
            // Skip anything nested inside a descendant repeater row
            if (fieldInDescendantRepeater(el, scope)) return;
            setValue(data, el);
        });

        // 3) Repeaters at this level (top-most only)
        scope.querySelectorAll('[data-repeater]').forEach(rep => {
            if (fieldInDescendantRepeater(rep, scope)) return;
            const key = rep.dataset.repeater;
            const rows = rep.querySelectorAll(':scope > [data-repeater-list] > [data-repeater-row]');
            const arr = [];
            rows.forEach(row => {
                arr.push(collectFields(row));
            });
            data[key] = arr;
        });

        return data;
    }

    // True if `el` sits inside a [data-repeater-row] whose nearest [data-repeater] ancestor is a descendant of `scope`
    function fieldInDescendantRepeater(el, scope) {
        const row = el.closest('[data-repeater-row]');
        if (!row) return false;
        // The row's own repeater
        const rep = row.parentElement && row.parentElement.closest('[data-repeater]');
        if (!rep) return false;
        // If scope itself is a row and this row belongs to that same scope's repeater's list, treat it as "not in descendant"
        if (scope.hasAttribute && scope.hasAttribute('data-repeater-row') && scope === row) return false;
        return scope.contains(rep) && rep !== scope;
    }

    function setValue(target, el) {
        const key = el.dataset.field;
        if (!key) return;
        // If this is an image field's hidden input inside an image wrapper, skip (handled elsewhere)
        if (el.tagName === 'INPUT' && el.type === 'hidden' && el.hasAttribute('data-value')) return;
        let value = '';
        if (el.tagName === 'TEXTAREA') {
            const $t = window.jQuery && jQuery(el);
            value = $t && $t.data('cs-rich') ? $t.summernote('code') : el.value;
        } else if (el.tagName === 'SELECT') {
            value = el.value;
            if (el.dataset.type === 'number') value = value === '' ? null : parseInt(value, 10);
        } else if (el.type === 'checkbox') {
            value = el.checked;
        } else {
            value = el.value;
        }
        target[key] = value;
    }

    async function saveEditor() {
        if (!currentSectionId) return;
        panelSaveBtn.disabled = true;
        panelSaveBtn.textContent = 'Saving…';
        try {
            const data = collectFields(panelBody);
            const json = await api('/admin/sections/' + currentSectionId, {
                method: 'PATCH',
                body: {
                    data: data,
                    is_published: panelPublished.checked,
                },
            });
            const wrap = list.querySelector('[data-section-id="' + currentSectionId + '"]');
            if (wrap) swapWrapper(wrap, json.html);
            closeDialog(panel);
            toast('Section saved');
        } catch (e) {
            toast('Save failed: ' + e.message, false);
        } finally {
            panelSaveBtn.disabled = false;
            panelSaveBtn.textContent = 'Save section';
        }
    }

    // ---------- reorder ----------

    function initSortable() {
        if (!window.Sortable) return;
        window.Sortable.create(list, {
            handle: '.cs-adm-move-handle',
            animation: 180,
            ghostClass: 'cs-adm-sortable-ghost',
            dragClass: 'cs-adm-sortable-drag',
            onEnd: async () => {
                const ids = Array.from(list.querySelectorAll('.cs-wrap')).map(w => parseInt(w.dataset.sectionId, 10));
                try {
                    await api('/admin/sections/reorder', {
                        method: 'POST',
                        body: {
                            ids: ids,
                            sectionable_type: sectionableType,
                            sectionable_id: sectionableId,
                        },
                    });
                    toast('Order saved');
                } catch (e) {
                    toast('Reorder failed: ' + e.message, false);
                }
            },
        });
    }

    // ---------- boot ----------

    // Bind existing wrappers
    list.querySelectorAll('.cs-wrap').forEach(bindWrapper);

    // Toolbar
    document.querySelectorAll('.cs-adm-add-btn').forEach(b => b.addEventListener('click', async () => {
        openDialog(picker);
        await ensureTemplatesLoaded();
    }));

    // Modal close bindings
    document.querySelectorAll('.cs-adm-picker [data-close]').forEach(el => el.addEventListener('click', () => closeDialog(picker)));
    document.querySelectorAll('.cs-adm-panel [data-close]').forEach(el => el.addEventListener('click', () => closeDialog(panel)));

    // Save
    if (panelSaveBtn) panelSaveBtn.addEventListener('click', saveEditor);

    // Escape closes
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (panel && !panel.hidden) closeDialog(panel);
            else if (picker && !picker.hidden) closeDialog(picker);
        }
    });

    initSortable();

    function escapeHtml(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, ch => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        })[ch]);
    }
})();
