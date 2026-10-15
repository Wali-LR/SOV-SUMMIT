@php
    /** @var \App\Models\Event $event */
    $cover = $event->cover_url;
@endphp
<a class="event-card" href="{{ route('events.show', $event) }}" aria-label="{{ $event->title }}">
  <div class="event-card__media {{ $cover ? '' : 'event-card__media--empty' }}">
    @if ($cover)
      <img src="{{ $cover }}" alt="" aria-hidden="true" loading="lazy" width="800" height="600">
      <div class="event-card__overlay" aria-hidden="true"></div>
    @else
      <span class="event-card__placeholder" aria-hidden="true">
        <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
          <rect x="7" y="11" width="34" height="26" rx="2"/>
          <circle cx="17" cy="21" r="2.5"/>
          <path d="M7 32l9-9 8 8 6-5 11 10"/>
        </svg>
      </span>
    @endif
  </div>
  <div class="event-card__body">
    @if ($event->event_date)
      <span class="event-card__date">{{ $event->event_date->format('d M Y') }}</span>
    @endif
    <h3 class="event-card__title">{{ $event->title }}</h3>
    @if ($event->location)
      <span class="event-card__meta">{{ $event->location }}</span>
    @endif
    @if ($event->summary)
      <p class="event-card__summary">{{ $event->summary }}</p>
    @endif
    <span class="event-card__cta">View programme <span aria-hidden="true">&rarr;</span></span>
  </div>
</a>
