# Dynamic Services — Implementation Plan

Turn the currently static `/services` index and 8 detail pages into a fully database-driven feature that mirrors the Blog module (dynamic model, admin CRUD, seeder, dynamic content sections, SEO parity).

---

## 1. Goals

- **Admin CRUD** for services (create / edit / publish / delete / reorder) — same UX conventions as `admin/blogs`.
- **Preserve current URLs** so SEO does not regress: `/services`, `/services/{slug}`, and the special `/management-training` top-level URL.
- **Preserve current hero design** on detail pages (two-column: text left, image right — the `service-hero__grid` layout in the current blade files stays).
- **Description block** rendered from Summernote HTML after the hero (same rich-text handling as blogs).
- **Dynamic content sections** below the description, using the existing `content_sections` polymorphic pipeline (same tool available on Blog/Event).
- **Structured extras** kept in typed JSON columns on the service itself: services checklist, "suitable for" checklist, FAQs — because these are core identity fields, not free-form marketing sections.
- **SEO parity** with the current static markup: dynamic JSON-LD (`Service`, `BreadcrumbList`, `FAQPage` when FAQs exist), full OG + Twitter cards, canonical, robots hints.
- **AI helpers** on the admin form: "Generate description" and "Generate SEO info" buttons, wired to a service-tuned prompt in `SeoGeneratorController` (same pattern as blog/event).
- **Seeder** that inserts all 8 existing services verbatim from the current blade content — so `php artisan migrate:fresh --seed` produces a site indistinguishable from today's static one.

---

## 2. Current inventory (what the seeder must reproduce)

Extracted verbatim from the current blade files. Every service has: `title`, `eyebrow`, `summary`, `seo_title`, `seo_description`, `hero_image` and either a `services_included` checklist, an optional `suitable_for` checklist, and an optional `faqs` array. Related-service slugs are captured for the auto-related logic.

| # | Slug | Hero image (assets/img/services/…) | Has checklist | Has "suitable for" | FAQs |
|---|---|---|---|---|---|
| 1 | `conference-planning` | conferences.webp | ✔ (19) | — | 2 |
| 2 | `delegation-management` | delegation.webp | ✔ (17) | ✔ (9) | 2 |
| 3 | `events-productions` | events.webp | (card list; store as `services_included`) | — | — |
| 4 | `media-coverage` | media.webp | ✔ (14) | ✔ (10 — labelled "Media coverage for") | 2 |
| 5 | `planning-coordination` | planning.webp | ✔ (15) | ✔ (9) | — |
| 6 | `security-coordination` | security.webp | ✔ (13) | ✔ (8) | 2 |
| 7 | `travel-experiences` | travel.webp | ✔ (15) | — | 2 |
| 8 | `management-training` | management-training.webp | ✔ (15 — "Programme areas") | ✔ (8 — "Programme formats") | — |

Full text for every checklist item, every FAQ Q/A, and every SEO string is captured in the seeder section below (§10).

---

## 3. Data model

### 3.1 Table: `services`

```
id                     bigIncrements
slug                   string, unique, indexed
title                  string
eyebrow                string, nullable                 -- e.g. "CONFERENCE PLANNING"
summary                text, nullable                   -- the lede paragraph shown under h1
description            longText, nullable               -- Summernote HTML rendered after hero

hero_image             string, nullable                 -- path on Spaces OR asset relative path
                                                        -- resolved by getHeroUrlAttribute() (mirrors Blog::cover_url)

services_included      json, nullable                   -- ["Item 1", "Item 2", ...] — first checklist
services_included_label  string, nullable, default 'Services included'
suitable_for           json, nullable                   -- ["Item 1", ...] — second checklist (optional)
suitable_for_label     string, nullable                 -- e.g. "Suitable for", "Programme formats"
faqs                   json, nullable                   -- [{"q": "...", "a": "..."}, ...]

seo_title              string(160), nullable
seo_keywords           string(500), nullable
seo_description        text, nullable                   -- separate from summary; used in meta

is_published           boolean, default true
position               unsignedInteger, default 0       -- controls order on /services grid
published_at           timestamp, nullable

timestamps
```

