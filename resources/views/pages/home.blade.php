@extends('layouts.site')

@section('title', 'SOV SUMMIT | International Event Organisation & Coordination')
@section('meta_description', 'SOV SUMMIT coordinates international events, conferences, delegations, management training, travel experiences, security support, and media coverage for organisations, corporations, institutions, and private clients.')
@section('canonical', 'https://sov-summit.com/')

@push('head')
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "Organization", "name": "SOV SUMMIT", "legalName": "Sovereign Summit GmbH", "url": "https://sov-summit.com/", "logo": "https://sov-summit.com/assets/img/logo.webp", "email": "info@sov-summit.com", "address": {"@type": "PostalAddress", "streetAddress": "Bahnhofstrasse 21", "addressLocality": "Zug", "postalCode": "6300", "addressCountry": "CH"}, "slogan": "People. Ideas. Impact."}' !!}</script>
<script type="application/ld+json">{!! '{"@context": "https://schema.org", "@type": "WebSite", "name": "SOV SUMMIT", "url": "https://sov-summit.com/"}' !!}</script>
@php
    $faqPage = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect([
            ['What does Sov Summit do?', 'Sov Summit provides end-to-end event and delegation coordination. We help clients source, compare, coordinate, and supervise services such as chauffeur transportation, logistics, accommodation, event support, and other specialist services.'],
            ['How can Sov Summit help with chauffeur services?', 'We identify suitable chauffeur providers based on your requirements, including vehicle category, passenger numbers, language requirements, itinerary, service level, and budget. We compare available options, negotiate competitive rates, coordinate the booking, and supervise the service.'],
            ['Can Sov Summit arrange accommodation?', 'Yes. We can source and coordinate hotels and other accommodation based on your preferred location, category, room requirements, budget, and guest profile.'],
            ['Can you manage the entire transportation and logistics operation?', 'Yes. We can coordinate multiple vehicles, drivers, routes, schedules, airport transfers, meet & greet, group movements, flight information, and on-site transportation requirements.'],
            ['How do you select your vendors?', 'We select providers according to the specific requirements of each project. Depending on the service, we assess factors such as experience, licensing, availability, service capability, reputation, and other relevant credentials. Where appropriate, we also conduct background and compliance checks.'],
            ['Do you work with your own vendors?', 'We work with both established partners and external providers. Existing partners can be used where they meet the requirements, while additional providers can be sourced when a project requires specific capabilities, locations, or capacity.'],
            ['Can you negotiate better rates?', 'We use our network and purchasing volume to obtain competitive offers. Where multiple suitable providers are available, we can compare proposals and negotiate commercial terms on the client’s behalf.'],
            ['Will I know what I am paying for?', 'Yes. We aim to provide clear and transparent proposals, with the services, quantities, pricing, and relevant conditions clearly defined.'],
            ['Why shouldn’t I contact the vendors directly?', 'You can, but managing multiple vendors can become time-consuming and complicated. Sov Summit provides a single coordination layer between you and the different suppliers, helping manage communication, scheduling, pricing, documentation, and execution.'],
            ['Who supervises the services?', 'Depending on the project scope, Sov Summit can provide dedicated coordination and operational supervision throughout the event or delegation.'],
            ['What happens if a vendor fails to deliver?', 'We work to identify reliable providers and maintain operational oversight. Where applicable and contractually agreed, Sov Summit can provide service guarantees or remedies for qualifying service failures.'],
            ['Do you provide a mobile app or platform?', 'Sov Summit is designed to organize services through a central digital platform, giving clients an easier way to manage vendors, services, schedules, and operational information.'],
            ['Can I see all the vendors and services in one place?', 'Yes. The objective is to bring fragmented services into one organized operational environment, making it easier to understand what has been booked, who is responsible, when the service takes place, and what has been agreed.'],
            ['Can Sov Summit handle VIP or diplomatic delegations?', 'Yes. We can coordinate requirements for VIP, corporate, diplomatic, executive, and other high-profile delegations, subject to the specific requirements and applicable regulations.'],
            ['Can you coordinate services in different countries?', 'Yes. Sov Summit can source and coordinate services across multiple destinations through its network of local and international partners.'],
            ['Do you provide security services?', 'Sov Summit can coordinate appropriate third-party security or related services where required. We do not present ourselves as a licensed security provider unless the relevant service is provided by an appropriately licensed partner.'],
            ['How far in advance should I contact Sov Summit?', 'The earlier you contact us, the more options we generally have for sourcing, negotiation, verification, and operational planning. However, we can also assist with urgent and short-notice requirements subject to availability.'],
            ['Can I request only one service?', 'Yes. You can use Sov Summit for a single requirement — such as chauffeur transportation or accommodation — or for complete event and delegation coordination.'],
            ['What information do you need to prepare a proposal?', 'Typically we need: event or travel dates, destination(s), number of guests, required services, itinerary or schedule, vehicle requirements, accommodation requirements, preferred service level, any special requirements, and budget if available.'],
            ['What is the main advantage of using Sov Summit?', 'The event-services market is fragmented. Instead of managing numerous suppliers yourself, Sov Summit brings the relevant services together into one coordinated solution. We source. We verify. We negotiate. We coordinate. We supervise.'],
            ['What makes Sov Summit different?', 'Sov Summit combines specialist advisors, a vendor network, commercial sourcing, operational coordination, and technology. Our objective is simple: give you one place to organize multiple services — with transparency, control, and professional execution.'],
        ])->map(fn ($qa) => [
            '@type' => 'Question',
            'name' => $qa[0],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $qa[1]],
        ])->all(),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($faqPage, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')

<section class="hero" id="hero">
  <div class="hero-bg" aria-hidden="true">
    <div class="hero-aura"></div>
    <div class="hero-gradient hero-gradient--tr"></div>
    <div class="hero-gradient hero-gradient--bl"></div>
    <div class="hero-grain"></div>
  </div>

  <div class="container hero-inner">
    <div class="hero-grid">
      <div class="hero-lead">
        <div class="brand-rule" aria-hidden="true">
          <span class="brand-rule__line"></span>
          <span class="brand-rule__dot"></span>
        </div>
        <p class="brandline reveal" data-reveal="up">PEOPLE <span class="brandline-dot">&middot;</span> IDEAS <span class="brandline-dot">&middot;</span> IMPACT</p>
        <div class="hero-diagram hero-diagram--inline" aria-label="How SOV SUMMIT delivers a successful event">
          <div class="hero-diagram__orbit hero-diagram__orbit--outer" aria-hidden="true"></div>
          <div class="hero-diagram__orbit hero-diagram__orbit--mid" aria-hidden="true"></div>
          <div class="hero-diagram__orbit hero-diagram__orbit--inner" aria-hidden="true"></div>
          <div class="hero-diagram__ring">
            <svg class="hero-diagram__lines" viewBox="0 0 400 400" preserveAspectRatio="none" aria-hidden="true">
              <defs>
                <radialGradient id="heroDiagramGlowInline" cx="50%" cy="50%" r="50%">
                  <stop offset="0%" stop-color="rgba(201,162,75,0.8)"/>
                  <stop offset="100%" stop-color="rgba(201,162,75,0)"/>
                </radialGradient>
              </defs>
              <circle class="hero-diagram__halo" cx="200" cy="200" r="80" fill="url(#heroDiagramGlowInline)"/>
              <line class="hero-diagram__line" data-line="1" x1="200" y1="200" x2="200" y2="60"/>
              <line class="hero-diagram__line" data-line="2" x1="200" y1="200" x2="322" y2="130"/>
              <line class="hero-diagram__line" data-line="3" x1="200" y1="200" x2="322" y2="270"/>
              <line class="hero-diagram__line" data-line="4" x1="200" y1="200" x2="200" y2="340"/>
              <line class="hero-diagram__line" data-line="5" x1="200" y1="200" x2="78" y2="270"/>
              <line class="hero-diagram__line" data-line="6" x1="200" y1="200" x2="78" y2="130"/>
              <circle class="hero-diagram__particle" data-particle="1" r="3.2" cx="200" cy="200"/>
              <circle class="hero-diagram__particle" data-particle="2" r="3.2" cx="200" cy="200"/>
              <circle class="hero-diagram__particle" data-particle="3" r="3.2" cx="200" cy="200"/>
              <circle class="hero-diagram__particle" data-particle="4" r="3.2" cx="200" cy="200"/>
              <circle class="hero-diagram__particle" data-particle="5" r="3.2" cx="200" cy="200"/>
              <circle class="hero-diagram__particle" data-particle="6" r="3.2" cx="200" cy="200"/>
            </svg>

            <div class="hero-diagram__center">
              <span class="hero-diagram__pulse" aria-hidden="true"></span>
              <svg class="hero-diagram__center-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M4 8l3.5 9h9L20 8l-4.5 3L12 5 8.5 11z" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" stroke-linecap="round"/>
                <path d="M6.5 19h11" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
              </svg>
              <span class="hero-diagram__center-label">Successful<br>event</span>
            </div>

            <div class="hero-diagram__node" data-pos="top" data-node="1" style="--i:1">
              <span class="hero-diagram__node-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M5 21V6l7-3 7 3v15" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M9 21v-5h6v5M10 9h1M13 9h1M10 12h1M13 12h1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              </span>
              <span class="hero-diagram__node-label">Venue</span>
            </div>

            <div class="hero-diagram__node" data-pos="tr" data-node="2" style="--i:2">
              <span class="hero-diagram__node-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M12 3l1.8 8L21 13l-6 3 1 5-4-3-4 3 1-5-6-3 7.2-2z" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M9 17.5l1.5-1.5m4.5 1.5l-1.5-1.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              </span>
              <span class="hero-diagram__node-label">Security</span>
            </div>

            <div class="hero-diagram__node" data-pos="br" data-node="3" style="--i:3">
              <span class="hero-diagram__node-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M4 17V9h10l3 3h3v5" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><circle cx="8" cy="18" r="1.8" fill="none" stroke="currentColor" stroke-width="1.4"/><circle cx="17" cy="18" r="1.8" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M4 17h1.5M10 17h5M19 17h1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              </span>
              <span class="hero-diagram__node-label">Transportation</span>
            </div>

            <div class="hero-diagram__node" data-pos="bottom" data-node="4" style="--i:4">
              <span class="hero-diagram__node-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M3 16V8h11v8m0-6h4l3 3v3" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><circle cx="7" cy="18" r="1.6" fill="none" stroke="currentColor" stroke-width="1.4"/><circle cx="17" cy="18" r="1.6" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M3 16h2.5M8.5 16h6.5M18.5 16H21" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
              </span>
              <span class="hero-diagram__node-label">Logistics</span>
            </div>

            <div class="hero-diagram__node" data-pos="bl" data-node="5" style="--i:5">
              <span class="hero-diagram__node-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M3 18v-5a2 2 0 012-2h9v7M3 18h18M21 18v-4h-7" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M6 11V8h4v3" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
              </span>
              <span class="hero-diagram__node-label">Accommodation</span>
            </div>

            <div class="hero-diagram__node" data-pos="tl" data-node="6" style="--i:6">
              <span class="hero-diagram__node-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M3 15l8-2 3-8 2 1-1 6 6-1 1 2-7 4-2 8-2-1 1-6-8 1z" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
              </span>
              <span class="hero-diagram__node-label">Private Flight</span>
            </div>
          </div>
        </div>
        <h1 class="hero-title" data-reveal-words>
          Connecting the dots. Delivering <em>successful</em> events.
        </h1>
        <p class="hero-subtitle reveal" data-reveal="up" data-reveal-delay="180">International coordination for events, delegations, and executive programmes.</p>
        <div class="cta-row reveal" data-reveal="up" data-reveal-delay="280">
          <a class="btn btn-primary btn-magnetic" href="/services">
            <span class="btn-label">Explore Our Services</span>
            <span class="btn-shimmer" aria-hidden="true"></span>
            <svg class="btn-arrow" width="14" height="14" viewBox="0 0 14 14" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
          <a class="btn btn-secondary btn-magnetic" href="/contact">
            <span class="btn-label">Talk with us</span>
          </a>
        </div>
      </div>
    </div>

    <div class="hero-meta">
      <div class="hero-marquee" aria-hidden="true">
        <div class="hero-marquee__track">
          <span>Governments</span><span class="dot">&bull;</span>
          <span>Corporations</span><span class="dot">&bull;</span>
          <span>Institutions</span><span class="dot">&bull;</span>
          <span>Private Clients</span><span class="dot">&bull;</span>
          <span>Delegations</span><span class="dot">&bull;</span>
          <span>Conferences</span><span class="dot">&bull;</span>
          <span>Media Coverage</span><span class="dot">&bull;</span>
          <span>Governments</span><span class="dot">&bull;</span>
          <span>Corporations</span><span class="dot">&bull;</span>
          <span>Institutions</span><span class="dot">&bull;</span>
          <span>Private Clients</span><span class="dot">&bull;</span>
          <span>Delegations</span><span class="dot">&bull;</span>
          <span>Conferences</span><span class="dot">&bull;</span>
          <span>Media Coverage</span><span class="dot">&bull;</span>
        </div>
      </div>

      <a class="scroll-cue" href="#about-anchor" aria-label="Scroll to next section">
        <span class="scroll-cue__label">Scroll</span>
        <span class="scroll-cue__line" aria-hidden="true"><span></span></span>
      </a>
    </div>
  </div>
</section>

<span id="about-anchor" aria-hidden="true"></span>

<section class="approach" aria-labelledby="approach-heading">
  <div class="container">
    <header class="approach__head">
      <span class="eyebrow">The SOV Summit Method</span>
      <h2 id="approach-heading">One partner. The right connections. A successful result.</h2>
    </header>

    <div class="approach__grid">
      <article class="approach__card">
        <img class="approach__image" src="{{ asset('assets/img/services/planning.webp') }}" alt="" aria-hidden="true" loading="lazy" width="800" height="1000">
        <div class="approach__overlay" aria-hidden="true"></div>
        <div class="approach__body">
          <span class="approach__num" aria-hidden="true">01</span>
          <h3>Planning</h3>
          <p>We develop the operational structure behind your programme, coordinate requirements, and identify suitable providers.</p>
        </div>
      </article>

      <article class="approach__card">
        <img class="approach__image" src="{{ asset('assets/img/services/delegation.webp') }}" alt="" aria-hidden="true" loading="lazy" width="800" height="1000">
        <div class="approach__overlay" aria-hidden="true"></div>
        <div class="approach__body">
          <span class="approach__num" aria-hidden="true">02</span>
          <h3>Connecting</h3>
          <p>We bring together venues, transportation, accommodation, aviation, production, security, hospitality, and media partners.</p>
        </div>
      </article>

      <article class="approach__card">
        <img class="approach__image" src="{{ asset('assets/img/services/events.webp') }}" alt="" aria-hidden="true" loading="lazy" width="800" height="1000">
        <div class="approach__overlay" aria-hidden="true"></div>
        <div class="approach__body">
          <span class="approach__num" aria-hidden="true">03</span>
          <h3>Delivering</h3>
          <p>We coordinate the moving parts before and during the programme so that the final experience is organised, professional, and reliable.</p>
        </div>
      </article>
    </div>
  </div>
</section>

<section class="pillars" aria-labelledby="pillars-heading" itemscope itemtype="https://schema.org/Organization">
  <div class="pillars__glow" aria-hidden="true"></div>
  <div class="container">
    <header class="pillars__head">
      <span class="eyebrow">Our Approach</span>
      <h2 id="pillars-heading" itemprop="slogan">Every successful event begins with the right connections.</h2>
      <p class="lede" itemprop="description">SOV SUMMIT is an international coordination partner for events, executive programmes, delegations, and complex multi-provider engagements &mdash; bringing structure, discretion, and reliability to every stage.</p>
    </header>

    <div class="pillars__grid">
      <article class="pillar">
        <span class="pillar__num" aria-hidden="true">01</span>
        <h3>The Challenge</h3>
        <p>Complex events and international programmes require more than a good idea. They demand reliable providers, clear communication, precise planning, and someone who understands how the individual elements fit together.</p>
      </article>

      <article class="pillar">
        <span class="pillar__num" aria-hidden="true">02</span>
        <h3>Our Method</h3>
        <p>SOV SUMMIT brings these elements together through one coordinated approach. We help clients plan, connect, organise, and deliver programmes that require discretion, flexibility, and attention to detail.</p>
      </article>

      <article class="pillar">
        <span class="pillar__num" aria-hidden="true">03</span>
        <h3>Your Outcome</h3>
        <p>One accountable partner across every element of your programme &mdash; a process that stays clear for the client and consistent for every provider involved.</p>
      </article>
    </div>
  </div>
</section>

<section class="services" aria-labelledby="services-heading">
  <div class="container">
    <header class="services__head">
      <span class="eyebrow">What We Coordinate</span>
      <h2 id="services-heading">A single point of coordination across every element of your programme.</h2>
      <p class="lede">From strategic planning to on-the-ground delivery, SOV SUMMIT coordinates the people, providers, and details behind ambitious international programmes.</p>
    </header>

    <div class="services__grid">
      <a class="service-card" href="/services/planning-coordination" aria-label="Explore Planning &amp; Coordination">
        <div class="service-card__media">
          <img src="{{ asset('assets/img/services/planning.webp') }}" alt="Grand venue prepared for an international programme" loading="lazy" width="600" height="400">
        </div>
        <div class="service-card__body">
          <h3>Planning &amp; Coordination</h3>
          <p>From initial concept to final delivery, we coordinate the people, suppliers, schedules, and operational details required for successful programmes.</p>
          <span class="service-card__cta">Explore <span aria-hidden="true">&rarr;</span></span>
        </div>
      </a>

      <a class="service-card" href="/management-training" aria-label="Explore Management Training">
        <div class="service-card__media">
          <img src="{{ asset('assets/img/services/management-training.webp') }}" alt="Executive management training session in a wood-panelled hall" loading="lazy" width="600" height="400">
        </div>
        <div class="service-card__body">
          <h3>Management Training</h3>
          <p>Bespoke management training programmes, executive learning, leadership development, team workshops, and international learning experiences.</p>
          <span class="service-card__cta">Explore <span aria-hidden="true">&rarr;</span></span>
        </div>
      </a>

      <a class="service-card" href="/services/conference-planning" aria-label="Explore Conferences &amp; Delegations">
        <div class="service-card__media">
          <img src="{{ asset('assets/img/services/conferences.webp') }}" alt="International diplomatic conference with delegates and flags" loading="lazy" width="600" height="400">
        </div>
        <div class="service-card__body">
          <h3>Conferences &amp; Delegations</h3>
          <p>International conferences, executive forums, government and institutional delegations, site visits, hospitality, and protocol coordination.</p>
          <span class="service-card__cta">Explore <span aria-hidden="true">&rarr;</span></span>
        </div>
      </a>

      <a class="service-card" href="/services/events-productions" aria-label="Explore Events &amp; Productions">
        <div class="service-card__media">
          <img src="{{ asset('assets/img/services/events.webp') }}" alt="Elegant chandelier-lit gala reception" loading="lazy" width="600" height="400">
        </div>
        <div class="service-card__body">
          <h3>Events &amp; Productions</h3>
          <p>Corporate events, celebrations, concerts, film festivals, fashion shows, road shows, and private productions.</p>
          <span class="service-card__cta">Explore <span aria-hidden="true">&rarr;</span></span>
        </div>
      </a>

      <a class="service-card" href="/services/security-coordination" aria-label="Explore Security Coordination">
        <div class="service-card__media">
          <img src="{{ asset('assets/img/services/security.webp') }}" alt="VIP protection detail escorting a client to a black SUV" loading="lazy" width="600" height="400">
        </div>
        <div class="service-card__body">
          <h3>Security Coordination</h3>
          <p>Coordination with suitable licensed security providers for events, delegations, executive programmes, travel, and VIP requirements.</p>
          <span class="service-card__cta">Explore <span aria-hidden="true">&rarr;</span></span>
        </div>
      </a>

      <a class="service-card" href="/services/media-coverage" aria-label="Explore Media Coverage">
        <div class="service-card__media">
          <img src="{{ asset('assets/img/services/media.webp') }}" alt="Photographers and press covering an international event" loading="lazy" width="600" height="400">
        </div>
        <div class="service-card__body">
          <h3>Media Coverage</h3>
          <p>Event photography, video production, documentation, press coordination, and post-event visual content.</p>
          <span class="service-card__cta">Explore <span aria-hidden="true">&rarr;</span></span>
        </div>
      </a>
    </div>

    <div class="services__footer">
      <a class="btn btn-secondary btn-magnetic" href="/services">
        <span class="btn-label">View All Services</span>
        <svg class="btn-arrow" width="14" height="14" viewBox="0 0 14 14" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>
</section>

<section class="helps" aria-labelledby="helps-heading">
  <div class="container">
    <header class="helps__head">
      <span class="eyebrow">How Sov Summit Helps You</span>
      <h2 id="helps-heading">We simplify the complex process of planning and executing high-level events.</h2>
      <p class="lede">We identify the right vendors across every service category, verify them, negotiate the best rates, and organise every service, schedule, and requirement through a single, easy-to-execute platform &mdash; giving you complete visibility and control throughout the event.</p>
    </header>

    <ul class="helps__grid" role="list">
      <li class="help-card help-card--feature" tabindex="0">
        <div class="help-card__accent" aria-hidden="true"></div>
        <span class="help-card__num" aria-hidden="true">01</span>
        <span class="help-card__icon" aria-hidden="true">
          <svg viewBox="0 0 32 32" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 4v3M16 25v3M4 16h3M25 16h3M7.5 7.5l2.1 2.1M22.4 22.4l2.1 2.1M7.5 24.5l2.1-2.1M22.4 9.6l2.1-2.1"/>
            <circle cx="16" cy="16" r="6.5"/>
            <path d="M16 12.5v3.5l2.4 1.6"/>
          </svg>
        </span>
        <h3>Best Deal in the Market</h3>
        <p>We source competitive offers from multiple qualified vendors and negotiate the best available terms for your requirements.</p>
        <span class="help-card__meta">Multi-vendor tendering</span>
      </li>

      <li class="help-card" tabindex="0">
        <div class="help-card__accent" aria-hidden="true"></div>
        <span class="help-card__num" aria-hidden="true">02</span>
        <span class="help-card__icon" aria-hidden="true">
          <svg viewBox="0 0 32 32" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 4C9.5 8 5 10 4 10c0 8 4.6 14.5 12 18 7.4-3.5 12-10 12-18-1 0-5.5-2-12-6z"/>
            <path d="M11.5 16.5l3.2 3.2L21 13.5"/>
          </svg>
        </span>
        <h3>Total Transparency</h3>
        <p>Clear pricing, verified vendors, defined services, and complete visibility of what has been arranged.</p>
        <span class="help-card__meta">Full audit trail</span>
      </li>

      <li class="help-card" tabindex="0">
        <div class="help-card__accent" aria-hidden="true"></div>
        <span class="help-card__num" aria-hidden="true">03</span>
        <span class="help-card__icon" aria-hidden="true">
          <svg viewBox="0 0 32 32" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
            <rect x="4" y="6" width="24" height="16" rx="1.5"/>
            <path d="M4 10h24M10 26h12M16 22v4"/>
            <path d="M9 15h4M9 18h6M18 15h5M18 18h3"/>
          </svg>
        </span>
        <h3>Easy-to-Execute Platform</h3>
        <p>Manage vendors, services, schedules, contacts, and operational requirements through one simple platform.</p>
        <span class="help-card__meta">One control room</span>
      </li>

      <li class="help-card" tabindex="0">
        <div class="help-card__accent" aria-hidden="true"></div>
        <span class="help-card__num" aria-hidden="true">04</span>
        <span class="help-card__icon" aria-hidden="true">
          <svg viewBox="0 0 32 32" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="16" cy="14" r="5"/>
            <path d="M16 9V4M16 24v4M6 14h5M21 14h5"/>
            <path d="M11.5 25.5C12.5 23 14 22 16 22s3.5 1 4.5 3.5"/>
          </svg>
        </span>
        <h3>Total Supervision</h3>
        <p>We coordinate and monitor the delivery of services throughout the event, helping ensure everything happens as planned.</p>
        <span class="help-card__meta">On-ground oversight</span>
      </li>

      <li class="help-card" tabindex="0">
        <div class="help-card__accent" aria-hidden="true"></div>
        <span class="help-card__num" aria-hidden="true">05</span>
        <span class="help-card__icon" aria-hidden="true">
          <svg viewBox="0 0 32 32" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 3l3.4 6.9 7.6 1.1-5.5 5.4 1.3 7.6L16 20.4l-6.8 3.6 1.3-7.6L5 11l7.6-1.1z"/>
          </svg>
        </span>
        <h3>Guaranteed Service Quality</h3>
        <p>Our service commitment includes quality control and, where contractually agreed, a money-back guarantee for qualifying service failures.</p>
        <span class="help-card__meta">Money-back guarantee</span>
      </li>
    </ul>
  </div>
</section>

<section class="why-sov" aria-labelledby="why-sov-heading">
  <div class="why-sov__glow" aria-hidden="true"></div>
  <div class="container">
    <header class="why-sov__head">
      <span class="eyebrow eyebrow--gold">Why Sov Summit?</span>
      <h2 id="why-sov-heading">From fragmented vendors to one organised event operation.</h2>
      <p class="lede">The event-services market is highly fragmented &mdash; multiple vendors, inconsistent service standards, limited transparency, and unnecessary markups. Rarely is there a single operational tool connecting, organising, and supervising every provider throughout an event.</p>
    </header>

    <div class="why-sov__compare">
      <article class="compare-card compare-card--before" aria-labelledby="compare-before">
        <span class="compare-card__tag">Without Sov Summit</span>
        <h3 id="compare-before">A fragmented market</h3>
        <div class="compare-card__viz compare-card__viz--scatter" aria-hidden="true">
          <span class="node" style="--x:12%;--y:22%;--d:0s"><span></span></span>
          <span class="node" style="--x:74%;--y:14%;--d:.4s"><span></span></span>
          <span class="node" style="--x:88%;--y:58%;--d:.9s"><span></span></span>
          <span class="node" style="--x:32%;--y:74%;--d:.6s"><span></span></span>
          <span class="node" style="--x:56%;--y:44%;--d:.2s"><span></span></span>
          <span class="node" style="--x:8%;--y:62%;--d:1.1s"><span></span></span>
          <span class="node" style="--x:70%;--y:82%;--d:.8s"><span></span></span>
        </div>
        <ul class="compare-card__list" role="list">
          <li>Multiple disconnected vendors</li>
          <li>Inconsistent service standards</li>
          <li>Limited pricing transparency</li>
          <li>Unnecessary markups</li>
          <li>No single operational tool</li>
        </ul>
      </article>

      <div class="why-sov__transform" aria-hidden="true">
        <svg viewBox="0 0 60 60" width="46" height="46" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M8 30h44M38 16l14 14-14 14"/>
        </svg>
        <span class="why-sov__transform-label">Sov Summit brings<br>the ecosystem together</span>
      </div>

      <article class="compare-card compare-card--after" aria-labelledby="compare-after">
        <span class="compare-card__tag">With Sov Summit</span>
        <h3 id="compare-after">One organised operation</h3>
        <div class="compare-card__viz compare-card__viz--hub" aria-hidden="true">
          <span class="hub-core"><span></span></span>
          <span class="hub-node" style="--a:-90deg"><span></span></span>
          <span class="hub-node" style="--a:-38deg"><span></span></span>
          <span class="hub-node" style="--a:14deg"><span></span></span>
          <span class="hub-node" style="--a:66deg"><span></span></span>
          <span class="hub-node" style="--a:118deg"><span></span></span>
          <span class="hub-node" style="--a:170deg"><span></span></span>
          <span class="hub-node" style="--a:-142deg"><span></span></span>
          <svg class="hub-lines" viewBox="0 0 200 200" preserveAspectRatio="xMidYMid meet">
            <circle cx="100" cy="100" r="72" fill="none" stroke="currentColor" stroke-width="0.6" stroke-dasharray="2 4"/>
          </svg>
        </div>
        <ul class="compare-card__list" role="list">
          <li>Vendor sourcing &amp; verification</li>
          <li>Negotiation &amp; coordination</li>
          <li>Technology &amp; supervision</li>
          <li>Clear pricing &amp; accountability</li>
          <li>One integrated solution</li>
        </ul>
      </article>
    </div>

    <div class="why-sov__signature">
      <span class="why-sov__sig-rule" aria-hidden="true"></span>
      <p class="why-sov__sig-line">
        <span>Sov Summit</span>
        <span class="why-sov__sig-dash">&mdash;</span>
        <span class="why-sov__sig-tag">People. Ideas. Impact.</span>
      </p>
      <span class="why-sov__sig-rule" aria-hidden="true"></span>
    </div>
  </div>
</section>

@if (!empty($featuredEvents) && $featuredEvents->isNotEmpty())
<section class="featured-events" aria-labelledby="featured-events-heading">
  <div class="container">
    <header class="featured-events__head">
      <span class="eyebrow">Featured Events</span>
      <h2 id="featured-events-heading">Programmes coordinated with care.</h2>
      <p class="lede">A short selection of recent and upcoming engagements from our coordination desk.</p>
    </header>
    <div class="events-grid">
      @foreach ($featuredEvents as $event)
        @include('partials.event-card', ['event' => $event])
      @endforeach
    </div>
    <div class="featured-events__cta-row">
      <a class="btn btn-secondary" href="{{ route('events.index') }}">
        <span class="btn-label">View all events</span>
        <svg class="btn-arrow" width="14" height="14" viewBox="0 0 14 14" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>
</section>
@endif

<section class="clients alt" aria-labelledby="clients-heading">
  <div class="container">
    <div class="clients__layout">
      <aside class="clients__intro">
        <span class="eyebrow">Who We Serve</span>
        <h2 id="clients-heading">Designed for complex requirements.</h2>
        <p class="lede">SOV SUMMIT works with clients who require more than a single service provider &mdash; coordinating the details behind programmes involving multiple destinations, suppliers, participants, and operational requirements.</p>
        <div class="clients__stat">
          <span class="clients__stat-num">8</span>
          <span class="clients__stat-label">Client sectors served across international programmes</span>
        </div>
      </aside>

      <ul class="clients__grid" role="list">
        <li class="client-card">
          <span class="client-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M4 21V10l8-5 8 5v11M9 21v-6h6v6M9 10h.01M12 10h.01M15 10h.01"/></svg>
          </span>
          <div>
            <h3>Governments &amp; Public Institutions</h3>
            <p>Delegations, official visits, and inter-governmental programmes.</p>
          </div>
        </li>

        <li class="client-card">
          <span class="client-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a13 13 0 0 1 0 18M12 3a13 13 0 0 0 0 18"/></svg>
          </span>
          <div>
            <h3>Diplomatic &amp; Institutional Organisations</h3>
            <p>Multilateral forums, missions, and cross-border coordination.</p>
          </div>
        </li>

        <li class="client-card">
          <span class="client-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V7l6-4 6 4v14M15 21V11h6v10M9 9h.01M9 13h.01M9 17h.01"/></svg>
          </span>
          <div>
            <h3>Corporations &amp; Executive Teams</h3>
            <p>Off-sites, leadership retreats, and executive programmes.</p>
          </div>
        </li>

        <li class="client-card">
          <span class="client-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.4 4.9 5.4.8-3.9 3.8.9 5.4L12 14.3l-4.8 2.6.9-5.4L4.2 7.7l5.4-.8L12 2z"/></svg>
          </span>
          <div>
            <h3>Family Offices &amp; Private Clients</h3>
            <p>Discreet planning for private events, travel, and hospitality.</p>
          </div>
        </li>

        <li class="client-card">
          <span class="client-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.2"/><circle cx="17" cy="10" r="2.6"/><path d="M3 20c0-3 2.7-5 6-5s6 2 6 5M14 20c0-2.2 2-4 4.5-4s4.5 1.8 4.5 4"/></svg>
          </span>
          <div>
            <h3>International Associations</h3>
            <p>Congresses, annual meetings, and member gatherings.</p>
          </div>
        </li>

        <li class="client-card">
          <span class="client-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="1"/><path d="M3 9h18M8 5V3M16 5V3"/></svg>
          </span>
          <div>
            <h3>Event Organisers &amp; Production Companies</h3>
            <p>Local coordination, venue access, and on-ground support.</p>
          </div>
        </li>

        <li class="client-card">
          <span class="client-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V6l8-3 8 3v13M8 19V11h8v8M12 3v3"/></svg>
          </span>
          <div>
            <h3>Management &amp; Training Organisations</h3>
            <p>Executive learning, workshops, and international programmes.</p>
          </div>
        </li>

        <li class="client-card">
          <span class="client-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12l8-8 8 8-8 8-8-8zM22 12l-2-2M22 12l-2 2"/></svg>
          </span>
          <div>
            <h3>Private Families &amp; Travelling Groups</h3>
            <p>Aviation, accommodation, and bespoke travel experiences.</p>
          </div>
        </li>
      </ul>
    </div>
  </div>
</section>

<section class="values" aria-labelledby="values-heading">
  <div class="container">
    <div class="values__layout">
      <figure class="values__figure">
        <img src="{{ asset('assets/img/img-8.webp') }}" alt="Executive audience seated in a marble conference hall" width="900" height="1100" loading="lazy">
        <figcaption class="values__caption">
          <span class="values__caption-label">In Practice</span>
          <span class="values__caption-text">Every element &mdash; from venue and stage to protocol and hospitality &mdash; coordinated as one programme.</span>
        </figcaption>
      </figure>

      <div class="values__content">
        <header class="values__head">
          <span class="eyebrow">Why SOV Summit</span>
          <h2 id="values-heading">Coordination with purpose.</h2>
          <p class="lede">Four principles shape how we work with clients, suppliers, and every detail in between &mdash; the foundation of every SOV Summit engagement.</p>
        </header>

        <ol class="values__list" role="list">
          <li class="value-row">
            <span class="value-row__num" aria-hidden="true">01</span>
            <div class="value-row__body">
              <h3>One point of coordination</h3>
              <p>Clients manage multiple requirements through a single central partner &mdash; one team, one accountable line of communication.</p>
            </div>
          </li>

          <li class="value-row">
            <span class="value-row__num" aria-hidden="true">02</span>
            <div class="value-row__body">
              <h3>International provider network</h3>
              <p>We identify and coordinate suitable local and international providers according to the exact requirements of each programme.</p>
            </div>
          </li>

          <li class="value-row">
            <span class="value-row__num" aria-hidden="true">03</span>
            <div class="value-row__body">
              <h3>Discretion &amp; professionalism</h3>
              <p>Confidentiality, clear communication, and appropriate handling of sensitive arrangements is the baseline of every engagement.</p>
            </div>
          </li>

          <li class="value-row">
            <span class="value-row__num" aria-hidden="true">04</span>
            <div class="value-row__body">
              <h3>Flexible execution</h3>
              <p>Each programme is developed around the client&rsquo;s objectives, schedule, participants, and operational realities &mdash; not a template.</p>
            </div>
          </li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="process alt" aria-labelledby="process-heading">
  <div class="container">
    <header class="process__head">
      <span class="eyebrow">Our Process</span>
      <h2 id="process-heading">From brief to delivery.</h2>
      <p class="lede">A structured five-stage approach that keeps every programme on schedule, on brief, and beautifully delivered.</p>
    </header>

    <ol class="process__timeline" role="list">
      <li class="process-step">
        <span class="process-step__marker" aria-hidden="true">
          <span class="process-step__dot"></span>
        </span>
        <span class="process-step__num">01</span>
        <h3>Understand</h3>
        <p>We clarify the purpose, participants, destination, timing, expectations, and operational requirements.</p>
      </li>

      <li class="process-step">
        <span class="process-step__marker" aria-hidden="true">
          <span class="process-step__dot"></span>
        </span>
        <span class="process-step__num">02</span>
        <h3>Structure</h3>
        <p>We create a clear programme framework and identify the services and providers required.</p>
      </li>

      <li class="process-step">
        <span class="process-step__marker" aria-hidden="true">
          <span class="process-step__dot"></span>
        </span>
        <span class="process-step__num">03</span>
        <h3>Coordinate</h3>
        <p>We manage communication between suppliers, venues, hosts, transport providers, production teams, and other partners.</p>
      </li>

      <li class="process-step">
        <span class="process-step__marker" aria-hidden="true">
          <span class="process-step__dot"></span>
        </span>
        <span class="process-step__num">04</span>
        <h3>Deliver</h3>
        <p>We coordinate the final arrangements and support the programme before and during execution.</p>
      </li>

      <li class="process-step">
        <span class="process-step__marker" aria-hidden="true">
          <span class="process-step__dot"></span>
        </span>
        <span class="process-step__num">05</span>
        <h3>Review</h3>
        <p>Where appropriate, we collect feedback and support post-event follow-up, documentation, and future planning.</p>
      </li>
    </ol>
  </div>
</section>

<section class="faq" aria-labelledby="faq-heading">
  <div class="container">
    <header class="section-head wide">
      <span class="eyebrow">Frequently Asked</span>
      <h2 id="faq-heading">Sov Summit &mdash; Frequently Asked Questions.</h2>
      <p class="lede">A short overview of how Sov Summit sources, coordinates, and supervises services across events and delegations.</p>
    </header>

    <div class="faq-list">
      <details class="faq-item">
        <summary>What does Sov Summit do?</summary>
        <p>Sov Summit provides end-to-end event and delegation coordination. We help clients source, compare, coordinate, and supervise services such as chauffeur transportation, logistics, accommodation, event support, and other specialist services.</p>
      </details>

      <details class="faq-item">
        <summary>How can Sov Summit help with chauffeur services?</summary>
        <p>We identify suitable chauffeur providers based on your requirements, including vehicle category, passenger numbers, language requirements, itinerary, service level, and budget.</p>
        <p>We compare available options, negotiate competitive rates, coordinate the booking, and supervise the service.</p>
      </details>

      <details class="faq-item">
        <summary>Can Sov Summit arrange accommodation?</summary>
        <p>Yes. We can source and coordinate hotels and other accommodation based on your preferred location, category, room requirements, budget, and guest profile.</p>
      </details>

      <details class="faq-item">
        <summary>Can you manage the entire transportation and logistics operation?</summary>
        <p>Yes. We can coordinate multiple vehicles, drivers, routes, schedules, airport transfers, meet &amp; greet, group movements, flight information, and on-site transportation requirements.</p>
      </details>

      <details class="faq-item">
        <summary>How do you select your vendors?</summary>
        <p>We select providers according to the specific requirements of each project. Depending on the service, we assess factors such as experience, licensing, availability, service capability, reputation, and other relevant credentials.</p>
        <p>Where appropriate, we also conduct background and compliance checks.</p>
      </details>

      <details class="faq-item">
        <summary>Do you work with your own vendors?</summary>
        <p>We work with both established partners and external providers. Existing partners can be used where they meet the requirements, while additional providers can be sourced when a project requires specific capabilities, locations, or capacity.</p>
      </details>

      <details class="faq-item">
        <summary>Can you negotiate better rates?</summary>
        <p>We use our network and purchasing volume to obtain competitive offers. Where multiple suitable providers are available, we can compare proposals and negotiate commercial terms on the client&rsquo;s behalf.</p>
      </details>

      <details class="faq-item">
        <summary>Will I know what I am paying for?</summary>
        <p>Yes. We aim to provide clear and transparent proposals, with the services, quantities, pricing, and relevant conditions clearly defined.</p>
      </details>

      <details class="faq-item">
        <summary>Why shouldn&rsquo;t I contact the vendors directly?</summary>
        <p>You can, but managing multiple vendors can become time-consuming and complicated.</p>
        <p>Sov Summit provides a single coordination layer between you and the different suppliers, helping manage communication, scheduling, pricing, documentation, and execution.</p>
      </details>

      <details class="faq-item">
        <summary>Who supervises the services?</summary>
        <p>Depending on the project scope, Sov Summit can provide dedicated coordination and operational supervision throughout the event or delegation.</p>
      </details>

      <details class="faq-item">
        <summary>What happens if a vendor fails to deliver?</summary>
        <p>We work to identify reliable providers and maintain operational oversight. Where applicable and contractually agreed, Sov Summit can provide service guarantees or remedies for qualifying service failures.</p>
      </details>

      <details class="faq-item">
        <summary>Do you provide a mobile app or platform?</summary>
        <p>Sov Summit is designed to organize services through a central digital platform, giving clients an easier way to manage vendors, services, schedules, and operational information.</p>
      </details>

      <details class="faq-item">
        <summary>Can I see all the vendors and services in one place?</summary>
        <p>Yes. The objective is to bring fragmented services into one organized operational environment, making it easier to understand what has been booked, who is responsible, when the service takes place, and what has been agreed.</p>
      </details>

      <details class="faq-item">
        <summary>Can Sov Summit handle VIP or diplomatic delegations?</summary>
        <p>Yes. We can coordinate requirements for VIP, corporate, diplomatic, executive, and other high-profile delegations, subject to the specific requirements and applicable regulations.</p>
      </details>

      <details class="faq-item">
        <summary>Can you coordinate services in different countries?</summary>
        <p>Yes. Sov Summit can source and coordinate services across multiple destinations through its network of local and international partners.</p>
      </details>

      <details class="faq-item">
        <summary>Do you provide security services?</summary>
        <p>Sov Summit can coordinate appropriate third-party security or related services where required. We do not present ourselves as a licensed security provider unless the relevant service is provided by an appropriately licensed partner.</p>
      </details>

      <details class="faq-item">
        <summary>How far in advance should I contact Sov Summit?</summary>
        <p>The earlier you contact us, the more options we generally have for sourcing, negotiation, verification, and operational planning. However, we can also assist with urgent and short-notice requirements subject to availability.</p>
      </details>

      <details class="faq-item">
        <summary>Can I request only one service?</summary>
        <p>Yes. You can use Sov Summit for a single requirement &mdash; such as chauffeur transportation or accommodation &mdash; or for complete event and delegation coordination.</p>
      </details>

      <details class="faq-item">
        <summary>What information do you need to prepare a proposal?</summary>
        <p>Typically, we need:</p>
        <ul>
          <li>Event or travel dates</li>
          <li>Destination(s)</li>
          <li>Number of guests</li>
          <li>Required services</li>
          <li>Itinerary or schedule</li>
          <li>Vehicle requirements</li>
          <li>Accommodation requirements</li>
          <li>Preferred service level</li>
          <li>Any special requirements</li>
          <li>Budget, if available</li>
        </ul>
      </details>

      <details class="faq-item">
        <summary>What is the main advantage of using Sov Summit?</summary>
        <p>The event-services market is fragmented. Instead of managing numerous suppliers yourself, Sov Summit brings the relevant services together into one coordinated solution.</p>
        <p><strong>We source. We verify. We negotiate. We coordinate. We supervise.</strong></p>
      </details>

      <details class="faq-item">
        <summary>What makes Sov Summit different?</summary>
        <p>Sov Summit combines specialist advisors, a vendor network, commercial sourcing, operational coordination, and technology.</p>
        <p>Our objective is simple: <em>give you one place to organize multiple services &mdash; with transparency, control, and professional execution.</em></p>
      </details>
    </div>
  </div>
</section>

<section class="cta-band"><div class="container">
<h2>Let&rsquo;s create something meaningful.</h2>
<p class="lede">Whether you are planning an international conference, an executive programme, a private family journey, or a complex multi-provider event, SOV SUMMIT can help coordinate the details.</p>
<div class="cta-row"><a class="btn btn-primary" href="/contact">Discuss Your Requirements</a></div>
</div></section>


@endsection
