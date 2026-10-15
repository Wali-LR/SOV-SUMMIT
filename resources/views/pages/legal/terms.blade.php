@extends('layouts.site')

@section('title', 'Terms and Conditions | SOV SUMMIT')
@section('meta_description', 'Terms and Conditions for use of sov-summit.com and engagement with Sovereign Summit GmbH (SOV SUMMIT).')
@section('canonical', 'https://sov-summit.com/terms-conditions/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Terms and Conditions", "item": "https://sov-summit.com/terms-conditions/"}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <span>Terms and Conditions</span></div>
@endsection

@section('content')
<section class=""><div class="container">
<div class="section-head wide">
  <p class="brandline">LEGAL</p>
  <h1>Terms and Conditions</h1>
</div>
<div class="prose" style="max-width:70ch;">
  <h2>1. About these terms</h2>
  <p>These terms and conditions govern your access to and use of this website (sov-summit.com) and the services offered by Sovereign Summit GmbH (&ldquo;SOV SUMMIT&rdquo;, &ldquo;we&rdquo;, &ldquo;us&rdquo;), a company registered in Zug, Switzerland, with registered office at Bahnhofstrasse 21, 6300 Zug. By using this website or engaging our services, you agree to these terms.</p>

  <h2>2. Services</h2>
  <p>SOV SUMMIT coordinates international events, management training, delegation management, travel experiences, security support, and media coverage for organisations, corporations, institutions, and private clients. The specific scope, deliverables, timelines, and fees for any engagement will be set out in a separate written proposal or agreement, which shall prevail over any inconsistent statement on this website.</p>

  <h2>3. Website content</h2>
  <p>The content on this website is provided for general information only. While we take reasonable care to keep information accurate and up to date, we make no representations or warranties, express or implied, about the completeness, accuracy, reliability, or availability of any information, images, or related materials. Content may be changed or removed at any time without notice.</p>

  <h2>4. Intellectual property</h2>
  <p>All content on this website &mdash; including text, graphics, logos, images, and layout &mdash; is the property of Sovereign Summit GmbH or its licensors and is protected by applicable intellectual property laws. You may view and print pages from this website for your own personal use, subject to these terms. You may not otherwise reproduce, republish, distribute, or commercially exploit any content without our prior written consent.</p>

  <h2>5. Enquiries and proposals</h2>
  <p>Submitting an enquiry through this website does not create a binding contract. Any engagement becomes binding only upon our written acceptance of an agreed scope of work and, where applicable, receipt of any required deposit or retainer.</p>

  <h2>6. Third-party links and providers</h2>
  <p>This website may reference or link to third-party services, venues, or providers. We are not responsible for the content, availability, or practices of any third party. Where a programme is delivered in coordination with external providers, their own terms may apply in addition to ours.</p>

  <h2>7. Confidentiality</h2>
  <p>We treat client details, programme information, and any sensitive material shared with us in the course of an engagement as confidential, subject to applicable law and any requirement to share information with providers strictly as needed to deliver the requested service.</p>

  <h2>8. Limitation of liability</h2>
  <p>To the fullest extent permitted by law, SOV SUMMIT shall not be liable for any indirect, incidental, consequential, or special losses arising out of or in connection with the use of this website or any information contained on it. Nothing in these terms limits any liability that cannot be limited under applicable Swiss law.</p>

  <h2>9. Privacy and cookies</h2>
  <p>Your use of this website is also governed by our <a href="/privacy-policy" style="color:var(--gold);">Privacy Policy</a> and <a href="/cookie-policy" style="color:var(--gold);">Cookie Policy</a>, which are incorporated into these terms by reference.</p>

  <h2>10. Changes to these terms</h2>
  <p>We may update these terms from time to time. The version in force is the one published on this page at the time of your visit or engagement. Material changes affecting an active engagement will be communicated in writing.</p>

  <h2>11. Governing law and jurisdiction</h2>
  <p>These terms and any dispute arising out of or in connection with them are governed by the substantive laws of Switzerland, excluding its conflict-of-laws rules and the United Nations Convention on Contracts for the International Sale of Goods (CISG). The courts of Zug, Switzerland, shall have exclusive jurisdiction, subject to any mandatory statutory provisions in favour of consumers.</p>

  <h2>12. Contact</h2>
  <p>Questions about these terms can be sent to <a href="mailto:info@sov-summit.com" style="color:var(--gold);">info@sov-summit.com</a>.</p>

  <p class="muted" style="margin-top:2rem;font-size:0.85rem;">These terms are provided as a starting template and should be reviewed by qualified legal counsel before publication to ensure full compliance with applicable Swiss law and any other jurisdictions in which SOV SUMMIT operates.</p>
</div>
</div></section>
@endsection