### 3.2 Model: `App\Models\Service`

- `use HasContentSections;` → participates in the existing polymorphic `content_sections` table (same as Blog/Event).
- `protected string $contentSectionPageType = 'service';`
- `$fillable` matches columns above.
- `$casts`: `services_included=>array`, `suitable_for=>array`, `faqs=>array`, `is_published=>boolean`, `published_at=>datetime`, `position=>integer`.
- `booted(): saving` auto-fills unique slug from title if empty (mirror `Blog::uniqueSlug`).
- `getHeroUrlAttribute()` — returns full URL: if `hero_image` starts with `http`, return as-is; if it begins with `assets/`, return `asset($hero_image)` (lets the seeder point at existing shipped WebPs); otherwise resolve via `Storage::disk('spaces')->url(...)`.
- `scopePublished` (`is_published = true`).
- `scopeOrdered` (`orderBy('position')->orderBy('title')`).

### 3.3 Migration file
`database/migrations/2026_09_23_000000_create_services_table.php` — straight `Schema::create` with the columns above, indexes on `slug`, `is_published`, `position`.

### 3.4 Config: `config/sections.php` — page type
Add `'service'` alongside the existing `'blog'` / `'event'` page types so the section templates work everywhere.

---

## 4. Routes

`routes/web.php` changes:

```php
// Replace the current Route::view lines:
Route::get('/services',                        [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}',         [ServiceController::class, 'show'])->name('services.show');

// Keep the special top-level URL for management-training (SEO):
Route::get('/management-training', function () {
    $service = \App\Models\Service::where('slug', 'management-training')->published()->firstOrFail();
    return app(\App\Http\Controllers\ServiceController::class)->show($service);
})->name('services.management-training');

// Admin (inside the existing auth+admin group):
Route::post('services/generate-seo',         [SeoGeneratorController::class, 'serviceSeo'])->name('services.generate-seo');
Route::post('services/generate-description', [SeoGeneratorController::class, 'serviceDescription'])->name('services.generate-description');
Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)->except(['show']);
```

Remove the 8 static `Route::view('/services/...')` lines.

---

## 5. Controllers

### 5.1 `App\Http\Controllers\ServiceController` (public)

- `index()` → `Service::published()->ordered()->get()`, returns `pages.services.index`.
- `show(Service $service)` → `abort_unless($service->is_published, 404);` computes `$related = Service::published()->where('id','!=',$service->id)->ordered()->limit(4)->get();` returns `pages.services.show`.

### 5.2 `App\Http\Controllers\Admin\ServiceController`

Mirror `Admin\BlogController` one-for-one:

- `index()`, `create()`, `store(StoreServiceRequest)`, `edit()`, `update(StoreServiceRequest)`, `destroy()`.
- Cover upload to Spaces under `services/YYYY/MM/uuid.ext`.
- `store` / `update`: normalise the incoming form fields — `services_included`, `suitable_for` arrive as textareas (one item per line) and are split into arrays; `faqs` arrives as a repeater (arrays `question[]` + `answer[]`) and is zipped into `[{q,a}]`.

### 5.3 `App\Http\Requests\StoreServiceRequest`
```
title required max:255
slug nullable alpha_dash max:200 unique:services,slug,{id}
eyebrow nullable max:120
summary nullable max:1000
description nullable
hero_image nullable image mimes:jpg,jpeg,png,webp max:5120
services_included_label nullable max:120
services_included nullable string        // textarea, one item per line
suitable_for_label nullable max:120
suitable_for nullable string
question array
question.* nullable string max:255
answer array
answer.* nullable string max:2000
seo_title nullable max:160
seo_description nullable max:500
seo_keywords nullable max:500
is_published boolean
position nullable integer min:0
```

