<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Customer\CustomerController;

// New Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;

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
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Stations
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/stations/{station}/force-logout', [AdminController::class, 'forceLogout'])->name('stations.force-logout');

    // Customers (Using AdminCustomerController to avoid conflict with the Customer namespace)
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers');
    Route::post('/customers/store', [AdminCustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/topup', [AdminCustomerController::class, 'topUp'])->name('customers.topup');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/orders/{order}/deliver', [OrderController::class, 'deliver'])->name('orders.deliver');
    Route::post('/orders/deliver-group', [OrderController::class, 'deliverGroup'])->name('orders.deliver-group');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});

// Customer routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::post('/order', [CustomerController::class, 'order'])->name('order');
    Route::post('/expired', [CustomerController::class, 'expired'])->name('expired');
    Route::get('/locked', [CustomerController::class, 'locked'])->name('locked');
});