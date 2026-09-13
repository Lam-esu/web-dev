<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request): RedirectResponse
    {
        // Validate the incoming request data.
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ]);

        // Determine whether the "remember me" checkbox was ticked.
        $remember = $request->boolean('remember');

        // Attempt to authenticate the user using the given credentials.
        // Auth::attempt() automatically hashes and checks the password
        // against the hashed password stored in the database.
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate the session to prevent session fixation attacks.
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Authentication failed — return back with a generic error message.
        // We intentionally don't reveal whether the email or password was
        // wrong, to avoid leaking which emails are registered.
        return back()
            ->withErrors([
                'email' => 'These credentials do not match our records.',
            ])
            ->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Invalidate the session and regenerate the CSRF token.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been logged out.');
    }
}
