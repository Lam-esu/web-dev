<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; // Make sure the model is imported

class EmployeeController extends Controller
{
    // Shows the employee registration page
    public function create()
    {
        return view('employee_registration');
    }

    // Handles the form submission when registering an employee
    public function store(Request $request)
    {
        /** 
         * GROUPMATE TASK:
         * 1. Validate the $request data (first_name, last_name, employee_number, department_position, picture)
         * 2. Handle picture upload (if any) and save the file path
         * 3. Use Employee::create() to save to the database
         * 4. Redirect back with a success message (e.g., return back()->with('success', 'Employee registered!');)
         */

        // Temporary return so the button doesn't break while building
        return back()->with('status', 'Employee store logic goes here!');
    }
}