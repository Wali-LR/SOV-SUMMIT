@extends('layouts.site')

@section('title', ($event->seo_title ?: $event->title) . ' | SOV SUMMIT')
@section('meta_description', $event->summary ?: 'A programme coordinated by SOV SUMMIT.')
@section('canonical', url()->current())
@section('og_image', $event->cover_url ?: 'https://sov-summit.com/assets/img/logo.webp')

@push('head')
@if ($event->seo_keywords)
<meta name="keywords" content="{{ $event->seo_keywords }}" />
@endif
@endpush

@section('content')
<article class="event-detail">
  <header class="event-detail__hero {{ $event->cover_url ? 'event-detail__hero--photo' : 'event-detail__hero--empty' }}">
    @if ($event->cover_url)
      <img class="event-detail__image" src="{{ $event->cover_url }}" alt="" aria-hidden="true" width="1600" height="900">
    @endif
    <div class="event-detail__hero-overlay" aria-hidden="true"></div>
    <div class="container event-detail__hero-inner">
      <nav class="event-detail__crumbs" aria-label="Breadcrumb">
        <a href="/">Home</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('events.index') }}">Events</a>
        <span aria-hidden="true">/</span>
        <span class="event-detail__crumbs-current">{{ $event->title }}</span>
      </nav>
      <p class="event-detail__eyebrow">Event</p>
      <h1>{{ $event->title }}</h1>
      @if ($event->summary)
        <p class="event-detail__standfirst">{{ $event->summary }}</p>
      @endif
      <ul class="event-detail__meta" role="list">
        @if ($event->event_date)
          <li><span class="event-detail__meta-label">Date</span><span class="event-detail__meta-value">{{ $event->event_date->format('l, d F Y') }}</span></li>
        @endif
        @if ($event->location)
          <li><span class="event-detail__meta-label">Location</span><span class="event-detail__meta-value">{{ $event->location }}</span></li>
        @endif
      </ul>
    </div>
  </header>

  <section class="event-detail__body"><div class="container">
    <div class="prose" style="max-width:70ch;">
      @if ($event->description)
        {!! $event->description !!}
      @endif
    </div>
  </div></section>

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
