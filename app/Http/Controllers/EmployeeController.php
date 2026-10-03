<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    // Shows the employee registration page
    public function create()
    {
        return view('employee_registration');
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
