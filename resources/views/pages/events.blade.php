@extends('layouts.site')

@section('title', 'Events | SOV SUMMIT')
@section('meta_description', 'Selected events coordinated by SOV SUMMIT — conferences, executive programmes, delegation dinners, private journeys, and international productions.')
@section('canonical', url()->current())

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Events", "item": "https://sov-summit.com/events/"}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <span>Events</span></div>
@endsection

@section('content')

<section class=""><div class="container">
<div class="section-head wide">
  <p class="brandline">EVENTS</p>
  <h1>Selected programmes we have coordinated.</h1>
  <p class="lede">A curated view of upcoming and delivered engagements &mdash; conferences, executive programmes, delegations, private journeys, and international productions.</p>
</div>
</div></section>

@if ($upcoming->isNotEmpty())
<section class="events-block"><div class="container">
  <header class="events-block__head">
    <span class="eyebrow">Upcoming</span>
    <h2>What&rsquo;s ahead.</h2>
  </header>
  <div class="events-grid">
    @foreach ($upcoming as $event)
      @include('partials.event-card', ['event' => $event])
    @endforeach
  </div>
</div></section>
@endif

@if ($past->isNotEmpty())
<section class="events-block alt"><div class="container">
  <header class="events-block__head">
    <span class="eyebrow">Delivered</span>
    <h2>Recently delivered programmes.</h2>
  </header>
  <div class="events-grid">
    @foreach ($past as $event)
      @include('partials.event-card', ['event' => $event])
    @endforeach
  </div>
</div></section>
@endif

@if ($undated->isNotEmpty())
<section class="events-block"><div class="container">
  <header class="events-block__head">
    <span class="eyebrow">Programmes</span>
    <h2>Ongoing formats.</h2>
  </header>
  <div class="events-grid">
    @foreach ($undated as $event)
      @include('partials.event-card', ['event' => $event])
    @endforeach
  </div>
</div></section>
@endif

@if ($upcoming->isEmpty() && $past->isEmpty() && $undated->isEmpty())
<section class=""><div class="container">
  <p class="lede" style="text-align:center;">No events published yet. Check back soon.</p>
</div></section>
@endif

@include('partials.cta-band')
@endsection
