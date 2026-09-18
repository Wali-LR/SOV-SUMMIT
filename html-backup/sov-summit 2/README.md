# SOV SUMMIT — Website

The full Phase 1 build for sov-summit.com: 17 pages of approved content,
structured for SEO and AEO, in the approved white / gold / charcoal visual
system (Cormorant Garamond + Manrope).

## Structure

```
sov-summit/
├── index.html                                  # Home
├── about/index.html
├── services/index.html                         # Services overview
├── services/planning-coordination/index.html
├── services/conference-planning/index.html
├── services/delegation-management/index.html
├── services/events-productions/index.html
├── services/security-coordination/index.html
├── services/media-coverage/index.html
├── services/travel-experiences/index.html
├── management-training/index.html
├── events/index.html                           # Events overview
├── insights/index.html                         # Insights hub (articles are Phase 2)
├── contact/index.html
├── legal-notice/index.html
├── privacy-policy/index.html
├── cookie-policy/index.html
├── 404.html
├── sitemap.xml
├── robots.txt
├── assets/
│   ├── css/style.css                           # shared design system
│   ├── js/main.js                              # mobile nav toggle
│   └── img/logo.png
└── docs/
    └── DEVELOPMENT_GUIDE.md                    # master build spec (source of truth)
```

Every page shares one header/footer/nav and links only to pages that exist in
this build (individual event-type pages like `/events/conferences/` are
Phase 2, per the guide's launch order, so the Events dropdown was kept to a
single overview page rather than linking out to pages that don't exist yet).

## How this was built

Rather than hand-writing 17 near-identical HTML shells, the site is generated
from a small Python template (not included in this zip — it's a one-time
build tool, not part of the site). The generated HTML files are plain static
markup, so you can edit them directly going forward.

## Running locally

```bash
cd sov-summit
python3 -m http.server 8000
```

Visit `http://localhost:8000`.

## Deploying with GitHub Pages

1. Push this repo to GitHub.
2. **Settings → Pages** → Source: `Deploy from a branch` → `main` / `/ (root)`.
3. If using the apex domain `sov-summit.com`, add a `CNAME` file at the repo
   root containing `sov-summit.com` and configure DNS per GitHub's custom
   domain docs.

## Before this goes live — things that need real decisions, not code

- **Contact form has no backend.** GitHub Pages only serves static files. The
  form in `/contact/` is fully built (all fields from the spec, validation,
  consent checkbox) but its `action="#"` needs to point at a real form
  handler — Formspree, a Netlify-hosted form, or a custom endpoint — before
  submissions actually go anywhere.
- **Legal Notice, Privacy Policy, and Cookie Policy are starting templates**,
  built from the company facts you provided (UID, registration number,
  address) but not reviewed by a lawyer. They say so at the bottom of each
  page — have counsel check them (especially the FADP/GDPR data-handling
  language) before launch.
- **Imagery.** Every page currently ships without photography or the hero
  video described in the brief (conferences, delegations, venues, aviation,
  in motion). The layouts have space reserved for it, but no stock or
  licensed footage has been added — the brief is explicit that generic
  luxury stock should be avoided in favour of real editorial photography.
- **Favicon** currently reuses the wide wordmark logo, which browsers will
  letterbox/shrink into a square tile. A dedicated square mark (or the "S"
  monogram mentioned in the brief) would look sharper at favicon size.
- **Domain / canonical URLs** in every page's `<link rel="canonical">` and
  Open Graph tags point at `https://sov-summit.com`. Update these (a simple
  find-and-replace across the HTML) if launch uses a different URL until DNS
  is live.
- **Insights section** is a placeholder hub listing the six Phase 2 article
  topics as "coming soon" — no articles are written yet, per the guide's own
  phased launch order.

## Notes

- Fonts (Cormorant Garamond, Manrope) load from Google Fonts via CDN.
- JSON-LD is included on every page: `Organization` sitewide, `BreadcrumbList`
  on every page, `Service` on each service page, and `FAQPage` wherever an
  FAQ block is visibly on the page — matching the guide's rule that
  structured data must match visible content.
- All internal links were checked programmatically; there are no broken
  links or 404s within the site.
- See `docs/DEVELOPMENT_GUIDE.md` for the full sitemap, content rules, and
  SEO/AEO requirements this build follows.
