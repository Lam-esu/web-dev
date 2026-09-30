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
            
            <!-- Groupmate Task: Build the data table here -->
            <div class="table-responsive">
                <!-- Data table will go here -->
            </div>
        </div>
    </div>
</div>
@endsection