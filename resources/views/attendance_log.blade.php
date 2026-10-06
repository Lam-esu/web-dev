@extends('layouts.app')

@section('title', 'Attendance Log - Baittendance')

@section('content')
<div class="attendance-wrapper">
    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="nav-brand">Baittendance Monitoring System</div>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Attendance</a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('employee.registration') }}">Employee Registration</a>
                <a href="{{ route('attendance.log') }}" style="color: var(--brand-primary);">Attendance Log</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-link">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="main-content" style="align-items: flex-start;">
        <div class="attendance-card" style="max-width: 1200px; width: 100%; padding: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
                <div style="text-align: left;">
                    <h2 style="margin-bottom: 8px;">Attendance Records</h2>
                    <p style="margin: 0;">View and filter all employee time-in logs.</p>
                </div>
            </div>

            <!-- Dashboard Filters -->
            <form method="GET" action="{{ route('attendance.log') }}" style="background: var(--bg-highlight); padding: 20px; border-radius: 8px; border: 1px solid var(--border-subtle); margin-bottom: 24px; display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
                
                <!-- Search -->
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; text-align: left;">SEARCH (NAME / ID)</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="e.g. bai-00001" style="text-align: left;">
                </div>

                <!-- Date Range -->
                <div style="flex: 1; min-width: 140px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; text-align: left;">START DATE</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" style="text-align: left; color-scheme: dark;">
                </div>
                <div style="flex: 1; min-width: 140px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; text-align: left;">END DATE</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" style="text-align: left; color-scheme: dark;">
                </div>

                <!-- Department Dropdown (Admins Only) -->
                @if(Auth::user()->isAdmin())
                <div style="flex: 1; min-width: 180px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; text-align: left;">DEPARTMENT</label>
                    <select name="department" class="form-control" style="text-align: left; appearance: auto;">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Status Filter -->
                <div style="flex: 1; min-width: 160px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; text-align: left;">STATUS</label>
                    <select name="status" class="form-control" style="text-align: left; appearance: auto;">
                        <option value="">All Statuses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Clocked Out</option>
                        <option value="missing_out" {{ request('status') == 'missing_out' ? 'selected' : '' }}>Missing Clock Out</option>
                    </select>
                </div>

                <!-- Actions -->
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px; border-radius: 6px;">Filter</button>
                    <a href="{{ route('attendance.log') }}" class="btn btn-secondary" style="padding: 10px 20px; border-radius: 6px; line-height: 1.2;">Clear</a>
                </div>
            </form>

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
                                <td>{{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') : '---' }}</td>
                                <td>
                                    @if($attendance->time_out)
                                        <span style="color: var(--text-primary);">{{ \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') }}</span>
                                    @else
                                        <span style="color: #ef4444; font-size: 12px; font-weight: bold; background: rgba(239, 68, 68, 0.1); padding: 4px 8px; border-radius: 4px;">MISSING</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="log-empty">No attendance records found matching your filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div style="margin-top: 24px; display: flex; justify-content: center;">
                {{ $attendances->links() }}
            </div>
            
        </div>
    </div>
</div>
@endsection