@php $d = $section->data; @endphp
<section class="cs cs--hero-banner {{ !empty($d['image']) ? 'cs--hero-has-image' : '' }}"
         @if (!empty($d['image'])) style="--cs-hero-image: url('{{ $d['image'] }}');" @endif>
    <div class="cs__hero-overlay" aria-hidden="true"></div>
    <div class="container cs__inner cs__hero">
        @if (!empty($d['eyebrow']))<p class="cs__eyebrow">{{ $d['eyebrow'] }}</p>@endif
        @if (!empty($d['heading']))<h2 class="cs__hero-heading">{{ $d['heading'] }}</h2>@endif
        @if (!empty($d['subheading']))<p class="cs__hero-sub">{{ $d['subheading'] }}</p>@endif
        @if (!empty($d['cta_label']))
            <a class="cs__cta-btn cs__cta-btn--light" href="{{ $d['cta_href'] ?: '#' }}">
                {{ $d['cta_label'] }}
                <svg width="12" height="12" viewBox="0 0 14 14" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        @endif
    </div>
</section>
