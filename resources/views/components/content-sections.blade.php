@props(['model'])
@php
    $sections = $model->publishedSections()->get();
    $isEditor = auth()->check();
    $sectionableType = get_class($model);
    $sectionableId = $model->getKey();
    $pageType = method_exists($model, 'contentSectionPageType') ? $model->contentSectionPageType() : 'default';
@endphp

@if ($sections->isNotEmpty() || $isEditor)
<div class="cs-root"
     data-sectionable-type="{{ $sectionableType }}"
     data-sectionable-id="{{ $sectionableId }}"
     data-page-type="{{ $pageType }}"
     @if ($isEditor) data-editor="1" @endif>
    <div class="cs-list">
        @foreach ($sections as $section)
            @include('components.content-section-wrapper', ['section' => $section])
        @endforeach
    </div>

    @if ($isEditor)
        @include('admin.sections._toolbar')
    @endif
</div>
@endif

@if ($isEditor)
    @push('head')
        <link rel="stylesheet" href="{{ asset('assets/css/content-sections-admin.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script src="{{ asset('assets/js/content-sections-admin.js') }}"></script>
    @endpush
@endif
