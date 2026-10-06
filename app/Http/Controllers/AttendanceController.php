<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        // Removed whereDate(today) so it accurately shows cross-midnight shift updates
        $recentLogs = Attendance::with('employee')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('recentLogs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => ['required', 'string', 'exists:employees,employee_number'],
            'action' => ['required', 'in:clock_in,clock_out'],
        ], [
            'employee_number.exists' => 'No employee was found with that number.',
        ]);

        $employee = Employee::where('employee_number', $validated['employee_number'])->firstOrFail();
        $now = now('Asia/Manila');
        $today = $now->toDateString();
        
        if ($validated['action'] === 'clock_in') {
            // For clock-ins, we still strictly check if they already clocked in TODAY
            $attendance = $employee->attendances()->whereDate('attendance_date', $today)->first();

            if ($attendance && $attendance->time_in) {
                return back()->with('error', 'Attendance already recorded today at ' . \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') . '.');
            }
            
            if (!$attendance) {
                $employee->attendances()->create([
                    'attendance_date' => $today,
                    'time_in' => $now->format('H:i:s'),
                ]);
            } else {
                $attendance->update(['time_in' => $now->format('H:i:s')]);
            }
        } 
        else { // clock_out
            // Look for the most recent shift that hasn't been clocked out yet (handles night shifts!)
            $attendance = $employee->attendances()
                ->whereNotNull('time_in')
                ->whereNull('time_out')
                ->orderBy('attendance_date', 'desc')
                ->first();

            if (!$attendance) {
                return back()->with('error', 'You must clock in first before clocking out.');
            }
            
            $attendance->update(['time_out' => $now->format('H:i:s')]);
        }

        return back()->with([
            'attendance_success' => true,
            'employee' => $employee,
            'action_time' => $now->format('F d, Y h:i:s A'),
            'action_type' => $validated['action'] === 'clock_in' ? 'CLOCKED IN' : 'CLOCKED OUT'
        ]);
    }

    public function log()
    {
        $query = Attendance::with('employee')
            ->orderByDesc('attendance_date')
            ->orderByDesc('time_in');

        if (!Auth::user()->isAdmin()) {
            $query->whereHas('employee', function ($q) {
                $q->where('employee_number', Auth::user()->username);
            });
        }

        $attendances = $query->paginate(15)->withQueryString();

        return view('attendance_log', compact('attendances'));
    }
}