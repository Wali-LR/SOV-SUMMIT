<x-admin-layout title="Users" subtitle="{{ $users->total() }} total">
    <x-slot name="actions">
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            New user
        </a>
    </x-slot>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="GET" class="mb-4 flex flex-wrap items-center gap-2">
        <div class="relative flex-1 max-w-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
            <input type="search" name="q" value="{{ $search }}" placeholder="Search by name or email"
                   class="w-full pl-9 pr-3 py-2 rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm">
        </div>
        <select name="role"
                class="py-2 pl-3 pr-8 rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm">
            <option value="">All roles</option>
            @foreach ($roles as $r)
                <option value="{{ $r->value }}" @selected($role === $r->value)>{{ $r->label() }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-sm font-medium rounded-md">Filter</button>
        @if ($search !== '' || ($role !== null && $role !== ''))
            <a href="{{ route('admin.users.index') }}" class="text-xs text-slate-600 hover:text-slate-900">Clear</a>
        @endif
    </form>

    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        @if ($users->isEmpty())
            <div class="p-10 text-center">
                <p class="text-slate-500 text-sm">
                    {{ ($search !== '' || ($role !== null && $role !== '')) ? 'No users match your filters.' : 'No users yet.' }}
                </p>
                @if ($search === '' && ($role === null || $role === ''))
                    <a href="{{ route('admin.users.create') }}" class="inline-block mt-3 text-sm font-medium text-slate-900 underline">Create your first user</a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-widest text-slate-500">
                            <th class="px-4 py-3 w-16">#</th>
                            <th class="px-4 py-3">User</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Verified</th>
                            <th class="px-4 py-3">Joined</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($users as $u)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-500 tabular-nums">{{ $u->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                                            {{ strtoupper(mb_substr($u->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-medium text-slate-900 truncate">{{ $u->name }}</div>
                                            <div class="text-xs text-slate-500 truncate">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $roleColors = [
                                            'admin'    => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                            'vendor'   => 'bg-amber-50  text-amber-700  border-amber-100',
                                            'customer' => 'bg-slate-100 text-slate-700  border-slate-200',
                                        ];
                                        $roleClass = $roleColors[$u->role?->value] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border {{ $roleClass }}">
                                        {{ $u->role?->label() ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($u->email_verified_at)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-700 whitespace-nowrap">
                                    {{ $u->created_at?->format('d M Y') ?? '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.users.edit', $u) }}" class="text-slate-900 hover:underline text-xs font-medium mr-3">Edit</a>
                                    @if (auth()->id() !== $u->id)
                                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Delete “{{ $u->name }}”?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">You</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 bg-white">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>
</x-admin-layout>
