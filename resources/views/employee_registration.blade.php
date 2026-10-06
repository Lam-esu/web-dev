@extends('layouts.app')

@section('title', 'Employee Approval - Baittendance')

@section('content')

<div class="attendance-wrapper">

    {{-- Top Navigation --}}
    <nav class="top-nav">

        <div class="nav-brand">
            Baittendance Monitoring System
        </div>

        <div class="nav-links">

            <a href="{{ route('dashboard') }}">
                Attendance
            </a>

            <a
                href="{{ route('employee.registration') }}"
                style="color: var(--brand-primary);"
            >
                Employee Approval
            </a>

            <a href="{{ route('attendance.log') }}">
                Attendance Log
            </a>

            <form
                method="POST"
                action="{{ route('logout') }}"
                style="display:inline;"
            >
                @csrf

                <button
                    type="submit"
                    class="btn-link"
                >
                    Logout
                </button>
            </form>

        </div>

    </nav>


    {{-- Main Content --}}
    <div class="main-content">

        <div class="attendance-card registration-card">

            <h2>
                Employee Approval
            </h2>

            <p>
                Review pending employee accounts and assign their employee number and role.
            </p>


            {{-- Success Message --}}
            @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

            @endif


            {{-- Errors --}}
            @if($errors->any())

                <div class="alert alert-error">

                    <strong>
                        Please correct the following errors:
                    </strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif


            {{-- Pending Employees --}}
            @forelse($pendingUsers as $user)

                <div
                    class="approval-card"
                    style="
                        border: 1px solid rgba(255,255,255,0.08);
                        border-radius: 12px;
                        padding: 24px;
                        margin-bottom: 20px;
                        background: var(--bg-surface);
                    "
                >

                    {{-- Employee Information --}}
                    <div style="margin-bottom: 20px;">

                        @if($user->pfp)

                            <img
                                src="{{ asset('storage/' . $user->pfp) }}"
                                alt="Profile Picture"
                                style="
                                    width: 80px;
                                    height: 80px;
                                    object-fit: cover;
                                    border-radius: 50%;
                                    margin-bottom: 12px;
                                "
                            >

                        @endif

                        <h3 style="margin-bottom: 5px;">
                            {{ $user->first_name }}
                            {{ $user->last_name }}
                        </h3>

                        <p style="margin: 0;">
                            {{ $user->email }}
                        </p>

                        <small style="color: var(--text-secondary);">
                            Registered:
                            {{ $user->created_at->format('M d, Y h:i A') }}
                        </small>

                    </div>


                    {{-- Approval Form --}}
                    <form
                        method="POST"
                        action="{{ route('employee.store') }}"
                    >

                        @csrf

                        <input
                            type="hidden"
                            name="user_id"
                            value="{{ $user->id }}"
                        >


                        {{-- Employee Number --}}
                        <div class="form-group">

                            <label for="employee_number_{{ $user->id }}">
                                Employee Number
                            </label>

                            <input
                                type="text"
                                name="employee_number"
                                class="form-control"
                                placeholder="Enter employee number"
                                value="{{ old('employee_number') }}"
                                required
                            >
                                style="
                                    background-color: var(--bg-surface);
                                    cursor: not-allowed;
                                    color: var(--text-secondary);
                                "
                            >

                        </div>


                        {{-- Role --}}
                        <div class="form-group">

                            <label for="department_position_{{ $user->id }}">
                                Role
                            </label>

                            <select
                                id="department_position_{{ $user->id }}"
                                name="department_position"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    Select employee role
                                </option>

                                <option value="HR Officer">
                                    HR Officer
                                </option>

                                <option value="Finance Staff">
                                    Finance Staff
                                </option>

                                <option value="Marketing Employee">
                                    Marketing Employee
                                </option>

                                <option value="IT Staff">
                                    IT Staff
                                </option>

                                <option value="Employee">
                                    Employee
                                </option>

                            </select>

                        </div>


                        <div
                            class="button-group"
                            style="gap: 10px;"
                        >

                            <button
                                type="submit"
                                class="btn btn-primary"
                                style="flex: 1;"
                            >
                                Approve Employee
                            </button>

                        </div>

                    </form>


                    {{-- Reject --}}
                    <form
                        method="POST"
                        action="{{ route('employee.reject') }}"
                        style="margin-top: 10px;"
                    >

                        @csrf

                        <input
                            type="hidden"
                            name="user_id"
                            value="{{ $user->id }}"
                        >

                        <button
                            type="submit"
                            class="btn btn-secondary"
                            style="width: 100%;"
                            onclick="
                                return confirm(
                                    'Are you sure you want to reject this account?'
                                );
                            "
                        >
                            Reject
                        </button>

                    </form>

                </div>

            @empty

                <div
                    style="
                        text-align: center;
                        padding: 40px 20px;
                        color: var(--text-secondary);
                    "
                >

                    <h3>
                        No Pending Employees
                    </h3>

                    <p>
                        There are currently no employee accounts waiting for approval.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection