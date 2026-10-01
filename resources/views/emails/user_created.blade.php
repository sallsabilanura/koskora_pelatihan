<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Baru Koskora</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 40px 20px; color: #334155; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 0 auto; }
        .card { background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border-top: 6px solid #1e1b9b; }
        .card-body { padding: 45px 40px; }
        .logo { height: 48px; margin-bottom: 35px; display: block; }
        h1 { margin: 0 0 15px 0; font-size: 24px; font-weight: 700; color: #0f172a; letter-spacing: -0.025em; }
        p { margin: 0 0 20px 0; font-size: 15px; line-height: 1.6; color: #475569; }
        .credentials-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; margin: 30px 0; }
        .cred-row { margin-bottom: 16px; }
        .cred-row:last-child { margin-bottom: 0; }
        .cred-label { display: block; font-size: 12px; text-transform: uppercase; font-weight: 600; color: #64748b; margin-bottom: 6px; letter-spacing: 0.05em; }
        .cred-value { font-size: 16px; font-weight: 600; color: #1e1b9b; }
        .btn { display: inline-block; padding: 14px 28px; background-color: #1e1b9b; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 15px; margin-top: 10px; transition: background-color 0.2s; }
        .btn:hover { background-color: #151375; }
        .footer { text-align: center; padding-top: 30px; font-size: 13px; color: #94a3b8; }
        .divider { height: 1px; background-color: #e2e8f0; margin: 35px 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="card-body">
                <img src="{{ $message->embed(public_path('koskora.png')) }}" alt="Koskora" class="logo" onerror="this.style.display='none'">
                
                <h1>Selamat Datang di Koskora!</h1>
                <p>Halo <strong>{{ $user->name }}</strong>,</p>
                <p>Akun Anda telah berhasil dibuat oleh tim administrator kami. Berikut adalah informasi akses masuk untuk akun Anda:</p>
                
                <div class="credentials-box">
                    <div class="cred-row">
                        <span class="cred-label">Username / Email</span>
                        <span class="cred-value">{{ $user->email }}</span>
                    </div>
                    <div class="cred-row">
                        <span class="cred-label">Password Sementara</span>
                        <span class="cred-value">{{ $password }}</span>
                    </div>
                </div>
                
                <p>Demi menjaga keamanan akun Anda, sistem akan meminta Anda untuk segera mengganti password sementara ini pada saat pertama kali login.</p>
                
                <a href="{{ url('/admin/login') }}" class="btn">Login Sekarang</a>
                
                <div class="divider"></div>
                <p style="font-size: 13px; color: #64748b; margin: 0;">Butuh bantuan? Silakan hubungi admin pengelola kos.</p>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Koskora. Hak cipta dilindungi.
        </div>
    </div>
</body>
</html>
