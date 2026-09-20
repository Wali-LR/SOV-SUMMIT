@extends('layouts.site')

@section('title', ($blog->seo_title ?: $blog->title) . ' | SOV SUMMIT')
@section('meta_description', $blog->summary ?: 'An insight from the SOV SUMMIT coordination desk.')
@section('canonical', url()->current())
@section('og_image', $blog->cover_url ?: 'https://sov-summit.com/assets/img/logo.webp')

@push('head')
@if ($blog->seo_keywords)
<meta name="keywords" content="{{ $blog->seo_keywords }}" />
@endif
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $blog->seo_title ?: $blog->title,
    'description' => $blog->summary,
    'image' => $blog->cover_url,
    'datePublished' => optional($blog->published_at)->toIso8601String(),
    'author' => $blog->author ? ['@type' => 'Person', 'name' => $blog->author] : null,
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'SOV SUMMIT',
        'logo' => ['@type' => 'ImageObject', 'url' => 'https://sov-summit.com/assets/img/logo.webp'],
    ],
    'mainEntityOfPage' => url()->current(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<article class="blog-detail">
  <header class="blog-detail__hero {{ $blog->cover_url ? 'blog-detail__hero--photo' : 'blog-detail__hero--empty' }}">
    @if ($blog->cover_url)
      <img class="blog-detail__image" src="{{ $blog->cover_url }}" alt="" aria-hidden="true" width="1600" height="900">
    @endif
    <div class="blog-detail__hero-overlay" aria-hidden="true"></div>
    <div class="container blog-detail__hero-inner">
      <nav class="blog-detail__crumbs" aria-label="Breadcrumb">
        <a href="/">Home</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('blog.index') }}">Journal</a>
        <span aria-hidden="true">/</span>
        <span class="blog-detail__crumbs-current">{{ $blog->title }}</span>
      </nav>
      <p class="blog-detail__eyebrow">{{ $blog->category ?: 'Insight' }}</p>
      <h1>{{ $blog->title }}</h1>
      @if ($blog->summary)
        <p class="blog-detail__standfirst">{{ $blog->summary }}</p>
      @endif
      <ul class="blog-detail__meta" role="list">
        @if ($blog->published_at)
          <li><span class="blog-detail__meta-label">Published</span><span class="blog-detail__meta-value">{{ $blog->published_at->format('d F Y') }}</span></li>
        @endif
        @if ($blog->author)
          <li><span class="blog-detail__meta-label">Author</span><span class="blog-detail__meta-value">{{ $blog->author }}</span></li>
        @endif
        @if ($blog->reading_time)
          <li><span class="blog-detail__meta-label">Reading</span><span class="blog-detail__meta-value">{{ $blog->reading_time }} min</span></li>
        @endif
      </ul>
    </div>
  </header>

  <section class="blog-detail__body"><div class="container">
    <div class="prose blog-detail__prose">
      @if ($blog->description)
        {!! $blog->description !!}
      @endif
    </div>
  </div></section>

  <x-content-sections :model="$blog" />

  @if ($related->isNotEmpty())
    <section class="blog-related"><div class="container">
      <header class="blog-list__head">
        <span class="eyebrow">More insights</span>
        <h2>Continue reading.</h2>
      </header>
      <div class="blog-grid">
        @foreach ($related as $r)
          @include('partials.blog-card', ['blog' => $r])
        @endforeach
      </div>
    </div></section>
  @endif
</article>

@include('partials.cta-band', ['cta' => 'Discuss Your Programme'])
@endsection
