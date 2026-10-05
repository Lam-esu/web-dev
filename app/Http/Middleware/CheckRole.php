<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if the user is logged in AND their role matches the required role
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }
        
        // If they fail the check, kick them back to the dashboard
        return redirect()->route('dashboard')->withErrors('Unauthorized access. Admin privileges required.');
    }
}