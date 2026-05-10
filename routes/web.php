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
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Customers
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::post('/customers/store', [AdminController::class, 'storeCustomer'])->name('customers.store');
    Route::post('/customers/topup', [AdminController::class, 'topUp'])->name('customers.topup');

    // Products
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products/store', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');

    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::post('/orders/{order}/deliver', [AdminController::class, 'deliverOrder'])->name('orders.deliver');
    Route::get('/orders/history', [AdminController::class, 'orderHistory'])->name('orders.history');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

    Route::post('/orders/deliver-group', [AdminController::class, 'deliverGroup'])->name('orders.deliver-group');

    // Inside admin group
    Route::post('/stations/{station}/force-logout', [AdminController::class, 'forceLogout'])->name('stations.force-logout');
});

// Customer routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::post('/order', [CustomerController::class, 'order'])->name('order');
    Route::post('/expired', [CustomerController::class, 'expired'])->name('expired');
    Route::get('/locked', [CustomerController::class, 'locked'])->name('locked');
});

