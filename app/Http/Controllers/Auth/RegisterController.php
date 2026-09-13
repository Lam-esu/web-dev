<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the application's registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        // Validate all incoming registration fields.
        // - "name" is required text.
        // - "email" must be a valid, unique email address (prevents duplicates).
        // - "password" must be confirmed (i.e. match "password_confirmation")
        //   and meet Laravel's default strength rules.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'An account with this email address already exists.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Your password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Create the new user record. The password is hashed before being
        // stored — never save plain-text passwords. Because the User model
        // casts "password" as "hashed", Hash::make() happens automatically
        // when the attribute is set, but we call it explicitly here as well
        // for clarity and to guarantee it regardless of casting configuration.
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Automatically log the newly registered user in.
        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Welcome, your account has been created!');
    }
}
