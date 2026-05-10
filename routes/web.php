<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Customer\CustomerController;

// Redirect root to login
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    }
    return redirect()->route('login');
});

// Auth routes (login/logout)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');

    // Customers
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::post('/customers/store', [AdminController::class, 'storeCustomer'])->name('customers.store');
    Route::post('/customers/topup', [AdminController::class, 'topUp'])->name('customers.topup');   
});

// Customer routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])
        ->name('dashboard');
});