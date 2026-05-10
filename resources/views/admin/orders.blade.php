@extends('layouts.admin')
@section('title', 'Orders')
@section('content')

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Pending Orders</h2>
        <a href="{{ route('admin.orders.history') }}" class="btn-edit">
            View History
        </a>
    </div>

    @if($pending->isEmpty())
        <p style="color:#94a3b8; font-size:14px;">No pending orders.</p>
    @else
        @foreach($pending as $stationId => $orders)
        <div class="order-group">
            <div class="order-group-header">
                <span>{{ $orders->first()->station->name }}</span>
                <span>{{ $orders->first()->user->name }}</span>
                <span class="order-group-count">{{ $orders->count() }} item(s)</span>
                <form method="POST" action="{{ route('admin.orders.deliver-group') }}" style="margin-left:auto;">
                    @csrf
                    <input type="hidden" name="station_id" value="{{ $stationId }}">
                    <button type="submit" class="btn-primary" style="width:auto; padding: 6px 14px;">
                        Deliver All
                    </button>
                </form>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->product->name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>₱{{ $order->total_price }}</td>
                        <td>
                            <span class="badge {{
                                $order->payment_method === 'balance' ? 'badge-green' :
                                ($order->payment_method === 'gcash' ? 'badge-blue' : 'badge-gray')
                            }}">
                                {{ ucfirst($order->payment_method) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    @endif
</div>

@endsection