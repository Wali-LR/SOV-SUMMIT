<x-admin-layout title="Blog" subtitle="{{ $blogs->total() }} total">
    <x-slot name="actions">
        <a href="{{ route('admin.blogs.create') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            New post
        </a>
    </x-slot>

    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        @if ($blogs->isEmpty())
            <div class="p-10 text-center">
                <p class="text-slate-500 text-sm">No posts yet.</p>
                <a href="{{ route('admin.blogs.create') }}" class="inline-block mt-3 text-sm font-medium text-slate-900 underline">Write your first post</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-widest text-slate-500">
                            <th class="px-4 py-3 w-16">Cover</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Published</th>
                            <th class="px-4 py-3">Author</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($blogs as $blog)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    @if ($blog->cover_url)
                                        <img src="{{ $blog->cover_url }}" alt="" class="h-10 w-14 rounded object-cover border border-slate-200">
                                    @else
                                        <div class="h-10 w-14 rounded bg-slate-100 border border-slate-200"></div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900">{{ $blog->title }}</div>
                                    <div class="text-xs text-slate-500 font-mono">/blog/{{ $blog->slug }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-700 whitespace-nowrap">
                                    @if ($blog->category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">{{ $blog->category }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-700 whitespace-nowrap">
                                    {{ $blog->published_at?->format('d M Y') ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-slate-700">{{ $blog->author ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @if ($blog->is_published)
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
                                    <a href="{{ route('blog.show', $blog) }}" target="_blank" class="text-slate-500 hover:text-slate-900 text-xs mr-3">View</a>
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="text-slate-900 hover:underline text-xs font-medium mr-3">Edit</a>
                                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="inline" onsubmit="return confirm('Delete this post?');">
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
            @if ($blogs->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 bg-white">
                    {{ $blogs->links() }}
                </div>
            @endif
        @endif
    </div>
</x-admin-layout>
