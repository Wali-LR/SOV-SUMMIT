/*
 * SOV SUMMIT — client-side image compressor
 *
 * Contract:
 *   window.compressImage(file, options?) -> Promise<File>
 *
 * Options: { maxBytes = 400_000, maxDimension = 2400, mime = 'image/webp' }
 *
 * Behaviour:
 *   - Non-image files pass through unchanged.
 *   - Animated GIFs pass through unchanged (canvas would lose animation).
 *   - Otherwise: draws the image to a canvas, downscales to fit maxDimension,
 *     then iterates quality from ~0.9 down to ~0.4 until the encoded blob is
 *     under maxBytes. Returns a new File with the original name switched to
 *     .webp and the target mime type.
 *
 * Also exposes window.attachAutoCompress(input) — a small helper that intercepts
 * change events on <input type="file"> and swaps the selected file with a
 * compressed one, so any existing form submission sends the compressed version.
 */
(function () {
    'use strict';

    const DEFAULTS = {
        maxBytes: 400 * 1024,
        maxDimension: 2400,
        mime: 'image/webp',
        minQuality: 0.4,
        startQuality: 0.9,
        qualityStep: 0.1,
    };

    function isCompressibleImage(file) {
        if (!file || !(file instanceof Blob)) return false;
        if (!file.type || !file.type.startsWith('image/')) return false;
        if (file.type === 'image/gif') return false;
        if (file.type === 'image/svg+xml') return false;
        return true;
    }

    function loadImageBitmap(file) {
        if (window.createImageBitmap) {
            return window.createImageBitmap(file).catch(() => loadImageEl(file));
        }
        return loadImageEl(file);
    }

    function loadImageEl(file) {
        return new Promise((resolve, reject) => {
            const url = URL.createObjectURL(file);
            const img = new Image();
            img.onload = () => { URL.revokeObjectURL(url); resolve(img); };
            img.onerror = (e) => { URL.revokeObjectURL(url); reject(e); };
            img.src = url;
        });
    }

    function computeSize(w, h, maxDim) {
        if (w <= maxDim && h <= maxDim) return { w, h };
        if (w >= h) {
            return { w: maxDim, h: Math.round(h * (maxDim / w)) };
        }
        return { w: Math.round(w * (maxDim / h)), h: maxDim };
    }

    function drawToCanvas(source, width, height) {
        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.imageSmoothingEnabled = true;
        ctx.imageSmoothingQuality = 'high';
        ctx.drawImage(source, 0, 0, width, height);
        return canvas;
    }

    function canvasToBlob(canvas, mime, quality) {
        return new Promise((resolve) => {
            canvas.toBlob(blob => resolve(blob), mime, quality);
        });
    }

    function replaceExt(name, ext) {
        return (name || 'image').replace(/\.[^.]+$/, '') + '.' + ext;
    }

    async function compressImage(file, options) {
        const opts = Object.assign({}, DEFAULTS, options || {});

        if (!isCompressibleImage(file)) return file;
        if (file.size <= opts.maxBytes && file.type === opts.mime) return file;

        let source;
        try {
            source = await loadImageBitmap(file);
        } catch (_) {
            return file;
        }

        const originalW = source.width || source.naturalWidth;
        const originalH = source.height || source.naturalHeight;
        if (!originalW || !originalH) return file;

        let { w, h } = computeSize(originalW, originalH, opts.maxDimension);
        let canvas = drawToCanvas(source, w, h);

        // Iterate quality until under target size
        let quality = opts.startQuality;
        let blob = await canvasToBlob(canvas, opts.mime, quality);

        while (blob && blob.size > opts.maxBytes && quality > opts.minQuality) {
            quality = Math.max(opts.minQuality, quality - opts.qualityStep);
            blob = await canvasToBlob(canvas, opts.mime, quality);
        }

        // If still too big at min quality, shrink dimensions and try again
        let attempts = 0;
        while (blob && blob.size > opts.maxBytes && attempts < 3) {
            w = Math.round(w * 0.75);
            h = Math.round(h * 0.75);
            canvas = drawToCanvas(source, w, h);
            blob = await canvasToBlob(canvas, opts.mime, opts.minQuality + 0.1);
            attempts++;
        }

        // Release bitmap if we can
        if (source && typeof source.close === 'function') source.close();

        if (!blob) return file;

        // If compression made it larger than the original, keep the original.
        if (blob.size >= file.size && file.type === opts.mime) return file;

        const ext = opts.mime === 'image/webp' ? 'webp'
                  : opts.mime === 'image/jpeg' ? 'jpg'
                  : opts.mime === 'image/png' ? 'png'
                  : 'bin';

        return new File([blob], replaceExt(file.name, ext), {
            type: opts.mime,
            lastModified: Date.now(),
        });
    }

    function attachAutoCompress(input, options) {
        if (!input || input.dataset.compressBound === '1') return;
        input.dataset.compressBound = '1';
        input.addEventListener('change', async () => {
            if (!input.files || !input.files.length) return;
            const original = input.files[0];
            if (!isCompressibleImage(original)) return;
            input.disabled = true;
            const prevTitle = input.title;
            input.title = 'Compressing…';
            try {
                const compressed = await compressImage(original, options);
                if (compressed !== original && typeof DataTransfer !== 'undefined') {
                    const dt = new DataTransfer();
                    dt.items.add(compressed);
                    input.files = dt.files;
                }
            } catch (e) {
                console.warn('Image compress failed', e);
            } finally {
                input.disabled = false;
                input.title = prevTitle;
                input.dispatchEvent(new CustomEvent('imagecompressed', { detail: { file: input.files[0] } }));
            }
        }, { capture: true });
    }

    // Auto-bind on any input with data-auto-compress attribute
    function autoBindAll(root) {
        (root || document).querySelectorAll('input[type="file"][data-auto-compress]').forEach(el => attachAutoCompress(el));
    }

    document.addEventListener('DOMContentLoaded', () => autoBindAll());
    // Provide a MutationObserver so dynamically inserted inputs get bound too
    if (typeof MutationObserver !== 'undefined') {
        const mo = new MutationObserver(mutations => {
            for (const m of mutations) {
                m.addedNodes && m.addedNodes.forEach(n => {
                    if (n.nodeType === 1) {
                        if (n.matches && n.matches('input[type="file"][data-auto-compress]')) attachAutoCompress(n);
                        if (n.querySelectorAll) n.querySelectorAll('input[type="file"][data-auto-compress]').forEach(el => attachAutoCompress(el));
                    }
                });
            }
        });
        mo.observe(document.documentElement, { childList: true, subtree: true });
    }

    window.compressImage = compressImage;
    window.attachAutoCompress = attachAutoCompress;
})();
