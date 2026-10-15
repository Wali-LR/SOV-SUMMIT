@include('admin.sections.editors._partials')
@php $d = $section->data ?? []; @endphp
<div class="cs-adm-field">
    <label>Eyebrow</label>
    <input type="text" data-field="eyebrow" value="{{ $d['eyebrow'] ?? '' }}" placeholder="Short label above the heading">
</div>
<div class="cs-adm-field">
    <label>Heading</label>
    <input type="text" data-field="heading" value="{{ $d['heading'] ?? '' }}">
</div>
<div class="cs-adm-field">
    <label>Subheading</label>
    <textarea data-field="subheading" rows="3">{{ $d['subheading'] ?? '' }}</textarea>
</div>
<div class="cs-adm-field">
    <label>Background image</label>
    <div class="cs-adm-image" data-image-field data-field="image">
        <input type="hidden" data-value value="{{ $d['image'] ?? '' }}">
        <div class="cs-adm-image__preview">
            @if (!empty($d['image']))<img src="{{ $d['image'] }}" alt="">@else<span>No image</span>@endif
        </div>
        <div class="cs-adm-image__actions">
            <input type="file" accept="image/*" data-image-input>
            @if (!empty($d['image']))<button type="button" data-image-clear class="cs-adm-btn cs-adm-btn--ghost cs-adm-btn--sm">Remove</button>@endif
        </div>
    </div>
</div>
<div class="cs-adm-field cs-adm-field--split">
    <div>
        <label>Button label</label>
        <input type="text" data-field="cta_label" value="{{ $d['cta_label'] ?? '' }}">
    </div>
    <div>
        <label>Button link</label>
        <input type="text" data-field="cta_href" value="{{ $d['cta_href'] ?? '' }}">
    </div>
</div>
