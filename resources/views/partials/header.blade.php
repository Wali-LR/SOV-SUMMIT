@php
    $navItems = [
        ['label' => 'Home',     'href' => '/',        'match' => '/'],
        ['label' => 'About',    'href' => '/about',   'match' => 'about*'],
    ];

    $dynamicNavPages = \App\Models\Page::query()
        ->published()->inNav()
        ->get(['slug', 'title', 'nav_label']);

    $servicesLinks = [
        ['label' => 'Planning & Coordination',  'href' => '/services/planning-coordination'],
        ['label' => 'Conference Planning',      'href' => '/services/conference-planning'],
        ['label' => 'Delegation Management',    'href' => '/services/delegation-management'],
        ['label' => 'Events & Productions',     'href' => '/services/events-productions'],
        ['label' => 'Security Coordination',    'href' => '/services/security-coordination'],
        ['label' => 'Media Coverage',           'href' => '/services/media-coverage'],
        ['label' => 'Travel',                   'href' => '/services/travel-experiences'],
        ['label' => 'Training',                 'href' => '/management-training'],
    ];
    $tailItems = [
        ['label' => 'Solution', 'href' => '/products',                     'match' => 'products*'],
        ['label' => 'Training', 'href' => '/management-training',          'match' => 'management-training*'],
        ['label' => 'Events',   'href' => '/events',                       'match' => 'events*'],
        ['label' => 'Travel',   'href' => '/services/travel-experiences',  'match' => 'services/travel-experiences*'],
        ['label' => 'Insights', 'href' => '/insights',                     'match' => 'insights*'],
    ];
@endphp
<header class="site-header">
  <div class="bar">
    <a class="brand" href="/" aria-label="SOV SUMMIT — home">
      <img src="{{ asset('assets/img/logo-2.png') }}" alt="SOV SUMMIT" width="160" height="53" />
    </a>
    <nav class="primary-nav" aria-label="Primary">
      <ul>
        @foreach ($navItems as $item)
          @php $isActive = $item['match'] === '/' ? request()->path() === '/' : request()->is($item['match']); @endphp
          <li><a href="{{ $item['href'] }}" @if($isActive) aria-current="page" @endif>{{ $item['label'] }}</a></li>
        @endforeach
        <li class="has-dropdown">
          @php $servicesActive = request()->is('services*'); @endphp
          <a href="/services" @if($servicesActive) aria-current="page" @endif>Services</a>
          <ul class="dropdown">
            @foreach ($servicesLinks as $s)
              <li><a href="{{ $s['href'] }}">{{ $s['label'] }}</a></li>
            @endforeach
          </ul>
        </li>
        @foreach ($tailItems as $item)
          @php $isActive = request()->is($item['match']); @endphp
          <li><a href="{{ $item['href'] }}" @if($isActive) aria-current="page" @endif>{{ $item['label'] }}</a></li>
        @endforeach
        @foreach ($dynamicNavPages as $p)
          @php $isActive = request()->is($p->slug); @endphp
          <li><a href="/{{ $p->slug }}" @if($isActive) aria-current="page" @endif>{{ $p->nav_label ?: $p->title }}</a></li>
        @endforeach
      </ul>
      <a class="nav-cta" href="/contact">
        <span>Talk with us</span>
        <svg width="12" height="12" viewBox="0 0 14 14" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </nav>
    <button class="nav-toggle" aria-expanded="false" aria-label="Toggle menu">Menu</button>
  </div>
</header>
