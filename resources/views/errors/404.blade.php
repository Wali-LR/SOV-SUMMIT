@extends('layouts.site')

@section('title', 'Page Not Found | SOV SUMMIT')
@section('meta_description', "The page you're looking for doesn't exist or has moved.")

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
@endpush

@section('content')
<div class="container error-page">
  <div>
    <p class="brandline">404</p>
    <h1 style="max-width:16ch;">This page hasn&rsquo;t been coordinated yet.</h1>
    <p class="lede">The page you&rsquo;re looking for doesn&rsquo;t exist or has moved. Head back to the homepage or explore our services.</p>
    <div class="cta-row">
      <a class="btn btn-primary" href="/">Back to Home</a>
      <a class="btn btn-secondary" href="/services">Explore Services</a>
    </div>
  </div>
</div>
@endsection
