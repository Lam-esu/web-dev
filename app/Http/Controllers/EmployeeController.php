<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;

class EmployeeController extends Controller
{
    // ----------------------------------------------------
    // SUPER ADMIN ONLY: User Account Management
    // ----------------------------------------------------
    public function users()
    {
        $pendingUsers = User::where('status', 'pending')->get();
        $approvedUsers = User::where('status', 'approved')->get();

        return view('users', compact('pendingUsers', 'approvedUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'string', 'max:150'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        $user->update([
            'status' => 'approved',
            'role' => $validated['role']
        ]);

        return redirect()->back()->with('success', "System account for {$user->first_name} has been approved!");
    }

    public function reject(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->delete();

        return redirect()->back()->with('success', 'Pending account has been rejected.');
    }

    public function updateRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', "Role successfully updated.");
    }

    // ----------------------------------------------------
    // ALL ADMINS: Manual Employee Registration
    // ----------------------------------------------------
    public function registration()
    {
        $lastEmployee = Employee::orderBy('id', 'desc')->first();
        if (!$lastEmployee || !str_starts_with($lastEmployee->employee_number, 'bai-')) {
            $nextNumber = 'bai-00001';
        } else {
            $lastSequence = (int) str_replace('bai-', '', $lastEmployee->employee_number);
            $nextNumber = 'bai-' . str_pad($lastSequence + 1, 5, '0', STR_PAD_LEFT);
        }

        return view('employee_registration', compact('nextNumber'));
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'employee_number' => ['required', 'string', 'max:50', 'unique:employees,employee_number'],
            'department_position' => ['required', 'string', 'max:150'],
            'picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('employees', 'public');
        }

        Employee::create($validated);

        return redirect()->back()->with('success', 'Employee registered manually!');
    }
}