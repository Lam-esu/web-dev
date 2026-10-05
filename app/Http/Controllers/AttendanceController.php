<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Shows the main dashboard time-in form
    public function index()
    {
        return view('dashboard');
    }

    // Handles the form submission when an employee presses enter on the dashboard
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => ['required', 'string', 'exists:employees,employee_number'],
            'action' => ['required', 'in:clock_in,clock_out'],
        ], [
            'employee_number.exists' => 'No employee was found with that number.',
        ]);

        if ($validated['action'] === 'clock_out') {
            return back()
                ->withErrors(['action' => 'Clock-out is not supported yet.'])
                ->withInput();
        }

        $employee = Employee::where('employee_number', $validated['employee_number'])->firstOrFail();

        if ($employee->attendances()->whereDate('attendance_date', today())->exists()) {
            return back()
                ->withErrors(['employee_number' => 'This employee has already clocked in today.'])
                ->withInput();
        }

        $employee->attendances()->create([
            'attendance_date' => today(),
            'attendance_time' => now()->format('H:i:s'),
        ]);

        return back()->with('status', 'Attendance recorded successfully.');
    }

    // Shows the data table of all attendance records
    public function log()
    {
        $query = Attendance::with('employee')
            ->orderByDesc('attendance_date')
            ->orderByDesc('attendance_time');

        if (!Auth::user()->isAdmin()) {
            // Students only see their own records
            $query->whereHas('employee', function ($q) {
                $q->where('employee_number', Auth::user()->username);
            });
        }

        $attendances = $query->get();

        return view('attendance_log', compact('attendances'));
    }
}