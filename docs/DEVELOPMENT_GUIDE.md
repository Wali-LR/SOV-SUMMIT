# SOV SUMMIT — Website Development Guide

**Legal entity:** Sovereign Summit GmbH · Bahnhofstrasse 21, 6300 Zug, Switzerland
**Brand:** SOV SUMMIT
**Domain:** sov-summit.com
**Tagline:** PEOPLE · IDEAS · IMPACT
**Promise:** Planning. Connecting. Delivering.

This guide turns the brand/content brief into a build-ready structure: what to build, in what order, with what content and rules. Use it as the master reference for design, copywriting, and development.

---

## 1. Positioning (read this before building anything)

**One-line description:** SOV SUMMIT is an international coordination partner — it connects clients with the right people, providers, and resources to deliver events, delegations, training, travel, security, and media programmes.

**Do not position the company as any single-service business** (event planner only, travel agency only, security company, media agency, training provider). The site's job is to communicate *coordination across providers* as the core value.

**Hero headline (recommended):** "Connecting the dots. Delivering successful events."

**Alternate headlines** (for A/B testing or section reuse):

- The right connections. Exceptional experiences.
- From vision to execution.
- Where people, ideas, and opportunities come together.
- International events. Meaningful connections.

---

## 2. Site Architecture

### 2.1 Full sitemap

```
/
├── /about/
├── /services/
│   ├── /services/planning-coordination/
│   ├── /services/conference-planning/
│   ├── /services/delegation-management/
│   ├── /services/events-productions/
│   ├── /services/security-coordination/
│   ├── /services/media-coverage/
│   └── /services/travel-experiences/
├── /management-training/
├── /events/
│   ├── /events/conferences/
│   ├── /events/corporate-events/
│   ├── /events/corporate-celebrations/
│   ├── /events/concerts/
│   ├── /events/film-festivals/
│   ├── /events/fashion-shows/
│   └── /events/road-shows/
├── /travel-experiences/
├── /insights/
├── /contact/
├── /privacy-policy/
├── /legal-notice/
└── /cookie-policy/
```

### 2.2 Primary navigation

Home · About · Services · Management Training · Events · Travel & Experiences · Insights · Contact Us

**Services dropdown:** Planning & Coordination · Conference Planning · Delegation Management · Events & Productions · Security Coordination · Media Coverage · Travel & Experiences · Management Training

**Events dropdown:** Conferences · Corporate Events · Corporate Celebrations · Concerts · Film Festivals · Fashion Shows · Road Shows · Executive Forums · Private Events

**Footer navigation:** About · Services · Management Training · Events · Travel & Experiences · Insights · Contact · Privacy Policy · Legal Notice · Cookie Policy

### 2.3 Build order (3 phases)

| Phase | Deliverable | Pages |
|---|---|---|
| **1 — Core site** | Launch-ready minimum | Home, About, Services (overview), Planning & Coordination, Management Training, Conference Planning, Delegation Management, Events & Productions, Security Coordination, Media Coverage, Travel & Experiences, Contact, Legal Notice, Privacy Policy |
| **2 — Search growth** | Content depth for SEO/AEO | Insights hub + 6 seed articles (conference, delegation, management training, family travel, security, media), then individual event-type pages (conferences, corporate events, concerts, festivals, fashion shows, road shows) |
| **3 — Authority** | Trust signals | Case studies, testimonials, project examples, professional photography, partner references, LinkedIn/industry content, Search Console review |

---

## 3. Page Specifications

Each row = what to build for that page. Build in this order within Phase 1.

