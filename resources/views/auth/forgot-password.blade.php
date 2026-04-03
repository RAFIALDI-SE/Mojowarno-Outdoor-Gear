<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Outdoor Rent</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --dark-navy: #0c2140;
            --light-blue: #9ac1f8;
            --white: #ffffff;
            --bg-light: #f8f9fa;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--dark-navy);
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .forgot-card {
            background: var(--white);
            width: 100%;
            max-width: 450px;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(12, 33, 64, 0.08);
            border: none;
            text-align: center;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background-color: #f0f7ff;
            color: var(--dark-navy);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 32px;
            border: 2px solid var(--light-blue);
        }

        .forgot-card h3 {
            color: var(--dark-navy);
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 12px;
        }

        .forgot-card p {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-label-custom {
            font-weight: 700;
            font-size: 0.75rem;
            color: var(--dark-navy);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            display: block;
            opacity: 0.8;
        }

        .input-custom {
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            border: 2px solid #edf2f7;
            background-color: #fdfdfd;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
            color: var(--dark-navy);
        }

        .input-custom:focus {
            border-color: var(--light-blue);
            background-color: var(--white);
            box-shadow: 0 0 0 4px rgba(154, 193, 248, 0.15);
        }

        .btn-reset {
            background-color: var(--dark-navy);
            color: var(--white);
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-reset:hover {
            background-color: #1a3a63;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(12, 33, 64, 0.2);
        }

        .btn-reset:active {
            transform: translateY(0);
        }

        .status-alert {
            background-color: #d1fae5;
            color: #065f46;
            padding: 16px;
            border-radius: 14px;
            font-size: 0.85rem;
            margin-bottom: 25px;
            border: 1px solid #a7f3d0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.8rem;
            margin-top: 6px;
            display: block;
            font-weight: 500;
        }

        .back-to-login {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .back-to-login a {
            color: var(--dark-navy);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .back-to-login a:hover {
            color: var(--light-blue);
        }

        /* Responsive Mobile */
        @media (max-width: 480px) {
            .forgot-card {
                padding: 35px 25px;
                border-radius: 20px;
            }

            .forgot-card h3 {
                font-size: 1.3rem;
            }

            .icon-wrapper {
                width: 70px;
                height: 70px;
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="forgot-card">
        <div class="icon-wrapper">
            <i class="fas fa-lock-open"></i>
        </div>

        <h3>Lupa Password?</h3>
        <p>Masukkan alamat email akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang password Anda.</p>

        @if (session('status'))
            <div class="status-alert">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label class="form-label-custom">Email Terdaftar</label>
                <input type="email" name="email" class="input-custom" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>

                @error('email')
                    <span class="error-text">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn-reset">
                <span>Kirim Link Reset</span>
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>

        <div class="back-to-login">
            <a href="{{ route('login') }}">
                <i class="fas fa-chevron-left"></i>
                Kembali ke Halaman Login
            </a>
        </div>
    </div>
</div>

</body>
</html>