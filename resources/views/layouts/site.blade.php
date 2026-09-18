<!doctype html>
<html lang="en">
<head>
@include('partials.head-meta')
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
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
@stack('scripts')
</body>
</html>