| Page | URL | SEO Title | Meta Description | H1 | Primary Keyword |
|---|---|---|---|---|---|
| Home | `/` | SOV SUMMIT \| International Event Organisation & Coordination | SOV SUMMIT coordinates international events, conferences, delegations, management training, travel experiences, security support, and media coverage for organisations, corporations, institutions, and private clients. | Connecting the dots. Delivering successful events. | international event organisation |
| About | `/about/` | About SOV SUMMIT \| People, Ideas, Impact | Learn about SOV SUMMIT, an international coordination partner for events, delegations, management training, travel experiences, security coordination, and media coverage. | People. Ideas. Impact. | SOV SUMMIT |
| Services (overview) | `/services/` | Event Organisation, Delegations & Travel Coordination | Explore SOV SUMMIT services, including event planning, conference organisation, delegation management, management training, travel coordination, security support, and media coverage. | Planning. Connecting. Delivering. | event coordination |
| Planning & Coordination | `/services/planning-coordination/` | Event Planning & Provider Coordination \| SOV SUMMIT | SOV SUMMIT coordinates event planning, suppliers, venues, accommodation, transportation, guest management, schedules, and event delivery. | Planning and coordination for complex programmes | event planning and coordination |
| Management Training | `/management-training/` | Management Training Programmes & Executive Learning | Bespoke management training programmes, leadership development, executive learning, team workshops, communication, negotiation, and international training experiences. | Management training designed around your objectives | management training programmes |
| Conference Planning | `/services/conference-planning/` | Conference Planning & Event Management \| SOV SUMMIT | International conference planning, executive forums, institutional meetings, venue sourcing, speakers, hospitality, technical production, and on-site coordination. | Conference planning with operational precision | conference planning |
| Delegation Management | `/services/delegation-management/` | Delegation Management & International Coordination | SOV SUMMIT coordinates government, institutional, corporate, and executive delegations, including travel, accommodation, transportation, protocol, security, and site visits. | Delegation management from arrival to departure | delegation management |
| Events & Productions | `/services/events-productions/` | Corporate & International Event Organisation | SOV SUMMIT coordinates corporate events, celebrations, concerts, film festivals, fashion shows, road shows, executive dinners, and private productions. | Events and productions that connect people | corporate event organisation |
| Security Coordination | `/services/security-coordination/` | Event Security & Executive Protection Coordination | SOV SUMMIT coordinates suitable licensed security providers for events, delegations, executive programmes, travel, access management, and VIP requirements. | Security coordination for people, venues, and programmes | security coordination |
| Media Coverage | `/services/media-coverage/` | Event Photography, Video Production & Media Coverage | SOV SUMMIT coordinates photographers, videographers, media teams, event documentation, press support, and post-event visual content. | Capture the moments that matter | event media coverage |
| Travel & Experiences | `/services/travel-experiences/` or `/travel-experiences/` | Private Travel, Aviation & Family Trip Coordination | Private aviation coordination, accommodation, chauffeur transportation, family trips, restaurants, activities, and bespoke travel experiences by SOV SUMMIT. | Travel experiences, carefully coordinated | private travel coordination |
| Events overview | `/events/` | Conferences, Corporate Events & International Productions | Discover the event formats coordinated by SOV SUMMIT, including conferences, corporate events, celebrations, concerts, film festivals, fashion shows, and road shows. | Different formats. One coordinated approach. | — |
| Insights | `/insights/` | SOV SUMMIT Insights \| Events, Travel & Management | — | Insights | event planning insights |
| Contact | `/contact/` | Contact SOV SUMMIT \| International Event and Travel Coordination | Contact SOV SUMMIT for event planning, conferences, delegations, management training, travel experiences, security coordination, and media coverage. | Let's create something meaningful. | contact SOV SUMMIT |

**Important naming note:** the brief lists Travel & Experiences at both `/travel-experiences/` and `/services/travel-experiences/`. Pick **one canonical URL** before build (recommend `/services/travel-experiences/` to keep all service pages under one parent) and 301-redirect the other.

### 3.1 Homepage section-by-section build

1. **Hero** — H1 + supporting line + brand line (PEOPLE · IDEAS · IMPACT) + two CTAs (Explore Our Services / Start a Conversation). Video/image background: conferences, delegations, venues, aviation, hospitality, production, security, media, family/cultural moments. **Do not** use footage implying affiliation with major events (WEF, etc.) unless factually true.
2. **Introduction** — "Every successful event begins with the right connections."
3. **Core proposition** — three pillars: Planning / Connecting / Delivering, each with one sentence.
4. **Main services grid** — 7 cards (Planning & Coordination, Management Training, Conferences & Delegations, Events & Productions, Security Coordination, Media Coverage, Travel & Experiences), each linking to its page.
5. **Who we serve** — client category list (only include categories that reflect actual experience).
6. **Why SOV SUMMIT** — four differentiators: single point of coordination, international provider network, discretion, flexible execution.
7. **Process** — 5 steps: Understand → Structure → Coordinate → Deliver → Review.
8. **Closing CTA** — "Let's create something meaningful."

### 3.2 Standard template for every service/event sub-page

Use this structure consistently:

1. H1 (direct statement of what the page covers)
2. Answer paragraph, 40–60 words, answering "what is this service" immediately (for AEO — see Section 6)
3. "Services included" bullet list
4. "Suitable for" / typical client or use-case list
5. Optional FAQ block (question-style H2s)
6. Internal links to 3+ related pages (see Section 7)
7. CTA to Contact

---

## 4. Design System

