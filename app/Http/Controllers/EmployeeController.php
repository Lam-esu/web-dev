<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    // Shows the employee registration page with an auto-generated ID
    public function create()
    {
        // Fetch the most recently added employee
        $lastEmployee = Employee::orderBy('id', 'desc')->first();

        // If the table is empty, start at 1. Otherwise, increment the last number.
        if (!$lastEmployee || !str_starts_with($lastEmployee->employee_number, 'bai-')) {
            $nextNumber = 'bai-00001';
        } else {
            // Strip 'bai-' from the string, convert to integer, add 1
            $lastSequence = (int) str_replace('bai-', '', $lastEmployee->employee_number);
            // Format back to bai-XXXXX
            $nextNumber = 'bai-' . str_pad($lastSequence + 1, 5, '0', STR_PAD_LEFT);
        }

        // Pass the generated number to the Blade view
        return view('employee_registration', compact('nextNumber'));
    }

    // Handles employee registration
    public function store(Request $request)
    {
        // Validate the submitted data
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'employee_number' => ['required', 'string', 'max:50', 'unique:employees,employee_number'],
            'department_position' => ['required', 'string', 'max:150'],
            'picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'employee_number.required' => 'Employee number is required.',
            'employee_number.unique' => 'This employee number is already registered.',
            'department_position.required' => 'Department/position is required.',
            'picture.image' => 'The uploaded file must be an image.',
            'picture.max' => 'The picture must not be larger than 2MB.',
        ]);

        // Handle picture upload if provided
        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')
                ->store('employees', 'public');
        }

        // Save employee to database
        Employee::create($validated);

        // Redirect back with success message
        return redirect()
            ->route('employee.registration')
            ->with('success', 'Employee registered successfully!');
    }
}
