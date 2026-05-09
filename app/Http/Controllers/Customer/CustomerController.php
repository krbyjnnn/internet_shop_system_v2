<?php

namespace App\Http\Controllers\Customer;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('customer.dashboard', compact('products'));
    }
    
}