### 5.4 `App\Http\Controllers\Admin\SeoGeneratorController::serviceSeo` / `serviceDescription`
Two new methods with prompts tuned for "service page copy" (not events, not blog):
- SEO prompt: SEO title 50-60 chars; meta description 130-155 chars single sentence; 6-10 keywords.
- Description prompt: 220-320-word HTML body with `<p>`, `<h3>`, `<ul>`; opens with the service purpose, one paragraph on "what SOV SUMMIT delivers", then bullet points, then a closing paragraph.

---

## 6. Views

### 6.1 `resources/views/pages/services/index.blade.php` — rewrite to be data-driven
Replaces the 8 hardcoded `<a class="service-card">` blocks with a loop over the `Service` collection. Preserves the exact `.services__grid` / `.service-card` markup, image sizes, and CTA copy. Keeps the current `Organization` + `BreadcrumbList` JSON-LD (already emitting).

### 6.2 `resources/views/pages/services/show.blade.php` — NEW (single dynamic detail view)
Structure — one file replaces all 7 static detail files:

```
1. @section head SEO block:
   - dynamic <title>, description, canonical, keywords, robots hints
   - Organization JSON-LD (constant)
   - BreadcrumbList JSON-LD (Home > Services > {service->title})
   - Service JSON-LD (name, description, url, provider, areaServed:Worldwide)
   - FAQPage JSON-LD (ONLY if $service->faqs is non-empty)
   - OG article/website tags + Twitter card
   - Same "microdata itemprop" convention used on the blog rewrite

2. Breadcrumb (visible)

3. <section class="service-hero"> — IDENTICAL markup to current pages:
     brandline eyebrow, h1, lede
     figure right-hand image (service->hero_url, alt = service->title)

4. <section class="service-description"> — NEW block, only if $service->description:
     .container > .prose.service-detail__prose {!! $service->description !!}
   Add matching CSS scoped to .service-detail__prose (max-width: none; inline-style
   neutralisation on span/font, same treatment as blog/event fixes).

5. Two optional checklist blocks (only render if the column is non-empty):
     <section class="alt">...<h2>{$service->services_included_label}</h2>
       <ul class="check-list">@foreach ... @endforeach</ul>
     </section>
     (same block again for suitable_for)

6. FAQ block (only if faqs non-empty):
     <details class="faq-item"> per FAQ.

7. Dynamic content sections:
     <x-content-sections :model="$service" />
   (This is what the user asked for — same subsystem as blog. Editors can add
    hero_banner / card_grid / accordion / cta_band / image_text / etc.)

8. Related services (auto: other published services, ordered, limit 4).

9. Closing CTA band (retain existing markup).
```

### 6.3 Delete the 7 static per-service views
Once seeded and verified, remove `pages/services/{conference-planning, delegation-management, events-productions, media-coverage, planning-coordination, security-coordination, travel-experiences}.blade.php`. The single `show.blade.php` covers all of them.
Also delete `pages/management-training.blade.php` — its content moves to the seeder and it is served by the special route.

### 6.4 Admin views (`resources/views/admin/services/`)
Mirror `resources/views/admin/blogs/`:
- `index.blade.php` — table with title, slug, published, position, updated_at, actions.
- `create.blade.php` and `edit.blade.php` — thin wrappers around `_form.blade.php`.
- `_form.blade.php` — copy of blog's `_form.blade.php` plus the extra fields:
  - `eyebrow` text input
  - `services_included_label` + `services_included` textarea (one item per line)
  - `suitable_for_label` + `suitable_for` textarea
  - FAQ repeater (question / answer pairs, add/remove rows with tiny JS)
  - `position` number input
  - Two AI buttons wired to the new routes above.
  - Same paste-sanitiser we added to blog/event Summernote init.

### 6.5 Admin nav
Add "Services" between "Events" and "Blog" in the sidebar (`admin-layout.blade.php`).

---

## 7. CSS

Add to `public/assets/css/style.css` in the service-detail area:

```css
.service-detail__prose { max-width: none; margin: 0; }
.service-detail__prose span,
.service-detail__prose font {
    font-family: inherit !important;
    font-size: inherit !important;
    color: inherit !important;
    background: transparent !important;
}
.service-detail__prose p { max-width: none; margin: 0 0 1.15rem;
    color: var(--ink-soft); font-size: 1.05rem; line-height: 1.75; }
/* h3, ul, ol, li, blockquote, img — same pattern as .blog-detail__prose */
```

