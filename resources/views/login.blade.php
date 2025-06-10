<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap">
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h1>Sign Up</h1>
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input id="username" type="text" name="username" placeholder="Enter Username Here" required
                        class="form-input">
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" placeholder="example@gmail.com" required
                        class="form-input">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" placeholder="Enter Password Here" required
                        class="form-input">
                </div>
                <div class="form-group">
                    <label for="verify-password" class="form-label">Verify Password</label>
                    <input id="verify-password" type="verify-password" name="verify-password"
                        placeholder="Verify Password Here" required class="form-input">
                </div>
                <button type="submit">Sign Up</button>
            </form>
        </div>

        <div class="form-container login">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h1>Login</h1>

                @if(session('error'))
                    <div class="error-message">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="text" name="email" placeholder="Enter Email Here" value="{{ old('email') }}"
                        required autofocus class="form-input @error('email') input-error @enderror">
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" placeholder="Enter Password Here" required
                        class="form-input @error('password') input-error @enderror">
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password...</a>
                <button type="submit">Login</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Log in</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    </script>
</body>

</html>