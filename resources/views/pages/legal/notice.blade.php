@extends('layouts.site')

@section('title', 'Legal Notice | SOV SUMMIT')
@section('meta_description', 'Legal notice and company information for SOV SUMMIT, the operating brand of Sovereign Summit GmbH, Zug, Switzerland.')
@section('canonical', 'https://sov-summit.com/legal-notice/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Legal Notice", "item": "https://sov-summit.com/legal-notice/"}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <span>Legal Notice</span></div>
@endsection

@section('content')
<section class=""><div class="container">
<div class="section-head wide">
  <p class="brandline">LEGAL</p>
  <h1>Legal Notice</h1>
</div>
<div class="prose" style="max-width:70ch;">
  <h2>Company information</h2>
  <p>Sovereign Summit GmbH<br>Bahnhofstrasse 21<br>6300 Zug, Switzerland</p>
  <p>Commercial register UID: CHE-282.946.999<br>Registration number: CH-170.4.0251299</p>
  <p>Email: <a href="mailto:info@sov-summit.com" style="color:var(--gold);">info@sov-summit.com</a><br>
  WhatsApp: +41 79 876 35 73<br>
  Website: sov-summit.com</p>

  <h2>Brand</h2>
  <p>SOV SUMMIT is the operating brand of Sovereign Summit GmbH.</p>

  <h2>Nature of services</h2>
  <p>SOV SUMMIT coordinates events, management training, delegations, travel experiences, security support, and media coverage on behalf of clients. Many of these services are delivered through external, independently licensed or approved third-party providers (including but not limited to aviation operators, security companies, transportation providers, venues, and media production teams). SOV SUMMIT coordinates these providers; it does not itself hold every licence required to directly provide every underlying service, and each engagement remains subject to local law, provider availability, and the specific scope agreed with the client.</p>

  <h2>Liability</h2>
  <p>While Sovereign Summit GmbH takes care to ensure the accuracy of the information on this website, no warranty is given for its completeness, accuracy, or currency. Content and services provided by third-party partners remain the responsibility of those providers under their own terms.</p>

  <h2>Copyright</h2>
  <p>The content, layout, and design of this website are protected by copyright. Reproduction, in whole or in part, requires prior written consent from Sovereign Summit GmbH.</p>

  <p class="muted" style="margin-top:2rem;font-size:0.85rem;">This legal notice is provided as a starting template and should be reviewed by qualified legal counsel before publication to ensure full compliance with Swiss law and any other applicable jurisdictions.</p>
</div>
</div></section>
@endsection
