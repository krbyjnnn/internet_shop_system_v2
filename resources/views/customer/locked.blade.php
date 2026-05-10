<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session Expired</title>
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
</head>
<body>
    <div class="locked-screen">
        <h1>⏰ Session Expired</h1>
        <p>Your time has run out. Please go to the counter to top up your balance.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-primary" style="margin-top: 20px; width: auto; padding: 12px 30px;">
                Back to Login
            </button>
        </form>
    </div>
</body>
</html>