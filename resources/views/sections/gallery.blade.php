@php
    $d = $section->data;
    $cols = (int) ($d['columns'] ?? 3);
    $cols = in_array($cols, [2, 3, 4], true) ? $cols : 3;
    $images = $d['images'] ?? [];
    $galleryId = 'cs-gal-' . $section->id;
@endphp
<section class="cs cs--gallery">
    <div class="container cs__inner">
        @if (!empty($d['eyebrow']) || !empty($d['heading']) || !empty($d['lede']))
            <header class="cs__head">
                @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
                @if (!empty($d['heading']))<h2 class="cs__h2">{{ $d['heading'] }}</h2>@endif
                @if (!empty($d['lede']))<p class="lede">{{ $d['lede'] }}</p>@endif
            </header>
        @endif
        <div class="cs__gallery cs__grid--cols-{{ $cols }}" data-lightbox-gallery="{{ $galleryId }}">
            @foreach ($images as $img)
                @continue(empty($img['src']))
                <figure class="cs__gallery-item">
                    <a href="{{ $img['src'] }}"
                       class="cs__gallery-link glightbox"
                       data-gallery="{{ $galleryId }}"
                       data-description="{{ $img['caption'] ?? '' }}"
                       aria-label="View image{{ !empty($img['caption']) ? ': ' . $img['caption'] : '' }}">
                        <img src="{{ $img['src'] }}" alt="{{ $img['alt'] ?? '' }}" loading="lazy">
                    </a>
                    @if (!empty($img['caption']))
                        <figcaption>{{ $img['caption'] }}</figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
    </div>
</section>
