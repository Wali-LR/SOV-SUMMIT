@include('admin.sections.editors._partials')
@include('admin.sections.editors._head-fields')
@php $d = $section->data ?? []; $items = $d['items'] ?? []; @endphp
<div class="cs-adm-repeater" data-repeater="items">
    <label class="cs-adm-repeater__label">Stats</label>
    <div class="cs-adm-repeater__list" data-repeater-list>
        @foreach ($items as $item)
            <div class="cs-adm-repeater__row" data-repeater-row>
                <button type="button" class="cs-adm-repeater__remove" data-repeater-remove aria-label="Remove">&times;</button>
                <div class="cs-adm-field"><label>Number</label><input type="text" data-field="number" value="{{ $item['number'] ?? '' }}"></div>
                <div class="cs-adm-field"><label>Label</label><input type="text" data-field="label" value="{{ $item['label'] ?? '' }}"></div>
            </div>
        @endforeach
    </div>
    <button type="button" class="cs-adm-btn cs-adm-btn--ghost cs-adm-btn--sm" data-repeater-add>+ Add stat</button>
    <template data-repeater-template>
        <div class="cs-adm-repeater__row" data-repeater-row>
            <button type="button" class="cs-adm-repeater__remove" data-repeater-remove aria-label="Remove">&times;</button>
            <div class="cs-adm-field"><label>Number</label><input type="text" data-field="number" value=""></div>
            <div class="cs-adm-field"><label>Label</label><input type="text" data-field="label" value=""></div>
        </div>
    </template>
</div>
