<div class="cs-adm-toolbar" role="region" aria-label="Section editor">
    <div class="cs-adm-toolbar__badge">Editing mode</div>
    <button type="button" class="cs-adm-toolbar__btn cs-adm-add-btn">
        <svg width="14" height="14" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <span>Add section</span>
    </button>
</div>

<div class="cs-adm-picker" role="dialog" aria-modal="true" aria-label="Pick a section template" hidden>
    <div class="cs-adm-picker__backdrop" data-close></div>
    <div class="cs-adm-picker__panel">
        <header class="cs-adm-picker__head">
            <div>
                <h3>Add a section</h3>
                <p>Pick a template to insert after the existing content.</p>
            </div>
            <button type="button" class="cs-adm-icon-btn" aria-label="Close" data-close>&times;</button>
        </header>
        <div class="cs-adm-picker__grid" data-picker-grid>
            <div class="cs-adm-picker__loading">Loading templates…</div>
        </div>
    </div>
</div>

<div class="cs-adm-panel" role="dialog" aria-modal="true" aria-label="Edit section" hidden>
    <div class="cs-adm-panel__backdrop" data-close></div>
    <aside class="cs-adm-panel__panel">
        <header class="cs-adm-panel__head">
            <div class="cs-adm-panel__title">
                <span class="cs-adm-panel__eyebrow">Section</span>
                <h3 data-panel-title>Edit</h3>
            </div>
            <button type="button" class="cs-adm-icon-btn" aria-label="Close" data-close>&times;</button>
        </header>
        <div class="cs-adm-panel__body" data-panel-body>
            <div class="cs-adm-panel__loading">Loading…</div>
        </div>
        <footer class="cs-adm-panel__foot">
            <label class="cs-adm-toggle">
                <input type="checkbox" data-panel-published>
                <span>Published</span>
            </label>
            <div class="cs-adm-panel__actions">
                <button type="button" class="cs-adm-btn cs-adm-btn--ghost" data-close>Cancel</button>
                <button type="button" class="cs-adm-btn cs-adm-btn--primary" data-panel-save>Save section</button>
            </div>
        </footer>
    </aside>
</div>

<template id="cs-adm-overlay-tmpl">
    <div class="cs-adm-overlay">
        <button type="button" class="cs-adm-btn cs-adm-btn--icon" data-action="move" title="Drag to reorder">
            <svg width="14" height="14" viewBox="0 0 20 20" aria-hidden="true"><path d="M7 5h2v2H7zM11 5h2v2h-2zM7 9h2v2H7zM11 9h2v2h-2zM7 13h2v2H7zM11 13h2v2h-2z" fill="currentColor"/></svg>
        </button>
        <button type="button" class="cs-adm-btn cs-adm-btn--icon" data-action="edit" title="Edit section">
            <svg width="14" height="14" viewBox="0 0 20 20" aria-hidden="true"><path d="M4 14.5V17h2.5l8-8-2.5-2.5-8 8zM14.7 5.3l1 1a1 1 0 010 1.4l-1.3 1.3-2.4-2.4 1.3-1.3a1 1 0 011.4 0z" fill="currentColor"/></svg>
        </button>
        <button type="button" class="cs-adm-btn cs-adm-btn--icon" data-action="visibility" title="Toggle publish">
            <svg width="14" height="14" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 4C5 4 2 10 2 10s3 6 8 6 8-6 8-6-3-6-8-6zm0 9a3 3 0 110-6 3 3 0 010 6z" fill="currentColor"/></svg>
        </button>
        <button type="button" class="cs-adm-btn cs-adm-btn--icon cs-adm-btn--danger" data-action="delete" title="Delete section">
            <svg width="14" height="14" viewBox="0 0 20 20" aria-hidden="true"><path d="M6 4h8v2H6zM5 7h10l-1 10H6L5 7zm3-6h4v2H8z" fill="currentColor"/></svg>
        </button>
    </div>
</template>
