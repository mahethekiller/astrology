<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }

        .content {
            padding: 40px;
            color: #333333;
            line-height: 1.6;
        }

        .content p {
            margin-bottom: 20px;
        }

        .btn-container {
            text-align: center;
            margin: 30px 0;
        }

        .btn {
            background: #6a11cb;
            color: #ffffff !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(106, 17, 203, 0.3);
        }

        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            color: #888888;
            font-size: 12px;
        }

        .footer a {
            color: #6a11cb;
            text-decoration: none;
        }

        .expiry {
            font-size: 13px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Astroauraa</h1>
        </div>
        <div class="content">
            <h2>Password Reset Request</h2>
            <p>Hello,</p>
            <p>You are receiving this email because we received a password reset request for your account. If you did
                not request a password reset, no further action is required.</p>
            <div class="btn-container">
                <a href="{{ $url }}" class="btn">Reset Password</a>
            </div>
            <p class="expiry">This password reset link will expire in {{ $count }} minutes.</p>
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web
                browser:</p>
            <p style="word-break: break-all; font-size: 13px; color: #6a11cb;">{{ $url }}</p>
            <p>Regards,<br>Astroauraa Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Astroauraa. All rights reserved.</p>
            <p>You received this email because you are a registered user of <a
                    href="{{ config('app.url') }}">Astroauraa</a>.</p>
        </div>
    </div>
</body>

</html>