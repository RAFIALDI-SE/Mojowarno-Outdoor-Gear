<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mojowarno Outdoor</title>

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
        <div class="col-12 col-lg-7">

            <div class="card card-auth">
                <div class="auth-header text-center">
                    <img src="{{ asset('storage/MojowarnoGearOutdoor.jpeg') }}" alt="Logo" height="50" class="rounded mb-2">
                    <h4 class="fw-bold mb-0" style="color: var(--navy);">Register Member</h4>
                </div>

                <div class="auth-body">
                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Nama Anda"
                                       required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alamat Email</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="email@domain.com"
                                       required>

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. WhatsApp</label>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="08123xxx"
                                       required>

                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="********"
                                       required>

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       placeholder="********"
                                       required>

                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-register w-100 shadow-sm">
                            DAFTAR SEKARANG
                        </button>
                    </form>
                </div>

                <div class="auth-footer text-center">
                    <span class="text-muted">Sudah punya akun? </span>
                    <a href="{{route ('login')}}" class="fw-bold text-decoration-none" style="color: var(--navy);">Login Disini</a>
                    <div class="mt-2">
                        <a href="/" class="small text-muted text-decoration-none"><i class="fas fa-home me-1"></i> Kembali ke Beranda</a>
                    </div>
                </div>
            </div>

            <p class="text-center mt-3 text-white-50 small">
                &copy; {{ date('Y') }} Mojowarno Outdoor Rental.
            </p>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>