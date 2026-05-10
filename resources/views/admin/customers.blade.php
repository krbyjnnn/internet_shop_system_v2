@extends('layouts.admin')
@section('title', 'Customers')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endpush

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
                <label>Search Customer</label>
                <select name="user_id" id="customer-select">
                    <option value="">-- Search customer --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">
                            {{ $customer->name }} ({{ $customer->username }}) — ₱{{ $customer->balance }}
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
            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method">
                    <option value="cash">Cash</option>
                    <option value="gcash">GCash</option>
                </select>
                @error('payment_method')<span class="error">{{ $message }}</span>@enderror
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#customer-select').select2({
            placeholder: '-- Search customer --',
            allowClear: true
        });
    });
</script>
@endpush