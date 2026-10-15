@extends('layouts.site')

@section('title', 'Journal | SOV SUMMIT')
@section('meta_description', 'Field notes and insights from the SOV SUMMIT coordination desk — writing on international programmes, protocol, logistics, and the craft of event coordination.')
@section('canonical', url()->current())

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Blog", "name": "SOV SUMMIT Journal", "url": "https://sov-summit.com/blog", "publisher": {"@type": "Organization", "name": "SOV SUMMIT", "logo": "https://sov-summit.com/assets/img/logo.webp"}}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Journal", "item": "https://sov-summit.com/blog/"}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <span>Journal</span></div>
@endsection

@section('content')

<section class="blog-hero"><div class="container">
  <div class="section-head wide">
    <p class="brandline">THE JOURNAL</p>
    <h1>Insights &amp; field notes.</h1>
    <p class="lede">Occasional writing on international programmes, protocol, logistics, and the craft of coordination &mdash; drawn from the SOV SUMMIT desk.</p>
  </div>
</div></section>

@if ($featured)
<section class="blog-featured-wrap"><div class="container">
  @php $fc = $featured->cover_url; @endphp
  <a class="blog-featured" href="{{ route('blog.show', $featured) }}" aria-label="{{ $featured->title }}">
    <div class="blog-featured__media {{ $fc ? '' : 'blog-featured__media--empty' }}">
      @if ($fc)
        <img src="{{ $fc }}" alt="" aria-hidden="true" loading="lazy" width="1200" height="900">
      @endif
      <div class="blog-featured__overlay" aria-hidden="true"></div>
      <span class="blog-featured__tag">Featured</span>
    </div>
    <div class="blog-featured__body">
      @if ($featured->category)
        <span class="blog-featured__category">{{ $featured->category }}</span>
      @endif
      <h2 class="blog-featured__title">{{ $featured->title }}</h2>
      @if ($featured->summary)
        <p class="blog-featured__summary">{{ $featured->summary }}</p>
      @endif
      <ul class="blog-featured__meta" role="list">
        @if ($featured->published_at)
          <li><span class="blog-featured__meta-label">Published</span><span class="blog-featured__meta-value">{{ $featured->published_at->format('d F Y') }}</span></li>
        @endif
        @if ($featured->author)
          <li><span class="blog-featured__meta-label">Author</span><span class="blog-featured__meta-value">{{ $featured->author }}</span></li>
        @endif
        @if ($featured->reading_time)
          <li><span class="blog-featured__meta-label">Reading</span><span class="blog-featured__meta-value">{{ $featured->reading_time }} min</span></li>
        @endif
      </ul>
      <span class="blog-featured__cta">Read the piece <span aria-hidden="true">&rarr;</span></span>
    </div>
  </a>
</div></section>
@endif

@if ($posts->isNotEmpty())
<section class="blog-list"><div class="container">
  <header class="blog-list__head">
    <span class="eyebrow">Latest</span>
    <h2>Recent writing.</h2>
  </header>
  <div class="blog-grid">
    @foreach ($posts as $blog)
      @include('partials.blog-card', ['blog' => $blog])
    @endforeach
  </div>
  @if ($posts->hasPages())
    <div class="blog-list__pagination">
      {{ $posts->links() }}
    </div>
  @endif
</div></section>
@endif

@if (!$featured && $posts->isEmpty())
<section class=""><div class="container">
  <p class="lede" style="text-align:center;">No posts published yet. Check back soon.</p>
</div></section>
@endif

@include('partials.cta-band')
@endsection
