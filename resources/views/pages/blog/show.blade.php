@extends('layouts.site')

@php
    // --------- SEO / social precomputed values ---------
    $seoTitleBase  = $blog->seo_title ?: $blog->title;
    $seoTitleFull  = $seoTitleBase . ' | SOV SUMMIT';
    $seoSummary    = trim((string) ($blog->summary ?: 'An insight from the SOV SUMMIT coordination desk.'));
    $canonical     = url()->current();
    $coverUrl      = $blog->cover_url ?: asset('assets/img/logo.webp');
    $coverAlt      = $blog->cover_url ? ($blog->title . ' — cover image') : 'SOV SUMMIT';

    $publishedIso  = optional($blog->published_at)->toIso8601String();
    $modifiedIso   = optional($blog->updated_at ?: $blog->published_at)->toIso8601String();

    $plainBody     = trim(preg_replace('/\s+/', ' ', strip_tags((string) $blog->description)));
    $wordCount     = $plainBody === '' ? 0 : str_word_count($plainBody);

    $keywordsList  = collect(explode(',', (string) $blog->seo_keywords))
        ->map(fn ($k) => trim($k))
        ->filter()
        ->values();

    $authorName    = $blog->author ?: 'The SOV SUMMIT Desk';
    $category      = $blog->category ?: 'Insight';
    $orgName       = 'SOV SUMMIT';
    $orgLogoUrl    = 'https://sov-summit.com/assets/img/logo.webp';
    $siteBase      = rtrim(config('app.url', 'https://sov-summit.com'), '/');

    // --------- Structured data ---------
    $blogPostingLd = array_filter([
        '@context'         => 'https://schema.org',
        '@type'            => 'BlogPosting',
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
        'headline'         => \Illuminate\Support\Str::limit($seoTitleBase, 110, ''),
        'name'             => $blog->title,
        'description'      => $seoSummary,
        'articleSection'   => $category,
        'keywords'         => $keywordsList->isNotEmpty() ? $keywordsList->implode(', ') : null,
        'wordCount'        => $wordCount > 0 ? $wordCount : null,
        'inLanguage'       => 'en',
        'url'              => $canonical,
        'isFamilyFriendly' => true,
        'datePublished'    => $publishedIso,
        'dateModified'     => $modifiedIso,
        'image'            => $blog->cover_url ? [
            '@type'  => 'ImageObject',
            'url'    => $blog->cover_url,
            'width'  => 1600,
            'height' => 900,
        ] : null,
        'author' => [
            '@type' => 'Person',
            'name'  => $authorName,
            'url'   => $siteBase . '/about',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name'  => $orgName,
            'url'   => $siteBase,
            'logo'  => [
                '@type'  => 'ImageObject',
                'url'    => $orgLogoUrl,
                'width'  => 512,
                'height' => 512,
            ],
        ],
    ], fn ($v) => !is_null($v));

    $breadcrumbLd = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',    'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Journal', 'item' => route('blog.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $blog->title, 'item' => $canonical],
        ],
    ];
@endphp

@section('title', $seoTitleFull)
@section('meta_description', $seoSummary)
@section('canonical', $canonical)
@section('og_image', $coverUrl)

@push('head')
{{-- Discoverability --}}
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1" />
<meta name="googlebot" content="index, follow, max-image-preview:large, max-snippet:-1" />
<meta name="author" content="{{ $authorName }}" />
@if ($keywordsList->isNotEmpty())
<meta name="keywords" content="{{ $keywordsList->implode(', ') }}" />
@endif

{{-- Open Graph (article) --}}
<meta property="og:type" content="article" />
<meta property="og:site_name" content="{{ $orgName }}" />
<meta property="og:locale" content="en_GB" />
<meta property="og:image:alt" content="{{ $coverAlt }}" />
@if ($blog->cover_url)
<meta property="og:image:width" content="1600" />
<meta property="og:image:height" content="900" />
@endif
@if ($publishedIso)
<meta property="article:published_time" content="{{ $publishedIso }}" />
@endif
@if ($modifiedIso)
<meta property="article:modified_time" content="{{ $modifiedIso }}" />
@endif
<meta property="article:author" content="{{ $authorName }}" />
<meta property="article:section" content="{{ $category }}" />
@foreach ($keywordsList as $kw)
<meta property="article:tag" content="{{ $kw }}" />
@endforeach

{{-- Twitter / X --}}
<meta name="twitter:title" content="{{ $seoTitleBase }}" />
<meta name="twitter:description" content="{{ $seoSummary }}" />
<meta name="twitter:image" content="{{ $coverUrl }}" />
<meta name="twitter:image:alt" content="{{ $coverAlt }}" />
@if ($blog->reading_time)
<meta name="twitter:label1" content="Reading time" />
<meta name="twitter:data1" content="{{ $blog->reading_time }} min read" />
@endif
<meta name="twitter:label2" content="Written by" />
<meta name="twitter:data2" content="{{ $authorName }}" />

{{-- Structured data --}}
<script type="application/ld+json">{!! json_encode($blogPostingLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<article class="blog-detail" itemscope itemtype="https://schema.org/BlogPosting">
  <meta itemprop="mainEntityOfPage" content="{{ $canonical }}">

  <header class="blog-detail__hero {{ $blog->cover_url ? 'blog-detail__hero--photo' : 'blog-detail__hero--empty' }}">
    @if ($blog->cover_url)
      <img class="blog-detail__image" src="{{ $blog->cover_url }}" alt="{{ $coverAlt }}"
           width="1600" height="900" fetchpriority="high" itemprop="image">
    @endif
    <div class="blog-detail__hero-overlay" aria-hidden="true"></div>
    <div class="container blog-detail__hero-inner">
      <nav class="blog-detail__crumbs" aria-label="Breadcrumb">
        <a href="/">Home</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('blog.index') }}">Journal</a>
        <span aria-hidden="true">/</span>
        <span class="blog-detail__crumbs-current" aria-current="page">{{ $blog->title }}</span>
      </nav>
      <p class="blog-detail__eyebrow" itemprop="articleSection">{{ $category }}</p>
      <h1 itemprop="headline">{{ $blog->title }}</h1>
      @if ($blog->summary)
        <p class="blog-detail__standfirst" itemprop="description">{{ $blog->summary }}</p>
      @endif
      <ul class="blog-detail__meta" role="list">
        @if ($blog->published_at)
          <li>
            <span class="blog-detail__meta-label">Published</span>
            <span class="blog-detail__meta-value">
              <time datetime="{{ $publishedIso }}" itemprop="datePublished">{{ $blog->published_at->format('d F Y') }}</time>
            </span>
          </li>
        @endif
        <li itemprop="author" itemscope itemtype="https://schema.org/Person">
          <span class="blog-detail__meta-label">Author</span>
          <span class="blog-detail__meta-value" itemprop="name">{{ $authorName }}</span>
        </li>
        @if ($blog->reading_time)
          <li>
            <span class="blog-detail__meta-label">Reading</span>
            <span class="blog-detail__meta-value">{{ $blog->reading_time }} min</span>
          </li>
        @endif
      </ul>
    </div>
  </header>

  <section class="blog-detail__body"><div class="container">
    <div class="prose blog-detail__prose" itemprop="articleBody">
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
