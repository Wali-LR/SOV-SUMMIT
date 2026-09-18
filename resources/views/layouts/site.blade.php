<!doctype html>
<html lang="en">
<head>
@include('partials.head-meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ @filemtime(public_path('assets/css/style.css')) }}" />
<link rel="stylesheet" href="{{ asset('assets/css/content-sections.css') }}?v={{ @filemtime(public_path('assets/css/content-sections.css')) }}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/css/glightbox.min.css">
@stack('head')
</head>
<body>
<a class="skip-link" href="#main" style="position:absolute;left:-9999px;">Skip to content</a>
@include('partials.header')
@yield('breadcrumb')
<main id="main">
@yield('content')
</main>
@include('partials.footer')
<script>document.getElementById('year').textContent = new Date().getFullYear();</script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/image-compress.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/js/glightbox.min.js" defer></script>
<script defer>
    window.addEventListener('DOMContentLoaded', function () {
        if (window.GLightbox) {
            window.__csLightbox = window.GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });
        }
    });
</script>
@stack('scripts')
</body>
</html>
