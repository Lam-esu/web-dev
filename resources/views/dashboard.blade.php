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
                <a href="{{ route('employee.registration') }}">Employee Registration</a>
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
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
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

            <form method="POST" action="{{ route('attendance.store') }}">
                @csrf
                
                <!-- The Input Field -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <input 
                        type="text" 
                        name="employee_number" 
                        class="form-control" 
                        value="{{ old('employee_number') }}"
                        placeholder="Enter Employee Number" 
                        required 
                        autofocus
                        style="width: 100%; padding: 15px; background: transparent; border: 1px solid #fff; color: #fff; text-align: center; border-radius: 5px;"
                    >
                </div>
                
                <!-- The Action Buttons -->
                <div class="button-group" style="display: flex; gap: 10px; justify-content: center;">
                    
                    <button type="submit" name="action" value="clock_in" class="btn btn-primary" style="background-color: #ff6b9e; color: #fff; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer;">
                        Clock In
                    </button>
                    
                    <button type="submit" name="action" value="clock_out" class="btn btn-secondary" style="background-color: #444; color: #fff; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer;">
                        Clock Out
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection