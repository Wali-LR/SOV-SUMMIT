<x-admin-layout title="Edit Page" subtitle="{{ $page->title }}">
    <x-slot name="actions">
        <a href="{{ $page->publicUrl() }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
            Preview
        </a>
        <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-slate-900">
            Back
        </a>
    </x-slot>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.pages._form', ['page' => $page])
    </form>

    <section class="mt-8 bg-white border border-slate-200 rounded-lg">
        <header class="px-5 py-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-900">Page sections</h3>
            <p class="text-xs text-slate-500 mt-0.5">Add, reorder, and edit sections that appear under the hero.</p>
        </header>
        <div class="p-5">
            <x-content-sections :model="$page" />
        </div>
    </section>
</x-admin-layout>
