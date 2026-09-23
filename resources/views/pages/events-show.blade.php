@extends('layouts.site')

@php
    // --------- SEO / social precomputed values ---------
    $seoTitleBase = $event->seo_title ?: $event->title;
    $seoTitleFull = $seoTitleBase . ' | SOV SUMMIT';
    $seoSummary   = trim((string) ($event->summary ?: 'A programme coordinated by SOV SUMMIT.'));
    $canonical    = url()->current();
    $coverUrl     = $event->cover_url ?: asset('assets/img/logo.webp');
    $coverAlt     = $event->cover_url ? ($event->title . ' — event cover') : 'SOV SUMMIT';

    $startIso     = optional($event->event_date)->toIso8601String();
    $modifiedIso  = optional($event->updated_at ?: $event->event_date)->toIso8601String();
    $isPast       = $event->event_date && $event->event_date->isPast();

    $keywordsList = collect(explode(',', (string) $event->seo_keywords))
        ->map(fn ($k) => trim($k))
        ->filter()
        ->values();

    $orgName    = 'SOV SUMMIT';
    $orgLogoUrl = 'https://sov-summit.com/assets/img/logo.webp';
    $siteBase   = rtrim(config('app.url', 'https://sov-summit.com'), '/');

    // --------- Structured data: Event ---------
    $eventLd = array_filter([
        '@context'            => 'https://schema.org',
        '@type'               => 'Event',
        'name'                => $event->title,
        'description'         => $seoSummary,
        'url'                 => $canonical,
        'startDate'           => $startIso,
        'eventStatus'         => 'https://schema.org/EventScheduled',
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'inLanguage'          => 'en',
        'image'               => $event->cover_url ? [
            '@type'  => 'ImageObject',
            'url'    => $event->cover_url,
            'width'  => 1600,
            'height' => 900,
        ] : null,
        'location' => $event->location ? [
            '@type'   => 'Place',
            'name'    => $event->location,
            'address' => [
                '@type'          => 'PostalAddress',
                'addressLocality' => $event->location,
            ],
        ] : [
            '@type' => 'VirtualLocation',
            'url'   => $canonical,
        ],
        'organizer' => [
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
        'performer' => [
            '@type' => 'Organization',
            'name'  => $orgName,
        ],
        'keywords' => $keywordsList->isNotEmpty() ? $keywordsList->implode(', ') : null,
    ], fn ($v) => !is_null($v));

    $breadcrumbLd = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',   'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Events', 'item' => route('events.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $event->title, 'item' => $canonical],
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
@if ($keywordsList->isNotEmpty())
<meta name="keywords" content="{{ $keywordsList->implode(', ') }}" />
@endif

{{-- Open Graph --}}
<meta property="og:type" content="{{ $isPast ? 'article' : 'website' }}" />
<meta property="og:site_name" content="{{ $orgName }}" />
<meta property="og:locale" content="en_GB" />
<meta property="og:image:alt" content="{{ $coverAlt }}" />
@if ($event->cover_url)
<meta property="og:image:width" content="1600" />
<meta property="og:image:height" content="900" />
@endif
@if ($startIso)
<meta property="event:start_time" content="{{ $startIso }}" />
@endif

{{-- Twitter / X --}}
<meta name="twitter:title" content="{{ $seoTitleBase }}" />
<meta name="twitter:description" content="{{ $seoSummary }}" />
<meta name="twitter:image" content="{{ $coverUrl }}" />
<meta name="twitter:image:alt" content="{{ $coverAlt }}" />
@if ($event->event_date)
<meta name="twitter:label1" content="Date" />
<meta name="twitter:data1" content="{{ $event->event_date->format('d F Y') }}" />
@endif
@if ($event->location)
<meta name="twitter:label2" content="Location" />
<meta name="twitter:data2" content="{{ $event->location }}" />
@endif

{{-- Structured data --}}
<script type="application/ld+json">{!! json_encode($eventLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<article class="event-detail" itemscope itemtype="https://schema.org/Event">
  <meta itemprop="url" content="{{ $canonical }}">

  <header class="event-detail__hero {{ $event->cover_url ? 'event-detail__hero--photo' : 'event-detail__hero--empty' }}">
    @if ($event->cover_url)
      <img class="event-detail__image" src="{{ $event->cover_url }}" alt="{{ $coverAlt }}"
           width="1600" height="900" fetchpriority="high" itemprop="image">
    @endif
    <div class="event-detail__hero-overlay" aria-hidden="true"></div>
    <div class="container event-detail__hero-inner">
      <nav class="event-detail__crumbs" aria-label="Breadcrumb">
        <a href="/">Home</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('events.index') }}">Events</a>
        <span aria-hidden="true">/</span>
        <span class="event-detail__crumbs-current" aria-current="page">{{ $event->title }}</span>
      </nav>
      <p class="event-detail__eyebrow">Event</p>
      <h1 itemprop="name">{{ $event->title }}</h1>
      @if ($event->summary)
        <p class="event-detail__standfirst" itemprop="description">{{ $event->summary }}</p>
      @endif
      <ul class="event-detail__meta" role="list">
        @if ($event->event_date)
          <li>
            <span class="event-detail__meta-label">Date</span>
            <span class="event-detail__meta-value">
              <time datetime="{{ $startIso }}" itemprop="startDate">{{ $event->event_date->format('l, d F Y') }}</time>
            </span>
          </li>
        @endif
        @if ($event->location)
          <li itemprop="location" itemscope itemtype="https://schema.org/Place">
            <span class="event-detail__meta-label">Location</span>
            <span class="event-detail__meta-value" itemprop="name">{{ $event->location }}</span>
          </li>
        @endif
      </ul>
    </div>
  </header>

  <section class="event-detail__body"><div class="container">
    <div class="prose event-detail__prose">
      @if ($event->description)
        {!! $event->description !!}
      @endif
    </div>
  </div></section>

  <x-content-sections :model="$event" />

  @if ($related->isNotEmpty())
    <section class="events-block alt"><div class="container">
      <header class="events-block__head">
        <span class="eyebrow">More Programmes</span>
        <h2>Continue exploring.</h2>
      </header>
      <div class="events-grid">
        @foreach ($related as $r)
          @include('partials.event-card', ['event' => $r])
        @endforeach
      </div>
    </div></section>
  @endif
</article>

@include('partials.cta-band', ['cta' => 'Discuss Your Programme'])
@endsection
