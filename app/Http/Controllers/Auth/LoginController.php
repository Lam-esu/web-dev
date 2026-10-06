<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show login page.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ]);

        /*
         * Find the account using the email.
         */
        $user = User::where(
            'email',
            $credentials['email']
        )->first();

        /*
         * Check whether credentials are correct.
         */
        if (
            !$user ||
            !Hash::check(
                $credentials['password'],
                $user->password
            )
        ) {
            return back()
                ->withErrors([
                    'email' =>
                        'These credentials do not match our records.',
                ])
                ->onlyInput('email');
        }

        /*
         * IMPORTANT:
         * Pending users cannot log in.
         */
        if ($user->status === 'pending') {

            return back()
                ->withErrors([
                    'email' =>
                        'Your account is still pending admin approval. Please wait for the administrator to approve your account.',
                ])
                ->onlyInput('email');
        }

        /*
         * Rejected users cannot log in.
         */
        if ($user->status === 'rejected') {

            return back()
                ->withErrors([
                    'email' =>
                        'Your account registration was not approved. Please contact the administrator.',
                ])
                ->onlyInput('email');
        }

        /*
         * Account is approved.
         */
        $remember = $request->boolean('remember');

        Auth::login($user, $remember);

        $request->session()->regenerate();

        return redirect()->intended(
            route('dashboard')
        );
    }

    /**
     * Log user out.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'status',
                'You have been logged out.'
            );
    }
}