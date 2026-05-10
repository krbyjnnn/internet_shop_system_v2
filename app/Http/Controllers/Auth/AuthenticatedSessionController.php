<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    // Login form with available stations
    public function create()
    {
        $stations = Station::where('is_occupied', false)->get();
        return view('auth.login', compact('stations'));
    }

    public function store(Request $request)
    {
        // Phase 1: Validation and Authentication
        // Validate input
        $request->validate([
            'username'  => 'required|string',
            'password'   => 'required|string'
        ]);

        // Attempt login
        if(!Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ])) {
            return back()->withErrors([
                'username' => 'Invalid username or password'
            ])->withInput();
        }

        // Redirect based on role
        if(Auth::user()->role === 'admin')
            {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard'); 
            }

        // Phase 2: Station Assignment (for customers)
        // Validate station selection
        $request->validate([
            'station_id'        => 'required|exists:stations,id',
            'station_password'  => 'required|string'
        ]);

        // Find the station the customer selected
        $station = Station::find($request->station_id);
 
        // Check if the station is occupied
        if($station->is_occupied)
            {
                Auth::logout();
                return back()->withErrors([
                    'station_id' => 'The PC is already occupied. Please select another one.'
                ])->withInput();
            }
        
        // Verify station password
        if($station->password !== $request->station_password)
            {
                Auth::logout();
                return back()->withErrors([
                    'station_password' => 'Incorrect PC password. Please try again.'
                ])->withInput();
            }

        // Mark the station as occupied and link it to the user
        $station->update([
            'is_occupied' => true,
            'user_id' => Auth::id()
        ]);

        Auth::user()->update([
            'station_id' => $station->id
        ]);
        
        // Regenerate session and redirect to customer dashboard
        $request->session()->regenerate();
        return redirect()->route('customer.dashboard');
    }  

    // Logout and free up station
    public function destroy(Request $request)
    {   
        // Get the currently authenticated user
        $user = Auth::user();

        // Logout the user
        if($user && $user->role === 'customer' && $user->station_id)
            {
                Station::where('id', $user->station_id)->update([
                    'is_occupied' => false,
                    'user_id' => null
                ]);

                $user->update(['station_id' => null]);
            }
        
        // Logout
        Auth::logout();

        // Invalidate session and regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login
        return redirect()->route('login');
    }
}
