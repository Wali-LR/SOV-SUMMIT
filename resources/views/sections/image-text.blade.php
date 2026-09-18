@php
    $d = $section->data;
    $align = ($d['align'] ?? 'left') === 'right' ? 'cs--image-right' : 'cs--image-left';
@endphp
<section class="cs cs--image-text {{ $align }}">
    <div class="container cs__inner cs__grid-2">
        <figure class="cs__figure">
            @if (!empty($d['image']))
                <img src="{{ $d['image'] }}" alt="{{ $d['image_alt'] ?? '' }}">
            @else
                <div class="cs__figure-placeholder">Add an image</div>
            @endif
        </figure>
        <div class="cs__col">
            @if (!empty($d['eyebrow']) || !empty($d['heading']) || !empty($d['lede']))
                <header class="cs__head">
                    @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
                    @if (!empty($d['heading']))<h2 class="cs__h2">{{ $d['heading'] }}</h2>@endif
                    @if (!empty($d['lede']))<p class="lede">{{ $d['lede'] }}</p>@endif
                </header>
            @endif
            <div class="cs__body prose">{!! $d['body'] ?? '' !!}</div>
        </div>
    </div>
</section>
