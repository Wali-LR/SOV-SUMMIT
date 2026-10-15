# HTML → Laravel Blade Conversion Plan

**Project:** SOV SUMMIT — Sovereign Summit GmbH
**Source:** `/Users/alireza/Sites/lr-projects/SOV-SUMMIT/html-backup/`
**Target:** Laravel 12 + Breeze (Blade) already installed at project root
**Goal:** Port the static HTML site into Blade views with **identical design**, using a proper layout + partials architecture. No visual regressions.

---

## 1. Guiding Principles

1. **Pixel-identical output.** The rendered HTML from Blade must match the source HTML structurally. CSS classes, DOM order, and asset paths stay the same.
2. **Single source of truth for chrome.** Header, footer, `<head>`, and shared scripts live in one master layout — never copy-pasted per page.
3. **Assets shipped as-is first.** Copy `assets/css`, `assets/js`, `assets/img` into `public/assets/` verbatim so paths like `/assets/css/style.css` keep working. Do **not** rewrite to Vite in phase 1.
4. **Content-only Blade sections.** Each page view only contains what's inside `<main>` — everything else comes from the layout.
5. **URLs preserved.** The HTML uses trailing-slash pretty URLs (`/about/`, `/services/planning-coordination/`). Laravel routes must match.
6. **Auth stays separate.** Breeze routes (`/login`, `/register`, `/dashboard`) already exist. Public pages coexist alongside them; the marketing site does not require login.

---

## 2. Source Inventory (20 pages)

Public marketing pages that must be converted:

| # | Source path | Route | Blade view |
|---|-------------|-------|------------|
| 1 | `index.html` | `/` | `pages.home` |
| 2 | `about/index.html` | `/about` | `pages.about` |
| 3 | `services/index.html` | `/services` | `pages.services.index` |
| 4 | `services/planning-coordination/index.html` | `/services/planning-coordination` | `pages.services.planning-coordination` |
| 5 | `services/conference-planning/index.html` | `/services/conference-planning` | `pages.services.conference-planning` |
| 6 | `services/delegation-management/index.html` | `/services/delegation-management` | `pages.services.delegation-management` |
| 7 | `services/events-productions/index.html` | `/services/events-productions` | `pages.services.events-productions` |
| 8 | `services/security-coordination/index.html` | `/services/security-coordination` | `pages.services.security-coordination` |
| 9 | `services/media-coverage/index.html` | `/services/media-coverage` | `pages.services.media-coverage` |
| 10 | `services/travel-experiences/index.html` | `/services/travel-experiences` | `pages.services.travel-experiences` |
| 11 | `management-training/index.html` | `/management-training` | `pages.management-training` |
| 12 | `products/index.html` | `/products` | `pages.products` |
| 13 | `events/index.html` | `/events` | `pages.events` |
| 14 | `insights/index.html` | `/insights` | `pages.insights` |
| 15 | `contact/index.html` | `/contact` | `pages.contact` |
| 16 | `legal-notice/index.html` | `/legal-notice` | `pages.legal.notice` |
| 17 | `privacy-policy/index.html` | `/privacy-policy` | `pages.legal.privacy` |
| 18 | `cookie-policy/index.html` | `/cookie-policy` | `pages.legal.cookie` |
| 19 | `terms-conditions/index.html` | `/terms-conditions` | `pages.legal.terms` |
| 20 | `404.html` | (error handler) | `errors.404` |

Trailing slashes should still resolve — Laravel handles that automatically for GET routes; add a route middleware only if issues appear.

---

## 3. Target Blade Architecture

```
resources/views/
├── layouts/
│   └── site.blade.php              # <html>, <head>, header, footer, scripts
├── partials/
│   ├── head-meta.blade.php         # per-page overrideable meta (title, description, OG, canonical, JSON-LD)
│   ├── header.blade.php            # <header class="site-header"> + primary nav
│   ├── footer.blade.php            # <footer class="site-footer">
│   └── cta-band.blade.php          # reusable "Let's create something meaningful" strip
├── pages/
│   ├── home.blade.php
│   ├── about.blade.php
│   ├── contact.blade.php
│   ├── products.blade.php
│   ├── events.blade.php
│   ├── insights.blade.php
│   ├── management-training.blade.php
│   ├── services/
│   │   ├── index.blade.php
│   │   ├── planning-coordination.blade.php
│   │   ├── conference-planning.blade.php
│   │   ├── delegation-management.blade.php
│   │   ├── events-productions.blade.php
│   │   ├── security-coordination.blade.php
│   │   ├── media-coverage.blade.php
│   │   └── travel-experiences.blade.php
│   └── legal/
│       ├── notice.blade.php
│       ├── privacy.blade.php
│       ├── cookie.blade.php
│       └── terms.blade.php
└── errors/
    └── 404.blade.php
```

### Layout contract (`layouts/site.blade.php`)

```blade
<!doctype html>
<html lang="en">
<head>
    @include('partials.head-meta')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('head')
</head>
<body>
    <a class="skip-link" href="#main" style="position:absolute;left:-9999px;">Skip to content</a>
    @include('partials.header')
    <main id="main">
        @yield('content')
    </main>
    @include('partials.footer')
    <script>document.getElementById('year').textContent = new Date().getFullYear();</script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
```

### Per-page override slots

Each page defines:

```blade
@extends('layouts.site')
@section('title', 'About | SOV SUMMIT')
@section('meta_description', '…')
@section('canonical', url('/about'))
@push('head')
    {{-- page-specific JSON-LD, OG image overrides --}}
@endpush
@section('content')
    …page markup lifted straight from <main>…
@endsection
```

`partials/head-meta.blade.php` consumes `@yield('title')`, `@yield('meta_description')`, `@yield('canonical')` with sensible defaults.

### Active nav state

The source uses `aria-current="page"` on the current link. In the header partial, compute it with `Route::is()`:

```blade
<a href="/about" @class(['is-active' => request()->is('about*')]) @if(request()->is('about*')) aria-current="page" @endif>About</a>
```

---

## 4. Assets Strategy (Phase 1: copy verbatim)

- Copy `html-backup/assets/**` → `public/assets/**` (css, js, img).
- The source references `/assets/img/logo-2.png`, `/assets/css/style.css`, `/assets/js/main.js` — Laravel's `public/` serves those directly. **No rewrites needed** except optionally wrapping in `{{ asset(...) }}` for portability.
- Google Fonts preconnect + stylesheet link goes into `partials/head-meta.blade.php`.
- Do NOT touch `resources/js/app.js` or Vite yet — that's phase 2.

**Phase 2 (later, optional):** migrate `style.css` into `resources/css/` and `main.js` into `resources/js/`, wire through Vite, replace `<link>`/`<script>` tags with `@vite(...)`. Skip unless the user asks.

---

## 5. Routes (`routes/web.php`)

Append after the Breeze block:

```php
Route::view('/',                                'pages.home')->name('home');
Route::view('/about',                           'pages.about')->name('about');
Route::view('/services',                        'pages.services.index')->name('services');
Route::view('/services/planning-coordination',  'pages.services.planning-coordination');
Route::view('/services/conference-planning',    'pages.services.conference-planning');
Route::view('/services/delegation-management',  'pages.services.delegation-management');
Route::view('/services/events-productions',     'pages.services.events-productions');
Route::view('/services/security-coordination',  'pages.services.security-coordination');
Route::view('/services/media-coverage',         'pages.services.media-coverage');
Route::view('/services/travel-experiences',     'pages.services.travel-experiences');
Route::view('/management-training',             'pages.management-training');
Route::view('/products',                        'pages.products');
Route::view('/events',                          'pages.events');
Route::view('/insights',                        'pages.insights');
Route::view('/contact',                         'pages.contact')->name('contact');
Route::view('/legal-notice',                    'pages.legal.notice');
Route::view('/privacy-policy',                  'pages.legal.privacy');
Route::view('/cookie-policy',                   'pages.legal.cookie');
Route::view('/terms-conditions',                'pages.legal.terms');
```

The Breeze-generated welcome route on `/` must be replaced with `pages.home`.

---

## 6. Extraction Recipe (per page)

For each source `*.html`:

1. Open the file, locate `<main id="main"> … </main>`.
2. Copy everything between those tags into `pages/…/xxx.blade.php` inside `@section('content')`.
3. Extract from `<head>`:
   - `<title>` → `@section('title', …)`
   - `<meta name="description">` → `@section('meta_description', …)`
   - `<link rel="canonical">` → `@section('canonical', …)`
   - Any `application/ld+json` blocks → `@push('head')`
   - Any OG/Twitter overrides → `@push('head')`
4. Verify the header/footer/nav in the source match what's in `partials/header.blade.php`. If a page has a different nav state (e.g., `aria-current`), that comes for free from the active-state logic in step 3.
5. Diff the rendered HTML against the source with `curl` + a normalizer (whitespace-collapse) to catch drift.

---

## 7. TODO — Ordered Execution Plan

