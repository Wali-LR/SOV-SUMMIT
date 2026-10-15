@extends('layouts.site')

@section('title', 'About SOV SUMMIT | People, Ideas, Impact')
@section('meta_description', 'Learn about SOV SUMMIT, an international coordination partner for events, delegations, management training, travel experiences, security coordination, and media coverage.')
@section('canonical', 'https://sov-summit.com/about/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "About", "item": "https://sov-summit.com/about/"}]}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "FAQPage", "mainEntity": [{"@type": "Question", "name": "What is SOV SUMMIT?", "acceptedAnswer": {"@type": "Answer", "text": "SOV SUMMIT is an international coordination and event organisation company that connects clients with suitable providers for events, management training, delegations, travel experiences, security coordination, and media coverage."}}, {"@type": "Question", "name": "What services does SOV SUMMIT provide?", "acceptedAnswer": {"@type": "Answer", "text": "SOV SUMMIT provides planning and coordination, conference planning, delegation management, management training programme coordination, event production, security coordination, media coverage, and travel experience planning."}}, {"@type": "Question", "name": "Who does SOV SUMMIT work with?", "acceptedAnswer": {"@type": "Answer", "text": "SOV SUMMIT works with corporations, institutions, governments, associations, executive teams, family offices, private clients, event organisers, and production companies, depending on the programme and requirements."}}, {"@type": "Question", "name": "Where is SOV SUMMIT based?", "acceptedAnswer": {"@type": "Answer", "text": "SOV SUMMIT is the operating brand of Sovereign Summit GmbH, based in Zug, Switzerland."}}, {"@type": "Question", "name": "How can I contact SOV SUMMIT?", "acceptedAnswer": {"@type": "Answer", "text": "You can contact SOV SUMMIT by email at info@sov-summit.com or by WhatsApp at +41 79 876 35 73."}}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <span>About</span></div>
@endsection

@section('content')
<section class=""><div class="container">
<div class="section-head wide">
  <p class="brandline">ABOUT SOV SUMMIT</p>
  <h1>People. Ideas. Impact.</h1>
  <p class="lede">SOV SUMMIT was created around a simple principle: successful programmes depend on the quality of the connections behind them.</p>
</div>
<div class="split">
  <div>
    <p>We bring together event organisation, management training, international coordination, travel planning, and specialist service providers to help clients deliver meaningful experiences and professionally managed programmes.</p>
  </div>
  <div>
    <p>Our work often involves several moving parts &mdash; venues, accommodation, transportation, aviation, hospitality, production, security, media, and participant management. We coordinate these elements through a clear and structured process.</p>
  </div>
</div>
</div></section>

<section class="alt"><div class="container">
<div class="split">
  <div>
    <h2>Our role</h2>
    <p>Our role is to connect the dots &mdash; from the first idea to the final guest experience.</p>
    <p>We do not believe that every client requires the same solution. Each programme should reflect its purpose, participants, destination, timeline, and level of complexity.</p>
  </div>
  <div>
    <h2>Our approach</h2>
    <ul class="check-list"><li>Listen carefully to the brief</li><li>Define the operational requirements</li><li>Identify suitable providers</li><li>Coordinate communication and schedules</li><li>Maintain clear expectations</li><li>Support delivery with discretion and precision</li></ul>
  </div>
</div>
</div></section>

<section class=""><div class="container">
<div class="section-head wide"><h2>Our values</h2></div>
<div class="trio" style="grid-template-columns:repeat(4,1fr);">
  <div><h3>Professionalism</h3><p>We communicate clearly, prepare carefully, and treat every requirement seriously.</p></div>
  <div><h3>Discretion</h3><p>We respect the sensitivity of private, executive, institutional, and international programmes.</p></div>
  <div><h3>Reliability</h3><p>We focus on accurate information, realistic planning, and dependable coordination.</p></div>
  <div><h3>Adaptability</h3><p>We develop solutions around the client&rsquo;s objectives rather than forcing every programme into a standard package.</p></div>
</div>
</div></section>

<section class="alt"><div class="container">
<div class="section-head wide"><h2>Frequently asked</h2></div>
<div class="faq-item"><h3>What is SOV SUMMIT?</h3><p>SOV SUMMIT is an international coordination and event organisation company that connects clients with suitable providers for events, management training, delegations, travel experiences, security coordination, and media coverage.</p></div><div class="faq-item"><h3>What services does SOV SUMMIT provide?</h3><p>SOV SUMMIT provides planning and coordination, conference planning, delegation management, management training programme coordination, event production, security coordination, media coverage, and travel experience planning.</p></div><div class="faq-item"><h3>Who does SOV SUMMIT work with?</h3><p>SOV SUMMIT works with corporations, institutions, governments, associations, executive teams, family offices, private clients, event organisers, and production companies, depending on the programme and requirements.</p></div><div class="faq-item"><h3>Where is SOV SUMMIT based?</h3><p>SOV SUMMIT is the operating brand of Sovereign Summit GmbH, based in Zug, Switzerland.</p></div><div class="faq-item"><h3>How can I contact SOV SUMMIT?</h3><p>You can contact SOV SUMMIT by email at info@sov-summit.com or by WhatsApp at +41 79 876 35 73.</p></div></div></section>

<section class="cta-band"><div class="container">
<h2>Let&rsquo;s create something meaningful.</h2>
<p class="lede">SOV SUMMIT is the operating brand of Sovereign Summit GmbH. Full legal details appear in our legal notice.</p>
<div class="cta-row"><a class="btn btn-primary" href="/contact">Contact Us</a><a class="btn btn-secondary" href="/legal-notice">Legal Notice</a></div>
</div></section>
@endsection
