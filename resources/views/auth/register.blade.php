@extends('layouts.app')

@section('title', 'Register - Baittendance')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-brand">
            <div class="logo-badge">B</div>
            <h1>Create your Baittendance account</h1>
            <p>It only takes a minute</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.attempt') }}" novalidate>
            @csrf

            <!-- New Username Field -->
            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username') }}"
                    placeholder="jdoe123"
                    required
                    autofocus
                >
                @error('username')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Updated Full Name Field -->
            <div class="form-group">
                <label for="full_name">Full name</label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    class="form-control @error('full_name') is-invalid @enderror"
                    value="{{ old('full_name') }}"
                    placeholder="Jane Doe"
                    required
                >
                @error('full_name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email address (Optional)</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                >
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

<div class="form-group">
                <label for="password">Password</label>
                <div class="password-field">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="At least 8 characters"
                        required
                    >
                    <button type="button" class="toggle-password" data-target="#password">Show</button>
                </div>
                
                <!-- New Password Strength Meter -->
                <div class="password-strength-container">
                    <div class="strength-bar-bg"><div class="strength-bar" id="strength-bar"></div></div>
                    <div class="strength-text" id="strength-text" style="color: #dc2626;">Password strength: Weak</div>
                    <ul class="req-list">
                        <li id="req-length" class="invalid">At least 8 characters</li>
                        <li id="req-upper" class="invalid">At least 1 uppercase letter</li>
                        <li id="req-lower" class="invalid">At least 1 lowercase letter</li>
                        <li id="req-number" class="invalid">At least 1 number</li>
                        <li id="req-special" class="invalid">At least 1 special character</li>
                    </ul>
                </div>
                
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="password-field">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Re-enter your password"
                        required
                    >
                    <button type="button" class="toggle-password" data-target="#password_confirmation">Show</button>
                </div>
                <div id="password-match-message" style="font-size: 13px; margin-top: 6px;"></div>
            </div>

            <!-- Side-by-Side Buttons -->
            <div class="button-group">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Create Account</button>
                <a href="{{ route('login') }}" class="btn btn-secondary" style="flex: 1;">Back to Login</a>
            </div>
        </form>
        
    </div>
</div>
@endsection