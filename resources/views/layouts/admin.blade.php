<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | ISS Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
    <div class="app-layout">

        {{-- Sidebar --}}
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h2>ISS Admin</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" 
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.customers') }}" 
                class="{{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                    Customers
                </a>
                <a href="#" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    Products
                </a>
                <a href="#" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    Orders
                </a>
                <a href="#" class="{{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    Reports
                </a>
            </nav>
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="main-content">
            <div class="topbar">
                <h1>@yield('title')</h1>
                <span>Welcome, {{ auth()->user()->name }}</span>
            </div>
            <div class="content">
                @yield('content')
            </div>
        </main>

    </div>
    @stack('scripts')
</body>
</html>