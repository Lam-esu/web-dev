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
        // Fetch unique departments for the dropdown filter
        $departments = Employee::select('department_position')
            ->whereNotNull('department_position')
            ->distinct()
            ->pluck('department_position');

        $query = Attendance::with('employee')
            ->orderByDesc('attendance_date')
            ->orderByDesc('time_in');

        // Role restriction: employees only see their own logs
        // Employees only see their own attendance logs.
        if (!Auth::user()->isAdmin()) {
            $query->whereHas('employee', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        // 1. Search Filter (ID or Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // 2. Date Range Filter
        if ($request->filled('start_date')) {
            $query->whereDate('attendance_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('attendance_date', '<=', $request->end_date);
        }

        // 3. Department Filter
        if ($request->filled('department')) {
            $department = $request->department;
            $query->whereHas('employee', function ($q) use ($department) {
                $q->where('department_position', $department);
            });
        }

        // 4. Status Filter (Missing Clock Out vs Completed)
        if ($request->filled('status')) {
            if ($request->status === 'missing_out') {
                $query->whereNull('time_out');
            } elseif ($request->status === 'completed') {
                $query->whereNotNull('time_out');
            }
        }

        // ->withQueryString() ensures pagination links remember the active filters
        $attendances = $query->paginate(15)->withQueryString();

        return view('attendance_log', compact('attendances', 'departments'));
    }
}