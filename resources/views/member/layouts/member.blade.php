<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Mojowarno Outdoor</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    {{-- <link rel="stylesheet" href="{{ asset('css/member.css') }}"> --}}
    <style>
        :root {
            --navy: #0c2140;
            --light-blue: #9ac1f8;
            --white: #ffffff;
        }

        body {
            font-family: "Segoe UI", Roboto, sans-serif;
            background-color: #f8f9fa;
            color: var(--navy);
        }

        /* Navbar */
        .navbar-user {
            background-color: var(--navy) !important;
            padding: 12px 0;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        }

        .navbar-user .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            transition: 0.3s;
        }

        .navbar-user .nav-link:hover {
            color: var(--light-blue) !important;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: var(--navy);
                padding: 20px;
                border-radius: 0 0 15px 15px;
                margin-top: 10px;
            }

            .navbar-nav .nav-link {
                padding: 10px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
        }

        .avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--light-blue);
        }

        .navbar-user .dropdown-toggle {
            color: var(--white) !important;
        }

        .navbar-user .navbar-brand h4 {
            color: var(--white) !important;
        }

        /* Sections */
        .section-title {
            font-weight: 800;
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 30px;
            display: inline-block;
        }

        .section-title::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 4px;
            background-color: var(--light-blue);
        }

        /* Footer */
        .footer-user {
            background-color: var(--navy);
            color: var(--white);
            padding: 30px 0;
        }

        .product-detail-img {
            border-radius: 20px;
            object-fit: cover;
            width: 100%;
            height: 450px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .category-badge {
            background-color: var(--light-blue);
            color: var(--navy);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 15px;
            border-radius: 50px;
        }

        .price-tag {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--navy);
        }

        .description-box {
            line-height: 1.8;
            color: #555;
            background: white;
            padding: 20px;
            border-radius: 15px;
            border-left: 5px solid var(--light-blue);
        }

        @media (max-width: 768px) {
            .product-detail-img {
                height: 300px;
            }
        }

        .input-group-text {
            border: none;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(15%) sepia(50%) saturate(1000%) hue-rotate(200deg);
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
        }
    </style>
</head>
<body>

    @include('member.partials.member_header')


    <main>
        @yield('content')
    </main>
    <div class="modal fade" id="modalTerms" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">
                <div class="modal-header border-0 text-white" style="background-color: var(--navy); border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-file-contract me-2"></i> Syarat & Ketentuan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        @foreach($terms as $item)
                        <div class="col-12 mb-3">
                            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                                <img src="{{ asset('storage/'.$item->image) }}" class="img-fluid">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center bg-light" style="border-radius: 0 0 20px 20px;">
                    <button type="button" class="btn btn-navy px-5 fw-bold" data-bs-dismiss="modal" style="background-color: var(--navy); color: white; border-radius: 10px;">
                        saya mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('member.partials.member_footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        setInterval(function() {
            fetch('/stock-data')
                .then(response => response.json())
                .then(data => {
                    data.forEach(product => {

                        // Update badge di HOME (card kecil)
                        let el = document.getElementById('stock-' + product.id);
                        if (el) {
                            el.innerText = product.stock > 0
                                ? 'Stok: ' + product.stock
                                : 'Stok: Habis';
                        }

                        // Update badge di DETAIL
                        let badge = document.getElementById('stock-badge-' + product.id);
                        if (badge) {
                            if (product.stock > 0) {
                                badge.classList.remove('bg-danger');
                                badge.classList.add('bg-success');
                                badge.innerText = 'Stok Tersedia: ' + product.stock;
                            } else {
                                badge.classList.remove('bg-success');
                                badge.classList.add('bg-danger');
                                badge.innerText = 'Stok Habis';
                            }
                        }

                        // Disable tombol kalau stok habis
                        let btn = document.querySelector('form[action*="/cart/add/'+product.id+'"] button');
                        if (btn) {
                            if (product.stock <= 0) {
                                btn.setAttribute('disabled', true);
                                btn.classList.add('disabled');
                            } else {
                                btn.removeAttribute('disabled');
                                btn.classList.remove('disabled');
                            }
                        }

                    });
                });
        }, 2000);
        </script>
</body>
</html>