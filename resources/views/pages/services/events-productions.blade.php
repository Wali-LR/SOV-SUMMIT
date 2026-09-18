@extends('layouts.site')

@section('title', 'Corporate & International Event Organisation | SOV SUMMIT')
@section('meta_description', 'SOV SUMMIT coordinates corporate events, celebrations, concerts, film festivals, fashion shows, road shows, executive dinners, and private productions.')
@section('canonical', 'https://sov-summit.com/services/events-productions/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Services", "item": "https://sov-summit.com/services/"}, {"@type": "ListItem", "position": 3, "name": "Events & Productions", "item": "https://sov-summit.com/services/events-productions/"}]}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Service", "name": "Events & Productions", "description": "SOV SUMMIT coordinates corporate events, celebrations, concerts, film festivals, fashion shows, road shows, executive dinners, and private productions.", "provider": {"@type": "Organization", "name": "SOV SUMMIT"}, "url": "https://sov-summit.com/services/events-productions/", "areaServed": "Worldwide"}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <a href="/services">Services</a> / <span>Events & Productions</span></div>
@endsection

@section('content')

<section class="service-hero"><div class="container">
  <div class="service-hero__grid">
    <div class="service-hero__body">
  <p class="brandline">EVENTS & PRODUCTIONS</p>
  <h1>Events and productions that connect people</h1>
  <p class="lede">Every event has its own purpose, audience, atmosphere, and operational requirements. SOV SUMMIT coordinates the providers and details needed to create a coherent and professionally delivered event.</p>
    </div>
    <figure class="service-hero__figure">
      <img src="{{ asset('assets/img/services/events.webp') }}" alt="Chandelier-lit gala with guests in evening dress" width="900" height="1100" loading="eager">
    </figure>
  </div>
</div></section>
<section class="alt"><div class="container">
<div class="section-head wide"><h2>Event types</h2></div>
<div class="card-grid">
  <div class="card"><h3>Corporate events</h3><p>Executive dinners, receptions, product launches, networking events, corporate gatherings, and client experiences.</p></div>
  <div class="card"><h3>Corporate celebrations</h3><p>Christmas events, anniversaries, company milestones, awards, and employee celebrations.</p></div>
  <div class="card"><h3>Concerts</h3><p>Artist coordination, venue requirements, production support, hospitality, transportation, and event logistics.</p></div>
  <div class="card"><h3>Film festivals</h3><p>Guest coordination, venues, screening logistics, hospitality, transportation, media, and programme support.</p></div>
  <div class="card"><h3>Fashion shows</h3><p>Venue coordination, production suppliers, guest management, backstage requirements, hospitality, security, and media.</p></div>
  <div class="card"><h3>Road shows</h3><p>Multi-city corporate programmes, product presentations, sales events, exhibitions, and travelling productions.</p></div>
  <div class="card"><h3>Private events</h3><p>Bespoke celebrations, private gatherings, family occasions, and high-touch guest experiences.</p></div>
</div>
</div></section>

<section class=""><div class="container">
  <div class="section-head wide" style="text-align:center;margin-left:auto;margin-right:auto;">
    <span class="eyebrow">Related Services</span>
    <h2>Continue exploring</h2>
  </div>
  <div class="related-services">
      <a class="related-card" href="/services/planning-coordination"><div class="related-card__media"><img src="{{ asset('assets/img/services/planning.webp') }}" alt="Planning & Coordination preview" loading="lazy" width="600" height="450"></div><div class="related-card__body"><span class="related-card__title">Planning & Coordination</span><span class="related-card__arrow" aria-hidden="true">&rarr;</span></div></a>      <a class="related-card" href="/services/conference-planning"><div class="related-card__media"><img src="{{ asset('assets/img/services/conferences.webp') }}" alt="Conference Planning preview" loading="lazy" width="600" height="450"></div><div class="related-card__body"><span class="related-card__title">Conference Planning</span><span class="related-card__arrow" aria-hidden="true">&rarr;</span></div></a>      <a class="related-card" href="/services/media-coverage"><div class="related-card__media"><img src="{{ asset('assets/img/services/media.webp') }}" alt="Media Coverage preview" loading="lazy" width="600" height="450"></div><div class="related-card__body"><span class="related-card__title">Media Coverage</span><span class="related-card__arrow" aria-hidden="true">&rarr;</span></div></a>      <a class="related-card" href="/events"><div class="related-card__media"><img src="{{ asset('assets/img/banner-3.webp') }}" alt="Events Overview preview" loading="lazy" width="600" height="450"></div><div class="related-card__body"><span class="related-card__title">Events Overview</span><span class="related-card__arrow" aria-hidden="true">&rarr;</span></div></a></div>
</div></section>

<section class="cta-band"><div class="container">
<h2>Let&rsquo;s create something meaningful.</h2>
<p class="lede">Tell us what you are planning and we will connect you with the right providers.</p>
<div class="cta-row"><a class="btn btn-primary" href="/contact">Discuss Your Event</a></div>
</div></section>

@endsection
