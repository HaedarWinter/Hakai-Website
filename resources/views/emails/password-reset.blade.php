<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reset Password - Hakai</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background-color: #f8f9fc;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #6C3AF0 0%, #4e73df 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px;
        }

        .content p {
            color: #5a5c69;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #6C3AF0 0%, #4e73df 100%);
            color: white !important;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .footer {
            background: #f8f9fc;
            padding: 20px;
            text-align: center;
            color: #858796;
            font-size: 12px;
        }

        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #856404;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Reset Password</h1>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $user->nama }}</strong>!</p>

            <p>Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah untuk melanjutkan:</p>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
            </div>

            <div class="warning">
                ⏰ <strong>Perhatian:</strong> Link ini hanya berlaku selama 1 jam.
            </div>

            <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini. Password Anda tidak akan berubah.</p>

            <p style="margin-top: 30px; color: #858796; font-size: 12px;">
                Jika tombol di atas tidak berfungsi, copy dan paste link berikut ke browser Anda:<br>
                <a href="{{ $resetUrl }}" style="color: #4e73df; word-break: break-all;">{{ $resetUrl }}</a>
            </p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Hakai. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>

</html>