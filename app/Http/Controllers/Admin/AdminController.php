<?php

namespace App\Http\Controllers\Admin;

use App\Models\Station;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stations = Station::with('user')->get();
        return view('admin.dashboard', compact('stations'));
    }

    public function customers()
    {
        $customers = User::where('role', 'customer')->get();
        return view('admin.customers', compact('customers'));
    }

    public function storeCustomer(Request $request)
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

    public function topUp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount'  => 'required|numeric|min:1',
        ]);

        $user = User::find($request->user_id);
        $user->increment('balance', $request->amount);

        return back()->with('success', 'Balance topped up successfully!');
    }

}
