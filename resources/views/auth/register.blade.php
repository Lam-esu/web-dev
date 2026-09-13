@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-brand">
            <div class="logo-badge">L</div>
            <h1>Create your account</h1>
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

            <div class="form-group">
                <label for="name">Full name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="Jane Doe"
                    required
                    autofocus
                >
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required
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
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
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

            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('login') }}">Log in</a>
        </div>

    </div>
</div>
@endsection
