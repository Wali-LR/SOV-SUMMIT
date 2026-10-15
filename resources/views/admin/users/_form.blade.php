@props(['user', 'roles', 'action', 'method' => 'POST'])

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="bg-white border border-slate-200 rounded-lg p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-800 mb-1.5">Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" required maxlength="255"
                       value="{{ old('name', $user->name) }}"
                       placeholder="e.g. Jane Doe"
                       class="w-full rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm">
                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-800 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input id="email" name="email" type="email" required maxlength="255"
                       value="{{ old('email', $user->email) }}"
                       placeholder="jane@example.com"
                       class="w-full rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm">
                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="role" class="block text-sm font-medium text-slate-800 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select id="role" name="role" required
                        class="w-full rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm">
                    @foreach ($roles as $r)
                        <option value="{{ $r->value }}" @selected(old('role', $user->role?->value) === $r->value)>{{ $r->label() }}</option>
                    @endforeach
                </select>
                @error('role')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <span class="block text-sm font-medium text-slate-800 mb-1.5">Email verification</span>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="email_verified" value="0">
                    <input type="checkbox" name="email_verified" value="1"
                           {{ old('email_verified', $user->email_verified_at ? 1 : 0) ? 'checked' : '' }}
                           class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                    <span class="text-sm text-slate-700">Mark email as verified</span>
                </label>
                <p class="mt-1 text-xs text-slate-500">Skip verification email and treat the account as verified.</p>
            </div>
        </div>

        <div class="pt-5 border-t border-slate-100">
            <h3 class="text-sm font-semibold text-slate-900">Password</h3>
            <p class="text-xs text-slate-500 mt-0.5 mb-4">
                @if ($user->exists)
                    Leave blank to keep the current password.
                @else
                    Minimum 8 characters.
                @endif
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-800 mb-1.5">
                        Password {{ $user->exists ? '' : '*' }}
                    </label>
                    <input id="password" name="password" type="password" autocomplete="new-password"
                           {{ $user->exists ? '' : 'required' }}
                           class="w-full rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm">
                    @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-800 mb-1.5">
                        Confirm password {{ $user->exists ? '' : '*' }}
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                           {{ $user->exists ? '' : 'required' }}
                           class="w-full rounded-md border border-slate-300 focus:border-slate-900 focus:ring-slate-900 text-sm">
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2">
        <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 text-sm font-medium text-slate-700 hover:text-slate-900">Cancel</a>
        <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
            {{ $method === 'POST' ? 'Create user' : 'Save changes' }}
        </button>
    </div>
</form>