(The existing `.service-hero`, `.check-list`, `.faq-item` classes remain unchanged — the detail view uses them as-is.)

---

## 8. SEO parity checklist

For every service detail page the rendered `<head>` must contain, at minimum:

- `<title>{seo_title or title} | SOV SUMMIT`
- `<meta name="description">` = seo_description || summary
- `<link rel="canonical">` = absolute URL
- `<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">`
- `<meta name="keywords">` (if seo_keywords)
- OG `og:type=website`, `og:title`, `og:description`, `og:url`, `og:image` (+ width/height/alt)
- Twitter `summary_large_image` + title/description/image + `label1/data1` = "Category" / "Services"
- JSON-LD `Organization` (const)
- JSON-LD `BreadcrumbList` (dynamic 3-level)
- JSON-LD `Service` (dynamic name/description/url/areaServed:"Worldwide"/provider Organization)
- JSON-LD `FAQPage` (only if `faqs` is non-empty)

Semantic HTML: `<article itemscope itemtype="https://schema.org/Service">` wrapper with `itemprop="name"` on h1 and `itemprop="description"` on lede, matching the treatment on blog/event detail pages.

---

## 9. Redirects & URL preservation

- Same URLs, so no redirect map needed for the 7 `/services/{slug}` URLs.
- `/management-training` continues to resolve — via the dedicated route that looks up `slug=management-training`.
- The current index-page card that links to `/management-training` keeps its href; a small if-branch renders that link with the special path if `slug === 'management-training'`, otherwise `/services/{slug}` — or move the /management-training URL under /services and add a 301. Recommend: **keep the top-level URL** (SEO-safe).

---

## 10. Seeder — `database/seeders/ServiceSeeder.php`

Insert **all 8 services in one go**, upsert by slug. Runs from `DatabaseSeeder`. Full text (from current blades, unchanged):

