@include('admin.sections.editors._partials')
@include('admin.sections.editors._head-fields')
@php $d = $section->data ?? []; $cards = $d['cards'] ?? []; @endphp
<div class="cs-adm-field">
    <label>Columns</label>
    <select data-field="columns" data-type="number">
        @foreach ([2,3,4] as $c)
            <option value="{{ $c }}" @if((int)($d['columns'] ?? 3) === $c) selected @endif>{{ $c }} columns</option>
        @endforeach
    </select>
</div>
<div class="cs-adm-repeater" data-repeater="cards">
    <label class="cs-adm-repeater__label">Cards</label>
    <div class="cs-adm-repeater__list" data-repeater-list>
        @foreach ($cards as $i => $card)
            <div class="cs-adm-repeater__row" data-repeater-row>
                <button type="button" class="cs-adm-repeater__remove" data-repeater-remove aria-label="Remove card">&times;</button>
                <div class="cs-adm-field"><label>Title</label><input type="text" data-field="title" value="{{ $card['title'] ?? '' }}"></div>
                <div class="cs-adm-field"><label>Description</label><textarea data-field="description" rows="3">{{ $card['description'] ?? '' }}</textarea></div>
                <div class="cs-adm-field">
                    <label>Image</label>
                    <div class="cs-adm-image" data-image-field data-field="image">
                        <input type="hidden" data-value value="{{ $card['image'] ?? '' }}">
                        <div class="cs-adm-image__preview">
                            @if (!empty($card['image']))<img src="{{ $card['image'] }}" alt="">@else<span>No image</span>@endif
                        </div>
                        <div class="cs-adm-image__actions">
                            <input type="file" accept="image/*" data-image-input>
                            @if (!empty($card['image']))<button type="button" data-image-clear class="cs-adm-btn cs-adm-btn--ghost cs-adm-btn--sm">Remove</button>@endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="cs-adm-btn cs-adm-btn--ghost cs-adm-btn--sm" data-repeater-add>+ Add card</button>
    <template data-repeater-template>
        <div class="cs-adm-repeater__row" data-repeater-row>
            <button type="button" class="cs-adm-repeater__remove" data-repeater-remove aria-label="Remove card">&times;</button>
            <div class="cs-adm-field"><label>Title</label><input type="text" data-field="title" value=""></div>
            <div class="cs-adm-field"><label>Description</label><textarea data-field="description" rows="3"></textarea></div>
            <div class="cs-adm-field">
                <label>Image</label>
                <div class="cs-adm-image" data-image-field data-field="image">
                    <input type="hidden" data-value value="">
                    <div class="cs-adm-image__preview"><span>No image</span></div>
                    <div class="cs-adm-image__actions"><input type="file" accept="image/*" data-image-input></div>
                </div>
            </div>
        </div>
    </template>
</div>
