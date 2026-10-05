@extends('layouts.app')

@section('title', 'Baittendance - Dashboard')

@section('content')
<div class="attendance-wrapper">
    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="nav-brand">Baittendance Monitoring System</div>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}" style="color: var(--brand-primary);">Attendance</a>
            
            <!-- Only show these links if the user is a Professor/Admin -->
            @if(Auth::user()->isAdmin())
                <a href="{{ route('employee.registration') }}">Student Registration</a>
                <a href="{{ route('attendance.log') }}">Full Attendance Log</a>
            @endif
            
            <!-- Logout Form (Visible to everyone) -->
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-link">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Card Area -->
    <div class="main-content">
        <div class="attendance-card">
            <h2>Employee Attendance</h2>
            <p>Enter the employee number and press Enter.</p>

            <form>
                <input 
                    type="text" 
                    class="form-control" 
                    placeholder="Enter Employee Number" 
                    autofocus 
                    style="text-align: center; font-size: 18px; padding: 16px;"
                >
                
                <div class="button-group" style="justify-content: center; margin-top: 24px;">
                    <button type="submit" class="btn btn-primary" style="width: auto; padding: 12px 32px;">Record Attendance</button>
                    <a href="{{ route('employee.registration') }}" class="btn btn-secondary" style="width: auto; padding: 12px 32px; display: inline-flex; align-items: center;">
                        Employee Registration
                    </a>                
                </div>
            </form>
        </div>
    </div>
</div>
@endsection