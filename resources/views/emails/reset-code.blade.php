<!DOCTYPE html>
<html>

<head>
    <title>Password Reset Code</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #FFF;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
        }

        h1 {
            color: #B2A0DC;
            text-align: center;
            margin-bottom: 30px;
        }

        .code {
            font-size: 32px;
            font-weight: bold;
            color: #B2A0DC;
            text-align: center;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 5px;
        }

        .expiry {
            color: #666;
            font-size: 14px;
            text-align: center;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Password Reset Code</h1>
        <p>You have requested to reset your password. Here is your verification code:</p>

        <div class="code">{{ $code }}</div>

        <p class="expiry">This code will expire in 30 minutes.</p>

        <p>If you did not request a password reset, please ignore this email.</p>

        <div class="footer">
            <p>Best regards,<br>Your Application Team</p>
        </div>
    </div>
</body>

</html>