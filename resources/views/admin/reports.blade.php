@extends('layouts.admin')
@section('title', 'Reports')
@section('content')

{{-- Summary Cards --}}
<div class="report-cards">
    <div class="report-card">
        <span class="report-label">Today's Session Revenue</span>
        <span class="report-value">₱{{ number_format($dailyTopupTotal, 2) }}</span>
        <span class="report-sub">{{ $dailyTopups->count() }} top-ups today</span>
    </div>
    <div class="report-card">
        <span class="report-label">Today's Shop Revenue</span>
        <span class="report-value">₱{{ number_format($dailyShopTotal, 2) }}</span>
        <span class="report-sub">{{ $dailyOrders->count() }} orders today</span>
    </div>
    <div class="report-card">
        <span class="report-label">All Time Session Revenue</span>
        <span class="report-value">₱{{ number_format($allTimeTopupTotal, 2) }}</span>
        <span class="report-sub">{{ $allTimeTopups->count() }} total top-ups</span>
    </div>
    <div class="report-card">
        <span class="report-label">All Time Shop Revenue</span>
        <span class="report-value">₱{{ number_format($allTimeShopTotal, 2) }}</span>
        <span class="report-sub">{{ $allTimeOrders->count() }} total orders</span>
    </div>
</div>

{{-- Payment Breakdown --}}
<div class="card" style="margin-top: 24px;">
    <h2>Shop Payment Breakdown</h2>
    @if($paymentBreakdown->isEmpty())
        <p style="color:#94a3b8; font-size:14px;">No delivered orders yet.</p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Payment Method</th>
                <th>Orders</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentBreakdown as $payment)
            <tr>
                <td>
                    <span class="badge {{
                        $payment->payment_method === 'balance' ? 'badge-green' :
                        ($payment->payment_method === 'gcash' ? 'badge-blue' : 'badge-gray')
                    }}">
                        {{ ucfirst($payment->payment_method) }}
                    </span>
                </td>
                <td>{{ $payment->count }}</td>
                <td>₱{{ number_format($payment->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

{{-- Today's Top-ups --}}
<div class="card" style="margin-top: 24px;">
    <h2>Today's Top-ups</h2>
    @if($dailyTopups->isEmpty())
        <p style="color:#94a3b8; font-size:14px;">No top-ups today yet.</p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyTopups as $topup)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $topup->user->name }}</td>
                <td>₱{{ number_format($topup->amount, 2) }}</td>
                <td>{{ $topup->created_at->format('h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

{{-- Today's Shop Orders --}}
<div class="card" style="margin-top: 24px;">
    <h2>Today's Shop Orders</h2>
    @if($dailyOrders->isEmpty())
        <p style="color:#94a3b8; font-size:14px;">No orders today yet.</p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyOrders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->product->name }}</td>
                <td>{{ $order->quantity }}</td>
                <td>₱{{ number_format($order->total_price, 2) }}</td>
                <td>
                    <span class="badge {{
                        $order->payment_method === 'balance' ? 'badge-green' :
                        ($order->payment_method === 'gcash' ? 'badge-blue' : 'badge-gray')
                    }}">
                        {{ ucfirst($order->payment_method) }}
                    </span>
                </td>
                <td>{{ $order->created_at->format('h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

@endsection