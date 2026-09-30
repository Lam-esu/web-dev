@extends('layouts.app')

@section('title', 'Register Employee - Baittendance')

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
    <div class="main-content">
        <div class="attendance-card" style="text-align: left;">
            <h2 style="text-align: center;">Register New Employee</h2>
            <p style="text-align: center;">Enter the employee details below.</p>
            
            <!-- Groupmate Task: Build the registration form here -->
            <form>
                <!-- Form fields will go here -->
                
                <button type="submit" class="btn btn-primary" style="margin-top: 24px;">Save Employee</button>
            </form>
        </div>
    </div>
</div>
@endsection