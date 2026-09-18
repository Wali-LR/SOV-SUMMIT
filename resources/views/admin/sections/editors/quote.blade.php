@include('admin.sections.editors._partials')
@php $d = $section->data ?? []; @endphp
<div class="cs-adm-field">
    <label>Quote</label>
    <textarea data-field="body" rows="4">{{ $d['body'] ?? '' }}</textarea>
</div>
<div class="cs-adm-field">
    <label>Author</label>
    <input type="text" data-field="author" value="{{ $d['author'] ?? '' }}">
</div>
<div class="cs-adm-field">
    <label>Author role / title</label>
    <input type="text" data-field="role" value="{{ $d['role'] ?? '' }}">
</div>
