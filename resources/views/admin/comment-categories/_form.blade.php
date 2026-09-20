@props(['category', 'action', 'method' => 'POST'])

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="bg-white border border-slate-200 rounded-lg p-6 space-y-5">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-800 mb-1.5">Name <span class="text-red-500">*</span></label>
            <input id="name" name="name" type="text" required maxlength="120"
                   value="{{ old('name', $category->name) }}"
                   placeholder="e.g. Feedback"
                   class="w-full rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="slug" class="block text-sm font-medium text-slate-800 mb-1.5">Slug</label>
            <input id="slug" name="slug" type="text" maxlength="140"
                   value="{{ old('slug', $category->slug) }}"
                   placeholder="Auto-generated from name if left blank"
                   class="w-full rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm font-mono">
            <p class="mt-1 text-xs text-slate-500">Letters, numbers, hyphens and underscores only.</p>
            @error('slug')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-800 mb-1.5">Description</label>
            <textarea id="description" name="description" rows="4" maxlength="1000"
                      placeholder="Optional short note about how this category is used"
                      class="w-full rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm">{{ old('description', $category->description) }}</textarea>
            @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="sort_order" class="block text-sm font-medium text-slate-800 mb-1.5">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                       value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                       class="w-full rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm">
                <p class="mt-1 text-xs text-slate-500">Lower numbers appear first.</p>
                @error('sort_order')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <span class="block text-sm font-medium text-slate-800 mb-1.5">Status</span>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                           class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                    <span class="text-sm text-slate-700">Active</span>
                </label>
                <p class="mt-1 text-xs text-slate-500">Inactive categories are hidden from selectors.</p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2">
        <a href="{{ route('admin.comment-categories.index') }}" class="px-3.5 py-2 text-sm font-medium text-slate-700 hover:text-slate-900">Cancel</a>
        <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
            {{ $method === 'POST' ? 'Create category' : 'Save changes' }}
        </button>
    </div>
</form>
