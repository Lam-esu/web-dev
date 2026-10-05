<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Employee;

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
        /** 
         * GROUPMATE TASK:
         * 1. Validate that 'employee_number' was submitted.
         * 2. Query the Employee model to find the user by that number.
         * 3. If found, insert a new Attendance record with today's date and current time.
         * 4. Watch out for duplicates! Check if they already timed in today.
         * 5. Redirect back with success or error message.
         */

        // Temporary return 
        return back()->with('status', 'Attendance recording logic goes here!');
    }

    // Shows the data table of all attendance records
    public function log()
    {
        if (Auth::user()->isAdmin()) {
            // Admin sees all records
            // $attendances = Attendance::with('employee')->latest()->get();
        } else {
            // Student sees only their own records 
            // Note: You will need to match the 'employee_number' to the student's logged-in account
            // $attendances = Attendance::where('employee_number', Auth::user()->username)->latest()->get();
        }

        return view('attendance_log'); // Pass the variable to the view via compact('attendances')
    }
}