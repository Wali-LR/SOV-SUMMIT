<x-admin-layout title="Edit User" subtitle="{{ $user->name }}">
    <x-slot name="actions">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-slate-600 hover:text-slate-900">&larr; Back to list</a>
    </x-slot>

    <div class="max-w-3xl">
        @include('admin.users._form', [
            'user' => $user,
            'roles' => $roles,
            'action' => route('admin.users.update', $user),
            'method' => 'PATCH',
        ])
    </div>
</x-admin-layout>
