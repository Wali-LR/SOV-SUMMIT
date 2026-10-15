@extends('layouts.site')

@section('title', 'Products | SOV SUMMIT')
@section('meta_description', 'Digital products by SOV SUMMIT — Attending Management and Event App — designed to streamline delegate coordination, registrations, communications, and on-site experience.')
@section('canonical', 'https://sov-summit.com/products/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Products", "item": "https://sov-summit.com/products/"}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <span>Products</span></div>
@endsection

@section('content')

<section class=""><div class="container">
<div class="section-head wide">
  <p class="brandline">PRODUCTS</p>
  <h1>Digital tools built for coordinated programmes.</h1>
  <p class="lede">SOV SUMMIT products support the operational side of international events &mdash; from delegate management to on-site engagement &mdash; so organisers can focus on the programme, not the logistics.</p>
</div>
</div></section>

<section class="services alt" aria-labelledby="products-heading"><div class="container">
<header class="services__head">
  <span class="eyebrow">Our Products</span>
  <h2 id="products-heading">Two products. One coordinated experience.</h2>
  <p class="lede">Purpose-built platforms that complement our coordination services and can be deployed independently for organisations running their own programmes.</p>
</header>

<div class="services__grid">
  <a class="service-card" href="/contact" aria-label="Enquire about Attending Management">
    <div class="service-card__media">
      <img src="{{ asset('assets/img/services/delegation.webp') }}" alt="Delegate registration and coordination interface" loading="lazy" width="600" height="400">
    </div>
    <div class="service-card__body">
      <h3>Attending Management</h3>
      <p>A dedicated platform for delegate coordination &mdash; registrations, invitations, RSVPs, credentials, arrival logistics, and communications. Built for conferences, delegations, and executive programmes.</p>
      <span class="service-card__cta">Request a demo <span aria-hidden="true">&rarr;</span></span>
    </div>
  </a>

  <a class="service-card" href="/contact" aria-label="Enquire about the Event App">
    <div class="service-card__media">
      <img src="{{ asset('assets/img/services/events.webp') }}" alt="Mobile event application on a phone at an international conference" loading="lazy" width="600" height="400">
    </div>
    <div class="service-card__body">
      <h3>Event App</h3>
      <p>A branded mobile experience for participants &mdash; live agenda, speaker profiles, venue maps, personalised schedules, notifications, and networking. Designed to support the on-site experience end to end.</p>
      <span class="service-card__cta">Request a demo <span aria-hidden="true">&rarr;</span></span>
    </div>
  </a>
</div>
</div></section>

<section class="cta-band"><div class="container">
<h2>Interested in a product demo?</h2>
<p class="lede">Tell us about your programme and we&rsquo;ll walk you through how Attending Management and the Event App can support it.</p>
<div class="cta-row"><a class="btn btn-primary" href="/contact">Discuss Your Requirements</a></div>
</div></section>

@endsection
