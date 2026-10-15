# Blog (Insights / Journal) — Plan

**Author's brief:** The SOV SUMMIT site already has a fully-modelled `Event` content type (admin CRUD, public list, public detail with dynamic content sections). Duplicate that shape into a `Blog` (a.k.a. Insights / Journal) content type: admin CRUD, a public blog list, a public blog detail page that supports the same `<x-content-sections>` picker/editor experience as the event detail page, and a compact "latest insights" section on the landing page.

**Requirement summary:**
1. Admin CRUD (list / create / edit / delete) for blog posts, matching the visual language and interactions of `admin/events`.
2. Modern, editorial public blog index at `/blog` — hero heading + featured lede post + card grid.
3. Public blog detail at `/blog/{slug}` mirroring `events-show` — cinematic hero, standfirst, meta strip, prose body, then `<x-content-sections>` (dynamic sections registry).
4. Landing page (`pages/home.blade.php`) shows a compact "Insights" / "Journal" section with the 3 most recent published posts, linking to `/blog`.
5. Register `App\Models\Blog::class` in `config/sections.php` so admins can add/edit dynamic sections on blog detail pages exactly as they do on events.

---

## 1. Guiding Principles

1. **Reuse, don't reinvent.** Blog mirrors Event 1:1 — same DB shape (with blog-specific fields), same form layout, same SEO health checklist, same Summernote toolbar, same DO Spaces upload path (`blogs/…`), same overlay/edit sections plumbing.
2. **Editorial voice on the front-end.** Event cards emphasise date + location. Blog cards emphasise category + read time + author.
3. **One picker registry, many parents.** Blog reuses the polymorphic `ContentSection` system from `dynamic-content-sections-plan.md`. `page_type = 'blog'`.
4. **No CSS collisions.** All new classes namespaced `.blog-*` (like `.event-*`), appended to `public/assets/css/style.css`.
5. **AI helpers reused.** The SEO generator (`/admin/events/generate-seo`) and description generator (`/admin/events/generate-description`) can be pointed at blog content later — for the MVP, hide the buttons behind an `if` and ship without them (or leave them out entirely — first pass ships without AI helpers, matching Event's markup minus the two AI blocks).

---

## 2. Data Model

### Migration: `create_blogs_table`

```php
Schema::create('blogs', function (Blueprint $t) {
    $t->id();
    $t->string('title');
    $t->string('slug')->unique();
    $t->string('seo_title', 160)->nullable();
    $t->string('seo_keywords', 500)->nullable();
    $t->string('summary', 500)->nullable();          // meta description + card standfirst
    $t->longText('description')->nullable();         // Summernote body (before sections)
    $t->string('cover_image')->nullable();           // DO Spaces path or absolute URL
    $t->string('category', 80)->nullable();          // e.g. "Coordination", "Events", "Travel"
    $t->string('author', 120)->nullable();           // by-line
    $t->unsignedSmallInteger('reading_time')->nullable(); // minutes
    $t->timestamp('published_at')->nullable();       // acts as the human date
    $t->boolean('is_published')->default(true);
    $t->timestamps();

    $t->index(['is_published', 'published_at']);
    $t->index('category');
});
```

### Model: `App\Models\Blog`

- `use HasContentSections;` with `protected string $contentSectionPageType = 'blog';`
- `$casts` for `published_at => 'datetime'`, `is_published => 'boolean'`.
- `booted()` auto-fills unique slug on saving if empty.
- `uniqueSlug(string $title, ?int $ignoreId = null): string`.
- `getCoverUrlAttribute()` — same DO Spaces url() logic as `Event`.
- Scopes: `published()`, `latest()` (`->orderByDesc('published_at')->orderByDesc('created_at')`).

---

## 3. Requests

`App\Http\Requests\StoreBlogRequest` — mirrors `StoreEventRequest`:

```php
'title'         => ['required','string','max:255'],
'slug'          => ['nullable','string','max:255','alpha_dash', Rule::unique('blogs','slug')->ignore($blogId)],
'seo_title'     => ['nullable','string','max:160'],
'seo_keywords'  => ['nullable','string','max:500'],
'summary'       => ['nullable','string','max:500'],
'description'   => ['nullable','string'],
'cover_image'   => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
'category'      => ['nullable','string','max:80'],
'author'        => ['nullable','string','max:120'],
'reading_time'  => ['nullable','integer','min:1','max:180'],
'published_at'  => ['nullable','date'],
'is_published'  => ['sometimes','boolean'],
```

---

## 4. Controllers

### `App\Http\Controllers\Admin\BlogController`

Copy of `Admin\EventController` — index/create/store/edit/update/destroy. Upload path prefix: `blogs/YYYY/MM/{uuid}.{ext}` on the `spaces` disk.

### `App\Http\Controllers\BlogController` (public)

```php
public function index() {
    $featured = Blog::published()->latest('published_at')->first();
    $posts = Blog::published()
        ->when($featured, fn ($q) => $q->where('id','!=',$featured->id))
        ->orderByDesc('published_at')
        ->paginate(9);
    return view('pages.blog.index', compact('featured','posts'));
}

public function show(Blog $blog) {
    abort_unless($blog->is_published, 404);
    $related = Blog::published()->where('id','!=',$blog->id)
        ->orderByDesc('published_at')->limit(3)->get();
    return view('pages.blog.show', compact('blog','related'));
}
```

---

## 5. Routes (`routes/web.php`)

Public:
```php
Route::get('/blog',                [BlogController::class,'index'])->name('blog.index');
Route::get('/blog/{blog:slug}',    [BlogController::class,'show'])->name('blog.show');
```

Admin (inside the existing `Route::middleware('auth')->prefix('admin')->name('admin.')` group):
```php
Route::resource('blogs', AdminBlogController::class)->except(['show']);
```

---

## 6. Admin Views (`resources/views/admin/blogs/…`)

- `index.blade.php` — same table shape as events, columns: Cover / Title / Category / Published / Author / Status / Actions. Uses `<x-admin-layout>`.
- `create.blade.php` + `edit.blade.php` — thin wrappers that include `_form.blade.php`.
- `_form.blade.php` — mirrors `admin/events/_form.blade.php` minus the two AI helper buttons. Fields:
  - Main column: Title, Slug, Category, Author, Reading time (min), Summernote description.
  - Sidebar: Publish card (published_at datetime-local + published checkbox + submit), Cover image, SEO card (SEO title, meta description, keywords).
- Uses the same Summernote CDN and the same `/admin/media/upload` endpoint for inline images.

### Admin nav

Add a Blogs entry to the "Content" group in `resources/views/components/admin-layout.blade.php`, just below Events, active when `request()->routeIs('admin.blogs.*')`.

---

## 7. Public Views

### `resources/views/pages/blog/index.blade.php`

Modern editorial layout:
1. Hero band: eyebrow "Journal", h1 "Insights & Field Notes.", lede.
2. Featured card (`.blog-featured`) with large media on the left, meta + title + summary + CTA on the right.
3. Grid of `.blog-card` (see partial below), 3 columns desktop / 2 tablet / 1 mobile.
4. Pagination footer if `$posts->hasPages()`.
5. Terminates with `@include('partials.cta-band')`.

### `resources/views/pages/blog/show.blade.php`

Cloned from `events-show.blade.php`:
- `.blog-detail__hero` (photo/empty variants, breadcrumbs, eyebrow "Insight", h1, standfirst = summary, meta strip: Published date · Category · Reading time · Author).
- `.blog-detail__body` with `.prose.blog-detail__prose`.
- `<x-content-sections :model="$blog" />` — dynamic sections render right after the prose body.
- Related posts strip using `.blog-card` partial.
- Trailing CTA band.

### `resources/views/partials/blog-card.blade.php`

Anchor tag → `route('blog.show',$blog)`, media block, body block:
- Category chip (top-left over media).
- Date + Reading time meta row.
- Title (serif h3).
- Summary (line-clamped to 3 lines).
- "Read insight →" CTA row.

---

## 8. Landing Page Section

In the `/` route closure (already fetches `$featuredEvents`), also fetch:
```php
$latestPosts = \App\Models\Blog::published()->latest('published_at')->limit(3)->get();
```
Pass into `view('pages.home', compact('featuredEvents','latestPosts'))`.

Inside `pages/home.blade.php`, insert a `.journal` (or `.insights-block`) section just before the closing CTA band. Structure:
```html
<section class="journal" aria-labelledby="journal-heading">
  <div class="container">
    <header class="journal__head">
      <span class="eyebrow">The Journal</span>
      <h2 id="journal-heading">Field notes from the coordination desk.</h2>
      <p class="lede">Occasional writing on international programmes, protocol, logistics, and the craft of coordination.</p>
    </header>
    <div class="journal__grid">
      @foreach ($latestPosts as $blog)
        @include('partials.blog-card', ['blog' => $blog])
      @endforeach
    </div>
    <div class="journal__footer">
      <a class="btn btn-secondary" href="{{ route('blog.index') }}">Read the journal</a>
    </div>
  </div>
</section>
```
Wrap in `@if(!empty($latestPosts) && $latestPosts->isNotEmpty())`.

---

## 9. Dynamic Sections Wiring

In `config/sections.php` extend the `sectionable_types` allow-list:
```php
'sectionable_types' => [
    \App\Models\Event::class,
    \App\Models\Blog::class,
],
```
`Blog::$contentSectionPageType = 'blog'` ensures picker inserts sections with `page_type='blog'` for future filtering.

No other section registry work is required — the whole 10-template library is instantly available on blog detail pages.

---

## 10. CSS

Append to `public/assets/css/style.css` a `/* ---------- Blog ---------- */` block that ports the event styles with `.blog-*` prefixes, plus:
- `.blog-hero` — editorial hero for the index page.
- `.blog-featured` — split card (image left, copy right).
- `.blog-grid` — same tokens as `.events-grid`.
- `.blog-card`, `.blog-card__category`, `.blog-card__meta`, `.blog-card__title`, `.blog-card__summary`, `.blog-card__cta`.
- `.blog-detail__hero`, `.blog-detail__body`, `.blog-detail__prose`, `.blog-detail__meta`.
- `.journal`, `.journal__head`, `.journal__grid`, `.journal__footer` for the landing page section.

Design tokens reused unchanged: `--paper`, `--paper-alt`, `--ink`, `--ink-soft`, `--gold`, `--gold-bright`, `--line`, `--font-serif`, `--font-sans`, `--radius`.

---

## 11. Ordered TODO

### Phase A — Backend
- [x] A1. `create_blogs_table` migration + `Blog` model with `HasContentSections`.
- [x] A2. `StoreBlogRequest`.
- [x] A3. `Admin\BlogController` (resource) + public `BlogController`.
- [x] A4. Routes wired in `routes/web.php`.
- [x] A5. `config/sections.php` allow-list updated.

### Phase B — Admin UI
- [x] B1. `admin/blogs/index.blade.php` (matches events index table).
- [x] B2. `admin/blogs/create.blade.php`, `edit.blade.php`, `_form.blade.php`.
- [x] B3. Admin sidebar nav entry.

### Phase C — Public UI
- [x] C1. `partials/blog-card.blade.php`.
- [x] C2. `pages/blog/index.blade.php`.
- [x] C3. `pages/blog/show.blade.php` (with `<x-content-sections>`).
- [x] C4. CSS block appended to `style.css`.

### Phase D — Landing page
- [x] D1. `/` route closure fetches `$latestPosts`.
- [x] D2. `pages/home.blade.php` renders the Journal section.

---

## 12. Non-Goals (Explicitly Out of Scope)

- Comments / reactions.
- Tags (only a single `category` string field for MVP).
- Archive by year / month.
- RSS feed.
- AI generator wiring for blog SEO/description (event helpers stay event-only for now — can be generalised later).
- Migration of any legacy blog HTML — no existing blog to import.

---

## 13. Reference

- Events plan / patterns: `resources/views/pages/events.blade.php`, `pages/events-show.blade.php`, `admin/events/*`.
- Dynamic sections: `memory_bank/dynamic-content-sections-plan.md`, `App\Models\ContentSection`, `App\Http\Controllers\Admin\SectionController`, `resources/views/components/content-sections.blade.php`.
- Media upload endpoint: `POST /admin/media/upload` (accepts a file, returns `{ url }`), stores under `spaces` disk.
- Design tokens: `public/assets/css/style.css` `:root` block.
