<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Topup;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // --- SHOP REVENUE (Orders) ---
        $dailyOrders = Order::with(['product', 'user'])
            ->where('status', 'delivered')
            ->whereDate('created_at', Carbon::today())
            ->get();

        $dailyShopTotal = $dailyOrders->sum('total_price');

        $allTimeOrders = Order::with(['product', 'user'])
            ->where('status', 'delivered')
            ->get();

        $allTimeShopTotal = $allTimeOrders->sum('total_price');

        // --- SESSION REVENUE (Top-ups) ---
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

        $topupBreakdown = Topup::selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
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
            'paymentBreakdown',
            'topupBreakdown'
        ));
    }
}
