@include('admin.sections.editors._partials')
@include('admin.sections.editors._head-fields')
@php $d = $section->data ?? []; @endphp
<div class="cs-adm-field">
    <label>Image</label>
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
<div class="cs-adm-field">
    <label>Image alt text</label>
    <input type="text" data-field="image_alt" value="{{ $d['image_alt'] ?? '' }}">
</div>
<div class="cs-adm-field">
    <label>Image position</label>
    <select data-field="align">
        <option value="left" @if(($d['align'] ?? 'left')==='left') selected @endif>Image on left</option>
        <option value="right" @if(($d['align'] ?? '')==='right') selected @endif>Image on right</option>
    </select>
</div>
<div class="cs-adm-field">
    <label>Body</label>
    <textarea data-field="body" data-rich="1" rows="8">{{ $d['body'] ?? '' }}</textarea>
</div>
