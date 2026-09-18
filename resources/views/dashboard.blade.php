<x-admin-layout title="Dashboard" subtitle="Welcome back, {{ Auth::user()->name }}.">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('admin.events.index') }}" class="group bg-white border border-slate-200 rounded-lg p-5 hover:border-slate-900 transition">
            <div class="text-[11px] font-semibold uppercase tracking-widest text-slate-500">Content</div>
            <div class="mt-1.5 text-base font-semibold text-slate-900 group-hover:text-slate-950">Manage Events →</div>
            <p class="mt-1 text-sm text-slate-600">Create, edit, and publish events shown on the homepage and events pages.</p>
        </a>

        <a href="{{ url('/') }}" target="_blank" class="group bg-white border border-slate-200 rounded-lg p-5 hover:border-slate-900 transition">
            <div class="text-[11px] font-semibold uppercase tracking-widest text-slate-500">Preview</div>
            <div class="mt-1.5 text-base font-semibold text-slate-900 group-hover:text-slate-950">Visit Public Site ↗</div>
            <p class="mt-1 text-sm text-slate-600">Open the live marketing site in a new tab.</p>
        </a>

        <a href="{{ route('profile.edit') }}" class="group bg-white border border-slate-200 rounded-lg p-5 hover:border-slate-900 transition">
            <div class="text-[11px] font-semibold uppercase tracking-widest text-slate-500">Account</div>
            <div class="mt-1.5 text-base font-semibold text-slate-900 group-hover:text-slate-950">Profile Settings →</div>
            <p class="mt-1 text-sm text-slate-600">Update your name, email, and password.</p>
        </a>
    </div>
</x-admin-layout>
