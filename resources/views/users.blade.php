@extends('layouts.app')
@section('title', 'System Users - Baittendance')

@section('content')
<div class="attendance-wrapper">
    @include('partials.nav')

    <div class="page page-medium">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="page-header">
            <h1>System Users</h1>
            <p>Manage system access, pending approvals, and administrative roles.</p>
        </div>

        {{-- SECTION 1: Pending Approvals --}}
        <div class="card card-flush">
            <div class="card-header">
                <h2 class="card-title">Pending Approvals</h2>
            </div>

            @forelse($pendingUsers as $user)
                <div class="user-row">

                    {{-- User Info --}}
                    <div class="user-info">
                        <div class="avatar">
                            @if($user->pfp)
                                <img src="{{ asset('storage/' . $user->pfp) }}" alt="{{ $user->first_name }}">
                            @else
                                {{ substr($user->first_name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <div class="user-name">{{ $user->first_name }} {{ $user->last_name }}</div>
                            <div class="user-email">{{ $user->email }}</div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="user-actions">
                        <form method="POST" action="{{ route('users.approve') }}" class="inline-form">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <select name="role" class="form-control form-control-sm select-role" required aria-label="Assign Role">
                                <option value="" disabled selected>Assign Role</option>
                                <option value="admin">Super Admin</option>
                                <option value="sub_admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                        </form>

                        <form method="POST" action="{{ route('users.reject') }}" class="inline-form">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this user?');">Reject</button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="empty-state">
                    There are currently no pending accounts waiting for approval.
                </div>
            @endforelse
        </div>

        {{-- SECTION 2: Active Users --}}
        <div class="card card-flush">
            <div class="card-header">
                <h2 class="card-title">Active System Users</h2>
            </div>

            @foreach($approvedUsers as $user)
                <div class="user-row">

                    {{-- User Info --}}
                    <div class="user-info">
                        <div class="avatar">
                            @if($user->pfp)
                                <img src="{{ asset('storage/' . $user->pfp) }}" alt="{{ $user->first_name }}">
                            @else
                                {{ substr($user->first_name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <div class="user-name">
                                {{ $user->first_name }} {{ $user->last_name }}
                                @if(auth()->id() === $user->id)
                                    <span class="badge badge-muted">You</span>
                                @endif
                            </div>
                            <div class="user-email">{{ $user->email }}</div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <form method="POST" action="{{ route('users.updateRole') }}" class="inline-form">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <select name="role" class="form-control form-control-sm select-role" required aria-label="Role">
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="sub_admin" {{ $user->role === 'sub_admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                        </select>
                        <button type="submit" class="btn btn-secondary btn-sm">Update</button>
                    </form>

                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection