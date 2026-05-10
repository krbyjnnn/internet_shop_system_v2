<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | ISS</title>
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    @stack('styles')
</head>
<body>
    <div class="customer-layout">

        {{-- Topbar --}}
        <header class="customer-topbar">
            <span class="brand">Internet Shop System</span>
            <div class="topbar-right">
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </header>

        {{-- Content --}}
        <main class="customer-main">
            @yield('content')
        </main>

    </div>
    @stack('scripts')
</body>
</html>