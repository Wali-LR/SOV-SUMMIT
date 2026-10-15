@php $isEditor = auth()->check(); @endphp
<div class="cs-wrap {{ $section->is_published ? '' : 'cs-wrap--hidden' }}"
     data-section-id="{{ $section->id }}"
     data-section-type="{{ $section->type }}"
     data-section-short="{{ $section->short }}">
    @include($section->template(), ['section' => $section])
    @if ($isEditor)
        @include('admin.sections._overlay', ['section' => $section])
    @endif
</div>
