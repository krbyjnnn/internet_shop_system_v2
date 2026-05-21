<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard display with stations.
    public function dashboard()
    {
        $stations = Station::with('user')->get();
        return view('admin.dashboard', compact('stations'));
    }

    // Force logout for a specific reason and at the same time clearing the balance of that customer.
    public function forceLogout(Station $station)
    {
        if ($station->user_id) {
            $user = User::find($station->user_id);
            // Safety check: ensure user exists before updating.
            if ($user) {
                $user->update(['station_id' => null, 'balance' => 0]);
            }
        }

        $station->update([
            'is_occupied' => false,
            'user_id'     => null,
        ]);

        return back()->with('success', 'Station ' . $station->name . ' has been cleared!');
    }
}