@extends('layouts.app')

@section('title', 'Register Employee - Baittendance')

@section('content')
<div class="attendance-wrapper">

    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="nav-brand">
            Baittendance Monitoring System
        </div>

        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Attendance</a>

            <a href="{{ route('employee.registration') }}">
                Employee Registration
            </a>

            <a href="{{ route('attendance.log') }}">
                Attendance Log
            </a>

            <!-- Logout Form -->
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf

                <button type="submit" class="btn-link">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">

        <div class="attendance-card registration-card">

            <h2>Register New Employee</h2>

            <p>
                Enter the employee details below.
            </p>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- General Validation Error -->
            @if($errors->any())
                <div class="alert alert-error">
                    <strong>Please correct the following errors:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Registration Form -->
            <form
                method="POST"
                action="{{ route('employee.store') }}"
                enctype="multipart/form-data"
            >
                @csrf

                <!-- First Name -->
                <div class="form-group">
                    <label for="first_name">
                        First Name
                    </label>

                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        class="form-control @error('first_name') is-invalid @enderror"
                        value="{{ old('first_name') }}"
                        placeholder="Enter first name"
                        maxlength="80"
                        required
                    >

                    @error('first_name')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Last Name -->
                <div class="form-group">
                    <label for="last_name">
                        Last Name
                    </label>

                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        class="form-control @error('last_name') is-invalid @enderror"
                        value="{{ old('last_name') }}"
                        placeholder="Enter last name"
                        maxlength="80"
                        required
                    >

                    @error('last_name')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Employee Number -->
                <div class="form-group">
                    <label for="employee_number">
                        Employee Number
                    </label>

                    <input
                        type="text"
                        id="employee_number"
                        name="employee_number"
                        class="form-control @error('employee_number') is-invalid @enderror"
                        value="{{ old('employee_number') }}"
                        placeholder="Enter employee number"
                        maxlength="50"
                        required
                    >

                    @error('employee_number')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Department / Position -->
                <div class="form-group">
                    <label for="department_position">
                        Department / Position
                    </label>

                    <input
                        type="text"
                        id="department_position"
                        name="department_position"
                        class="form-control @error('department_position') is-invalid @enderror"
                        value="{{ old('department_position') }}"
                        placeholder="e.g. IT Department / Software Developer"
                        maxlength="150"
                        required
                    >

                    @error('department_position')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Picture -->
                <div class="form-group">
                    <label for="picture">
                        Employee Picture
                    </label>

                    <input
                        type="file"
                        id="picture"
                        name="picture"
                        class="form-control file-input @error('picture') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small class="file-help">
                        Optional. JPG, JPEG, PNG, or WEBP. Maximum size: 2MB.
                    </small>

                    @error('picture')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="btn btn-primary registration-submit"
                >
                    Save Employee
                </button>

            </form>

        </div>

    </div>
</div>
@endsection