### Phase A — Scaffolding
- [ ] A1. Copy `html-backup/assets/{css,js,img}` → `public/assets/` (verbatim).
- [ ] A2. Create `resources/views/layouts/site.blade.php` with the layout contract.
- [ ] A3. Create `partials/head-meta.blade.php` (title/description/canonical/OG defaults + Google Fonts).
- [ ] A4. Create `partials/header.blade.php` — copy header from `index.html` verbatim, then swap the nav item currently-active detection to `request()->is(...)`.
- [ ] A5. Create `partials/footer.blade.php` — copy footer from `index.html` verbatim.
- [ ] A6. Create `partials/cta-band.blade.php` for the reusable CTA strip.
- [ ] A7. Wire routes in `routes/web.php` (Route::view per page). Remove or repurpose Breeze's `welcome` route on `/`.

### Phase B — Page conversion (in this order, easiest first)
- [ ] B1. `pages/legal/notice.blade.php` — small, static, good pilot.
- [ ] B2. `pages/legal/privacy.blade.php`
- [ ] B3. `pages/legal/cookie.blade.php`
- [ ] B4. `pages/legal/terms.blade.php`
- [ ] B5. `pages/contact.blade.php`
- [ ] B6. `pages/about.blade.php`
- [ ] B7. `pages/services/index.blade.php`
- [ ] B8. `pages/services/planning-coordination.blade.php`
- [ ] B9. `pages/services/conference-planning.blade.php`
- [ ] B10. `pages/services/delegation-management.blade.php`
- [ ] B11. `pages/services/events-productions.blade.php`
- [ ] B12. `pages/services/security-coordination.blade.php`
- [ ] B13. `pages/services/media-coverage.blade.php`
- [ ] B14. `pages/services/travel-experiences.blade.php`
- [ ] B15. `pages/management-training.blade.php`
- [ ] B16. `pages/products.blade.php`
- [ ] B17. `pages/events.blade.php`
- [ ] B18. `pages/insights.blade.php`
- [ ] B19. `pages/home.blade.php` — largest, hero + all sections. Do last so the layout is battle-tested.
- [ ] B20. `errors/404.blade.php` — mirrors `404.html`.

### Phase C — Verification
- [ ] C1. `php artisan serve`, walk every route in the browser. Check:
  - Hero renders identically (animated diagram intact).
  - Header nav shows correct active state per page.
  - Footer year auto-updates.
  - Fonts load.
  - No 404s on assets in Network tab.
- [ ] C2. For each route, `curl -s http://127.0.0.1:8000/<path>` and diff against the source HTML (ignoring dynamic bits like CSRF/year). Zero structural drift on `<main>` contents.
- [ ] C3. Lighthouse pass on `/` — parity vs. the static build should be within a few points.
- [ ] C4. `php artisan route:list` — confirm all 20 routes are named and reachable.

### Phase D — Cleanup
- [ ] D1. Delete Breeze's `resources/views/welcome.blade.php` if unused.
- [ ] D2. Verify `.gitignore` still ignores `public/build/` and `public/hot`.
- [ ] D3. Commit with message covering the port; keep `html-backup/` untouched for reference.

---

## 8. Non-Goals (do NOT do)

- Do not convert the static site into a CMS / DB-driven pages. Content stays in Blade files.
- Do not migrate the CSS/JS into Vite in this pass.
- Do not restyle, refactor components into Alpine/Livewire, or "modernize" markup. Any change is a design change.
- Do not touch Breeze auth views.
- Do not compress/re-optimize images.
- Do not remove `html-backup/` — it is the reference for future diffs.

---

## 9. Risk Notes

- **The homepage hero has heavy inline SVG animations** referenced by CSS class hooks in `style.css` and JS in `main.js`. Copy the SVG markup byte-for-byte; do not "clean up" attributes.
- **JSON-LD blocks** contain literal `<`/`>`/`&` inside strings — put them inside `@push('head')` unescaped via `{!! !!}` or use `@verbatim` blocks. Do not let Blade escape them.
- **Trailing-slash URLs** in the source (`/about/`) — Laravel routes match without the slash and issue no redirect by default. Nav links inside the header partial should point to the non-slash form (`/about`) to avoid a redirect chain; or add a `TrimTrailingSlashes`-style middleware. Decide before Phase B4.
- **`aria-current="page"` in the source is hardcoded per file.** Replace with the dynamic Blade helper so the header partial is truly shared.

---

## 10. Reference

- Source guide: `html-backup/SOV-SUMMIT-Website-Development-Guide.md`
- Source README: `html-backup/README.md`
- Existing dev-guide: `html-backup/docs/DEVELOPMENT_GUIDE.md`
