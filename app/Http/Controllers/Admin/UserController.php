<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $role = $request->query('role');

        $users = User::query()
            ->when($search !== '', fn ($q) => $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->when($role !== null && $role !== '', fn ($q) => $q->where('role', $role))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'roles' => UserRole::cases(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'user' => new User(['role' => UserRole::Customer]),
            'roles' => UserRole::cases(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $emailVerified = (bool) ($data['email_verified'] ?? false);
        unset($data['email_verified']);

        $data['email_verified_at'] = $emailVerified ? now() : null;

        $user = User::create($data);

        return redirect()->route('admin.users.index')
            ->with('status', "User “{$user->name}” created.");
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => UserRole::cases(),
        ]);
    }

    public function update(StoreUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $emailVerified = (bool) ($data['email_verified'] ?? false);
        unset($data['email_verified']);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $data['email_verified_at'] = $emailVerified
            ? ($user->email_verified_at ?? now())
            : null;

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('status', "User “{$user->name}” updated.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()?->id === $user->id) {
            return redirect()->route('admin.users.index')
                ->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', "User “{$name}” deleted.");
    }
}
