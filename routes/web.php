<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Root route → redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Disable public registration
Route::get('/register', function () {
    return redirect()->route('login');
})->name('register');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard route — admin/manager goes to employees list, employee sees their profile
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if (in_array($user->role, ['admin', 'manager'])) {
            return redirect()->route('employees.index');
        }

        return view('dashboard', ['employee' => $user]);
    })->name('dashboard');

    // Admin-only Employee CRUD (create, store, destroy)
    Route::resource('employees', EmployeeController::class)
        ->only(['create', 'store', 'destroy'])
        ->middleware('can:manage,' . User::class);

    // Admin + Manager (index, edit, update)
    Route::resource('employees', EmployeeController::class)
        ->only(['index', 'edit', 'update'])
        ->middleware('can:manageOrManager,' . User::class);

    // Employee profile view
    Route::get('employees/{employee}', [EmployeeController::class, 'show'])
        ->name('employees.show');

    // Admin Logs page
    Route::get('/admin/logs', [AdminController::class, 'logs'])
        ->middleware('can:manage,' . User::class)
        ->name('admin.logs');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes
require __DIR__.'/auth.php';