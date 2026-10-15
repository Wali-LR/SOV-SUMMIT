@extends('layouts.site')

@section('title', 'Contact SOV SUMMIT | International Event and Travel Coordination')
@section('meta_description', 'Contact SOV SUMMIT for event planning, conferences, delegations, management training, travel experiences, security coordination, and media coverage.')
@section('canonical', 'https://sov-summit.com/contact/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home", "item": "https://sov-summit.com/"}, {"@type": "ListItem", "position": 2, "name": "Contact", "item": "https://sov-summit.com/contact/"}]}' !!}</script>
@endpush

@section('breadcrumb')
<div class="breadcrumb container"><a href="/">Home</a> / <span>Contact</span></div>
@endsection

@section('content')
<section class=""><div class="container">
<div class="section-head wide">
  <p class="brandline">CONTACT</p>
  <h1>Let&rsquo;s create something meaningful.</h1>
  <p class="lede">Tell us what you are planning, where it will take place, and what you need to coordinate. We will review your requirements and respond with the next appropriate steps.</p>
</div>
<div class="split">
  <div>
    <h2 style="font-size:1.3rem;font-family:var(--font-sans);font-weight:700;margin-bottom:1rem;">Contact us about</h2>
    <ul class="check-list" style="grid-template-columns:1fr;">
      <li>An upcoming conference or event</li>
      <li>A management training programme</li>
      <li>A government or corporate delegation</li>
      <li>A private flight or family trip</li>
      <li>A venue, accommodation, or transportation requirement</li>
      <li>A complete event or travel programme</li>
    </ul>
    <div style="margin-top:2rem;border-top:1px solid var(--line);padding-top:1.5rem;">
      <p style="margin-bottom:0.3rem;font-weight:700;">SOV SUMMIT</p>
      <p class="muted" style="margin-bottom:0.3rem;">Sovereign Summit GmbH</p>
      <p class="muted" style="margin-bottom:0.3rem;">Bahnhofstrasse 21, 6300 Zug, Switzerland</p>
      <p class="muted" style="margin-bottom:0.3rem;"><a href="mailto:info@sov-summit.com" style="color:var(--gold);">info@sov-summit.com</a></p>
      <p class="muted"><a href="https://wa.me/41798763573" style="color:var(--gold);">WhatsApp +41 79 876 35 73</a></p>
    </div>
  </div>
  <div>
    <form class="contact-form" action="#" method="POST" style="display:flex;flex-direction:column;gap:1rem;">
      @csrf
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Full name*
          <input type="text" name="full_name" required style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);" />
        </label>
        <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Company / organisation
          <input type="text" name="company" style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);" />
        </label>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Email*
          <input type="email" name="email" required style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);" />
        </label>
        <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Phone / WhatsApp
          <input type="tel" name="phone" style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);" />
        </label>
      </div>
      <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Service required*
        <select name="service" required style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);background:var(--paper);">
          <option value="">Select a service</option>
          <option>Planning & Coordination</option>
          <option>Management Training</option>
          <option>Conference Planning</option>
          <option>Delegation Management</option>
          <option>Events & Productions</option>
          <option>Security Coordination</option>
          <option>Media Coverage</option>
          <option>Travel & Experiences</option>
          <option>Other</option>
        </select>
      </label>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Event / programme type
          <input type="text" name="programme_type" style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);" />
        </label>
        <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Destination
          <input type="text" name="destination" style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);" />
        </label>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
        <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Preferred date
          <input type="date" name="preferred_date" style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);" />
        </label>
        <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Participants
          <input type="number" min="1" name="participants" style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);" />
        </label>
        <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Duration
          <input type="text" name="duration" placeholder="e.g. 3 days" style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);" />
        </label>
      </div>
      <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Required services
        <input type="text" name="required_services" placeholder="e.g. venue, accommodation, transport" style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);" />
      </label>
      <label style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">Message*
        <textarea name="message" rows="5" required style="padding:0.7rem;border:1px solid var(--line-strong);font-family:var(--font-sans);resize:vertical;"></textarea>
      </label>
      <label style="display:flex;align-items:flex-start;gap:0.6rem;font-size:0.85rem;color:var(--ink-soft);">
        <input type="checkbox" name="consent" required style="margin-top:0.2rem;" />
        <span>I consent to SOV SUMMIT processing my data in accordance with the <a href="/privacy-policy" style="color:var(--gold);">Privacy Policy</a>.*</span>
      </label>
      <button type="submit" class="btn btn-primary" style="align-self:flex-start;">Send Enquiry</button>
      <p class="muted" style="font-size:0.82rem;">Thank you for contacting SOV SUMMIT. Your enquiry has been received and will be reviewed shortly.</p>
    </form>
  </div>
</div>
</div></section>
@endsection
