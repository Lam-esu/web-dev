<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the authenticated user's dashboard.
     *
     * This route is protected by the "auth" middleware (see routes/web.php),
     * so unauthenticated visitors are automatically redirected to the
     * login page before this method ever executes.
     */
    public function index(Request $request): View
    {
        return view('dashboard', [
            'user' => $request->user(),
        ]);
    }
}
/** */