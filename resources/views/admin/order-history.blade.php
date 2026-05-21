@extends('layouts.admin')
@section('title', 'Order History')
@section('content')

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Order History</h2>
        <a href="{{ route('admin.orders') }}" class="btn-edit">
            Back to Pending
        </a>
    </div>

    @if($orders->isEmpty())
        <p style="color:#94a3b8; font-size:14px;">No order history yet.</p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Station</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->station->name }}</td>
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
                <td>
                    <span class="badge {{ $order->status === 'delivered' ? 'badge-green' : 'badge-red' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td title="{{ $order->created_at }}">
                    {{ $order->created_at->diffForHumans() }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

@endsection