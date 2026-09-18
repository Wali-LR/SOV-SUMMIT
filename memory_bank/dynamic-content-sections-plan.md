# Dynamic Content Sections — Plan

**Author's brief:** After the main content of any public page (event detail, blog post detail, service detail, custom page, etc.), the site should allow a signed-in admin to click **"Add section"**, pick from a modal of pre-designed section templates (with mockup previews), insert the chosen template into the database, and then edit each field of that section inline — title, description, images, rich text, etc. — with a proper rich editor for text-heavy fields.

**Requirement summary:**
1. Feature must be reusable across content types (events today, blog + services + pages tomorrow).
2. Admin-only UI. Public visitors see the rendered output only.
3. 5–10 predefined section templates with mockup previews.
4. Each section instance is editable in place.
5. Rich text uses Summernote (already installed).
6. Images upload to DigitalOcean Spaces under `sob-summit/media/` via the existing `/admin/media/upload` endpoint.

---

## 1. Guiding Principles

1. **One system, many parents.** Sections attach to any Eloquent model via polymorphic relation (`sectionable_type` + `sectionable_id`).
2. **Data first, templates second.** Section content is JSON in the DB, keyed by a `type` string. Rendering is a Blade partial per type. Adding a new template = new registry entry + new Blade file, no schema change.
3. **No page reload for edits.** All admin actions are AJAX. Section renders swap in place after save.
4. **Fail-open on the public side.** Public rendering never checks auth; the admin overlay is layered on top only when `auth()->check()`.
5. **Reuse what exists.** Summernote for rich text. `/admin/media/upload` for images. Existing `admin-layout` styles for the picker modal.
6. **Content sections coexist with the main body.** They render AFTER the current `description` field on each page (unless later configured otherwise).
7. **No page-builder maximalism.** We are not rebuilding Gutenberg. Ten curated templates that fit the SOV SUMMIT brand — that's the ceiling.

---

## 2. Data Model

### Migration: `content_sections`

```php
Schema::create('content_sections', function (Blueprint $t) {
    $t->id();
    $t->morphs('sectionable');            // sectionable_type + sectionable_id + index
    $t->string('page_type', 40)->nullable(); // free-form page bucket: 'event', 'blog', 'service', 'home_page', ...
    $t->string('type', 60);               // registry key, e.g. 'rich_text'
    $t->string('short', 255)->nullable(); // short label / summary / anchor slug
    $t->unsignedInteger('position')->default(0);
    $t->json('data');                     // type-specific payload
    $t->boolean('is_published')->default(true);
    $t->timestamps();
    $t->index(['sectionable_type', 'sectionable_id', 'position']);
    $t->index('page_type');
});
```

**`page_type`** — a free-form string bucket (no enum) so we can filter or theme sections by the page family they belong to. Populate it from the parent when creating a section (event detail → `'event'`, blog detail → `'blog'`, service detail → `'service'`, home page → `'home_page'`, etc.). Adding a new page family is a one-line change — just start passing the new string.

**`short`** — an optional short label / summary for the section. Useful as an admin-facing nickname, an in-page anchor slug, or a nav-menu label for long pages.

### Model: `App\Models\ContentSection`

```php
class ContentSection extends Model {
    protected $fillable = ['sectionable_type','sectionable_id','page_type','type','short','position','data','is_published'];
    protected $casts = ['data' => 'array', 'is_published' => 'boolean'];

    public function sectionable() { return $this->morphTo(); }

    public function definition(): array {
        return config("sections.types.{$this->type}", []);
    }
}
```

### Trait: `App\Models\Concerns\HasContentSections`

```php
trait HasContentSections {
    public function sections() {
        return $this->morphMany(ContentSection::class, 'sectionable')
            ->orderBy('position');
    }
    public function publishedSections() {
        return $this->sections()->where('is_published', true);
    }
}
```

Apply to `Event` now. Later: `Post`, `Service`, `Page`.

---

## 3. Section Registry (`config/sections.php`)

Each entry is the single source of truth for a template:

