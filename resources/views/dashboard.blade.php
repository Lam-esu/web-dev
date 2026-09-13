@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-card">

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="dashboard-header">
        <div class="avatar-circle">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h1>Hello, {{ $user->name }} 👋</h1>
            <p>Here's your account overview</p>
        </div>
    </div>

    <div class="info-list">
        <div class="info-row">
            <span>Full Name</span>
            <span>{{ $user->name }}</span>
        </div>
        <div class="info-row">
            <span>Email</span>
            <span>{{ $user->email }}</span>
        </div>
        <div class="info-row">
            <span>Member since</span>
            <span>{{ $user->created_at->format('M d, Y') }}</span>
        </div>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline">Log Out</button>
    </form>

</div>
@endsection
