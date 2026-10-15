@extends('layouts.site')

@php
    $seoTitleBase = $page->seo_title ?: $page->title;
    $seoTitleFull = $seoTitleBase . ' | SOV SUMMIT';
    $seoSummary   = trim((string) ($page->seo_description ?: $page->summary ?: 'A page from SOV SUMMIT.'));
    $canonical    = url()->current();
    $heroUrl      = $page->hero_url ?: asset('assets/img/logo.webp');
    $heroAlt      = $page->hero_url ? ($page->title . ' — page image') : 'SOV SUMMIT';

    $keywordsList = collect(explode(',', (string) $page->seo_keywords))
        ->map(fn ($k) => trim($k))->filter()->values();

    $breadcrumbLd = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $page->title, 'item' => $canonical],
        ],
    ];
@endphp

@section('title', $seoTitleFull)
@section('meta_description', $seoSummary)
@section('canonical', $canonical)
@section('og_image', $heroUrl)

@push('head')
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1" />
@if ($keywordsList->isNotEmpty())
<meta name="keywords" content="{{ $keywordsList->implode(', ') }}" />
@endif

<meta property="og:type" content="website" />
<meta property="og:site_name" content="SOV SUMMIT" />
<meta property="og:locale" content="en_GB" />
<meta property="og:image:alt" content="{{ $heroAlt }}" />

<meta name="twitter:title" content="{{ $seoTitleBase }}" />
<meta name="twitter:description" content="{{ $seoSummary }}" />
<meta name="twitter:image" content="{{ $heroUrl }}" />
<meta name="twitter:image:alt" content="{{ $heroAlt }}" />

<script type="application/ld+json">{!! json_encode($breadcrumbLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container">
  <a href="/">Home</a> / <span>{{ $page->title }}</span>
</div>
@endsection

@section('content')
<article>
  <section class="service-hero"><div class="container">
    <div class="service-hero__grid">
      <div class="service-hero__body">
        @if ($page->eyebrow)
          <p class="brandline">{{ $page->eyebrow }}</p>
        @endif
        <h1>{{ $page->title }}</h1>
        @if ($page->summary)
          <p class="lede">{{ $page->summary }}</p>
        @endif
      </div>
      @if ($page->hero_url)
        <figure class="service-hero__figure">
          <img src="{{ $page->hero_url }}" alt="{{ $heroAlt }}" width="900" height="1100" loading="eager" fetchpriority="high">
        </figure>
      @endif
    </div>
  </div></section>

  @if ($page->description)
    <section class="service-detail__body"><div class="container">
      <div class="prose service-detail__prose">
        {!! $page->description !!}
      </div>
    </div></section>
  @endif

  <x-content-sections :model="$page" />
</article>
@endsection
