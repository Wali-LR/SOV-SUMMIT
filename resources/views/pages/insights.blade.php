@extends('layouts.site')

@section('title', 'SOV SUMMIT Insights | Events, Travel & Management')
@section('meta_description', 'Practical insights on event planning, delegation management, management training, travel coordination, security coordination, and media coverage from SOV SUMMIT.')
@section('canonical', 'https://sov-summit.com/insights/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Insights", "item": "https://sov-summit.com/insights/"}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <span>Insights</span></div>
@endsection

@section('content')

<section class=""><div class="container">
<div class="section-head wide">
  <p class="brandline">INSIGHTS</p>
  <h1>Insights</h1>
  <p class="lede">Practical articles on event planning, delegations, management training, travel, security, and media coordination &mdash; drawn from how SOV SUMMIT actually plans and delivers programmes.</p>
</div>
</div></section>

<section class="alt"><div class="container">
<div class="section-head wide"><h2>Coming up</h2><p class="lede">We are publishing one article per topic cluster first, then expanding. Here is what is coming.</p></div>
<div class="card-grid"><div class="card"><h3>Event Planning</h3><p>How to Plan an International Conference</p><span class="card-link" style="color:var(--muted);">Coming soon</span></div><div class="card"><h3>Delegations</h3><p>What Does Delegation Management Include?</p><span class="card-link" style="color:var(--muted);">Coming soon</span></div><div class="card"><h3>Management Training</h3><p>How to Design an Effective Management Training Programme</p><span class="card-link" style="color:var(--muted);">Coming soon</span></div><div class="card"><h3>Travel</h3><p>Coordinating Private Aviation, Accommodation, and Ground Transportation</p><span class="card-link" style="color:var(--muted);">Coming soon</span></div><div class="card"><h3>Security</h3><p>What Is Security Coordination for Events?</p><span class="card-link" style="color:var(--muted);">Coming soon</span></div><div class="card"><h3>Media</h3><p>Why Professional Event Documentation Matters</p><span class="card-link" style="color:var(--muted);">Coming soon</span></div></div>
</div></section>

<section class="cta-band"><div class="container">
<h2>Have a question we haven&rsquo;t covered yet?</h2>
<p class="lede">Get in touch and we will help directly, or point you to what we know.</p>
<div class="cta-row"><a class="btn btn-primary" href="/contact">Contact Us</a></div>
</div></section>

@endsection
