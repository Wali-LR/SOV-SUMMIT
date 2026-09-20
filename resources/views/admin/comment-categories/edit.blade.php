<x-admin-layout title="Edit Category" subtitle="{{ $category->name }}">
    <x-slot name="actions">
        <a href="{{ route('admin.comment-categories.index') }}" class="text-sm text-slate-600 hover:text-slate-900">&larr; Back to list</a>
    </x-slot>

    <div class="max-w-2xl">
        @include('admin.comment-categories._form', [
            'category' => $category,
            'action' => route('admin.comment-categories.update', $category),
            'method' => 'PATCH',
        ])
    </div>
</x-admin-layout>