| Element | Spec |
|---|---|
| Palette | White background, gold accents, dark charcoal type |
| Layout | Minimal, generous spacing, no crowded cards, no heavy gradients |
| Imagery | Editorial photography; no generic luxury stock |
| Headings font | Cormorant Garamond, Medium |
| Body/nav font | Manrope, Regular/Medium |
| Logo | Custom "S + SUMMIT" lettering — kept independent of the website type system |
| Alternate font pairings (if repositioning) | Playfair Display + Manrope (more dramatic/editorial) · Bodoni Moda + Manrope (fashion-forward, high contrast — fits if fashion/cultural events are emphasized) · DM Serif Display + DM Sans (more modern, unified) |

**Brand hierarchy for footer/hero blocks:**

```
SOV SUMMIT
PEOPLE · IDEAS · IMPACT
Planning. Connecting. Delivering.
Sovereign Summit GmbH   ← legal name, footer/legal only
```

---

## 5. Content Rules

**Use:** clear service descriptions, specific operational language, short paragraphs, accurate claims, original insights, clear CTAs, consistent terminology across pages (always "event coordination," "conference planning," "delegation management," "management training," "security coordination," "media coverage," "travel experiences" — not synonyms that dilute keyword/AEO consistency).

**Avoid these phrases/claims anywhere on the site:**

- "We do everything" / "The best in the world" / "Unmatched luxury" / "Guaranteed results"
- "Global coverage" without evidence · "Fully licensed" without verification
- "24/7 service" or "guaranteed availability" unless substantiated
- Generic AI-filler, repeated keyword phrases
- Unsupported client logos or unverified testimonials
- Implied government affiliation or implied affiliation with major named events

**Security & Media pages specifically:** use "coordination" language, not "provision" language, unless SOV SUMMIT itself holds relevant licenses/employs the staff. Include the disclaimer that these services are delivered via external licensed/approved providers, subject to local law and availability.

---

## 6. SEO & AEO Requirements

### 6.1 Keyword themes

- **Brand:** SOV SUMMIT, Sovereign Summit, Sovereign Summit GmbH, SOV SUMMIT Switzerland
- **Core service:** international event organisation, conference planning, delegation management, executive event planning, management training programmes, corporate event organisation, private travel coordination, private aviation coordination, security coordination for events, event media coverage
- **Long-tail:** international conference planning company, executive delegation management, corporate event coordination in Europe, private travel coordination for families, management training programme organisation, multi-city road show coordination, VIP travel and event coordination

Place primary keywords naturally in: page title, H1, first paragraph, relevant H2s, meta description, URL slug, image alt text, internal link anchor text, FAQ answers. Do not keyword-stuff or spin near-duplicate pages for minor keyword variants.

### 6.2 AEO (answer-engine optimization) rules

1. Answer the page's core question in the first 40–60 words (see homepage/service template above).
2. Use question-format H2s where natural: "What does event coordination include?", "Does SOV SUMMIT organise international delegations?"
3. Put the direct answer first, elaboration after.
4. Prefer structured lists over long paragraphs.
5. Include original operational detail (what clients should prepare, what a brief needs, how schedules get coordinated) — this is what differentiates the site from generic AI-written competitor pages.
6. Keep company facts (who, what, where, which parts are via external providers, how to contact) clearly and consistently stated.
7. **Do not** build AI-only pages, artificial LLM files, mass-produced generic articles, or duplicate FAQ pages as a ranking hack — Google's guidance rewards genuinely useful original content, not AEO tricks.

### 6.3 FAQ library (place on relevant pages, not one mega-FAQ page)

Organize by topic and attach to the matching page:

- **General** (Home/About): What is SOV SUMMIT? What services does it provide? Who does it work with? Where is it based? How can I contact it?
- **Events** (Conference/Events pages): Does it organise international conferences? Corporate events? Multi-city events?
- **Delegations**: Does it manage government/institutional delegations? What does delegation management include?
- **Travel**: Does it arrange private flights? Organise family trips?
- **Security**: Does it provide security personnel? (No — coordinates licensed providers.) What is security coordination?
- **Media**: Does it provide event photography? Can it arrange video highlights?

### 6.4 Technical SEO checklist

- [ ] Responsive, mobile-first
- [ ] Fast load speed, HTTPS
- [ ] Clean URL structure, canonical URLs
- [ ] XML sitemap, robots.txt
- [ ] Search Console + privacy-compliant analytics
- [ ] One H1 per page, logical H2/H3 hierarchy
- [ ] Descriptive, unique meta titles/descriptions per page
- [ ] Crawlable internal links, no "click here" anchors
- [ ] Optimized images + descriptive alt text
- [ ] Custom 404, proper redirects
- [ ] Accessible nav and form labels
- [ ] Cookie/privacy compliance banner

### 6.5 Structured data (JSON-LD)

