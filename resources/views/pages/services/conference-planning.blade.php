@extends('layouts.site')

@section('title', 'Conference Planning & Event Management | SOV SUMMIT')
@section('meta_description', 'International conference planning, executive forums, institutional meetings, venue sourcing, speakers, hospitality, technical production, and on-site coordination.')
@section('canonical', 'https://sov-summit.com/services/conference-planning/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Services", "item": "https://sov-summit.com/services/"}, {"@type": "ListItem", "position": 3, "name": "Conference Planning", "item": "https://sov-summit.com/services/conference-planning/"}]}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Service", "name": "Conference Planning", "description": "International conference planning, executive forums, institutional meetings, venue sourcing, speakers, hospitality, technical production, and on-site coordination.", "provider": {"@type": "Organization", "name": "SOV SUMMIT"}, "url": "https://sov-summit.com/services/conference-planning/", "areaServed": "Worldwide"}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "FAQPage", "mainEntity": [{"@type": "Question", "name": "What does conference planning include?", "acceptedAnswer": {"@type": "Answer", "text": "Conference planning can include venue sourcing, programme coordination, speaker management, participant logistics, accommodation, transportation, technical production, catering, security coordination, media coverage, and on-site delivery."}}, {"@type": "Question", "name": "Can SOV SUMMIT coordinate international conferences?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. SOV SUMMIT can coordinate the relevant providers and operational requirements for international conferences, subject to the destination, scope, availability, and client brief."}}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <a href="/services">Services</a> / <span>Conference Planning</span></div>
@endsection

@section('content')

<section class="service-hero"><div class="container">
  <div class="service-hero__grid">
    <div class="service-hero__body">
  <p class="brandline">CONFERENCE PLANNING</p>
  <h1>Conference planning with operational precision</h1>
  <p class="lede">Conferences bring together people, information, schedules, venues, technology, hospitality, and expectations. SOV SUMMIT coordinates these elements to create a structured and professional event experience.</p>
    </div>
    <figure class="service-hero__figure">
      <img src="{{ asset('assets/img/services/conferences.webp') }}" alt="International conference with delegates and national flags" width="900" height="1100" loading="eager">
    </figure>
  </div>
</div></section>
<section class="alt"><div class="container">
<div class="section-head wide"><h2>Conference services</h2></div>
<ul class="check-list"><li>International conference planning</li><li>Executive forums</li><li>Government and institutional meetings</li><li>Corporate conferences</li><li>Workshops</li><li>Seminars</li><li>Venue sourcing</li><li>Accommodation coordination</li><li>Speaker coordination</li><li>Guest registration</li><li>Participant management</li><li>Technical production</li><li>Audiovisual coordination</li><li>Catering and hospitality</li><li>Transportation</li><li>Security coordination</li><li>Media coverage</li><li>On-site event management</li><li>Post-event documentation</li></ul></div></section>
<section class="alt"><div class="container"><div class="section-head wide"><h2>Frequently asked</h2></div><details class="faq-item"><summary>What does conference planning include?</summary><p>Conference planning can include venue sourcing, programme coordination, speaker management, participant logistics, accommodation, transportation, technical production, catering, security coordination, media coverage, and on-site delivery.</p></details><details class="faq-item"><summary>Can SOV SUMMIT coordinate international conferences?</summary><p>Yes. SOV SUMMIT can coordinate the relevant providers and operational requirements for international conferences, subject to the destination, scope, availability, and client brief.</p></details></div></section>
<section class=""><div class="container">
  <div class="section-head wide" style="text-align:center;margin-left:auto;margin-right:auto;">
    <span class="eyebrow">Related Services</span>
    <h2>Continue exploring</h2>
  </div>
  <div class="related-services">
      <a class="related-card" href="/services/planning-coordination"><div class="related-card__media"><img src="{{ asset('assets/img/services/planning.webp') }}" alt="Planning & Coordination preview" loading="lazy" width="600" height="450"></div><div class="related-card__body"><span class="related-card__title">Planning & Coordination</span><span class="related-card__arrow" aria-hidden="true">&rarr;</span></div></a>      <a class="related-card" href="/services/delegation-management"><div class="related-card__media"><img src="{{ asset('assets/img/services/delegation.webp') }}" alt="Delegation Management preview" loading="lazy" width="600" height="450"></div><div class="related-card__body"><span class="related-card__title">Delegation Management</span><span class="related-card__arrow" aria-hidden="true">&rarr;</span></div></a><a class="related-card" href="/services/media-coverage"><div class="related-card__media"><img src="{{ asset('assets/img/services/media.webp') }}" alt="Media Coverage preview" loading="lazy" width="600" height="450"></div><div class="related-card__body"><span class="related-card__title">Media Coverage</span><span class="related-card__arrow" aria-hidden="true">&rarr;</span></div></a>      <a class="related-card" href="/services/events-productions"><div class="related-card__media"><img src="{{ asset('assets/img/services/events.webp') }}" alt="Events & Productions preview" loading="lazy" width="600" height="450"></div><div class="related-card__body"><span class="related-card__title">Events & Productions</span><span class="related-card__arrow" aria-hidden="true">&rarr;</span></div></a></div>
</div></section>

<section class="cta-band"><div class="container">
<h2>Let&rsquo;s create something meaningful.</h2>
<p class="lede">Tell us what you are planning and we will connect you with the right providers.</p>
<div class="cta-row"><a class="btn btn-primary" href="/contact">Discuss Your Conference</a></div>
</div></section>

@endsection
