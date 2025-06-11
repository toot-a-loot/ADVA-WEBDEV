<!DOCTYPE html>
<html>
<head>
    <title>Set New Password</title>
</head>
<body>
    <h1>Set New Password</h1>
    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
    <form method="POST" action="{{ route('password.set_new_password') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="code" value="{{ $code }}">
        <label>New Password:</label>
        <input type="password" name="password" id="password" required minlength="8">
        <button type="button" onclick="togglePassword('password')">Show/Hide</button>
        <br>
        <label>Confirm Password:</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8">
        <button type="button" onclick="togglePassword('password_confirmation')">Show/Hide</button>
        <br>
        <div id="match-error" style="color:red;display:none;">Passwords do not match!</div>
        <button type="submit" id="submit-btn">Reset Password</button>
    </form>
    <a href="{{ route('password.code.form') }}">Back</a>
    <script>
        function togglePassword(id) {
            var input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
        const password = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const error = document.getElementById('match-error');
        const submitBtn = document.getElementById('submit-btn');
        function checkMatch() {
            if (password.value && confirm.value && password.value !== confirm.value) {
                error.style.display = 'block';
                submitBtn.disabled = true;
            } else {
                error.style.display = 'none';
                submitBtn.disabled = false;
            }
        }
        password.addEventListener('input', checkMatch);
        confirm.addEventListener('input', checkMatch);
    </script>
</body>
</html>
