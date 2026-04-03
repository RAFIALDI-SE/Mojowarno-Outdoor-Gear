<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - Outdoor Rent</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --dark-navy: #0c2140;
            --light-blue: #9ac1f8;
            --white: #ffffff;
            --bg-light: #f8f9fa;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

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

        .reset-card {
            background: var(--white);
            width: 100%;
            max-width: 450px;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(12, 33, 64, 0.08);
            text-align: center;
        }

        .icon-wrapper {
            width: 80px; height: 80px;
            background-color: #f0f7ff;
            color: var(--dark-navy);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 25px;
            font-size: 32px;
            border: 2px solid var(--light-blue);
        }

        .reset-card h3 { font-weight: 800; font-size: 1.5rem; margin-bottom: 12px; }

        /* Box Keterangan Email */
        .email-info-box {
            background-color: #f8f9fa;
            border: 1px dashed var(--light-blue);
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 25px;
        }
        .email-info-box small { color: #6c757d; display: block; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; margin-bottom: 2px; }
        .email-info-box span { font-weight: 600; color: var(--dark-navy); word-break: break-all; }

        .form-group { text-align: left; margin-bottom: 18px; }
        .form-label-custom { font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block; opacity: 0.8; }

        .input-custom {
            width: 100%; padding: 14px 18px; border-radius: 14px;
            border: 2px solid #edf2f7; background-color: #fdfdfd;
            font-size: 1rem; transition: all 0.3s ease; outline: none;
        }

        .input-custom:focus { border-color: var(--light-blue); background-color: var(--white); box-shadow: 0 0 0 4px rgba(154, 193, 248, 0.15); }

        .btn-reset {
            background-color: var(--dark-navy); color: var(--white);
            width: 100%; padding: 16px; border: none; border-radius: 14px;
            font-weight: 700; cursor: pointer; margin-top: 15px;
            transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }

        .btn-reset:hover { background-color: #1a3a63; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(12, 33, 64, 0.2); }

        .error-text { color: #dc2626; font-size: 0.8rem; margin-top: 6px; display: block; font-weight: 500; }

        @media (max-width: 480px) {
            .reset-card { padding: 35px 25px; border-radius: 20px; }
            .reset-card h3 { font-size: 1.3rem; }
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="reset-card">
        <div class="icon-wrapper">
            <i class="fas fa-user-shield"></i>
        </div>

        <h3>Atur Password Baru</h3>

        <div class="email-info-box">
            <small>Mereset Password Untuk:</small>
            <span>{{ $email }}</span>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label class="form-label-custom">Password Baru</label>
                <input type="password" name="password" class="input-custom" placeholder="Minimal 6 karakter" required autofocus>
                @error('password')
                    <span class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label-custom">Ulangi Password Baru</label>
                <input type="password" name="password_confirmation" class="input-custom" placeholder="Konfirmasi password" required>
            </div>

            <button type="submit" class="btn-reset">
                <span>Update Password</span>
                <i class="fas fa-check-double"></i>
            </button>
        </form>
    </div>
</div>

</body>
</html>