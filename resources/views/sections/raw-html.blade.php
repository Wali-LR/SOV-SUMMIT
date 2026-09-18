@php $d = $section->data; @endphp
<section class="cs cs--raw-html">
    <div class="container cs__inner">
        {!! $d['html'] ?? '' !!}
    </div>
</section>
