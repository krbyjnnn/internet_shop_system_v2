<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Topup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // Displays the list of all registered customers on the Admin Dashboard.
    // It filters the User table so the Admin doesn't see other Admins in the list.
    public function index()
    {
        $customers = User::where('role', 'customer')->get();
        return view('admin.customers', compact('customers'));
    }

    // Registers a new customer into the system.
    // It validates the data, encrypts the password for security, and sets their starting balance to 0.
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
            'balance'  => 0,
        ]);

        return back()->with('success', 'Customer created successfully!');
    }

    // Handles the money-adding process.
    // This function does two things at once: 
    // 1. Increases the User's actual 'balance' column.
    // 2. Records the transaction in the 'topups' table for accounting/history.
    public function topUp(Request $request)
    {
        // Validation ensures we don't accept negative money or weird payment methods.
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,gcash',
        ]);

        $user = User::find($request->user_id);
        
        // This is a shorthand for $user->balance = $user->balance + $request->amount;
        $user->increment('balance', $request->amount);

        // Mini notification.
        Topup::create([
            'user_id'        => $user->id,
            'amount'         => $request->amount,
            'payment_method' => $request->payment_method,
        ]);

        return back()->with('success', 'Balance topped up successfully!');
    }
}