@php($role = auth()->user()->role)
<nav class="top-nav">
    <div class="nav-brand">
        <span class="nav-logo">B</span>
        <span>Baittendance</span>
        <span class="role-badge">
            @if($role === 'admin') Super Admin
            @elseif($role === 'sub_admin') Admin
            @else User @endif
        </span>
    </div>

    <div class="nav-links">
        <div class="dropdown">
            <button type="button" class="dropbtn {{ request()->routeIs('dashboard', 'attendance.log') ? 'is-active' : '' }}">Attendance</button>
            <div class="dropdown-content">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Clock In / Out</a>
                <a href="{{ route('attendance.log') }}" class="{{ request()->routeIs('attendance.log') ? 'is-active' : '' }}">Full Attendance Log</a>
            </div>
        </div>

        @if(in_array($role, ['admin', 'sub_admin']))
        <div class="dropdown">
            <button type="button" class="dropbtn {{ request()->routeIs('employee.registration') ? 'is-active' : '' }}">Employees</button>
            <div class="dropdown-content">
                <a href="{{ route('employee.registration') }}" class="{{ request()->routeIs('employee.registration') ? 'is-active' : '' }}">Manual Registration</a>
            </div>
        </div>
        @endif

        @if($role === 'admin')
        <div class="dropdown">
            <button type="button" class="dropbtn {{ request()->routeIs('users.index') ? 'is-active' : '' }}">System</button>
            <div class="dropdown-content">
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.index') ? 'is-active' : '' }}">User Approvals & Roles</a>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn-link nav-logout">Logout</button>
        </form>
    </div>
</nav>