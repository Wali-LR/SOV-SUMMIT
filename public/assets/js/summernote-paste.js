/**
 * Shared Summernote paste handling for the admin editors.
 *
 * Pasted rich text (Google Docs, Word, web pages, ChatGPT…) is cleaned down to
 * the editor's allowed semantic tags: paragraphs, h3/h4, lists, blockquote,
 * links, bold/italic/underline and line breaks. Inline styles, classes, fonts
 * and colours are dropped; Google Docs / Word style-based bold & italics are
 * converted to <strong>/<em>.
 *
 * Usage:
 *   callbacks: { onPaste: SnPaste.handler($desc) }
 */
(function (w) {
    'use strict';

    const ALLOWED = new Set(['P', 'BR', 'STRONG', 'B', 'EM', 'I', 'U', 'H3', 'H4', 'UL', 'OL', 'LI', 'BLOCKQUOTE', 'A']);
    // Removed together with everything inside them.
    const DROP = new Set(['SCRIPT', 'STYLE', 'META', 'LINK', 'TITLE', 'HEAD', 'NOSCRIPT', 'TEMPLATE', 'IFRAME', 'OBJECT', 'EMBED', 'SVG', 'CANVAS', 'VIDEO', 'AUDIO', 'SELECT', 'BUTTON', 'INPUT', 'TEXTAREA', 'IMG']);
    const RENAME = { H1: 'H3', H2: 'H3', H5: 'H4', H6: 'H4' };
    // Generic block containers: become <p> when they only hold inline content,
    // otherwise they are unwrapped so their block children survive.
    const BLOCKISH = new Set(['DIV', 'SECTION', 'ARTICLE', 'HEADER', 'FOOTER', 'MAIN', 'ASIDE', 'NAV', 'FIGURE', 'FIGCAPTION', 'PRE', 'ADDRESS', 'TR', 'DT', 'DD', 'CAPTION']);
    const BLOCK_TAGS = new Set(['P', 'H3', 'H4', 'UL', 'OL', 'LI', 'BLOCKQUOTE', 'DIV', 'SECTION', 'ARTICLE', 'HEADER', 'FOOTER', 'MAIN', 'ASIDE', 'NAV', 'FIGURE', 'TABLE', 'TBODY', 'THEAD', 'TFOOT', 'TR', 'TD', 'TH', 'H1', 'H2', 'H5', 'H6', 'PRE', 'DL', 'DT', 'DD', 'HR', 'ADDRESS', 'CAPTION', 'FIGCAPTION']);
    const SAFE_HREF = /^(https?:|mailto:|tel:|#|\/)/i;

    function hasBlockChild(el) {
        for (const c of el.childNodes) {
            if (c.nodeType === 1 && BLOCK_TAGS.has(c.tagName)) return true;
        }
        return false;
    }

    function unwrap(el) {
        const parent = el.parentNode;
        if (!parent) return;
        while (el.firstChild) parent.insertBefore(el.firstChild, el);
        parent.removeChild(el);
    }

    function replaceTag(el, tag) {
        const doc = el.ownerDocument;
        const n = doc.createElement(tag);
        while (el.firstChild) n.appendChild(el.firstChild);
        el.parentNode.replaceChild(n, el);
        return n;
    }

    // Wrap el's children in formatting tags derived from its inline style.
    function styleToSemantic(el) {
        const s = el.getAttribute && el.getAttribute('style');
        if (!s) return [];
        const st = s.toLowerCase();
        const tags = [];
        const fw = /font-weight\s*:\s*(bold|bolder|[6-9]00)/.exec(st);
        if (fw) tags.push('STRONG');
        if (/font-style\s*:\s*italic/.test(st)) tags.push('EM');
        if (/text-decoration[^;]*underline/.test(st)) tags.push('U');
        return tags;
    }

    function isEmptyBlock(el) {
        if (el.querySelector && el.querySelector('br') && el.textContent.replace(/[\s ]/g, '') === '') {
            return true;
        }
        return el.textContent.replace(/[\s ​]/g, '') === '';
    }

    function sanitize(html) {
        const doc = new DOMParser().parseFromString(html || '', 'text/html');
        const root = doc.body;
        if (!root) return '';

        (function walk(node) {
            for (const child of Array.from(node.childNodes)) {
                if (child.nodeType === 8) { child.remove(); continue; } // comments (<!--StartFragment--> etc.)
                if (child.nodeType !== 1) continue;
                const tag = child.tagName.toUpperCase();

                if (DROP.has(tag)) { child.remove(); continue; }

                walk(child); // children first

                let el = child;
                // Google Docs wraps everything in <b style="font-weight:normal">.
                if ((tag === 'B' || tag === 'STRONG') && /font-weight\s*:\s*(normal|[1-4]00)/i.test(el.getAttribute('style') || '')) {
                    unwrap(el); continue;
                }

                // Style-carried formatting (spans from Docs/Word) -> semantic tags.
                const fmt = (tag === 'SPAN' || tag === 'FONT') ? styleToSemantic(el) : [];

                if (RENAME[tag]) {
                    el = replaceTag(el, RENAME[tag]);
                } else if (tag === 'TD' || tag === 'TH') {
                    el.appendChild(doc.createTextNode(' '));
                    unwrap(el); continue;
                } else if (BLOCKISH.has(tag)) {
                    if (hasBlockChild(el)) { unwrap(el); continue; }
                    if (tag === 'PRE') el.innerHTML = el.innerHTML.replace(/\r?\n/g, '<br>');
                    el = replaceTag(el, 'P');
                } else if (tag === 'HR') {
                    el.remove(); continue;
                } else if (!ALLOWED.has(tag)) {
                    if (fmt.length) {
                        let target = el;
                        for (const f of fmt) {
                            const wrap = doc.createElement(f);
                            while (target.firstChild) wrap.appendChild(target.firstChild);
                            target.appendChild(wrap);
                            target = wrap;
                        }
                    }
                    unwrap(el); continue;
                }

                // Paragraph nested inside a list item / paragraph: flatten.
                if (el.tagName === 'P' && el.parentNode && el.parentNode.nodeType === 1 && /^(LI|P|H3|H4)$/.test(el.parentNode.tagName)) {
                    unwrap(el); continue;
                }

                // Strip attributes (keep safe link href only).
                for (const attr of Array.from(el.attributes)) {
                    if (el.tagName === 'A' && attr.name === 'href' && SAFE_HREF.test(attr.value.trim())) continue;
                    el.removeAttribute(attr.name);
                }
                if (el.tagName === 'A' && !el.getAttribute('href')) { unwrap(el); continue; }
            }
        })(root);

        // Normalise top level: group stray inline/text runs into <p>, drop
        // whitespace-only text between blocks and empty paragraphs.
        const out = doc.createElement('div');
        let para = null;
        for (const node of Array.from(root.childNodes)) {
            const isBlock = node.nodeType === 1 && BLOCK_TAGS.has(node.tagName);
            if (isBlock) {
                para = null;
                if (/^(P|H3|H4|LI|BLOCKQUOTE)$/.test(node.tagName) && isEmptyBlock(node)) continue;
                out.appendChild(node);
            } else if (node.nodeType === 1 && node.tagName === 'BR') {
                // <br><br> at top level = paragraph break.
                if (para) para = null;
            } else if (node.nodeType === 3 && !node.textContent.trim()) {
                if (para) para.appendChild(node);
            } else {
                if (!para) { para = doc.createElement('p'); out.appendChild(para); }
                para.appendChild(node);
            }
        }
        for (const p of Array.from(out.querySelectorAll('p'))) {
            if (isEmptyBlock(p)) p.remove();
        }
        // Remove whitespace-only text nodes inside list containers.
        for (const list of Array.from(out.querySelectorAll('ul,ol'))) {
            for (const c of Array.from(list.childNodes)) {
                if (c.nodeType === 3 && !c.textContent.trim()) c.remove();
            }
        }
        return out.innerHTML.trim();
    }

    function escapeHtml(s) {
        return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function textToHtml(text) {
        const norm = text.replace(/\r\n?/g, '\n').trim();
        if (!norm) return '';
        return norm
            .split(/\n[ \t]*\n+/)
            .map((block) => '<p>' + escapeHtml(block.trim()).replace(/\n/g, '<br>') + '</p>')
            .join('');
    }

    function insert($el, html) {
        if (!html) return;
        // Make sure Summernote's saved range is the current caret position.
        try { $el.summernote('editor.restoreRange'); } catch (_) {}
        try { $el.summernote('editor.setLastRange'); } catch (_) {}
        $el.summernote('pasteHTML', html);
        // Pasting into an empty editor leaves its placeholder <p><br></p> on top.
        try {
            const editable = $el.next('.note-editor').find('.note-editable')[0];
            const first = editable && editable.firstElementChild;
            if (first && first !== editable.lastElementChild && first.tagName === 'P' && isEmptyBlock(first)) {
                first.remove();
            }
        } catch (_) {}
        // Keep the source <textarea> in sync so a form submit always posts it.
        try { $el.val($el.summernote('code')); } catch (_) {}
    }

    function handler($el) {
        return function (e) {
            const ev = e.originalEvent || e;
            const cb = ev.clipboardData || w.clipboardData;
            if (!cb) return; // let the browser handle it

            const html = cb.getData('text/html') || '';
            const text = cb.getData('text/plain') || cb.getData('Text') || '';

            // Image-only clipboard: leave it to Summernote's own image paste.
            if (!html && !text) return;

            let out = '';
            try {
                out = html ? sanitize(html) : '';
            } catch (err) {
                if (w.console) console.warn('[summernote-paste] sanitize failed, falling back to plain text', err);
                out = '';
            }
            if (!out) out = textToHtml(text);
            if (!out) return;

            e.preventDefault();
            insert($el, out);
        };
    }

    w.SnPaste = { sanitize: sanitize, textToHtml: textToHtml, handler: handler };
    // Back-compat for any inline code still calling the old global.
    w.sanitizePastedHtml = sanitize;
})(window);
