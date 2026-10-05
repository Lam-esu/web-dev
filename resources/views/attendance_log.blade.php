@extends('layouts.app')

@section('title', 'Attendance Log - Baittendance')

@section('content')
<div class="attendance-wrapper">
    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="nav-brand">Baittendance Monitoring System</div>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Attendance</a>
            <a href="{{ route('employee.registration') }}">Employee Registration</a>
            <a href="{{ route('attendance.log') }}">Attendance Log</a>

            <!-- Logout Form -->
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-link">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="main-content" style="align-items: flex-start;">
        <div class="attendance-card" style="max-width: 900px; width: 100%;">
            <h2>Attendance Records</h2>
            <p>View all employee time-in logs.</p>

            <div class="table-responsive">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>Department / Position</th>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->employee->employee_number }}</td>
                                <td>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</td>
                                <td>{{ $attendance->employee->department_position }}</td>
                                <td>{{ $attendance->attendance_date->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->attendance_time)->format('h:i A') }}</td>
                                <td>{{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : '---' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="log-empty">No attendance records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection