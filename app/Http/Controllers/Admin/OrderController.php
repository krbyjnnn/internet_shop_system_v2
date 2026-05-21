<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // It fetches all "pending" orders and groups them by Station.
    // This allows the Admin to see orders.
    public function index()
    {
        $pending = Order::with(['user', 'station', 'product'])
                        ->where('status', 'pending')
                        ->latest()
                        ->get()
                        ->groupBy('station_id');

        return view('admin.orders', compact('pending'));
    }

    // Marking an order "done" and handles the payment logic.
    // If the customer chose to pay with their account balance, 
    // it deducts the money from their balance after the order is delivered.
    public function deliver(Order $order)
    {
        if ($order->payment_method === 'balance') {
            // "Charge" the customer's account for this specific order.
            $order->user->decrement('balance', $order->total_price);
        }

        $order->update(['status' => 'delivered']);
        return back()->with('success', 'Order marked as delivered!');
    }

    // Bulk delivery.
    // If a customer orders 5 items at once, the Admin can click one button.
    // to mark everything at that PC as 'delivered' in one go.
    public function deliverGroup(Request $request)
    {
        $request->validate(['station_id' => 'required|exists:stations,id']);

        Order::where('station_id', $request->station_id)
            ->where('status', 'pending')
            ->update(['status' => 'delivered']);

        return back()->with('success', 'All orders delivered for that station!');
    }

    // Shows a list of everything that has already been delivered.
    // This is used for checking total sales.
    public function history()
    {
        $orders = Order::with(['user', 'station', 'product'])
                    ->whereIn('status', ['delivered', 'cancelled'])
                    ->latest()
                    ->get();
        return view('admin.order-history', compact('orders'));
    }
}