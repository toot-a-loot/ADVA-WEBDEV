<!DOCTYPE html>
<html>
<head>
    <title>Enter Code</title>
</head>
<body>
    <h1>Enter the code sent to your email</h1>
    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
    <form method="POST" action="{{ route('password.verify_code') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <label>Code:</label>
        <input type="text" name="code" required>
        <button type="submit">Proceed</button>
    </form>
    <a href="{{ route('forgot.password') }}">Back</a>
</body>
</html>