```php
<?php
namespace Database\Seeders;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->data() as $i => $row) {
            $row['position'] = $i;
            $row['is_published'] = true;
            $row['published_at'] = now();
            Service::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }

    private function data(): array
    {
        return [
            // ---- 1 ----
            [
                'slug' => 'planning-coordination',
                'title' => 'Planning and coordination for complex programmes',
                'eyebrow' => 'PLANNING & COORDINATION',
                'summary' => 'Successful programmes require more than individual suppliers. They require a clear plan, reliable communication, and coordination between every moving part. SOV SUMMIT supports clients from the initial brief through to delivery, helping organise the operational structure behind events, delegations, training programmes, and private experiences.',
                'description' => null, // start blank; editors can enrich later
                'hero_image' => 'assets/img/services/planning.webp',
                'services_included_label' => 'Services included',
                'services_included' => [
                    'Initial programme briefing','Event concept and operational planning','Venue and supplier sourcing',
                    'Accommodation coordination','Transportation coordination','Private aviation coordination',
                    'Guest and participant management','Schedule development','Hospitality coordination',
                    'Catering coordination','Production coordination','Security coordination',
                    'Media coordination','On-site coordination','Post-event follow-up',
                ],
                'suitable_for_label' => 'Suitable for',
                'suitable_for' => [
                    'Corporate events','International conferences','Executive programmes','Institutional meetings',
                    'Delegations','Management training','Private events','Family trips','Multi-destination programmes',
                ],
                'faqs' => null,
                'seo_title' => 'Event Planning & Provider Coordination | SOV SUMMIT',
                'seo_description' => 'SOV SUMMIT coordinates event planning, suppliers, venues, accommodation, transportation, guest management, schedules, and event delivery.',
                'seo_keywords' => 'event planning, supplier coordination, venue sourcing, guest management, schedules, logistics',
            ],

            // ---- 2 ----
            [
                'slug' => 'management-training',
                'title' => 'Management training designed around your objectives',
                'eyebrow' => 'MANAGEMENT TRAINING',
                'summary' => 'Effective management training should be relevant to the organisation, the participants, and the challenges they face. SOV SUMMIT coordinates bespoke management training programmes that combine learning, professional development, practical exchange, and carefully selected environments.',
                'hero_image' => 'assets/img/services/management-training.webp',
                'services_included_label' => 'Programme areas',
                'services_included' => [
                    'Leadership development','Executive development','Strategic management','Team development',
                    'Communication skills','Negotiation','International business','Decision-making',
                    'Organisational development','Conflict management','Change management','Cross-cultural cooperation',
                    'Executive retreats','Workshops and seminars','Experiential learning programmes',
                ],
                'suitable_for_label' => 'Programme formats',
                'suitable_for' => [
                    'Executive workshops','Management seminars','Leadership retreats','Team-building programmes',
                    'International learning visits','Corporate training events','Multi-day executive programmes','Bespoke institutional programmes',
                ],
                'faqs' => null,
                'seo_title' => 'Management Training Programmes & Executive Learning | SOV SUMMIT',
                'seo_description' => 'Bespoke management training programmes, leadership development, executive learning, team workshops, communication, negotiation, and international training experiences.',
                'seo_keywords' => 'management training, leadership development, executive learning, team workshops, negotiation',
            ],

            // ---- 3 ----
            [
                'slug' => 'conference-planning',
                'title' => 'Conference planning with operational precision',
                'eyebrow' => 'CONFERENCE PLANNING',
                'summary' => 'Conferences bring together people, information, schedules, venues, technology, hospitality, and expectations. SOV SUMMIT coordinates these elements to create a structured and professional event experience.',
                'hero_image' => 'assets/img/services/conferences.webp',
                'services_included_label' => 'Conference services',
                'services_included' => [
                    'International conference planning','Executive forums','Government and institutional meetings',
                    'Corporate conferences','Workshops','Seminars','Venue sourcing','Accommodation coordination',
                    'Speaker coordination','Guest registration','Participant management','Technical production',
                    'Audiovisual coordination','Catering and hospitality','Transportation','Security coordination',
                    'Media coverage','On-site event management','Post-event documentation',
                ],
                'suitable_for_label' => null,
                'suitable_for' => null,
                'faqs' => [
                    ['q' => 'What does conference planning include?',
                     'a' => 'Conference planning can include venue sourcing, programme coordination, speaker management, participant logistics, accommodation, transportation, technical production, catering, security coordination, media coverage, and on-site delivery.'],
                    ['q' => 'Can SOV SUMMIT coordinate international conferences?',
                     'a' => 'Yes. SOV SUMMIT can coordinate the relevant providers and operational requirements for international conferences, subject to the destination, scope, availability, and client brief.'],
                ],
                'seo_title' => 'Conference Planning & Event Management | SOV SUMMIT',
                'seo_description' => 'International conference planning, executive forums, institutional meetings, venue sourcing, speakers, hospitality, technical production, and on-site coordination.',
                'seo_keywords' => 'conference planning, executive forums, institutional meetings, venue sourcing, speakers',
            ],

            // ---- 4 ----
            [
                'slug' => 'delegation-management',
                'title' => 'Delegation management from arrival to departure',
                'eyebrow' => 'DELEGATION MANAGEMENT',
                'summary' => 'Delegations require accurate schedules, appropriate hospitality, reliable transportation, clear communication, and careful coordination between multiple participants and providers. SOV SUMMIT supports the operational planning of government, institutional, corporate, and executive delegations.',
                'hero_image' => 'assets/img/services/delegation.webp',
                'services_included_label' => 'Services included',
                'services_included' => [
                    'Delegation planning','Participant and guest coordination','Private aviation coordination',
                    'Airport assistance coordination','Accommodation','Chauffeur transportation','Ground transportation',
                    'Protocol and hospitality','Meeting schedules','Site visits','Venue coordination',
                    'Restaurant reservations','Cultural programmes','Security coordination','Media documentation',
                    'On-site support','Departure coordination',
                ],
                'suitable_for_label' => 'Typical delegation requirements',
                'suitable_for' => [
                    'Executive visits','Institutional meetings','Government programmes','Corporate delegations',
                    'Business missions','Site inspections','International forums','Multi-city itineraries','VIP hospitality programmes',
                ],
                'faqs' => [
                    ['q' => 'Does SOV SUMMIT manage government and institutional delegations?',
                     'a' => 'SOV SUMMIT coordinates the operational requirements of government, institutional, corporate, and executive delegations, including travel, accommodation, transportation, schedules, hospitality, site visits, and security coordination.'],
                    ['q' => 'What does delegation management include?',
                     'a' => 'Delegation management may include participant coordination, aviation, accommodation, ground transportation, protocol, meeting schedules, site visits, hospitality, security coordination, media documentation, and on-site support.'],
                ],
                'seo_title' => 'Delegation Management & International Coordination | SOV SUMMIT',
                'seo_description' => 'SOV SUMMIT coordinates government, institutional, corporate, and executive delegations, including travel, accommodation, transportation, protocol, security, and site visits.',
                'seo_keywords' => 'delegation management, protocol, government delegations, executive delegations, hospitality',
            ],

            // ---- 5 ----
            [
                'slug' => 'events-productions',
                'title' => 'Events and productions that connect people',
                'eyebrow' => 'EVENTS & PRODUCTIONS',
                'summary' => 'Every event has its own purpose, audience, atmosphere, and operational requirements. SOV SUMMIT coordinates the providers and details needed to create a coherent and professionally delivered event.',
                'hero_image' => 'assets/img/services/events.webp',
                'services_included_label' => 'Event types',
                'services_included' => [
                    'Corporate events','Corporate celebrations','Concerts','Film festivals',
                    'Fashion shows','Road shows','Private events',
                ],
                'suitable_for_label' => null,
                'suitable_for' => null,
                'faqs' => null,
                'seo_title' => 'Corporate & International Event Organisation | SOV SUMMIT',
                'seo_description' => 'SOV SUMMIT coordinates corporate events, celebrations, concerts, film festivals, fashion shows, road shows, executive dinners, and private productions.',
                'seo_keywords' => 'corporate events, concerts, film festivals, fashion shows, road shows, private productions',
            ],

            // ---- 6 ----
            [
                'slug' => 'security-coordination',
                'title' => 'Security coordination for people, venues, and programmes',
                'eyebrow' => 'SECURITY COORDINATION',
                'summary' => 'Security requirements should be considered as part of the overall programme from the beginning. SOV SUMMIT coordinates with suitable licensed security providers according to the destination, event type, participant profile, venue, schedule, and operational requirements.',
                'hero_image' => 'assets/img/services/security.webp',
                'services_included_label' => 'Services included',
                'services_included' => [
                    'Event security coordination','Executive and VIP protection coordination','Delegation security support',
                    'Venue security coordination','Access and guest management','Security planning with approved providers',
                    'Travel security coordination','Arrival and departure coordination','Route and schedule coordination',
                    'On-site communication','Coordination with venue management','Coordination with local providers','Security-related logistics',
                ],
                'suitable_for_label' => 'Suitable for',
                'suitable_for' => [
                    'Executive events','Government and institutional delegations','Corporate conferences','Private functions',
                    'International travel programmes','High-profile guests','Multi-location events','Sensitive meetings',
                ],
                'faqs' => [
                    ['q' => 'Does SOV SUMMIT provide security personnel?',
                     'a' => 'SOV SUMMIT coordinates suitable licensed security providers for events, delegations, travel, and executive programmes. The exact scope depends on local licensing requirements and the assignment.'],
                    ['q' => 'What is security coordination?',
                     'a' => 'Security coordination is the process of identifying and coordinating appropriate security providers, venue requirements, access procedures, schedules, transportation, and communication for a programme.'],
                ],
                'seo_title' => 'Event Security & Executive Protection Coordination | SOV SUMMIT',
                'seo_description' => 'SOV SUMMIT coordinates suitable licensed security providers for events, delegations, executive programmes, travel, access management, and VIP requirements.',
                'seo_keywords' => 'event security, executive protection, VIP security, delegation security, venue security',
            ],

            // ---- 7 ----
            [
                'slug' => 'media-coverage',
                'title' => 'Capture the moments that matter',
                'eyebrow' => 'MEDIA COVERAGE',
                'summary' => 'Events create important moments, relationships, and messages. Professional documentation helps organisations preserve those moments and communicate their impact after the event has ended. SOV SUMMIT coordinates suitable photographers, videographers, production teams, and media partners according to the purpose and format of the programme.',
                'hero_image' => 'assets/img/services/media.webp',
                'services_included_label' => 'Services included',
                'services_included' => [
                    'Event photography','Conference photography','Executive and delegation documentation',
                    'Video production','Event highlights','Interviews','Corporate communications content',
                    'Press and media coordination','Social media content','Behind-the-scenes documentation',
                    'Venue and programme coverage','Edited photo delivery','Edited video highlights','Post-event content packages',
                ],
                'suitable_for_label' => 'Media coverage for',
                'suitable_for' => [
                    'Conferences','Corporate events','Executive programmes','Delegations','Concerts',
                    'Film festivals','Fashion shows','Road shows','Private events','Management training programmes',
                ],
                'faqs' => [
                    ['q' => 'Does SOV SUMMIT provide event photography?',
                     'a' => 'SOV SUMMIT coordinates professional photographers, videographers, and media teams for conferences, corporate events, delegations, productions, and private programmes.'],
                    ['q' => 'Can SOV SUMMIT arrange video highlights?',
                     'a' => 'Yes. Video production and post-event highlight content can be coordinated with suitable media-production partners according to the programme\'s objectives.'],
                ],
                'seo_title' => 'Event Photography, Video Production & Media Coverage | SOV SUMMIT',
                'seo_description' => 'SOV SUMMIT coordinates photographers, videographers, media teams, event documentation, press support, and post-event visual content.',
                'seo_keywords' => 'event photography, video production, media coverage, event documentation, press coordination',
            ],

            // ---- 8 ----
            [
                'slug' => 'travel-experiences',
                'title' => 'Travel experiences, carefully coordinated',
                'eyebrow' => 'TRAVEL & EXPERIENCES',
                'summary' => 'Travel becomes more complex when several people, destinations, providers, and preferences must be coordinated at the same time. SOV SUMMIT organises the details behind private and executive travel experiences, from transportation and accommodation to restaurants, activities, special occasions, and local support.',
                'hero_image' => 'assets/img/services/travel.webp',
                'services_included_label' => 'Services included',
                'services_included' => [
                    'Private aviation coordination','Airport transfers','Chauffeur transportation','Accommodation coordination',
                    'Family travel planning','Executive travel','Restaurant reservations','Cultural experiences',
                    'Activities and excursions','Special occasions','Multi-city itineraries','Ground transportation',
                    'Travel schedules','Local provider coordination','On-site travel support',
                ],
                'suitable_for_label' => null,
                'suitable_for' => null,
                'faqs' => [
                    ['q' => 'Does SOV SUMMIT arrange private flights?',
                     'a' => 'SOV SUMMIT coordinates private aviation requirements through appropriate aviation providers, subject to availability, destination, timing, and the client\'s requirements.'],
                    ['q' => 'Does SOV SUMMIT organise family trips?',
                     'a' => 'Yes. SOV SUMMIT can coordinate family travel, including accommodation, transportation, child-friendly arrangements, restaurants, activities, special occasions, and multi-destination itineraries.'],
                ],
                'seo_title' => 'Private Travel, Aviation & Family Trip Coordination | SOV SUMMIT',
                'seo_description' => 'Private aviation coordination, accommodation, chauffeur transportation, family trips, restaurants, activities, and bespoke travel experiences by SOV SUMMIT.',
                'seo_keywords' => 'private aviation, family trips, chauffeur transportation, luxury travel, executive travel',
            ],
        ];
    }
}
```

