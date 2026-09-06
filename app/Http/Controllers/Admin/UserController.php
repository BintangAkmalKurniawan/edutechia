<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $role = (string) $request->query('role');
        $users = User::query()
            ->when($search, fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            }))
            ->when(in_array($role, [User::ROLE_ADMIN, User::ROLE_TEACHER, User::ROLE_STUDENT], true), fn ($query) => $query->where('role', $role))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'institution_id' => ['nullable', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            ...$validated,
            'role' => User::ROLE_TEACHER,
            'status' => 'active',
            'email_verified_at' => now(),
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index', ['role' => User::ROLE_TEACHER])
            ->with('success', 'Akun guru berhasil dibuat dan dapat langsung digunakan untuk masuk.');
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === $request->user()->id, 422, 'Anda tidak dapat menonaktifkan akun sendiri.');
        $user->update(['status' => $user->status === 'active' ? 'inactive' : 'active']);

        return back()->with('success', 'Status akun '.$user->name.' berhasil diperbarui.');
    }
}
