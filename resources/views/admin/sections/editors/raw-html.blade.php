@include('admin.sections.editors._partials')
@php $d = $section->data ?? []; @endphp
<div class="cs-adm-field">
    <label>HTML</label>
    <textarea data-field="html" rows="16" class="cs-adm-mono">{{ $d['html'] ?? '' }}</textarea>
    <p class="cs-adm-hint">Advanced. Anything you paste here is rendered verbatim on the public page.</p>
</div>