Wire into `database/seeders/DatabaseSeeder.php`:
```php
$this->call([ServiceSeeder::class]);
```

---

## 11. Implementation order (do in this order — each step is independently verifiable)

1. **Migration** — `create_services_table` → `php artisan migrate`.
2. **Model** `App\Models\Service` (+ config/sections.php page-type entry).
3. **Seeder** — write `ServiceSeeder`, register in `DatabaseSeeder`, run `php artisan db:seed --class=ServiceSeeder`. Verify rows exist in DB.
4. **Public views** — new `pages/services/show.blade.php`, rewrite `pages/services/index.blade.php` to loop the model. Add CSS for `.service-detail__prose`.
5. **Routes** — replace static `Route::view` lines with the resource + special management-training route. Delete the 7 static per-service blade files + `pages/management-training.blade.php`.
6. **Manual QA** — click through every service URL, diff against the old static rendering (JSON-LD, breadcrumbs, checklist counts, FAQ counts, related cards). Confirm `/management-training` still resolves at the same URL.
7. **Admin CRUD** — `Admin\ServiceController`, `StoreServiceRequest`, `admin/services/{index,create,edit,_form}.blade.php`, sidebar link. Include the FAQ repeater JS + Summernote paste sanitiser.
8. **AI helpers** — `SeoGeneratorController::serviceSeo` + `serviceDescription`, routes, wire the two buttons in `_form.blade.php`.
9. **Full QA** — create a new service in admin, publish, check public detail page, edit an existing seeded service, add a dynamic content section from the section picker, verify SEO tags with Rich Results Test (schema.org validator).

