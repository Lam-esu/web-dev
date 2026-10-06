@extends('layouts.app')

@section('title', 'Baittendance - Dashboard')

@section('content')

<!-- 3-Second Popup -->
@if(session('attendance_success'))
    <div id="attendance-popup" class="popup-overlay">
        <div class="popup-card">

            <div class="popup-banner {{ session('action_type') == 'CLOCKED IN' ? 'success-in' : 'success-out' }}">
                SUCCESSFULLY {{ session('action_type') }}
            </div>

            <h2 class="popup-name">
                {{ session('employee')->last_name }}, {{ session('employee')->first_name }}
            </h2>

            <div class="popup-image-wrapper">
                @if(session('employee')->picture)
                    <img src="{{ asset('storage/' . session('employee')->picture) }}" alt="Profile Picture">
                @else
                    <div class="popup-avatar-empty">No Photo Available</div>
                @endif
            </div>

            <div class="popup-info-row">
                <span>Employee ID:</span>
                <strong>{{ session('employee')->employee_number }}</strong>
            </div>

            <div class="popup-info-row">
                <span>Department:</span>
                <strong>{{ session('employee')->department_position }}</strong>
            </div>

            <div class="popup-time">{{ session('action_time') }}</div>

        </div>
    </div>

    <script>
        setTimeout(function () {
            const popup = document.getElementById('attendance-popup');
            if (popup) { popup.style.display = 'none'; }
        }, 3000);
    </script>
@endif

<div class="attendance-wrapper">
    @include('partials.nav')

    <div class="page">
        <div class="dashboard-layout">

            <!-- LEFT: Philippine Time -->
            <div class="card time-panel">
                <div id="live-time" class="time-display">--:--:--</div>
                <div id="live-date" class="date-display">---</div>
                <div class="timezone-display">Philippine Standard Time</div>
            </div>

            <!-- CENTER: Clock In / Out -->
            <div class="card clock-card">
                <h2>Clock In / Out</h2>
                <p class="card-sub">Enter your employee number to record your attendance.</p>

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
                            class="form-control form-control-lg"
                            value="{{ old('employee_number') }}"
                            placeholder="Enter Employee Number"
                            aria-label="Employee Number"
                            required
                            autofocus
                        >
                    </div>

                    <div class="button-group">
                        <button type="submit" name="action" value="clock_in" class="btn btn-primary btn-block">Clock In</button>
                        <button type="submit" name="action" value="clock_out" class="btn btn-secondary btn-block">Clock Out</button>
                    </div>
                </form>
            </div>

            <!-- RIGHT: Recent Activity (Semi-Censored) -->
            <div class="card activity-panel">
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
                    <div class="empty-state">No recent activity today.</div>
                @endforelse
            </div>

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