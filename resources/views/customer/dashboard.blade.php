<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard | ISS</title>
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, {{ auth()->user()->name }}! 👋</h1>
        <p>Balance: ₱{{ auth()->user()->balance }}</p>
        <p>Station: {{ auth()->user()->station->name ?? 'None' }}</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>