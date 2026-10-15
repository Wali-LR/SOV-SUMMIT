@include('admin.sections.editors._partials')
@include('admin.sections.editors._head-fields')
@php $d = $section->data ?? []; @endphp
<div class="cs-adm-field">
    <label>Body</label>
    <textarea data-field="body" data-rich="1" rows="10">{{ $d['body'] ?? '' }}</textarea>
</div>
