@extends('layouts.app')

@section('title', 'Create Account - Baittendance')

@section('content')

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-brand">
            <div class="logo-badge">B</div>

            <h1>Create your Baittendance account</h1>

            <p>
                Create an account to get started
            </p>
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

        <form
            method="POST"
            action="{{ route('register.attempt') }}"
            enctype="multipart/form-data"
            novalidate
        >

            @csrf

            {{-- First Name --}}
            <div class="form-group">

                <label for="first_name">
                    First Name
                </label>

                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    class="form-control @error('first_name') is-invalid @enderror"
                    value="{{ old('first_name') }}"
                    placeholder="Enter first name"
                    maxlength="80"
                    required
                    autofocus
                >

                @error('first_name')
                    <div class="field-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Last Name --}}
            <div class="form-group">

                <label for="last_name">
                    Last Name
                </label>

                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    class="form-control @error('last_name') is-invalid @enderror"
                    value="{{ old('last_name') }}"
                    placeholder="Enter last name"
                    maxlength="80"
                    required
                >

                @error('last_name')
                    <div class="field-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Email --}}
            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

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
                    <div class="field-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Profile Picture --}}
            <div class="form-group">

                <label for="pfp">
                    Profile Picture
                </label>

                <input
                    type="file"
                    id="pfp"
                    name="pfp"
                    class="form-control file-input @error('pfp') is-invalid @enderror"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <small class="file-help">
                    Optional. JPG, JPEG, PNG, or WEBP. Maximum 2MB.
                </small>

                @error('pfp')
                    <div class="field-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Password --}}
            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <div class="password-field">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="At least 8 characters"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        data-target="#password"
                    >
                        Show
                    </button>

                </div>

                @error('password')
                    <div class="field-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Confirm Password --}}
            <div class="form-group">

                <label for="password_confirmation">
                    Confirm Password
                </label>

                <div class="password-field">

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Re-enter your password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        data-target="#password_confirmation"
                    >
                        Show
                    </button>

                </div>

            </div>


            <div class="button-group">

                <button
                    type="submit"
                    class="btn btn-primary"
                    style="flex: 1;"
                >
                    Create Account
                </button>

                <a
                    href="{{ route('login') }}"
                    class="btn btn-secondary"
                    style="flex: 1;"
                >
                    Back to Login
                </a>

            </div>

        </form>

    </div>

</div>

@endsection