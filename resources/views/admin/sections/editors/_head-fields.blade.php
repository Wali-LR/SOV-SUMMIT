@php $d = $section->data ?? []; @endphp
<div class="cs-adm-field">
    <label>Eyebrow <span class="cs-adm-hint-inline">(small gold label, e.g. "The SOV Summit Method")</span></label>
    <input type="text" data-field="eyebrow" value="{{ $d['eyebrow'] ?? '' }}" placeholder="Optional short label">
</div>
<div class="cs-adm-field">
    <label>Heading</label>
    <input type="text" data-field="heading" value="{{ $d['heading'] ?? '' }}" placeholder="Section heading">
</div>
<div class="cs-adm-field">
    <label>Lede <span class="cs-adm-hint-inline">(short intro paragraph under the heading)</span></label>
    <textarea data-field="lede" rows="2" placeholder="Optional supporting sentence">{{ $d['lede'] ?? '' }}</textarea>
</div>
