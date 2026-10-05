@extends('layouts.app')

@section('title', 'Baittendance - Dashboard')

@section('content')

<!-- Enhanced 3-Second Popup -->
@if(session('attendance_success'))
    <div id="attendance-popup" class="popup-overlay">
        <div class="popup-card">
            
            <div class="popup-banner {{ session('action_type') == 'CLOCKED IN' ? 'success-in' : 'success-out' }}">
                SUCCESSFULLY {{ session('action_type') }}
            </div>

            <h2 style="margin: 0 0 16px 0; font-size: 1.6rem; font-weight: 900;">
                {{ session('employee')->last_name }}, {{ session('employee')->first_name }}
            </h2>
            
            <div class="popup-image-wrapper">
                @if(session('employee')->picture)
                    <img src="{{ asset('storage/' . session('employee')->picture) }}" alt="Profile Picture">
                @else
                    <div style="width: 220px; height: 220px; background: var(--bg-base); border-radius: 8px; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">
                        No Photo Available
                    </div>
                @endif
            </div>
            
            <div class="popup-info-row">
                <span style="color: var(--text-secondary);">Employee ID:</span> 
                <strong>{{ session('employee')->employee_number }}</strong>
            </div>
            
            <div class="popup-info-row">
                <span style="color: var(--text-secondary);">Department:</span> 
                <strong>{{ session('employee')->department_position }}</strong>
            </div>
            
            <div style="color: var(--brand-primary); font-weight: 700; font-size: 1.1rem; margin-top: 24px;">
                {{ session('action_time') }}
            </div>
            
        </div>
    </div>

    <script>
        setTimeout(function() {
            const popup = document.getElementById('attendance-popup');
            if (popup) { popup.style.display = 'none'; }
        }, 3000);
    </script>
@endif

<div class="attendance-wrapper">
    <nav class="top-nav">
        <div class="nav-brand">Baittendance Monitoring System</div>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}" style="color: var(--brand-primary);">Attendance</a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('employee.registration') }}">Employee Registration</a>
                <a href="{{ route('attendance.log') }}">Full Attendance Log</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-link">Logout</button>
            </form>
        </div>
    </nav>

    <!-- 3-Column Main Layout -->
    <div class="dashboard-layout">
        
        <!-- LEFT: Philippine Time -->
        <div class="time-panel">
            <div id="live-time" class="time-display">--:--:--</div>
            <div id="live-date" class="date-display">---</div>
            <div class="timezone-display">Philippine Standard Time</div>
        </div>

        <!-- CENTER: Form Area -->
        <div class="attendance-card">
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
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
                <div class="form-group">
                    <input 
                        type="text" 
                        name="employee_number" 
                        class="form-control" 
                        value="{{ old('employee_number') }}"
                        placeholder="Enter Employee Number" 
                        required 
                        autofocus
                    >
                </div>
                
                <div class="button-group">
                    <button type="submit" name="action" value="clock_in" class="btn btn-primary" style="flex: 1;">
                        Clock In
                    </button>
                    
                    <button type="submit" name="action" value="clock_out" class="btn btn-secondary" style="flex: 1;">
                        Clock Out
                    </button>
                </div>
            </form>
        </div>

        <!-- RIGHT: Recent Activity (Semi-Censored) -->
        <div class="activity-panel">
            <h3 class="activity-header">Recent Activity</h3>
            
            @forelse($recentLogs ?? [] as $log)
                <div class="activity-item">
                    <div class="activity-name">
                        {{ mb_substr($log->employee->first_name, 0, 1) }}*** {{ mb_substr($log->employee->last_name, 0, 1) }}***
                    </div>
                    <div class="activity-id">
                        ID: {{ mb_substr($log->employee->employee_number, 0, 4) }}***{{ mb_substr($log->employee->employee_number, -2) }}
                    </div>
                    <div class="activity-status {{ $log->time_out && $log->updated_at->format('H:i:s') == $log->time_out ? 'status-out' : 'status-in' }}">
                        @if($log->time_out && $log->updated_at->format('H:i:s') == $log->time_out)
                            Clocked Out: {{ \Carbon\Carbon::parse($log->time_out)->format('h:i A') }}
                        @else
                            Clocked In: {{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}
                        @endif
                    </div>
                </div>
            @empty
                <div style="color: var(--text-secondary); font-size: 14px; text-align: center; margin-top: 20px;">
                    No recent activity today.
                </div>
            @endforelse
        </div>

    </div>
</div>

<!-- JavaScript for Live Clock -->
<script>
    function updateClock() {
        const now = new Date();
        const timeOptions = { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        document.getElementById('live-time').innerText = now.toLocaleTimeString('en-US', timeOptions);
        
        const dateOptions = { timeZone: 'Asia/Manila', weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('live-date').innerText = now.toLocaleDateString('en-US', dateOptions);
    }
    
    updateClock();
    setInterval(updateClock, 1000);
</script>
@endsection