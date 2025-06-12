<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Page with Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap">
    <style>
        /* Add these new styles before your existing styles */
        .mobile-login-container {
            display: none;
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
        }

        .mobile-login-container h1 {
            color: #B2A0DC;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .mobile-login-container .form-group {
            margin-bottom: 1rem;
        }

        .mobile-login-container label {
            display: block;
            color: #B2A0DC;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .mobile-login-container input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f8f8f8;
        }

        .mobile-login-container .password-input-container {
            position: relative;
        }

        .mobile-login-container .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #B2A0DC;
        }

        .mobile-login-container button {
            width: 100%;
            padding: 12px;
            background-color: #B2A0DC;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 1rem;
            cursor: pointer;
        }

        .mobile-login-container .links {
            margin-top: 1rem;
            text-align: center;
        }

        .mobile-login-container .links a {
            color: #B2A0DC;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin: 0.5rem 0;
        }

        .mobile-login-container .error-message {
            background-color: #fee2e2;
            color: #dc2626;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 14px;
            text-align: center;
        }

        .mobile-login-container .error-text {
            color: #dc2626;
            font-size: 12px;
            margin-top: 0.25rem;
        }

        /* Single media query for mobile devices */
        @media screen and (max-width: 800px) {
            .container {
                display: none;
            }

            .mobile-login-container {
                display: block;
            }
        }

        /* Your existing styles continue below */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Open Sans', sans-serif;
        }

        body {
            background-color: #FFF;
            min-height: 100vh;
            width: 100%;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
        }

        /* Responsive styles */
        @media screen and (max-width: 768px) {
            .container {
                min-height: 500px;
            }

            .form-container {
                width: 100% !important;
                padding: 0 20px !important;
            }

            .form-container h1 {
                font-size: 2rem !important;
            }

            .toggle-container {
                display: none;
            }

            .container.active .login,
            .container.active .sign-up,
            .container.forgot-active .login,
            .container.forgot-active .forgot-password,
            .container.code-active .login,
            .container.code-active .enter-code,
            .container.reset-active .login,
            .container.reset-active .reset-password,
            .container.success-active .login,
            .container.success-active .success {
                transform: translateX(0) !important;
                opacity: 1 !important;
                z-index: 5 !important;
            }

            .container.active .sign-up,
            .container.forgot-active .forgot-password,
            .container.code-active .enter-code,
            .container.reset-active .reset-password,
            .container.success-active .success {
                transform: translateX(100%) !important;
            }

            .container input {
                font-size: 14px;
                padding: 12px 15px;
            }

            .container button {
                width: 100%;
                padding: 12px 45px;
                font-size: 14px;
            }

            .back-arrow {
                top: 15px;
                left: 15px;
            }
        }

        @media screen and (max-width: 480px) {
            .container {
                border-radius: 20px;
                min-height: 450px;
            }

            .form-container h1 {
                font-size: 1.75rem !important;
            }

            .container p {
                font-size: 13px;
            }

            .container input {
                font-size: 13px;
                padding: 10px 12px;
            }

            .container button {
                padding: 10px 35px;
                font-size: 13px;
            }

            .error-message,
            .success-message {
                font-size: 12px;
                padding: 8px;
            }
        }

        .container p {
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 0.3px;
            margin: 20px 0;
        }

        .container span {
            font-size: 12px;
        }

        .container a {
            color: #B2A0DC;
            font-size: 13px;
            text-decoration: underline;
            margin: 15px 0 10px;
            cursor: pointer;
        }

        .container button {
            background-color: #B2A0DC;
            color: #fff;
            font-size: 12px;
            padding: 10px 45px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 10px;
            cursor: pointer;
        }

        .container button.hidden {
            background-color: transparent;
            border-color: #fff;
        }

        .container form {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            height: 100%;
        }

        .container input {
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .form-container h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #B2A0DC;
        }

        .login {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.active .login {
            transform: translateX(100%);
        }

        .container.forgot-active .login {
            transform: translateX(-100%);
            opacity: 0;
            z-index: 1;
        }

        .container.forgot-active .toggle-right {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .sign-up {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.active .sign-up {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: move 0.6s;
        }

        .forgot-password {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
            transform: translateX(100%);
        }

        .container.forgot-active .forgot-password {
            transform: translateX(0);
            opacity: 1;
            z-index: 5;
            animation: move 0.6s;
        }

        .enter-code {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
            transform: translateX(100%);
        }

        .container.code-active .enter-code {
            transform: translateX(0);
            opacity: 1;
            z-index: 5;
            animation: move 0.6s;
        }

        .container.code-active .login {
            transform: translateX(-100%);
            opacity: 0;
            z-index: 1;
        }

        .container.code-active .toggle-right {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .form-label {
            color: #B2A0DC;
            align-self: flex-start;
            font-weight: 600;
        }

        @keyframes move {

            0%,
            49.99% {
                opacity: 0;
                z-index: 1;
            }

            50%,
            100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .toggle-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: all 0.6s ease-in-out;
            border-radius: 150px 0 0 100px;
            z-index: 1000;
        }

        .container.active .toggle-container {
            transform: translateX(-100%);
            border-radius: 0 150px 100px 0;
        }

        .container.forgot-active .toggle-container {
            transform: translateX(0%);
        }

        .container.code-active .toggle-container {
            transform: translateX(0%);
        }

        .container.success-active .toggle-container {
            transform: translateX(0%);
        }

        .toggle {
            background-color: #B2A0DC;
            height: 100%;
            background: linear-gradient(to right, #B2A0DC, #B2A0DC);
            color: #fff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .container.active .toggle {
            transform: translateX(50%);
        }

        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            text-align: center;
            top: 0;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }

        .toggle-left {
            transform: translateX(-200%);
        }

        .container.active .toggle-left {
            transform: translateX(0);
        }

        .toggle-right {
            right: 0;
            transform: translateX(0);
        }

        .container.active .toggle-right {
            transform: translateX(200%);
        }

        .error-message {
            margin-bottom: 16px;
            color: #dc2626;
            background-color: #fee2e2;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            text-align: center;
            font-size: 13px;
        }

        .error-text {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .back-arrow {
            position: absolute;
            top: 25px;
            left: 25px;
            width: 20px;
            height: 20px;
            border-left: 3px solid #B2A0DC;
            border-bottom: 3px solid #B2A0DC;
            transform: rotate(45deg);
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .back-arrow:hover {
            border-color: #8a7ab8;
        }

        /* Password input container */
        .password-input-container {
            position: relative;
            width: 100%;
        }

        .password-input-container input {
            padding-right: 40px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #B2A0DC;
            font-size: 14px;
            user-select: none;
        }

        .password-toggle:hover {
            color: #8a7ab8;
        }

        .password-match-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .success-message {
            color: #22c55e;
            background-color: #ecfdf5;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            text-align: center;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .success {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
            transform: translateX(100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .container.success-active .success {
            transform: translateX(0);
            opacity: 1;
            z-index: 5;
            animation: move 0.6s;
        }

        .container.success-active .enter-code {
            transform: translateX(-100%);
            opacity: 0;
            z-index: 1;
        }

        .container.success-active .toggle-right {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .reset-password {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
            transform: translateX(100%);
        }

        .container.reset-active .reset-password {
            transform: translateX(0);
            opacity: 1;
            z-index: 5;
            animation: move 0.6s;
        }

        .container.reset-active .toggle-right {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .container.reset-active .enter-code {
            transform: translateX(-100%);
            opacity: 0;
            z-index: 1;
        }
    </style>
</head>

<body>
    <!-- Simplified Mobile Login Form -->
    <div class="mobile-login-container">
        <h1>Login</h1>
        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="mobile_login_email">Email</label>
                <input id="mobile_login_email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="mobile_login_password">Password</label>
                <div class="password-input-container">
                    <input id="mobile_login_password" type="password" name="password" required>
                    <span class="password-toggle" onclick="toggleMobilePassword('mobile_login_password')">👁️</span>
                </div>
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit">Login</button>

            <div class="links">
                <a href="#" onclick="showMobileForgotPassword()">Forgot Password?</a>
                <a href="#" onclick="showMobileSignup()">Create Account</a>
            </div>
        </form>
    </div>

    <!-- Mobile Forgot Password Form -->
    <div class="mobile-login-container" id="mobile-forgot-password" style="display: none;">
        <h1>Reset Password</h1>
        <form method="POST" action="{{ route('password.send_code') }}">
            @csrf
            <div class="form-group">
                <label for="mobile_forgot_email">Email</label>
                <input id="mobile_forgot_email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit">Send Reset Code</button>
            <div class="links">
                <a href="#" onclick="showMobileLogin()">Back to Login</a>
            </div>
        </form>
    </div>

    <!-- Mobile Signup Form -->
    <div class="mobile-login-container" id="mobile-signup" style="display: none;">
        <h1>Create Account</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="mobile_username">Username</label>
                <input id="mobile_username" type="text" name="username" required>
                @error('username')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="mobile_email">Email</label>
                <input id="mobile_email" type="email" name="email" required>
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="mobile_password">Password</label>
                <div class="password-input-container">
                    <input id="mobile_password" type="password" name="password" required>
                    <span class="password-toggle" onclick="toggleMobilePassword('mobile_password')">👁️</span>
                </div>
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="mobile_password_confirmation">Confirm Password</label>
                <div class="password-input-container">
                    <input id="mobile_password_confirmation" type="password" name="password_confirmation" required>
                    <span class="password-toggle"
                        onclick="toggleMobilePassword('mobile_password_confirmation')">👁️</span>
                </div>
            </div>

            <button type="submit">Sign Up</button>
            <div class="links">
                <a href="#" onclick="showMobileLogin()">Back to Login</a>
            </div>
        </form>
    </div>

    <!-- Mobile Code Verification Form -->
    <div class="mobile-login-container" id="mobile-code-verification" style="display: none;">
        <h1>Enter Code</h1>
        <form method="POST" action="{{ route('password.verify_code') }}" id="mobile-reset-password-form">
            @csrf
            <div class="form-group">
                <label for="mobile_code">Verification Code</label>
                <input type="text" name="code" id="mobile_code" placeholder="Enter code" required>
                @error('code')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            <input type="hidden" name="email" value="{{ session('email') }}">
            <button type="submit">Verify Code</button>
            <div class="links">
                <a href="#" onclick="showMobileForgotPassword()">Back</a>
            </div>
        </form>
    </div>

    <!-- Mobile Reset Password Form -->
    <div class="mobile-login-container" id="mobile-reset-password" style="display: none;">
        <h1>New Password</h1>
        <form method="POST" action="{{ route('password.reset') }}" id="mobile-password-reset-form">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') }}">
            <div class="form-group">
                <label for="mobile_new_password">New Password</label>
                <div class="password-input-container">
                    <input type="password" name="password" id="mobile_new_password" required minlength="8">
                    <span class="password-toggle" onclick="toggleMobilePassword('mobile_new_password')">👁️</span>
                </div>
            </div>
            <div class="form-group">
                <label for="mobile_confirm_password">Confirm Password</label>
                <div class="password-input-container">
                    <input type="password" name="password_confirmation" id="mobile_confirm_password" required
                        minlength="8">
                    <span class="password-toggle" onclick="toggleMobilePassword('mobile_confirm_password')">👁️</span>
                </div>
            </div>
            <div class="error-text" id="mobile-password-match-error" style="display: none;">
                Passwords do not match!
            </div>
            <button type="submit" id="mobile-reset-submit-btn">Update Password</button>
            <div class="links">
                <a href="#" onclick="showMobileCodeVerification()">Back</a>
            </div>
        </form>
    </div>

    <!-- Mobile Success Message -->
    <div class="mobile-login-container" id="mobile-success" style="display: none;">
        <h1>Success!</h1>
        <div class="success-message">
            Your password has been successfully reset.
        </div>
        <div class="links">
            <a href="{{ route('login') }}">
                <button>Back to Login</button>
            </a>
        </div>
    </div>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h1>Sign Up</h1>
                <label for="username" class="form-label">Username</label>
                <input id="username" type="text" name="username" placeholder="Username" required>

                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" placeholder="Email" required>

                <label for="password" class="form-label">Password</label>
                <div class="password-input-container">
                    <input id="password" type="password" name="password" placeholder="Password" required>
                    <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
                </div>

                <label for="password_confirmation" class="form-label">Verify Password Here</label>
                <div class="password-input-container">
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        placeholder="Confirm Password" required>
                    <span class="password-toggle" onclick="togglePassword('password_confirmation')">👁️</span>
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
                <label for="login_email" class="form-label">Email</label>
                <input id="login_email" type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                    required autofocus>
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
                <label for="login_password" class="form-label">Password</label>
                <div class="password-input-container">
                    <input id="login_password" type="password" name="password" placeholder="Password" required>
                    <span class="password-toggle" onclick="togglePassword('login_password')">👁️</span>
                </div>
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
                <a href="#" id="forgot-password-link">Forgot your password?</a>
                <button type="submit">Login</button>
            </form>
        </div>

        <div class="form-container forgot-password">
            <div id="back-to-login-arrow" class="back-arrow"></div>
            <form method="POST" action="{{ route('password.send_code') }}">
                @csrf
                <h1>Oh no...</h1>
                <p>Please provide your email below so we can send you
                    a verification code. Code expires in 30 minutes.</p>
                <label for="forgot_email" class="form-label">Email</label>
                <input id="forgot_email" type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                    required>
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
                <button type="submit">Proceed</button>
            </form>
        </div>

        <!-- Enter Code Form -->
        <div class="form-container enter-code">
            <div id="back-to-login-arrow-code" class="back-arrow"></div>
            <form method="POST" action="{{ route('password.verify_code') }}" id="reset-password-form">
                @csrf
                <h1>Almost there!</h1>
                <p>Code sent successfully to your email! Make sure to
                    check your spam folder.</p>

                <!-- Show errors for code verification -->
                @if(session('code_active') && $errors->has('code'))
                    <div class="error-message">
                        {{ $errors->first('code') }}
                    </div>
                @endif

                <input type="hidden" name="email" value="{{ session('email') }}">

                <label for="code" class="form-label">Code</label>
                <input type="text" name="code" id="code" placeholder="XXX-XXX" required>

                <button type="submit" id="reset-submit-btn">Verify</button>
            </form>
        </div>

        <!-- Password Reset Form (NEW CONTAINER) -->
        <div class="form-container reset-password">
            <div id="back-to-code-arrow" class="back-arrow"></div>
            <form method="POST" action="{{ route('password.reset') }}" id="password-reset-form">
                @csrf
                <h1>Success!</h1>
                <p>Verification successful! You may now update your
                    password.</p>
                <input type="hidden" name="email" value="{{ session('email') }}">
                <label for="new_password" class="form-label">New Password</label>
                <div class="password-input-container">
                    <input type="password" name="password" id="new_password_reset" placeholder="Enter New Password"
                        required minlength="8">
                    <span class="password-toggle" onclick="togglePassword('new_password_reset')">👁️</span>
                </div>
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <div class="password-input-container">
                    <input type="password" name="password_confirmation" id="confirm_password_reset"
                        placeholder="Confirm New Password" required minlength="8">
                    <span class="password-toggle" onclick="togglePassword('confirm_password_reset')">👁️</span>
                </div>
                <div class="password-match-error" id="password-match-error-reset">
                    Passwords do not match!
                </div>
                <button type="submit" id="reset-submit-btn-reset">Update</button>
            </form>
        </div>

        <!-- Success Message Container -->
        <div class="form-container success">
            <h1>Password Changed!</h1>
            <div class="success-message">
                Your password has been successfully reset.
            </div>
            <a href="{{ route('login') }}">
                <button>Back to Login</button>
            </a>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="hidden" id="login">Log In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');
        const forgotPasswordLink = document.getElementById('forgot-password-link');
        const backToLoginArrow = document.getElementById('back-to-login-arrow');
        const backToLoginArrowCode = document.getElementById('back-to-login-arrow-code');
        const backToCodeArrow = document.getElementById('back-to-code-arrow');

        // Function to handle form transitions
        function handleFormTransition(showForm, hideForms) {
            // Remove all active classes first
            container.classList.remove('active', 'forgot-active', 'code-active', 'reset-active', 'success-active');

            // Add the active class for the form to show
            if (showForm) {
                container.classList.add(showForm);
            }

            // Hide other forms if specified
            if (hideForms && Array.isArray(hideForms)) {
                hideForms.forEach(form => {
                    container.classList.remove(form);
                });
            }
        }

        // When code is verified, show reset password container
        @if (session('reset_active'))
            handleFormTransition('reset-active');
        @endif

        // Back arrow from reset password to code entry
        backToCodeArrow?.addEventListener('click', (e) => {
            e.preventDefault();
            handleFormTransition('code-active', ['reset-active']);
        });

        registerBtn?.addEventListener('click', () => {
            handleFormTransition('active');
        });

        loginBtn?.addEventListener('click', () => {
            handleFormTransition(null, ['active', 'forgot-active', 'code-active', 'success-active']);
        });

        forgotPasswordLink?.addEventListener('click', (e) => {
            e.preventDefault();
            handleFormTransition('forgot-active');
        });

        backToLoginArrow?.addEventListener('click', (e) => {
            e.preventDefault();
            handleFormTransition(null, ['forgot-active']);
        });

        backToLoginArrowCode?.addEventListener('click', (e) => {
            e.preventDefault();
            handleFormTransition('forgot-active', ['code-active']);
        });

        // Check for code_active session and activate the code form
        @if (session('code_active'))
            handleFormTransition('code-active');
        @endif

            // Password visibility toggle function
            function togglePassword(inputId) {
                const input = document.getElementById(inputId);
                const toggle = input.nextElementSibling;

                if (input.type === 'password') {
                    input.type = 'text';
                    toggle.textContent = '🙈';
                } else {
                    input.type = 'password';
                    toggle.textContent = '👁️';
                }
            }

        // Password matching validation for reset form
        const newPasswordReset = document.getElementById('new_password_reset');
        const confirmPasswordReset = document.getElementById('confirm_password_reset');
        const passwordMatchErrorReset = document.getElementById('password-match-error-reset');
        const resetSubmitBtnReset = document.getElementById('reset-submit-btn-reset');

        function validatePasswordsReset() {
            if (newPasswordReset && confirmPasswordReset && newPasswordReset.value && confirmPasswordReset.value) {
                if (newPasswordReset.value !== confirmPasswordReset.value) {
                    passwordMatchErrorReset.style.display = 'block';
                    resetSubmitBtnReset.disabled = true;
                    resetSubmitBtnReset.style.opacity = '0.5';
                    return false;
                } else {
                    passwordMatchErrorReset.style.display = 'none';
                    resetSubmitBtnReset.disabled = false;
                    resetSubmitBtnReset.style.opacity = '1';
                    return true;
                }
            }
            return true;
        }

        if (newPasswordReset && confirmPasswordReset) {
            newPasswordReset.addEventListener('input', validatePasswordsReset);
            confirmPasswordReset.addEventListener('input', validatePasswordsReset);

            document.getElementById('password-reset-form')?.addEventListener('submit', function (e) {
                if (!validatePasswordsReset()) {
                    e.preventDefault();
                }
            });
        }

        // Function to activate the success container
        function showSuccessMessage() {
            handleFormTransition('success-active');
        }

        // Check for success message and activate the success container
        @if (session('status') == 'Password has been reset!')
            showSuccessMessage();
        @endif

            // Mobile form functions
            function showMobileLogin() {
                hideAllMobileForms();
                document.querySelector('.mobile-login-container').style.display = 'block';
            }

        function showMobileForgotPassword() {
            hideAllMobileForms();
            document.getElementById('mobile-forgot-password').style.display = 'block';
        }

        function showMobileSignup() {
            hideAllMobileForms();
            document.getElementById('mobile-signup').style.display = 'block';
        }

        function showMobileCodeVerification() {
            hideAllMobileForms();
            document.getElementById('mobile-code-verification').style.display = 'block';
        }

        function showMobileResetPassword() {
            hideAllMobileForms();
            document.getElementById('mobile-reset-password').style.display = 'block';
        }

        function showMobileSuccess() {
            hideAllMobileForms();
            document.getElementById('mobile-success').style.display = 'block';
        }

        function hideAllMobileForms() {
            const mobileForms = document.querySelectorAll('.mobile-login-container');
            mobileForms.forEach(form => {
                form.style.display = 'none';
            });
        }

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                if (window.innerWidth > 800) {
                    hideAllMobileForms();
                    document.getElementById('container').style.display = 'block';
                } else {
                    document.getElementById('container').style.display = 'none';
                    showMobileLogin();
                }
            }, 250);
        });

        // Password matching validation for mobile reset form
        const mobileNewPassword = document.getElementById('mobile_new_password');
        const mobileConfirmPassword = document.getElementById('mobile_confirm_password');
        const mobilePasswordMatchError = document.getElementById('mobile-password-match-error');
        const mobileResetSubmitBtn = document.getElementById('mobile-reset-submit-btn');

        function validateMobilePasswords() {
            if (mobileNewPassword && mobileConfirmPassword && mobileNewPassword.value && mobileConfirmPassword.value) {
                if (mobileNewPassword.value !== mobileConfirmPassword.value) {
                    mobilePasswordMatchError.style.display = 'block';
                    mobileResetSubmitBtn.disabled = true;
                    mobileResetSubmitBtn.style.opacity = '0.5';
                    return false;
                } else {
                    mobilePasswordMatchError.style.display = 'none';
                    mobileResetSubmitBtn.disabled = false;
                    mobileResetSubmitBtn.style.opacity = '1';
                    return true;
                }
            }
            return true;
        }

        if (mobileNewPassword && mobileConfirmPassword) {
            mobileNewPassword.addEventListener('input', validateMobilePasswords);
            mobileConfirmPassword.addEventListener('input', validateMobilePasswords);

            document.getElementById('mobile-password-reset-form')?.addEventListener('submit', function (e) {
                if (!validateMobilePasswords()) {
                    e.preventDefault();
                }
            });
        }

        // Show appropriate form based on session state
        @if (session('code_active'))
            if (window.innerWidth <= 800) {
                showMobileCodeVerification();
            }
        @endif

        @if (session('reset_active'))
            if (window.innerWidth <= 800) {
                showMobileResetPassword();
            }
        @endif

        @if (session('status') == 'Password has been reset!')
            if (window.innerWidth <= 800) {
                showMobileSuccess();
            }
        @endif
    </script>
</body>

</html>