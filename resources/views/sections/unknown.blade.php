@php $isEditor = auth()->check(); @endphp
@if ($isEditor)
<section class="cs cs--unknown">
    <div class="container cs__inner">
        <p class="cs__unknown">Unknown section type: <code>{{ $section->type }}</code></p>
    </div>
</section>
@endif