Use: Organization, WebSite, WebPage, Service, BreadcrumbList, Article (Insights), FAQPage (only where FAQ content is visibly on-page).

**Rule:** structured data must match visible page content exactly. Do **not** mark up SOV SUMMIT as a security company, aviation operator, or media-production company unless that classification is legally/operationally accurate — misrepresenting business type in schema is both a compliance and trust risk.

---

## 7. Internal Linking Map

Minimum 3 related-page links per service page.

| From page | Link to |
|---|---|
| Conference Planning | Planning & Coordination · Delegation Management · Security Coordination · Media Coverage · Events & Productions · Contact |
| Management Training | Travel & Experiences · Conference Planning · Planning & Coordination · Delegation Management · Contact |
| Travel & Experiences | Delegation Management · Security Coordination · Planning & Coordination · Media Coverage · Contact |

Use descriptive anchor text: "international conference planning," "delegation management services," "event security coordination," "private travel coordination," "management training programmes," "professional event media coverage."

---

## 8. Contact Page Build Spec

**Fields:** Full name · Company/organisation · Email · Phone/WhatsApp · Service required (dropdown) · Event/programme type · Destination · Preferred date · Number of participants · Estimated duration · Required services · Message · Consent to privacy policy checkbox

**Service dropdown options:** Planning & Coordination · Management Training · Conference Planning · Delegation Management · Events & Productions · Security Coordination · Media Coverage · Travel & Experiences · Other

**Confirmation message:** "Thank you for contacting SOV SUMMIT. Your enquiry has been received and will be reviewed shortly."

**Contact details to display:**

```
Brand: SOV SUMMIT
Legal company: Sovereign Summit GmbH
Address: Bahnhofstrasse 21, 6300 Zug, Switzerland
Email: info@sov-summit.com
WhatsApp: +41 79 876 35 73
Website: sov-summit.com
```

---

## 9. Insights Section — Article Backlog (Phase 2)

Seed with one article per topic cluster first, then expand:

| Cluster | Seed article | Additional backlog |
|---|---|---|
| Event planning | How to Plan an International Conference | What Does Professional Event Coordination Include? · Conference Planning Checklist · Choosing a Venue for an Executive Event · Coordinating Multiple Event Suppliers · Planning a Multi-City Road Show · Organising a Corporate Celebration · What to Include in an Event Brief |
| Delegations | What Does Delegation Management Include? | Planning an International Business Delegation · Coordinating Executive Travel · Organising a Government/Institutional Site Visit · What Is Protocol Coordination? · Managing Travel for a Large Delegation |
| Management training | How to Design an Effective Management Training Programme | What Makes an Executive Learning Experience Successful? · Training Retreats: Key Planning Considerations · Combining Training, Travel, and Team Development · Leadership Programme Planning Checklist |
| Travel | Coordinating Private Aviation, Accommodation, and Ground Transportation | Planning a Family Trip with Multiple Providers · Multi-Destination Executive Itineraries · Considerations for Family Travel · Coordinating Travel for VIP Guests |
| Security | What Is Security Coordination for Events? | When Should Security Be Considered in Event Planning? · Coordinating Security for an Executive Delegation · Security Coordination vs. Security Provision |
| Media | Why Professional Event Documentation Matters | Planning Media Coverage for a Conference · Event Photography and Video: What Should Be Included? · Creating Useful Post-Event Content |

---

## 10. Footer Build Spec

```
SOV SUMMIT
PEOPLE · IDEAS · IMPACT
Planning. Connecting. Delivering.

SOV SUMMIT coordinates international events, management training,
delegations, travel experiences, security support, and media coverage
for organisations, corporations, institutions, and private clients.

Sovereign Summit GmbH
Bahnhofstrasse 21
6300 Zug, Switzerland
info@sov-summit.com
+41 79 876 35 73

Legal Notice · Privacy Policy · Cookie Policy
```

---

## Quick-start build checklist

- [ ] Lock canonical URL for Travel & Experiences (Section 3, naming note)
- [ ] Set up design system: colors, Cormorant Garamond + Manrope, spacing rules
- [ ] Build Phase 1 pages using the standard service-page template (Section 3.2)
- [ ] Write all copy following Content Rules (Section 5) — no banned phrases
- [ ] Implement AEO answer-paragraphs + question H2s on every service page
- [ ] Add JSON-LD matching only verified business classifications
- [ ] Wire internal linking per the map in Section 7
- [ ] Build and test contact form with full field/dropdown spec
- [ ] Complete technical SEO checklist before launch
- [ ] Queue Phase 2 (Insights) and Phase 3 (case studies/testimonials) post-launch
