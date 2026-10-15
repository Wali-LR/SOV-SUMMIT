@php
    $pageTitle = trim($__env->yieldContent('title')) ?: 'SOV SUMMIT | International Event Organisation & Coordination';
    $pageDescription = trim($__env->yieldContent('meta_description')) ?: 'SOV SUMMIT coordinates international events, conferences, delegations, management training, travel experiences, security support, and media coverage for organisations, corporations, institutions, and private clients.';
    $canonicalUrl = trim($__env->yieldContent('canonical')) ?: url()->current();
    $ogImage = trim($__env->yieldContent('og_image')) ?: 'https://sov-summit.com/assets/img/logo.webp';
@endphp
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}" />
<link rel="canonical" href="{{ $canonicalUrl }}" />
<link rel="icon" type="image/png" href="{{ asset('assets/img/logo-2.png') }}" />
<link rel="apple-touch-icon" href="{{ asset('assets/img/logo-2.png') }}" />
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ $pageTitle }}" />
<meta property="og:description" content="{{ $pageDescription }}" />
<meta property="og:url" content="{{ $canonicalUrl }}" />
<meta property="og:image" content="{{ $ogImage }}" />
<meta name="twitter:card" content="summary_large_image" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />
