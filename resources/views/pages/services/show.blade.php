@extends('layouts.site')

@php
    $seoTitleBase = $service->seo_title ?: $service->title;
    $seoTitleFull = $seoTitleBase . ' | SOV SUMMIT';
    $seoSummary   = trim((string) ($service->seo_description ?: $service->summary ?: 'A service coordinated by SOV SUMMIT.'));
    $canonical    = url()->current();
    $heroUrl      = $service->hero_url ?: asset('assets/img/logo.webp');
    $heroAlt      = $service->hero_url ? ($service->title . ' — service image') : 'SOV SUMMIT';

    $keywordsList = collect(explode(',', (string) $service->seo_keywords))
        ->map(fn ($k) => trim($k))->filter()->values();

    $orgName    = 'SOV SUMMIT';
    $orgLogoUrl = 'https://sov-summit.com/assets/img/logo.webp';
    $siteBase   = rtrim(config('app.url', 'https://sov-summit.com'), '/');

    $organizationLd = [
        '@context'  => 'https://schema.org',
        '@type'     => 'Organization',
        'name'      => $orgName,
        'legalName' => 'Sovereign Summit GmbH',
        'url'       => $siteBase,
        'logo'      => $orgLogoUrl,
        'email'     => 'info@sov-summit.com',
        'address'   => [
            '@type'          => 'PostalAddress',
            'streetAddress'  => 'Bahnhofstrasse 21',
            'addressLocality' => 'Zug',
            'postalCode'     => '6300',
            'addressCountry' => 'CH',
        ],
        'slogan' => 'People. Ideas. Impact.',
    ];

    $breadcrumbLd = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',     'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Services', 'item' => url('/services')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $service->title, 'item' => $canonical],
        ],
    ];

    $serviceLd = array_filter([
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        'name'        => $service->title,
        'description' => $seoSummary,
        'url'         => $canonical,
        'provider'    => ['@type' => 'Organization', 'name' => $orgName, 'url' => $siteBase],
        'areaServed'  => 'Worldwide',
        'image'       => $service->hero_url ?: null,
        'serviceType' => $service->eyebrow ?: null,
    ], fn ($v) => !is_null($v));

    $faqLd = null;
    if (is_array($service->faqs) && count($service->faqs) > 0) {
        $faqLd = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => collect($service->faqs)->map(fn ($f) => [
                '@type' => 'Question',
                'name'  => $f['q'] ?? '',
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a'] ?? ''],
            ])->values()->all(),
        ];
    }
@endphp

@section('title', $seoTitleFull)
@section('meta_description', $seoSummary)
@section('canonical', $canonical)
@section('og_image', $heroUrl)

@push('head')
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1" />
<meta name="googlebot" content="index, follow, max-image-preview:large, max-snippet:-1" />
@if ($keywordsList->isNotEmpty())
<meta name="keywords" content="{{ $keywordsList->implode(', ') }}" />
@endif

<meta property="og:type" content="website" />
<meta property="og:site_name" content="{{ $orgName }}" />
<meta property="og:locale" content="en_GB" />
<meta property="og:image:alt" content="{{ $heroAlt }}" />

<meta name="twitter:title" content="{{ $seoTitleBase }}" />
<meta name="twitter:description" content="{{ $seoSummary }}" />
<meta name="twitter:image" content="{{ $heroUrl }}" />
<meta name="twitter:image:alt" content="{{ $heroAlt }}" />
<meta name="twitter:label1" content="Category" />
<meta name="twitter:data1" content="Services" />

<script type="application/ld+json">{!! json_encode($organizationLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($serviceLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@if ($faqLd)
<script type="application/ld+json">{!! json_encode($faqLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
@endpush

@section('breadcrumb')
<div class="breadcrumb container">
  <a href="/">Home</a> / <a href="/services">Services</a> / <span>{{ $service->title }}</span>
</div>
@endsection

@section('content')
<article itemscope itemtype="https://schema.org/Service">
  <meta itemprop="url" content="{{ $canonical }}">
  <meta itemprop="areaServed" content="Worldwide">

  <section class="service-hero"><div class="container">
    <div class="service-hero__grid">
      <div class="service-hero__body">
        @if ($service->eyebrow)
          <p class="brandline">{{ $service->eyebrow }}</p>
        @endif
        <h1 itemprop="name">{{ $service->title }}</h1>
        @if ($service->summary)
          <p class="lede" itemprop="description">{{ $service->summary }}</p>
        @endif
      </div>
      @if ($service->hero_url)
        <figure class="service-hero__figure">
          <img src="{{ $service->hero_url }}" alt="{{ $heroAlt }}" width="900" height="1100" loading="eager" fetchpriority="high" itemprop="image">
        </figure>
      @endif
    </div>
  </div></section>

  @if ($service->description)
    <section class="service-detail__body"><div class="container">
      <div class="prose service-detail__prose">
        {!! $service->description !!}
      </div>
    </div></section>
  @endif

  @if (!empty($service->services_included))
    <section class="alt"><div class="container">
      <div class="section-head wide"><h2>{{ $service->services_included_label ?: 'Services included' }}</h2></div>
      <ul class="check-list">
        @foreach ($service->services_included as $item)
          <li>{{ $item }}</li>
        @endforeach
      </ul>
    </div></section>
  @endif

  @if (!empty($service->faqs))
    <section class="alt"><div class="container">
      <div class="section-head wide"><h2>Frequently asked</h2></div>
      @foreach ($service->faqs as $faq)
        <details class="faq-item">
          <summary>{{ $faq['q'] ?? '' }}</summary>
          <p>{{ $faq['a'] ?? '' }}</p>
        </details>
      @endforeach
    </div></section>
  @endif

  <x-content-sections :model="$service" />

  @if ($related->isNotEmpty())
    <section class=""><div class="container">
      <div class="section-head wide" style="text-align:center;margin-left:auto;margin-right:auto;">
        <span class="eyebrow">Related Services</span>
        <h2>Continue exploring</h2>
      </div>
      <div class="related-services">
        @foreach ($related as $r)
          <a class="related-card" href="{{ $r->publicUrl() }}">
            <div class="related-card__media">
              @if ($r->hero_url)
                <img src="{{ $r->hero_url }}" alt="{{ $r->title }} preview" loading="lazy" width="600" height="450">
              @endif
            </div>
            <div class="related-card__body">
              <span class="related-card__title">{{ $r->title }}</span>
              <span class="related-card__arrow" aria-hidden="true">&rarr;</span>
            </div>
          </a>
        @endforeach
      </div>
    </div></section>
  @endif
</article>

<section class="cta-band"><div class="container">
  <h2>Let&rsquo;s create something meaningful.</h2>
  <p class="lede">Tell us what you are planning and we will connect you with the right providers.</p>
  <div class="cta-row"><a class="btn btn-primary" href="/contact">Discuss Your Requirements</a></div>
</div></section>
@endsection
