@extends('layouts.app')

@section('title', 'Attendance Log - Baittendance')

@section('content')
<div class="attendance-wrapper">
    @include('partials.nav')

    <div class="page">
        <div class="page-header">
            <h1>Attendance Records</h1>
            <p>View and filter all employee time-in logs.</p>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('attendance.log') }}" class="filter-bar">

            <div class="filter-field filter-field-wide">
                <label for="search">Search (Name / ID)</label>
                <input type="text" id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="e.g. bai-00001">
            </div>

            <div class="filter-field">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="filter-field">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            <!-- Department Dropdown (Admins Only) -->
            @if(Auth::user()->isAdmin())
            <div class="filter-field">
                <label for="department">Department</label>
                <select id="department" name="department" class="form-control">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="filter-field">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Clocked Out</option>
                    <option value="missing_out" {{ request('status') == 'missing_out' ? 'selected' : '' }}>Missing Clock Out</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('attendance.log') }}" class="btn btn-secondary">Clear</a>
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
                                    {{ \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') }}
                                @else
                                    <span class="badge badge-danger">MISSING</span>
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
        <div class="pagination-wrap">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection