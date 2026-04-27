<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - PDAM Kebocoran</title>
    <style>
        :root {
            --primary: #0f3f9a;
            --primary-soft: #eaf1ff;
            --line: #d7deeb;
            --bg: #f4f6fb;
            --text: #223047;
            --muted: #65758f;
            --white: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            background: url('/images/bg-login.jpg') center center / cover no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(180deg, #0f3f9a 0%, #0a2d6e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #e0f2fe;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .login-header h1 {
            margin: 0 0 8px;
            font-size: 24px;
            font-weight: 700;
        }

        .login-header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .login-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text);
        }

        .form-group input {
            width: 100%;
            border: 2px solid var(--line);
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 63, 154, 0.1);
        }

        .btn-login {
            width: 100%;
            border: 0;
            border-radius: 10px;
            padding: 14px 20px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            background: linear-gradient(180deg, #0f3f9a 0%, #0a2d6e 100%);
            color: #fff;
            transition: transform 0.1s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(15, 63, 154, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .flash {
            margin-bottom: 20px;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            background: #fff1f1;
            border: 1px solid #f3c3c3;
            color: #9d2020;
        }

        .flash-success {
            background: #e7fff1;
            border: 1px solid #b8e8cc;
            color: #096a42;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: var(--muted);
            font-size: 13px;
            text-decoration: none;
        }

        .back-link a:hover {
            color: var(--primary);
        }

        @media (max-width: 480px) {
            .login-header {
                padding: 24px;
            }

            .login-header h1 {
                font-size: 20px;
            }

            .login-body {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo" style="overflow: hidden; width: 70px; height: 70px;">
                    <img src="/images/pipa.png" width="70" height="70" alt="Logo" style="object-fit: cover;">
                </div>
                <h1>PDAM Kebocoran</h1>
                <p>Silakan login untuk mengakses sistem</p>
            </div>

            <div class="login-body">
                @if (session('error'))
                    <div class="flash">{{ session('error') }}</div>
                @endif

                @if (session('success'))
                    <div class="flash flash-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            id="email" 
                            name="email" 
                            type="text" 
                            placeholder="Masukkan email"
                            value=""
                            autocomplete="off"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            placeholder="Masukkan password"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-login">Masuk</button>
                </form>

                <div class="back-link">
                    <a href="/">← Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
