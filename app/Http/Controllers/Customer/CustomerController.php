<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $products = Product::latest()->get();
        return view('customer.dashboard', compact('products'));
    }

    public function order(Request $request)
    {
        $items = json_decode($request->items, true);
        $paymentMethod = $request->payment_method;
        $user = Auth::user();

        if (!$items || count($items) === 0) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Calculate total
        $total = 0;
        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->stock < $item['quantity']) {
                return back()->with('error', 'Some items are out of stock.');
            }
            $total += $product->price * $item['quantity'];
        }

        // Check balance if paying with balance
        if ($paymentMethod === 'balance' && $user->balance < $total) {
            return back()->with('error', 'Insufficient balance.');
        }

        // Create orders and deduct stock
        foreach ($items as $item) {
            $product = Product::find($item['id']);

            Order::create([
                'user_id'        => $user->id,
                'station_id'     => $user->station_id,
                'product_id'     => $product->id,
                'quantity'       => $item['quantity'],
                'total_price'    => $product->price * $item['quantity'],
                'status'         => 'pending',
                'payment_method' => $paymentMethod,
            ]);

            // Deduct stock
            $product->decrement('stock', $item['quantity']);
        }

        // Deduct balance immediately if paying with balance
        if ($paymentMethod === 'balance') {
            $user->decrement('balance', $total);
        }

        return back()->with('success', 'Order placed successfully!');
    }

    public function expired(Request $request)
    {
        $user = Auth::user();
        $user->update(['balance' => 0]);
        return response()->json(['ok' => true]);
    }

    public function locked()
    {
        return view('customer.locked');
    }
}