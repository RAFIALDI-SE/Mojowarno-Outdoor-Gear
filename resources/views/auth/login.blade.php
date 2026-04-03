<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mojowarno Outdoor</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    {{-- <link rel="stylesheet" href="{{ asset('css/auth.css') }}"> --}}
    <style>
        :root {
    --navy: #0c2140;
    --light-blue: #9ac1f8;
    --white: #ffffff;
    }

    body {
        font-family: "Segoe UI", Roboto, sans-serif;
        background: linear-gradient(135deg, var(--navy) 0%, #1a3a63 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 20px 0;
    }

    .card-auth {
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        background-color: var(--white);
    }

    .auth-header {
        padding: 25px 25px 10px;
    }

    .auth-body {
        padding: 0 30px 25px;
    }

    .form-label {
        font-weight: 600;
        color: var(--navy);
        font-size: 0.85rem;
        margin-bottom: 5px;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 15px;
        font-size: 0.9rem;
        border: 1px solid #dee2e6;
    }

    .form-control:focus {
        border-color: var(--light-blue);
        box-shadow: 0 0 0 0.25rem rgba(154, 193, 248, 0.2);
    }

    .btn-register {
        background-color: var(--navy);
        color: white;
        font-weight: bold;
        padding: 12px;
        border-radius: 10px;
        border: none;
        letter-spacing: 1px;
        transition: 0.3s;
    }

    .btn-register:hover {
        background-color: #1a3a63;
        color: var(--light-blue);
    }

    .auth-footer {
        border-top: 1px solid #f1f1f1;
        padding: 20px;
        font-size: 0.85rem;
    }

    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-4">

            <div class="card card-auth">
                <div class="auth-header text-center">
                    <img src="{{ asset('storage/MojowarnoGearOutdoor.jpeg') }}" alt="Logo" height="65" class="rounded mb-3 shadow-sm border">
                    <h4 class="fw-bold mb-0" style="color: var(--navy);">Welcome Back!</h4>
                    <p class="text-muted small">Silakan masuk ke akun Anda</p>
                </div>

                <div class="auth-body">
                    @if(session('error'))
                        <div class="alert alert-danger border-0 small py-2">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.process') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>

                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                       placeholder="nama@email.com"
                                       required>
                            </div>

                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label class="form-label">Password</label>
                                <a href="{{ route('password.request') }}"
                                   class="small text-decoration-none"
                                   style="color: var(--light-blue);">
                                    Lupa?
                                </a>
                            </div>

                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>

                                <input type="password"
                                       name="password"
                                       class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                       placeholder="********"
                                       required>
                            </div>

                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-register w-100 shadow-sm">
                            LOG IN
                        </button>
                    </form>
                </div>

                <div class="auth-footer text-center">
                    <p class="mb-0 text-muted">Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: var(--navy);">Daftar</a></p>
                    <div class="mt-3">
                        <a href="/" class="small text-muted text-decoration-none"><i class="fas fa-home me-1"></i> Kembali ke Beranda</a>
                    </div>
                </div>
            </div>

            <p class="text-center mt-4 text-white-50 small">
                &copy; {{ date('Y') }} Mojowarno Outdoor Rental.
            </p>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>