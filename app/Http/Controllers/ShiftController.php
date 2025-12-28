<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
     * Display a listing of shifts
     */
    public function index()
    {
        $shifts = Shift::with('users')->orderBy('start_time')->get();
        return view('admin.shifts.index', compact('shifts'));
    }

    /**
     * Store a newly created shift
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:shifts,name',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        Shift::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil dibuat.');
    }

    /**
     * Update the specified shift
     */
    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:shifts,name,' . $id,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        $shift->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil diperbarui.');
    }

    /**
     * Assign user to shift
     */
    public function assignUser(Request $request, $shiftId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Only allow admin, kitchen, and super_admin roles (check spatie roles)
        $allowedRoles = ['admin', 'kitchen', 'super_admin'];
        $userRole = $user->getRoleNames()->first();
        if (!in_array($userRole, $allowedRoles)) {
            return back()->with('error', 'Hanya user dengan role admin, kitchen, atau super_admin yang bisa di-assign ke shift.');
        }

        $user->update([
            'shift_id' => $shiftId
        ]);

        return back()->with('success', 'User berhasil di-assign ke shift.');
    }

    /**
     * Remove user from shift
     */
    public function removeUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['shift_id' => null]);

        return back()->with('success', 'User berhasil dihapus dari shift.');
    }

}
