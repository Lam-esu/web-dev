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
            // 1. Check if they already successfully completed a shift today
            $completedShift = $employee->attendances()
                ->whereDate('attendance_date', $today)
                ->whereNotNull('time_out')
                ->first();

            if ($completedShift) {
                return back()->with('error', 'You have already clocked out today at ' . \Carbon\Carbon::parse($completedShift->time_out)->format('h:i A') . '.');
            }

            // 2. Look for the most recent shift that hasn't been clocked out yet (handles night shifts!)
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

    public function log(Request $request)
    {
        $query = Attendance::with('employee');

        // 1. Search (Name or ID)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%");
            });
        }

        // 2. Date Range Filters (Matching start_date and end_date from the Blade view)
        if ($request->filled('start_date')) {
            $query->whereDate('attendance_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('attendance_date', '<=', $request->input('end_date'));
        }

        // 3. Status Filter (Matching 'completed' and 'missing_out' from the Blade view)
        if ($request->filled('status')) {
            if ($request->input('status') === 'completed') {
                $query->whereNotNull('time_out');
            } elseif ($request->input('status') === 'missing_out') {
                $query->whereNull('time_out');
            }
        }

        // 4. Department Filter
        if ($request->filled('department')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_position', $request->input('department'));
            });
        }

        // Paginate results and preserve all filter URL parameters
        $attendances = $query->latest('attendance_date')->paginate(15)->withQueryString();

        // Fetch distinct departments for the dropdown
        $departments = Employee::select('department_position')
            ->distinct()
            ->whereNotNull('department_position')
            ->pluck('department_position');

        return view('attendance_log', compact('attendances', 'departments'));
    }
}