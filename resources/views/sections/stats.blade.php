@php $d = $section->data; $items = $d['items'] ?? []; @endphp
<section class="cs cs--stats">
    <div class="container cs__inner">
        @if (!empty($d['eyebrow']) || !empty($d['heading']) || !empty($d['lede']))
            <header class="cs__head">
                @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
                @if (!empty($d['heading']))<h2 class="cs__h2">{{ $d['heading'] }}</h2>@endif
                @if (!empty($d['lede']))<p class="lede">{{ $d['lede'] }}</p>@endif
            </header>
        @endif
        <ul class="cs__stats" role="list">
            @foreach ($items as $item)
                <li>
                    <span class="cs__stats-number">{{ $item['number'] ?? '' }}</span>
                    <span class="cs__stats-label">{{ $item['label'] ?? '' }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</section>
