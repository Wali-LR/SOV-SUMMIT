<x-admin-layout title="Pages" subtitle="{{ $pages->total() }} total">
    <x-slot name="actions">
        <a href="{{ route('admin.pages.create') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            New page
        </a>
    </x-slot>

    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        @if ($pages->isEmpty())
            <div class="p-10 text-center">
                <p class="text-slate-500 text-sm">No pages yet.</p>
                <a href="{{ route('admin.pages.create') }}" class="inline-block mt-3 text-sm font-medium text-slate-900 underline">Create your first page</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-widest text-slate-500">
                            <th class="px-4 py-3 w-16">Hero</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">URL</th>
                            <th class="px-4 py-3">In nav</th>
                            <th class="px-4 py-3 w-20 text-center">Order</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($pages as $page)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    @if ($page->hero_url)
                                        <img src="{{ $page->hero_url }}" alt="" class="h-10 w-14 rounded object-cover border border-slate-200">
                                    @else
                                        <div class="h-10 w-14 rounded bg-slate-100 border border-slate-200"></div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900">{{ $page->title }}</div>
                                    @if ($page->eyebrow)
                                        <div class="text-xs text-slate-500">{{ $page->eyebrow }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-700 whitespace-nowrap font-mono text-xs">/{{ $page->slug }}</td>
                                <td class="px-4 py-3">
                                    @if ($page->show_in_nav)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-sky-50 text-sky-700 border border-sky-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                            Nav
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center text-slate-700">{{ $page->nav_order }}</td>
                                <td class="px-4 py-3">
                                    @if ($page->is_published)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <a href="{{ $page->publicUrl() }}" target="_blank" class="text-slate-500 hover:text-slate-900 text-xs mr-3">View</a>
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="text-slate-900 hover:underline text-xs font-medium mr-3">Edit</a>
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('Delete this page?');">
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
            @if ($pages->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 bg-white">
                    {{ $pages->links() }}
                </div>
            @endif
        @endif
    </div>
</x-admin-layout>
