<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:80',
            ],

            'last_name' => [
                'required',
                'string',
                'max:80',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'pfp' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'An account with this email already exists.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'pfp.image' => 'The profile picture must be an image.',
            'pfp.max' => 'The profile picture must not exceed 2MB.',
        ]);

        /*
         * Check whether this is the FIRST user in the system.
         *
         * First-ever user:
         *      role   = admin
         *      status = approved
         *
         * All following users:
         *      role   = employee
         *      status = pending
         */
        $isFirstUser = User::count() === 0;

        /*
         * Generate a temporary username for compatibility
         * with the existing database.
         */
        $username = strtolower(
            preg_replace(
                '/[^a-zA-Z0-9]/',
                '',
                $validated['first_name'] . $validated['last_name']
            )
        );

        /*
         * Make username unique.
         */
        $baseUsername = $username ?: 'user';
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        /*
         * Upload profile picture.
         */
        $pfpPath = null;

        if ($request->hasFile('pfp')) {
            $pfpPath = $request->file('pfp')
                ->store('profile-pictures', 'public');
        }

        $user = User::create([
            'username' => $username,

            'full_name' => $validated['first_name']
                . ' '
                . $validated['last_name'],

            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],

            'email' => $validated['email'],

            'password' => Hash::make(
                $validated['password']
            ),

            'pfp' => $pfpPath,

            'role' => $isFirstUser
                ? 'admin'
                : 'employee',

            'status' => $isFirstUser
                ? 'approved'
                : 'pending',
        ]);

        /*
         * FIRST USER
         */
        if ($isFirstUser) {
            return redirect()
                ->route('login')
                ->with(
                    'status',
                    'Master Admin account created successfully. You may now log in.'
                );
        }

        /*
         * ALL OTHER USERS
         */
        return redirect()
            ->route('login')
            ->with(
                'status',
                'Account created successfully. Your account is now pending admin approval.'
            );
    }
}