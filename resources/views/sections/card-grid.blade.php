@php
    $d = $section->data;
    $cols = (int) ($d['columns'] ?? 3);
    $cols = in_array($cols, [2, 3, 4], true) ? $cols : 3;
    $cards = $d['cards'] ?? [];
@endphp
<section class="cs cs--card-grid approach">
    <div class="container">
        @if (!empty($d['eyebrow']) || !empty($d['heading']) || !empty($d['lede']))
            <header class="approach__head">
                @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
                @if (!empty($d['heading']))<h2>{{ $d['heading'] }}</h2>@endif
                @if (!empty($d['lede']))<p class="lede">{{ $d['lede'] }}</p>@endif
            </header>
        @endif

        <div class="approach__grid cs__approach-grid cs__approach-grid--cols-{{ $cols }}">
            @foreach ($cards as $i => $card)
                <article class="approach__card">
                    @if (!empty($card['image']))
                        <img class="approach__image" src="{{ $card['image'] }}" alt="" aria-hidden="true" loading="lazy">
                    @else
                        <div class="approach__image approach__image--placeholder"></div>
                    @endif
                    <div class="approach__overlay" aria-hidden="true"></div>
                    <div class="approach__body">
                        <span class="approach__num" aria-hidden="true">{{ str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        @if (!empty($card['title']))<h3>{{ $card['title'] }}</h3>@endif
                        @if (!empty($card['description']))<p>{{ $card['description'] }}</p>@endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
