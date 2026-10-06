<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public / Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware('guest')->group(function () {

    // Login
    Route::get(
        '/login',
        [LoginController::class, 'showLoginForm']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'login']
    )->name('login.attempt');


    // Registration
    Route::get(
        '/register',
        [RegisterController::class, 'showRegistrationForm']
    )->name('register');

    Route::post(
        '/register',
        [RegisterController::class, 'register']
    )->name('register.attempt');

});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
     * Dashboard / Attendance
     */
    Route::get(
        '/dashboard',
        [AttendanceController::class, 'index']
    )->name('dashboard');

    Route::post(
        '/record-attendance',
        [AttendanceController::class, 'store']
    )->name('attendance.store');


    /*
     * Logout
     */
    Route::post(
        '/logout',
        [LoginController::class, 'logout']
    )->name('logout');


    /*
     * ADMIN ONLY
     */
    Route::middleware(['role:admin'])->group(function () {

        /*
         * Employee Approval
         */
        Route::get(
            '/employee-registration',
            [EmployeeController::class, 'create']
        )->name('employee.registration');

        Route::post(
            '/employee-registration',
            [EmployeeController::class, 'store']
        )->name('employee.store');

        Route::post(
            '/employee-registration/reject',
            [EmployeeController::class, 'reject']
        )->name('employee.reject');


        /*
         * Full Attendance Log
         */
        Route::get(
            '/attendance-log',
            [AttendanceController::class, 'log']
        )->name('attendance.log');

    });

});