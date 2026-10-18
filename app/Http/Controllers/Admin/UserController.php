<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $role = $request->query('role');
        $search = $request->query('search');

        $users = User::withCount('posts')
            ->when($role, fn ($q) => $q->where('role', $role))
            ->when($search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'role', 'search'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = [
            'editor' => 'Editor (Can manage posts, pages, categories, tags & media)',
            'author' => 'Author (Can publish and manage their own posts)',
            'admin' => 'Administrator (Full access to all settings and users)',
        ];

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'editor', 'author'])],
            'bio' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'bio' => $validated['bio'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' created successfully with role '{$user->role}'.");
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $roles = [
            'editor' => 'Editor (Can manage posts, pages, categories, tags & media)',
            'author' => 'Author (Can publish and manage their own posts)',
            'admin' => 'Administrator (Full access to all settings and users)',
        ];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'editor', 'author'])],
            'bio' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Prevent disabling or removing admin role from oneself
        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'You cannot change your own administrator role.');
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'bio' => $validated['bio'] ?? null,
            'is_active' => $user->id === auth()->id() ? true : $request->has('is_active'),
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $fallbackAdminId = auth()->id() ?? User::where('role', 'admin')->where('id', '!=', $user->id)->value('id') ?? 1;

        DB::transaction(function () use ($user, $fallbackAdminId) {
            // Reassign authored content to an administrator to guarantee zero content loss
            Post::where('user_id', $user->id)->update(['user_id' => $fallbackAdminId]);
            Page::where('user_id', $user->id)->update(['user_id' => $fallbackAdminId]);
            Media::where('user_id', $user->id)->update(['user_id' => $fallbackAdminId]);

            $user->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$name}' deleted successfully. All authored posts, pages, and media were safely preserved and reassigned.");
    }
}
