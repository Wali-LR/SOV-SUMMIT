@php
    $d = $section->data;
    $variant = ($d['variant'] ?? 'dark') === 'light' ? 'cs--cta-light' : 'cs--cta-dark';
@endphp
<section class="cs cs--cta-band {{ $variant }}">
    <div class="container cs__inner cs__cta">
        <div class="cs__cta-text">
            @if (!empty($d['eyebrow']))<span class="eyebrow">{{ $d['eyebrow'] }}</span>@endif
            @if (!empty($d['heading']))<h2 class="cs__h2">{{ $d['heading'] }}</h2>@endif
            @if (!empty($d['subheading']))<p class="lede">{{ $d['subheading'] }}</p>@endif
        </div>
        @if (!empty($d['cta_label']))
            <a class="btn btn-primary" href="{{ $d['cta_href'] ?: '#' }}">
                <span class="btn-label">{{ $d['cta_label'] }}</span>
                <svg class="btn-arrow" width="14" height="14" viewBox="0 0 14 14" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        @endif
    </div>
</section>
