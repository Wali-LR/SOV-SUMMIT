@extends('layouts.site')

@section('title', 'Event Planning & Provider Coordination | SOV SUMMIT')
@section('meta_description', 'SOV SUMMIT coordinates event planning, suppliers, venues, accommodation, transportation, guest management, schedules, and event delivery.')
@section('canonical', 'https://sov-summit.com/services/planning-coordination/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Services", "item": "https://sov-summit.com/services/"}, {"@type": "ListItem", "position": 3, "name": "Planning & Coordination", "item": "https://sov-summit.com/services/planning-coordination/"}]}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Service", "name": "Planning & Coordination", "description": "SOV SUMMIT coordinates event planning, suppliers, venues, accommodation, transportation, guest management, schedules, and event delivery.", "provider": {"@type": "Organization", "name": "SOV SUMMIT"}, "url": "https://sov-summit.com/services/planning-coordination/", "areaServed": "Worldwide"}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <a href="/services">Services</a> / <span>Planning & Coordination</span></div>
@endsection

@section('content')

<section class="service-hero"><div class="container">
  <div class="service-hero__grid">
    <div class="service-hero__body">
  <p class="brandline">PLANNING & COORDINATION</p>
  <h1>Planning and coordination for complex programmes</h1>
  <p class="lede">Successful programmes require more than individual suppliers. They require a clear plan, reliable communication, and coordination between every moving part. SOV SUMMIT supports clients from the initial brief through to delivery, helping organise the operational structure behind events, delegations, training programmes, and private experiences.</p>
    </div>
    <figure class="service-hero__figure">
      <img src="{{ asset('assets/img/services/planning.webp') }}" alt="Planning and coordination workspace with prepared conference hall" width="900" height="1100" loading="eager">
    </figure>
  </div>
</div></section>
<section class="alt"><div class="container">
<div class="split">
  <div>
    <h2>Services included</h2>
    <ul class="check-list"><li>Initial programme briefing</li><li>Event concept and operational planning</li><li>Venue and supplier sourcing</li><li>Accommodation coordination</li><li>Transportation coordination</li><li>Private aviation coordination</li><li>Guest and participant management</li><li>Schedule development</li><li>Hospitality coordination</li><li>Catering coordination</li><li>Production coordination</li><li>Security coordination</li><li>Media coordination</li><li>On-site coordination</li><li>Post-event follow-up</li></ul>
  </div>
  <div>
    <h2>Suitable for</h2>
    <ul class="check-list"><li>Corporate events</li><li>International conferences</li><li>Executive programmes</li><li>Institutional meetings</li><li>Delegations</li><li>Management training</li><li>Private events</li><li>Family trips</li><li>Multi-destination programmes</li></ul>
  </div>
</div>
</div></section>

<section class=""><div class="container">
  <div class="section-head wide" style="text-align:center;margin-left:auto;margin-right:auto;">
    <span class="eyebrow">Related Services</span>
    <h2>Continue exploring</h2>
  </div>
  <div class="related-services">
      <a class="related-card" href="/services/conference-planning"><div class="related-card__media"><img src="{{ asset('assets/img/services/conferences.webp') }}" alt="Conference Planning preview" loading="lazy" width="600" height="450"></div><div class="related-card__body"><span class="related-card__title">Conference Planning</span><span class="related-card__arrow" aria-hidden="true">&rarr;</span></div></a>      <a class="related-card" href="/services/delegation-management"><div class="related-card__media"><img src="{{ asset('assets/img/services/delegation.webp') }}" alt="Delegation Management preview" loading="lazy" width="600" height="450"></div><div class="related-card__body"><span class="related-card__title">Delegation Management</span><span class="related-card__arrow" aria-hidden="true">&rarr;</span></div></a>      <a class="related-card" href="/services/events-productions"><div class="related-card__media"><img src="{{ asset('assets/img/services/events.webp') }}" alt="Events & Productions preview" loading="lazy" width="600" height="450"></div><div class="related-card__body"><span class="related-card__title">Events & Productions</span><span class="related-card__arrow" aria-hidden="true">&rarr;</span></div></a></div>
</div></section>

<section class="cta-band"><div class="container">
<h2>Let&rsquo;s create something meaningful.</h2>
<p class="lede">Tell us what you are planning and we will connect you with the right providers.</p>
<div class="cta-row"><a class="btn btn-primary" href="/contact">Discuss Your Programme</a></div>
</div></section>

@endsection
