@php $d = $section->data; @endphp
<section class="cs cs--quote">
    <div class="container cs__inner">
        @if (!empty($d['eyebrow']))
            <span class="eyebrow cs__quote-eyebrow">{{ $d['eyebrow'] }}</span>
        @endif
        <blockquote class="cs__quote">
            <p>{{ $d['body'] ?? '' }}</p>
            @if (!empty($d['author']) || !empty($d['role']))
                <footer class="cs__cite">
                    @if (!empty($d['author']))<span class="cs__cite-author">{{ $d['author'] }}</span>@endif
                    @if (!empty($d['role']))<span class="cs__cite-role">{{ $d['role'] }}</span>@endif
                </footer>
            @endif
        </blockquote>
    </div>
</section>
