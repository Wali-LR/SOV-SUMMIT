@php $d = $section->data; @endphp
<section class="cs cs--rich-text">
    <div class="container cs__inner">
        @if (!empty($d['eyebrow']) || !empty($d['heading']) || !empty($d['lede']))
            <header class="cs__head">
                @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
                @if (!empty($d['heading']))<h2 class="cs__h2">{{ $d['heading'] }}</h2>@endif
                @if (!empty($d['lede']))<p class="lede">{{ $d['lede'] }}</p>@endif
            </header>
        @endif
        <div class="cs__body prose">{!! $d['body'] ?? '' !!}</div>
    </div>
</section>
