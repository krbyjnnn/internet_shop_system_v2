@extends('layouts.admin')
@section('title', 'Customers')
@section('content')

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="two-col">

    {{-- Create Customer Form --}}
    <div class="card">
        <h2>Create Customer</h2>
        <form method="POST" action="{{ route('admin.customers.store') }}">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Full name">
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Username">
                @error('username')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password">
                @error('password')<span class="error">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn-primary">Create Customer</button>
        </form>
    </div>

    {{-- Top Up Form --}}
    <div class="card">
        <h2>Top Up Balance</h2>
        <form method="POST" action="{{ route('admin.customers.topup') }}">
            @csrf
            <div class="form-group">
                <label>Select Customer</label>
                <select name="user_id">
                    <option value="">-- Select Customer --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">
                            {{ $customer->name }} (₱{{ $customer->balance }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Amount (₱)</label>
                <input type="number" name="amount" placeholder="Enter amount" min="1">
                @error('amount')<span class="error">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn-primary">Top Up</button>
        </form>
    </div>

</div>

{{-- Customers Table --}}
<div class="card" style="margin-top: 24px;">
    <h2>All Customers</h2>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Username</th>
                <th>Balance</th>
                <th>Station</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->username }}</td>
                <td>₱{{ $customer->balance }}</td>
                <td>{{ $customer->station->name ?? 'None' }}</td>
                <td>
                    <span class="badge {{ $customer->station_id ? 'badge-red' : 'badge-green' }}">
                        {{ $customer->station_id ? 'Online' : 'Offline' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection