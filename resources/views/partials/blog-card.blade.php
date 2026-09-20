@php
    /** @var \App\Models\Blog $blog */
    $cover = $blog->cover_url;
@endphp
<a class="blog-card" href="{{ route('blog.show', $blog) }}" aria-label="{{ $blog->title }}">
  <div class="blog-card__media {{ $cover ? '' : 'blog-card__media--empty' }}">
    @if ($cover)
      <img src="{{ $cover }}" alt="" aria-hidden="true" loading="lazy" width="800" height="600">
      <div class="blog-card__overlay" aria-hidden="true"></div>
    @else
      <span class="blog-card__placeholder" aria-hidden="true">
        <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
          <path d="M11 8h20a3 3 0 0 1 3 3v26a3 3 0 0 1-3 3H11a3 3 0 0 1-3-3V11a3 3 0 0 1 3-3z"/>
          <path d="M14 16h14M14 22h14M14 28h9"/>
        </svg>
      </span>
    @endif
    @if ($blog->category)
      <span class="blog-card__category">{{ $blog->category }}</span>
    @endif
  </div>
  <div class="blog-card__body">
    <div class="blog-card__meta">
      @if ($blog->published_at)
        <span class="blog-card__date">{{ $blog->published_at->format('d M Y') }}</span>
      @endif
      @if ($blog->reading_time)
        <span class="blog-card__dot" aria-hidden="true">&middot;</span>
        <span class="blog-card__read">{{ $blog->reading_time }} min read</span>
      @endif
    </div>
    <h3 class="blog-card__title">{{ $blog->title }}</h3>
    @if ($blog->summary)
      <p class="blog-card__summary">{{ $blog->summary }}</p>
    @endif
    @if ($blog->author)
      <span class="blog-card__author">By {{ $blog->author }}</span>
    @endif
    <span class="blog-card__cta">Read insight <span aria-hidden="true">&rarr;</span></span>
  </div>
</a>
