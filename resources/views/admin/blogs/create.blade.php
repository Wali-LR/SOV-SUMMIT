<x-admin-layout title="Create Post" subtitle="Add a new blog post">
    <x-slot name="actions">
        <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Back to blog
        </a>
    </x-slot>

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.blogs._form')
    </form>
</x-admin-layout>
