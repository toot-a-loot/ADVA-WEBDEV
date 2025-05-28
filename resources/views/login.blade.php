<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="login-container">
        <h2 class="login-title">Welcome back!</h2>
        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="username"></label>
                <input id="username" type="text" name="username" placeholder="Username" value="{{ old('username') }}" required autofocus
                       class="form-input @error('username') input-error @enderror">
                @error('username')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password"></label>
                <input id="password" type="password" name="password" placeholder="Password"required
                       class="form-input @error('password') input-error @enderror">
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="login-button">
                LOGIN
            </button>

            <div class="auth-links">
                <a href="{{ route('register') }}" class="auth-link">Sign Up</a>
                <a href="{{ route('password.request') }}" class="auth-link">Forgot Password</a>
            </div>

        </form>
    </div>
</body>
</html>
