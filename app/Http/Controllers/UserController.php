<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of all staff users (excludes students).
     */
    public function index(Request $request)
    {
        $query = User::with('roles')
            ->whereDoesntHave('roles', fn($q) => $q->where('name', 'Student'))
            ->latest();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(fn($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            );
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        $users = $query->paginate(15)->withQueryString();
        $roles  = Role::whereNotIn('name', ['Student'])->get();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new staff user.
     */
    public function create()
    {
        $roles = Role::whereNotIn('name', ['Student'])->get();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created staff user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|exists:roles,name',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')
            ->with('success', "User '{$user->name}' created successfully with the {$validated['role']} role.");
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Prevent editing student accounts from this UI
        if ($user->hasRole('Student')) {
            return redirect()->route('users.index')
                ->with('error', 'Student accounts cannot be managed here. Use the Students module.');
        }

        $roles = Role::whereNotIn('name', ['Student'])->get();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->hasRole('Student')) {
            return redirect()->route('users.index')
                ->with('error', 'Student accounts cannot be managed here.');
        }

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|exists:roles,name',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Sync role (remove all current roles, assign the new one)
        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * Toggle the active / inactive status of a user account.
     * Uses the 'status' column: 'active' or 'inactive'.
     */
    public function toggleStatus(User $user)
    {
        if ($user->hasRole('Student')) {
            return redirect()->route('users.index')
                ->with('error', 'Student accounts cannot be managed here.');
        }

        // Prevent locking yourself out
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot deactivate your own account.');
        }

        if ($user->status === 'active') {
            $user->update(['status' => 'inactive']);
            $message = "Account for '{$user->name}' has been deactivated.";
        } else {
            $user->update(['status' => 'active']);
            $message = "Account for '{$user->name}' has been activated.";
        }

        return redirect()->route('users.index')->with('success', $message);
    }

    /**
     * Reset a user's password to a new admin-supplied value.
     */
    public function resetPassword(Request $request, User $user)
    {
        if ($user->hasRole('Student')) {
            return redirect()->route('users.index')
                ->with('error', 'Student passwords must be reset from the Students module.');
        }

        $validated = $request->validate([
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update(['password' => Hash::make($validated['new_password'])]);

        return redirect()->route('users.index')
            ->with('success', "Password for '{$user->name}' has been reset successfully.");
    }
}
