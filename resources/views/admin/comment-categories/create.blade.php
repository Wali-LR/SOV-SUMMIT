<x-admin-layout title="New Comment Category" subtitle="Add a category for organising comments">
    <x-slot name="actions">
        <a href="{{ route('admin.comment-categories.index') }}" class="text-sm text-slate-600 hover:text-slate-900">&larr; Back to list</a>
    </x-slot>

    <div class="max-w-2xl">
        @include('admin.comment-categories._form', [
            'category' => $category,
            'action' => route('admin.comment-categories.store'),
            'method' => 'POST',
        ])
    </div>
</x-admin-layout>
