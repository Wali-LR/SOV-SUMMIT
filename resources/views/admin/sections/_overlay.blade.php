<div class="cs-adm-overlay">
    <button type="button" class="cs-adm-btn cs-adm-btn--icon cs-adm-move-handle" data-action="move" title="Drag to reorder">
        <svg width="14" height="14" viewBox="0 0 20 20" aria-hidden="true"><path d="M7 5h2v2H7zM11 5h2v2h-2zM7 9h2v2H7zM11 9h2v2h-2zM7 13h2v2H7zM11 13h2v2h-2z" fill="currentColor"/></svg>
    </button>
    <button type="button" class="cs-adm-btn cs-adm-btn--icon" data-action="edit" title="Edit section">
        <svg width="14" height="14" viewBox="0 0 20 20" aria-hidden="true"><path d="M4 14.5V17h2.5l8-8-2.5-2.5-8 8zM14.7 5.3l1 1a1 1 0 010 1.4l-1.3 1.3-2.4-2.4 1.3-1.3a1 1 0 011.4 0z" fill="currentColor"/></svg>
    </button>
    <button type="button" class="cs-adm-btn cs-adm-btn--icon" data-action="visibility" title="{{ $section->is_published ? 'Hide from public' : 'Show on public' }}">
        <svg width="14" height="14" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 4C5 4 2 10 2 10s3 6 8 6 8-6 8-6-3-6-8-6zm0 9a3 3 0 110-6 3 3 0 010 6z" fill="currentColor"/></svg>
    </button>
    <button type="button" class="cs-adm-btn cs-adm-btn--icon cs-adm-btn--danger" data-action="delete" title="Delete section">
        <svg width="14" height="14" viewBox="0 0 20 20" aria-hidden="true"><path d="M6 4h8v2H6zM5 7h10l-1 10H6L5 7zm3-6h4v2H8z" fill="currentColor"/></svg>
    </button>
</div>