```php
return [
    'types' => [
        'rich_text' => [
            'label'       => 'Rich Text',
            'description' => 'Long-form prose with headings and lists.',
            'icon'        => 'text',
            'preview'     => '/assets/img/sections/preview/rich-text.svg',
            'template'    => 'sections.rich-text',
            'editor'      => 'sections.editors.rich-text',
            'default'     => ['heading' => null, 'body' => '<p></p>'],
            'fields'      => [
                'heading' => ['type' => 'text', 'label' => 'Heading', 'max' => 160],
                'body'    => ['type' => 'richtext', 'label' => 'Body'],
            ],
        ],
        'image_text' => [ ... ],
        'card_grid' => [ ... ],
        'quote' => [ ... ],
        'gallery' => [ ... ],
        'stats' => [ ... ],
        'cta_band' => [ ... ],
        'accordion' => [ ... ],
        'raw_html' => [ ... ],
        'hero_banner' => [ ... ],
    ],
];
```

### Ten templates for MVP

| Key            | Label            | Shape                                                                     |
|----------------|------------------|---------------------------------------------------------------------------|
| `rich_text`    | Rich Text        | `{ heading, body(html) }`                                                 |
| `image_text`   | Image + Text     | `{ heading, body, image, align: left/right }`                             |
| `card_grid`    | Card Grid        | `{ heading, cards: [{ title, description, image }] }` — 2/3/4 columns    |
| `quote`        | Pull Quote       | `{ body, author, role }`                                                  |
| `gallery`      | Gallery          | `{ heading, images: [{ src, alt, caption }], columns }`                  |
| `stats`        | Stats Row        | `{ heading, items: [{ number, label }] }`                                 |
| `cta_band`     | CTA Band         | `{ heading, subheading, cta_label, cta_href, variant: light/dark }`      |
| `accordion`    | FAQ / Accordion  | `{ heading, items: [{ q, a(html) }] }`                                    |
| `hero_banner`  | Hero Banner      | `{ eyebrow, heading, subheading, image, cta_label, cta_href }`           |
| `raw_html`     | Custom HTML      | `{ html }` — advanced escape hatch                                        |

Each preview mockup is a lightweight SVG at `/public/assets/img/sections/preview/{key}.svg` — hand-drawn wireframes ~320×200, ~8 KB each. Total ~80 KB for the picker modal.

---

## 4. Routes

```php
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get   ('sections/templates',            [SectionController::class, 'templates'])->name('sections.templates');
    Route::post  ('sections',                      [SectionController::class, 'store'])->name('sections.store');
    Route::get   ('sections/{section}/edit',       [SectionController::class, 'edit'])->name('sections.edit');
    Route::patch ('sections/{section}',            [SectionController::class, 'update'])->name('sections.update');
    Route::delete('sections/{section}',            [SectionController::class, 'destroy'])->name('sections.destroy');
    Route::post  ('sections/reorder',              [SectionController::class, 'reorder'])->name('sections.reorder');
});
```

`store` receives `{ sectionable_type, sectionable_id, page_type, type, short? }` and inserts a row with the registry's `default` data at `position = max+1`. `update` receives the full `data` object plus optional `short`. `edit` returns the rendered editor form (server-rendered HTML fragment) for the side panel. `reorder` receives `{ ids: [3,1,2,...] }`.

**Authorisation:** whitelist sectionable_type against the trait — only models that use `HasContentSections` can be a parent. Prevents attackers pointing sections at arbitrary tables.

---

## 5. Public Rendering

### The Blade component

```blade
{{-- resources/views/components/content-sections.blade.php --}}
@props(['model'])
@php
    $sections = $model->publishedSections()->get();
@endphp

@if ($sections->isNotEmpty() || auth()->check())
  <div class="content-sections" data-sectionable-type="{{ get_class($model) }}" data-sectionable-id="{{ $model->id }}">
    @foreach ($sections as $section)
      <div class="content-section-wrapper" data-section-id="{{ $section->id }}">
        @include($section->definition()['template'], ['section' => $section])
        @auth
          @include('admin.sections._overlay', ['section' => $section])
        @endauth
      </div>
    @endforeach

    @auth
      @include('admin.sections._add-button')
    @endauth
  </div>
@endif
```

Drop the component after the existing description block on any content-type page:

```blade
{{-- pages/events-show.blade.php --}}
<section class="event-detail__body">
  <div class="container">
    <div class="prose event-detail__prose">{!! $event->description !!}</div>
  </div>
</section>

<x-content-sections :model="$event" />
```

### Each section template

Each `sections/*.blade.php` receives `$section` and reads `$section->data`:

```blade
{{-- resources/views/sections/rich-text.blade.php --}}
@php $d = $section->data; @endphp
<section class="cs cs--rich-text">
  <div class="container cs__inner">
    @if (!empty($d['heading']))
      <h2 class="cs__heading">{{ $d['heading'] }}</h2>
    @endif
    <div class="cs__body event-detail__prose">{!! $d['body'] ?? '' !!}</div>
  </div>
</section>
```

All section CSS lives under a `.cs` namespace so it can't collide with the marketing site's existing styles.

---

## 6. Admin UI

### Global admin toolbar (only when `auth()->check()` on public pages)

A subtle fixed pill bar at the bottom-right of the viewport: "🖉 Editing mode · Add section". Doesn't intrude on visitor experience because it's not rendered for anonymous users.

### Add-section modal

A backdrop + centered card, ~720 px wide, showing a 3-column grid of the ten templates. Each card = SVG preview + label + one-line description. Clicking a card fires:

```js
POST /admin/sections
{ sectionable_type: 'App\\Models\\Event', sectionable_id: 42, type: 'card_grid' }
```

Response returns the rendered HTML of the freshly-created section. Client appends it inside `.content-sections`. Modal closes.

### Section overlay (per-section admin controls)

Shown only when logged in. Absolutely positioned top-right of each `.content-section-wrapper`:

```
[✎ Edit] [↕ Move] [👁 Hide] [✕ Delete]
```

Hovering the wrapper reveals a dashed outline to make the boundary obvious.

### Edit panel

Clicking Edit opens a right-hand slide-in panel (500 px wide, full-height). Server renders the fields via the registry's `editor` view. Text fields = inputs. Richtext fields = Summernote instance. Image fields = upload button + preview. Repeatable arrays (cards, items, images) = add/remove row buttons.

Save button → `PATCH /admin/sections/{id}` with the assembled `data` object → response returns the freshly rendered section HTML → replace the wrapper's inner HTML.

### Reordering

Wrap `.content-sections` with SortableJS on `.content-section-wrapper` children. `onEnd` posts the new order to `/admin/sections/reorder`. Handle = the Move button in the overlay so accidental drags don't happen when clicking Edit.

---

## 7. Feature Flag / Rollout

Before wiring the admin toolbar and the `<x-content-sections>` component into every page, gate it with a config toggle:

```php
// config/features.php
return ['dynamic_sections' => env('DYNAMIC_SECTIONS_ENABLED', false)];
```

Public pages render `<x-content-sections>` only when the flag is on. This lets us ship the backend and admin UI without any visitor-facing exposure until we're happy.

---

## 8. Migration Path to Other Content Types

To enable sections on a new model (blog `Post`, `Service`, custom `Page`):

1. Add the `HasContentSections` trait to the model.
2. Add the model's class name to the sectionable allow-list in `SectionController::authorizeSectionable()`.
3. Drop `<x-content-sections :model="$post" />` into that model's detail Blade view.

No new controllers, no new migrations, no new registry work. The entire template library is instantly available.

For **custom standalone pages** (marketing pages authored purely as sections, no primary body), introduce a lightweight `App\Models\Page { slug, title, meta_* }` — sections attached polymorphically supply the entire body.

---

## 9. Ordered TODO

### Phase A — Foundation (backend)
- [ ] A1. `content_sections` migration + `ContentSection` model.
- [ ] A2. `HasContentSections` trait. Apply to `Event`.
- [ ] A3. `config/sections.php` registry — start with 3 types (`rich_text`, `image_text`, `quote`).
- [ ] A4. `SectionController` with store / update / edit / destroy / reorder / templates methods.
- [ ] A5. Authorisation helper: whitelist sectionable classes.
- [ ] A6. Routes under `admin` middleware group.
- [ ] A7. Feature flag `config/features.php` → default off.

### Phase B — Public render
- [ ] B1. `x-content-sections` Blade component.
- [ ] B2. Section CSS namespace (`.cs`, `.cs--*`) file appended to `style.css`.
- [ ] B3. Build `sections/rich-text.blade.php`, `sections/image-text.blade.php`, `sections/quote.blade.php`.
- [ ] B4. Wire it into `pages/events-show.blade.php` behind the feature flag.
- [ ] B5. Seed 1 event with 3 hand-authored sections via tinker; visually verify.

### Phase C — Admin picker + create
- [ ] C1. Admin toolbar pill (bottom-right fixed) — visible only to authed users on public pages.
- [ ] C2. Section picker modal with 3 template cards + SVG previews.
- [ ] C3. `POST /admin/sections` wired; new section appears in DOM without page reload.
- [ ] C4. Per-section overlay (edit/delete/move handles) drawn only when authed.

### Phase D — Inline editing
- [ ] D1. Server-rendered editor forms per type. `editor` Blade partials under `admin/sections/editors/`.
- [ ] D2. Slide-in right panel that loads the editor via `GET /admin/sections/{id}/edit`.
- [ ] D3. Summernote instance for `richtext` fields inside the panel.
- [ ] D4. Image field component reusing `/admin/media/upload`.
- [ ] D5. `PATCH /admin/sections/{id}` → response returns new HTML → swap.
- [ ] D6. Delete button with confirm → `DELETE /admin/sections/{id}` → fade+remove wrapper.

### Phase E — Reorder + polish
- [ ] E1. SortableJS on `.content-sections`. Handle = move button.
- [ ] E2. `POST /admin/sections/reorder`.
- [ ] E3. Per-section "Hide from public" toggle (`is_published`).
- [ ] E4. Empty-state message: "No extra sections yet. Add one below."
- [ ] E5. Loading / error states on all admin actions (spinner + toast, reuse patterns from event form).

### Phase F — Remaining templates
- [ ] F1. `card_grid` + editor with dynamic card rows.
- [ ] F2. `gallery` + editor with multi-image upload.
- [ ] F3. `stats` + editor.
- [ ] F4. `cta_band` + editor.
- [ ] F5. `accordion` + editor with dynamic Q&A rows.
- [ ] F6. `hero_banner` + editor.
- [ ] F7. `raw_html` + editor (CodeMirror or plain textarea with mono font).

### Phase G — Cross-model rollout
- [ ] G1. `Post` model (when blog ships) — trait + allow-list + view integration.
- [ ] G2. `Service` model — trait + allow-list + view integration on service detail pages.
- [ ] G3. `Page` model for standalone marketing pages powered entirely by sections.

### Phase H — Quality
- [ ] H1. Validation: server-side per-type schema check (max lengths, required fields).
- [ ] H2. XSS: strip disallowed tags from `richtext` and `raw_html` server-side using an allow-list.
- [ ] H3. Rate-limit admin AJAX (60/min) to prevent runaway loops.
- [ ] H4. Flip the feature flag on.

---

## 10. Non-Goals (Explicitly Out of Scope)

- No visual page-builder canvas (drag-to-place at pixel positions). Sections stack vertically.
- No per-section theming controls beyond the toggles baked into each template (e.g. `variant: light/dark` on `cta_band`).
- No version history / revisions. Editing is destructive. (Add later if the client asks.)
- No public preview URL for unpublished sections. `is_published=false` = hidden from public, visible in admin only.
- No section templates duplicated across content types with different names — one registry serves all.
- No conversion of the existing static hand-authored home page sections into DB rows. Those stay in Blade.
- No Vue/React SPA. Vanilla JS + Blade fragments swapped via AJAX.

---

## 11. Risks & Open Questions

- **Rich text sanitisation:** Summernote output can contain arbitrary HTML. Must sanitise server-side (HTMLPurifier or an allow-list) before persisting `richtext` fields. Same for `raw_html` — restrict tags harder and warn admin the field is trusted.
- **Slug/anchor collisions:** if two sections both render `<h2 id="programme">`, in-page anchors break. Auto-slug and dedupe per page at render time.
- **Preview SVGs:** hand-drawn wireframes. Design pass needed before Phase C1. Placeholder SVGs are fine for MVP.
- **Migration reversibility:** rolling back `content_sections` migration would delete data. Once we ship, only *additive* migrations from here.
- **Cache invalidation:** any page-level HTTP cache must key on `sections.updated_at` per model, or bust on section save. Not urgent — the current stack has no HTTP cache.

---

## 12. Reference

- Existing rich editor: Summernote 0.8.20 (lite). Reuse init from `resources/views/admin/events/_form.blade.php`.
- Media upload endpoint: `POST /admin/media/upload` → returns `{ url, path }`. Stores under `sob-summit/media/YYYY/MM/{uuid}.{ext}` on DO Spaces.
- Auth: Breeze session auth. Admin login at `/login`, redirects to `/dashboard`.
- Existing memory bank doc: `html-to-blade-conversion.md`.
