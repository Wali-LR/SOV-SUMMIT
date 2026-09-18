@extends('layouts.site')

@section('title', 'Privacy Policy | SOV SUMMIT')
@section('meta_description', 'Privacy Policy for SOV SUMMIT, describing how Sovereign Summit GmbH handles personal data submitted through this website.')
@section('canonical', 'https://sov-summit.com/privacy-policy/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Privacy Policy", "item": "https://sov-summit.com/privacy-policy/"}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <span>Privacy Policy</span></div>
@endsection

@section('content')
<section class=""><div class="container">
<div class="section-head wide">
  <p class="brandline">LEGAL</p>
  <h1>Privacy Policy</h1>
</div>
<div class="prose" style="max-width:70ch;">
  <h2>Who we are</h2>
  <p>This website is operated by Sovereign Summit GmbH (SOV SUMMIT), Bahnhofstrasse 21, 6300 Zug, Switzerland. For questions about this policy or your data, contact <a href="mailto:info@sov-summit.com" style="color:var(--gold);">info@sov-summit.com</a>.</p>

  <h2>What we collect</h2>
  <p>When you use the contact form on this website, we collect the information you choose to provide, which may include your name, company, email address, phone number, and details about the programme you are enquiring about. We do not knowingly collect data from anyone under the age required by applicable law to give consent.</p>

  <h2>How we use it</h2>
  <p>We use the information you submit to respond to your enquiry, prepare proposals, and coordinate the services you request. We do not sell your personal data to third parties.</p>

  <h2>Sharing with providers</h2>
  <p>Where a programme requires coordination with external providers (such as venues, aviation operators, accommodation, transportation, security, or media partners), we may share the relevant details with those providers strictly as needed to deliver the requested service.</p>

  <h2>Data retention</h2>
  <p>We retain enquiry and client data for as long as necessary to fulfil the purposes described above and to meet legal, accounting, or reporting obligations.</p>

  <h2>Your rights</h2>
  <p>Depending on your jurisdiction, you may have the right to access, correct, or request deletion of your personal data. To exercise these rights, contact us at <a href="mailto:info@sov-summit.com" style="color:var(--gold);">info@sov-summit.com</a>.</p>

  <h2>Cookies</h2>
  <p>See our <a href="/cookie-policy" style="color:var(--gold);">Cookie Policy</a> for details on how this website uses cookies and similar technologies.</p>

  <p class="muted" style="margin-top:2rem;font-size:0.85rem;">This privacy policy is provided as a starting template and should be reviewed by qualified legal counsel before publication to ensure full compliance with the Swiss Federal Act on Data Protection (FADP), GDPR where applicable, and any other relevant regulations.</p>
</div>
</div></section>
@endsection
