<x-admin-layout title="Comment Categories" subtitle="{{ $categories->total() }} total">
    <x-slot name="actions">
        <a href="{{ route('admin.comment-categories.create') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            New category
        </a>
    </x-slot>

    <form method="GET" class="mb-4 flex items-center gap-2">
        <div class="relative flex-1 max-w-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
            <input type="search" name="q" value="{{ $search }}" placeholder="Search by name or slug"
                   class="w-full pl-9 pr-3 py-2 rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm">
        </div>
        @if ($search !== '')
            <a href="{{ route('admin.comment-categories.index') }}" class="text-xs text-slate-600 hover:text-slate-900">Clear</a>
        @endif
    </form>

    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        @if ($categories->isEmpty())
            <div class="p-10 text-center">
                <p class="text-slate-500 text-sm">
                    {{ $search !== '' ? 'No categories match your search.' : 'No comment categories yet.' }}
                </p>
                @if ($search === '')
                    <a href="{{ route('admin.comment-categories.create') }}" class="inline-block mt-3 text-sm font-medium text-slate-900 underline">Create your first category</a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-widest text-slate-500">
                            <th class="px-4 py-3 w-16">#</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3 w-24">Order</th>
                            <th class="px-4 py-3 w-28">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($categories as $category)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-500 tabular-nums">{{ $category->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900">{{ $category->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $category->slug }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <span class="line-clamp-2 max-w-md block">{{ $category->description ?: '—' }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-700 tabular-nums">{{ $category->sort_order }}</td>
                                <td class="px-4 py-3">
                                    @if ($category->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.comment-categories.edit', $category) }}" class="text-slate-900 hover:underline text-xs font-medium mr-3">Edit</a>
                                    <form action="{{ route('admin.comment-categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete “{{ $category->name }}”?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($categories->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 bg-white">
                    {{ $categories->links() }}
                </div>
            @endif
        @endif
    </div>
</x-admin-layout>
