@extends('layouts.app')
@section('title', 'Register Employees - Baittendance')

@section('content')
<div class="attendance-wrapper">
    @include('partials.nav')

    <div class="page page-narrow">
        <div class="page-header">
            <h1>Register Employees</h1>
            <p>Register an employee directly into the system.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-body">
            <form method="POST" action="{{ route('employee.storeManual') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="employee_number">Employee Number (Auto-generated)</label>
                    <input
                        type="text"
                        id="employee_number"
                        name="employee_number"
                        class="form-control"
                        value="{{ $nextNumber }}"
                        readonly
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="department_position">Department / Position</label>
                    <select id="department_position" name="department_position" class="form-control" required>
                        <option value="">Select Department/Position</option>
                        <option value="HR Officer">HR Officer</option>
                        <option value="Finance Staff">Finance Staff</option>
                        <option value="Marketing Employee">Marketing Employee</option>
                        <option value="IT Staff">IT Staff</option>
                        <option value="employee">Standard Employee</option>
                    </select>
                </div>

                {{-- Restored Profile Picture Field --}}
                <div class="form-group">
                    <label for="picture">Profile Picture (Optional)</label>
                    <input type="file" id="picture" name="picture" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    <small class="file-help">JPG, JPEG, PNG, or WEBP. Maximum 2MB.</small>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Register Manually</button>
            </form>
        </div>
    </div>
</div>
@endsection