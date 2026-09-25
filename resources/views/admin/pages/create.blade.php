<x-admin-layout title="Create Page" subtitle="Add a new page">
    <x-slot name="actions">
        <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Back to pages
        </a>
    </x-slot>

    <div class="mb-4 rounded-md bg-sky-50 border border-sky-200 text-sky-900 px-4 py-3 text-sm">
        Save the basics first — after that you can add and reorder sections on the edit screen.
    </div>

    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.pages._form', ['page' => $page])
    </form>
</x-admin-layout>