---

## 12. Non-goals / explicitly out of scope

- No category taxonomy for services (single flat list, `position`-ordered).
- No pagination on `/services` (8-ish services — a single grid is fine).
- No user-facing filtering.
- No i18n — single-language site.
- No historical redirect map — URLs are preserved 1:1.
- No section-level authorisation beyond the existing auth middleware.

---

## 13. Risks / things to double-check

- `hero_image` mixes two shapes (relative `assets/…` seeded path vs. Spaces upload path). The `getHeroUrlAttribute` accessor handles both — cover with a small model-level test.
- The `/management-training` special route must run **before** the resource route or the resource route will greedily match; put it first.
- The Explore agent found that some current pages emit slightly different `<h2>` labels above the checklist ("Conference services", "Programme areas", "Media coverage for", etc.). The seeder captures each as `services_included_label` / `suitable_for_label` so the labels stay verbatim on rebuild.
- Section templates config: verify the `page_type => 'service'` addition does not need any admin section-picker view update (it should just work if the picker reads `config('sections.types')` and the section types are un-scoped).

---

## 14. Deliverables checklist

- [ ] `database/migrations/…_create_services_table.php`
- [ ] `app/Models/Service.php`
- [ ] `database/seeders/ServiceSeeder.php` (+ registered in `DatabaseSeeder`)
- [ ] `config/sections.php` — add `service` page-type key
- [ ] `app/Http/Controllers/ServiceController.php` (public)
- [ ] `app/Http/Controllers/Admin/ServiceController.php`
- [ ] `app/Http/Requests/StoreServiceRequest.php`
- [ ] `resources/views/pages/services/index.blade.php` (rewrite)
- [ ] `resources/views/pages/services/show.blade.php` (new, replaces 7 files)
- [ ] Delete 7 static service blades + `management-training.blade.php`
- [ ] `resources/views/admin/services/{index,create,edit,_form}.blade.php`
- [ ] `resources/views/components/admin-layout.blade.php` — add Services nav link
- [ ] `routes/web.php` — swap Route::view for resource + special management-training route + admin routes + AI-generation routes
- [ ] `app/Http/Controllers/Admin/SeoGeneratorController.php` — `serviceSeo` + `serviceDescription`
- [ ] `public/assets/css/style.css` — `.service-detail__prose` block
- [ ] Manual QA sweep (URLs, JSON-LD, admin CRUD, content-sections attach)
