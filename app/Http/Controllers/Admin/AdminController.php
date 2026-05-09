<?php

namespace App\Http\Controllers\Admin;

use App\Models\Station;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stations = Station::with('user')->get();
        return view('admin.dashboard', compact('stations'));
    }


}
