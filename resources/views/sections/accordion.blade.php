@php $d = $section->data; $items = $d['items'] ?? []; @endphp
<section class="cs cs--accordion faq">
    <div class="container">
        @if (!empty($d['eyebrow']) || !empty($d['heading']) || !empty($d['lede']))
            <header class="section-head wide">
                @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
                @if (!empty($d['heading']))<h2>{{ $d['heading'] }}</h2>@endif
                @if (!empty($d['lede']))<p class="lede">{{ $d['lede'] }}</p>@endif
            </header>
        @endif
        <div class="faq-list">
            @foreach ($items as $item)
                <details class="faq-item">
                    <summary>{{ $item['q'] ?? '' }}</summary>
                    <div class="prose">{!! $item['a'] ?? '' !!}</div>
                </details>
            @endforeach
        </div>
    </div>
</section>
