@extends('layouts.site')

@section('title', 'Cookie Policy | SOV SUMMIT')
@section('meta_description', 'Cookie Policy for SOV SUMMIT, explaining how cookies are used on sov-summit.com.')
@section('canonical', 'https://sov-summit.com/cookie-policy/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Cookie Policy", "item": "https://sov-summit.com/cookie-policy/"}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <span>Cookie Policy</span></div>
@endsection

@section('content')
<section class=""><div class="container">
<div class="section-head wide">
  <p class="brandline">LEGAL</p>
  <h1>Cookie Policy</h1>
</div>
<div class="prose" style="max-width:70ch;">
  <h2>What are cookies</h2>
  <p>Cookies are small text files placed on your device when you visit a website. They help the site function and, where enabled, help us understand how visitors use it.</p>

  <h2>Cookies we use</h2>
  <p>This website currently uses only cookies strictly necessary for basic functionality. If analytics or other non-essential cookies are added in future, this page will be updated and, where required, consent will be requested before they are set.</p>

  <h2>Managing cookies</h2>
  <p>You can control or delete cookies through your browser settings at any time. Restricting cookies may affect the functionality of some parts of this website.</p>

  <p class="muted" style="margin-top:2rem;font-size:0.85rem;">This cookie policy is provided as a starting template and should be reviewed by qualified legal counsel before publication, and updated to reflect any analytics or tracking tools actually deployed on the live site.</p>
</div>
</div></section>
@endsection
