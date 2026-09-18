@include('admin.sections.editors._partials')
@php $d = $section->data ?? []; @endphp
<div class="cs-adm-field">
    <label>Eyebrow</label>
    <input type="text" data-field="eyebrow" value="{{ $d['eyebrow'] ?? '' }}" placeholder="Optional short label">
</div>
<div class="cs-adm-field">
    <label>Heading</label>
    <input type="text" data-field="heading" value="{{ $d['heading'] ?? '' }}">
</div>
<div class="cs-adm-field">
    <label>Subheading</label>
    <textarea data-field="subheading" rows="2">{{ $d['subheading'] ?? '' }}</textarea>
</div>
<div class="cs-adm-field cs-adm-field--split">
    <div>
        <label>Button label</label>
        <input type="text" data-field="cta_label" value="{{ $d['cta_label'] ?? '' }}">
    </div>
    <div>
        <label>Button link</label>
        <input type="text" data-field="cta_href" value="{{ $d['cta_href'] ?? '' }}" placeholder="/contact">
    </div>
</div>
<div class="cs-adm-field">
    <label>Variant</label>
    <select data-field="variant">
        <option value="dark" @if(($d['variant'] ?? 'dark') === 'dark') selected @endif>Dark</option>
        <option value="light" @if(($d['variant'] ?? '') === 'light') selected @endif>Light</option>
    </select>
</div>
