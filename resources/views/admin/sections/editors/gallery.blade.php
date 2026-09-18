@include('admin.sections.editors._partials')
@include('admin.sections.editors._head-fields')
@php $d = $section->data ?? []; $images = $d['images'] ?? []; @endphp
<div class="cs-adm-field">
    <label>Columns</label>
    <select data-field="columns" data-type="number">
        @foreach ([2,3,4] as $c)
            <option value="{{ $c }}" @if((int)($d['columns'] ?? 3) === $c) selected @endif>{{ $c }} columns</option>
        @endforeach
    </select>
</div>
<div class="cs-adm-repeater" data-repeater="images">
    <label class="cs-adm-repeater__label">Images</label>
    <div class="cs-adm-repeater__list" data-repeater-list>
        @foreach ($images as $img)
            <div class="cs-adm-repeater__row" data-repeater-row>
                <button type="button" class="cs-adm-repeater__remove" data-repeater-remove aria-label="Remove">&times;</button>
                <div class="cs-adm-field">
                    <label>Image</label>
                    <div class="cs-adm-image" data-image-field data-field="src">
                        <input type="hidden" data-value value="{{ $img['src'] ?? '' }}">
                        <div class="cs-adm-image__preview">
                            @if (!empty($img['src']))<img src="{{ $img['src'] }}" alt="">@else<span>No image</span>@endif
                        </div>
                        <div class="cs-adm-image__actions"><input type="file" accept="image/*" data-image-input></div>
                    </div>
                </div>
                <div class="cs-adm-field"><label>Alt text</label><input type="text" data-field="alt" value="{{ $img['alt'] ?? '' }}"></div>
                <div class="cs-adm-field"><label>Caption</label><input type="text" data-field="caption" value="{{ $img['caption'] ?? '' }}"></div>
            </div>
        @endforeach
    </div>
    <button type="button" class="cs-adm-btn cs-adm-btn--ghost cs-adm-btn--sm" data-repeater-add>+ Add image</button>
    <template data-repeater-template>
        <div class="cs-adm-repeater__row" data-repeater-row>
            <button type="button" class="cs-adm-repeater__remove" data-repeater-remove aria-label="Remove">&times;</button>
            <div class="cs-adm-field">
                <label>Image</label>
                <div class="cs-adm-image" data-image-field data-field="src">
                    <input type="hidden" data-value value="">
                    <div class="cs-adm-image__preview"><span>No image</span></div>
                    <div class="cs-adm-image__actions"><input type="file" accept="image/*" data-image-input></div>
                </div>
            </div>
            <div class="cs-adm-field"><label>Alt text</label><input type="text" data-field="alt" value=""></div>
            <div class="cs-adm-field"><label>Caption</label><input type="text" data-field="caption" value=""></div>
        </div>
    </template>
</div>
