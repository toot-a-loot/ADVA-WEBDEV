<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Signup Page</title>
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div class="signup-container">
        <h2 class="signup-title">Sign Up</h2>
        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="email"></label>
                <input id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required
                    autofocus class="form-input @error('email') input-error @enderror">
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="username"></label>
                <input id="username" type="text" name="username" placeholder="Username" value="{{ old('username') }}"
                    required autofocus class="form-input @error('username') input-error @enderror">
                @error('username')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password"></label>
                <input id="password" type="password" name="password" placeholder="Password" required
                    class="form-input @error('password') input-error @enderror">
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="buttons">
                <button type="create" class="create-button">
                    CREATE ACCOUNT
                </button>
                <button type="google" class="google-button">
                    SIGN IN WITH GOOGLE
                </button>
            </div>


            <div class="auth-links">
                <a href="{{ route('login') }}" class="auth-link">Already have an Account?<span> Login</span></a>
            </div>

        </form>
    </div>
</body>

</html>