<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Topup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        // Record the top-up
        Topup::create([
            'user_id' => $user->id,
            'amount'  => $request->amount,
        ]);

        return back()->with('success', 'Balance topped up successfully!');
    }

    public function products()
    {
        $products = Product::latest()->get();
        return view('admin.products', compact('products'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'  => 'required|string',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create([
            'name'  => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return back()->with('success', 'Product added successfully!');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted successfully!');
    }

    public function editProduct(Product $product)
    {
        $products = Product::latest()->get();
        return view('admin.products', compact('products', 'product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name'  => 'required|string',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'name'  => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function orders()
    {
        $pending = Order::with(['user', 'station', 'product'])
                        ->where('status', 'pending')
                        ->latest()
                        ->get()
                        ->groupBy('station_id');

        return view('admin.orders', compact('pending'));
    }

    public function deliverOrder(Order $order)
    {
        // Deduct balance if payment method is balance
        if ($order->payment_method === 'balance') {
            $order->user->decrement('balance', $order->total_price);
        }

        $order->update(['status' => 'delivered']);
        return back()->with('success', 'Order marked as delivered!');
    }

    public function orderHistory()
    {
        $orders = Order::with(['user', 'station', 'product'])
                    ->whereIn('status', ['delivered', 'cancelled'])
                    ->latest()
                    ->get();
        return view('admin.order-history', compact('orders'));
    }

    public function reports()
    {
        // --- SHOP REVENUE (orders) ---
        $dailyOrders = Order::with(['product', 'user'])
            ->where('status', 'delivered')
            ->whereDate('created_at', Carbon::today())
            ->get();

        $dailyShopTotal = $dailyOrders->sum('total_price');

        $allTimeOrders = Order::with(['product', 'user'])
            ->where('status', 'delivered')
            ->get();

        $allTimeShopTotal = $allTimeOrders->sum('total_price');

        // --- SESSION REVENUE (topups) ---
        $dailyTopups = Topup::with('user')
            ->whereDate('created_at', Carbon::today())
            ->get();

        $dailyTopupTotal = $dailyTopups->sum('amount');

        $allTimeTopups = Topup::with('user')
            ->get();

        $allTimeTopupTotal = $allTimeTopups->sum('amount');

        // --- PAYMENT BREAKDOWN ---
        $paymentBreakdown = Order::where('status', 'delivered')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_price) as total')
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports', compact(
            'dailyOrders',
            'dailyShopTotal',
            'allTimeShopTotal',
            'dailyTopups',
            'dailyTopupTotal',
            'allTimeTopupTotal',
            'allTimeOrders',
            'allTimeTopups',
            'paymentBreakdown'
        ));
    }
    
    public function deliverGroup(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
        ]);

        $orders = Order::with('user')
            ->where('station_id', $request->station_id)
            ->where('status', 'pending')
            ->get();

        foreach ($orders as $order) {
            $order->update(['status' => 'delivered']);
        }

        return back()->with('success', 'All orders delivered for that station!');
    }

    public function forceLogout(Station $station)
    {
        if ($station->user_id) {
            $user = User::find($station->user_id);
            $user->update(['station_id' => null, 'balance' => 0]);
        }

        $station->update([
            'is_occupied' => false,
            'user_id'     => null,
        ]);

        return back()->with('success', 'Station ' . $station->name . ' has been cleared!');
    }
}
