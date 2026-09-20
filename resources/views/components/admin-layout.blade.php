@props([
    'title' => 'Admin',
    'subtitle' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} · SOV SUMMIT Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    </style>
    @stack('head')
</head>
<body class="antialiased bg-slate-50 text-slate-800">
    <div class="min-h-screen flex" x-data="{ open: false }">
        <button type="button" class="lg:hidden fixed top-3 left-3 z-40 inline-flex items-center justify-center w-10 h-10 rounded-md bg-white border border-slate-200 shadow-sm text-slate-700" @click="open = !open" aria-label="Toggle menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm1 4a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd"/></svg>
        </button>

        <aside
            class="fixed lg:sticky top-0 left-0 z-30 h-screen w-64 bg-white border-r border-slate-200 flex flex-col transform transition-transform duration-200"
            :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="px-5 pt-6 pb-5 border-b border-slate-100">
                <div class="text-[13px] tracking-[0.28em] font-bold text-slate-900">SOV SUMMIT</div>
                <div class="text-xs text-slate-500 mt-0.5">Admin</div>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4">
                <div class="px-2 pt-1 pb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Overview</div>
                @php $isDash = request()->routeIs('dashboard'); @endphp
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm font-medium mb-1 {{ $isDash ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    Dashboard
                </a>

                <div class="px-2 pt-4 pb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Content</div>
                @php $isEvents = request()->routeIs('admin.events.*'); @endphp
                <a href="{{ route('admin.events.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm font-medium mb-1 {{ $isEvents ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                    Events
                </a>

                <div class="px-2 pt-6 pb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Others</div>
                @php $isCommentCats = request()->routeIs('admin.comment-categories.*'); @endphp
                <a href="{{ route('admin.comment-categories.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm font-medium mb-1 {{ $isCommentCats ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"/></svg>
                    Comment Categories
                </a>

                <div class="px-2 pt-6 pb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">External</div>
                <a href="{{ url('/') }}" target="_blank"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm font-medium mb-1 text-slate-700 hover:bg-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/></svg>
                    Public site
                </a>
            </nav>

            <div class="p-4 border-t border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                        {{ strtoupper(mb_substr(Auth::user()->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('profile.edit') }}" class="block text-sm font-medium text-slate-900 truncate hover:underline">{{ Auth::user()->name ?? 'Guest' }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs text-slate-500 hover:text-slate-900">Log out</button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 lg:pl-0">
            <header class="bg-white border-b border-slate-200 sticky top-0 z-20">
                <div class="px-6 py-5 flex items-center justify-between gap-4">
                    <div class="pl-12 lg:pl-0">
                        <h1 class="text-lg font-semibold text-slate-900 leading-tight">{{ $title }}</h1>
                        @if ($subtitle)
                            <p class="text-sm text-slate-500 mt-0.5">{{ $subtitle }}</p>
                        @endif
                    </div>
                    @isset($actions)
                        <div class="flex items-center gap-2 shrink-0">{{ $actions }}</div>
                    @endisset
                </div>
            </header>

            @if (session('status'))
                <div class="mx-6 mt-4 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    <script src="{{ asset('assets/js/image-compress.js') }}"></script>
    @stack('scripts')
</body>
</html>
