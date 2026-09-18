@extends('layouts.site')

@section('title', 'Conferences, Corporate Events & International Productions | SOV SUMMIT')
@section('meta_description', 'Discover the event formats coordinated by SOV SUMMIT, including conferences, corporate events, celebrations, concerts, film festivals, fashion shows, and road shows.')
@section('canonical', 'https://sov-summit.com/events/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Events", "item": "https://sov-summit.com/events/"}]}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "FAQPage", "mainEntity": [{"@type": "Question", "name": "Does SOV SUMMIT organise corporate events?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. SOV SUMMIT coordinates corporate events such as executive dinners, receptions, launches, networking events, celebrations, and company milestones."}}, {"@type": "Question", "name": "Can SOV SUMMIT coordinate a multi-city event?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. Multi-city programmes can be coordinated through a structured itinerary covering venues, transportation, accommodation, suppliers, schedules, and local providers."}}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <span>Events</span></div>
@endsection

@section('content')

<section class=""><div class="container">
<div class="section-head wide">
  <p class="brandline">EVENTS</p>
  <h1>Different formats. One coordinated approach.</h1>
  <p class="lede">SOV SUMMIT coordinates events across corporate, institutional, cultural, executive, and private environments.</p>
</div>
</div></section>

<section class="alt"><div class="container">
<div class="section-head wide"><h2>Event categories</h2></div>
<div class="tag-cloud">
  <span>Conferences</span><span>Corporate events</span><span>Corporate celebrations</span>
  <span>Executive dinners</span><span>Concerts</span><span>Film festivals</span>
  <span>Fashion shows</span><span>Road shows</span><span>Private events</span>
  <span>Institutional programmes</span><span>Management training events</span>
  <span>International forums</span>
</div>
<p class="muted" style="margin-top:1.6rem;">See the full scope of what we coordinate for each format on the <a href="/services/events-productions" style="color:var(--gold);">Events &amp; Productions</a> page, or discuss your specific programme with our team.</p>
</div></section>

<section class=""><div class="container">
<div class="section-head wide"><h2>Frequently asked</h2></div>
<div class="faq-item"><h3>Does SOV SUMMIT organise corporate events?</h3><p>Yes. SOV SUMMIT coordinates corporate events such as executive dinners, receptions, launches, networking events, celebrations, and company milestones.</p></div><div class="faq-item"><h3>Can SOV SUMMIT coordinate a multi-city event?</h3><p>Yes. Multi-city programmes can be coordinated through a structured itinerary covering venues, transportation, accommodation, suppliers, schedules, and local providers.</p></div></div></section>

<section class="cta-band"><div class="container">
<h2>Let&rsquo;s create something meaningful.</h2>
<p class="lede">Tell us what you are planning, and we will connect you with the right providers.</p>
<div class="cta-row"><a class="btn btn-primary" href="/contact">Discuss Your Event</a></div>
</div></section>

@endsection
