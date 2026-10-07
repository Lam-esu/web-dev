@extends('layouts.app')

@section('title', 'Create Account - Baittendance')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card auth-card-wide">

        <div class="auth-brand">
            <img src="{{ asset('artwork/favicon.png') }}" alt="Baittendance" class="auth-logo">
            <h1>Create your Baittendance account</h1>
            <p>Create an account to get started</p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.attempt') }}" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="form-grid">

                {{-- LEFT COLUMN: Personal Information --}}
                <div>
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" placeholder="Enter first name" maxlength="80" required autofocus>
                        @error('first_name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" placeholder="Enter last name" maxlength="80" required>
                        @error('last_name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="you@example.com" required>
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="pfp">Profile Picture</label>
                        <input type="file" id="pfp" name="pfp" class="form-control @error('pfp') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp">
                        <small class="file-help">Optional. JPG, JPEG, PNG, or WEBP. Maximum 2MB.</small>
                        @error('pfp')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- RIGHT COLUMN: Security & Account --}}
                <div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-field">
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Create a strong password" required>
                            <button type="button" class="toggle-password" data-target="#password">Show</button>
                        </div>
                        @error('password')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Dynamic Password Requirements Meter --}}
                    <div class="strength-box">
                        <div class="strength-bar-bg">
                            <div id="password-progress" class="strength-bar"></div>
                        </div>
                        <ul class="req-list">
                            <li id="req-length">At least 8 characters</li>
                            <li id="req-upper">At least 1 uppercase letter</li>
                            <li id="req-number">At least 1 number</li>
                            <li id="req-special">At least 1 special character</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Re-enter your password" required>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="#">Terms and Conditions</a></label>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="button-group">
                <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                <a href="{{ route('login') }}" class="btn btn-secondary btn-block">Back to Login</a>
            </div>

        </form>
    </div>
</div>

{{-- Password Strength Logic --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pw = document.getElementById('password');
        const bar = document.getElementById('password-progress');

        const rules = {
            'req-length': v => v.length >= 8,
            'req-upper': v => /[A-Z]/.test(v),
            'req-number': v => /[0-9]/.test(v),
            'req-special': v => /[^A-Za-z0-9]/.test(v)
        };

        pw.addEventListener('input', function () {
            let score = 0;

            for (const id in rules) {
                const ok = rules[id](this.value);
                document.getElementById(id).classList.toggle('valid', ok);
                if (ok) score++;
            }

            bar.style.width = (score * 25) + '%';
            bar.className = 'strength-bar ' + (score <= 2 ? 'is-weak' : score === 3 ? 'is-medium' : 'is-strong');
        });
    });
</script>
@endsection