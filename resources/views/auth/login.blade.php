<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Internet Shop System</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-box">

            <h1>Internet Shop System</h1>
            <p class="subtitle">Sign in to continue</p>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                {{-- Username --}}
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="{{ old('username') }}"
                        placeholder="Enter username"
                        autocomplete="off">
                    @error('username')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        placeholder="Enter password">
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- PC Selection --}}
                <div class="form-group">
                    <label for="station_id">Select PC</label>
                    <select id="station_id" name="station_id">
                        <option value="">-- Select a PC --</option>
                        @foreach($stations as $station)
                            <option 
                                value="{{ $station->id }}"
                                {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                {{ $station->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('station_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- PC Password --}}
                <div class="form-group">
                    <label for="station_password">PC Password</label>
                    <input 
                        type="password" 
                        id="station_password" 
                        name="station_password"
                        placeholder="Enter PC password">
                    @error('station_password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-login">Login</button>

            </form>
        </div>
    </div>
</body>
</html>