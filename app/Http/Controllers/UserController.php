<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        // Menampilkan semua user kecuali ID yang sedang login
        $users = User::with('shift')
            ->where('id', '!=', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $shifts = Shift::where('is_active', true)->orderBy('start_time')->get();

        return view('admin.manage-users.index', compact('users', 'shifts'));
    }

    public function create()
    {
        $shifts = Shift::where('is_active', true)->orderBy('start_time')->get();
        return view('admin.manage-users.create', compact('shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => ['required', 'in:admin,kitchen'],
            'shift_id' => ['nullable', 'exists:shifts,id'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'shift_id' => $validated['shift_id'] ?? null,
        ]);

        return redirect()->route('admin.manage-users.index')
            ->with('success', "Akses operasional untuk {$validated['name']} telah diaktifkan.");
    }

    public function edit(User $user)
    {
        // Proteksi tambahan: Jangan izinkan edit diri sendiri lewat URL manual
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.manage-users.index')->with('error', 'Gunakan menu Profil untuk mengedit akun Anda.');
        }

        $shifts = Shift::where('is_active', true)->orderBy('start_time')->get();
        return view('admin.manage-users.edit', compact('user', 'shifts'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users,email,' . $user->id],
            'role'  => ['required', 'in:admin,kitchen'],
            'shift_id' => ['nullable', 'exists:shifts,id'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->shift_id = $validated['shift_id'] ?? null;

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.manage-users.index')
            ->with('success', "Data kredensial {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $userName = $user->name;
        $user->delete();

        return back()->with('success', "Akses sistem untuk {$userName} telah dicabut.");
    }
}
