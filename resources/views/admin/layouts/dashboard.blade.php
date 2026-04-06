<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Outdoor Rent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sliders.css') }}"> --}}
    <style>
        :root {
    --dark-navy: #0c2140;
    --light-blue: #9ac1f8;
    --white: #ffffff;
    --bg-light: #f8f9fa;
    }

    body {
        font-family: "Inter", sans-serif;
        background-color: var(--bg-light);
    }

    #sidebar {
        min-width: 280px;
        max-width: 280px;
        background: var(--dark-navy);
        color: #fff;
        min-height: 100vh;
        transition: all 0.3s;
    }

    #sidebar .sidebar-header {
        padding: 20px;
        background: rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
    }

    #sidebar .sidebar-header img {
        object-fit: contain;
    }

    #sidebar ul li a {
        padding: 15px 25px;
        display: block;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: 0.3s;
        border-left: 4px solid transparent;
    }

    #sidebar ul li a:hover,
    #sidebar ul li.active > a {
        color: var(--white) !important;
        background: rgba(255, 255, 255, 0.1);
        border-left: 4px solid var(--light-blue);
        font-weight: 600;
    }

    #sidebar ul li.active > a i {
        color: var(--light-blue);
    }

    @media (max-width: 768px) {
        #sidebar {
            margin-left: -280px;
            position: fixed;
            z-index: 999;
        }
        #sidebar.active {
            margin-left: 0;
        }
        #content {
            width: 100% !important;
        }
        .overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }
        .overlay.active {
            display: block;
        }
    }

    #sidebar, #content {
        transition: all 0.3s ease-in-out;
    }

    @media (max-width: 576px) {
        .p-4 { padding: 1rem !important; }
    }

    .btn-navy {
        background-color: var(--dark-navy);
        color: white;
    }
    .btn-navy:hover {
        background-color: #153560;
        color: white;
    }

    .table {
    border-collapse: separate;
    border-spacing: 0 10px;
    }

    .table thead th {
        background-color: var(--bg-light);
        border: none;
        color: var(--dark-navy);
        font-weight: 600;
        padding: 15px;
    }

    .table tbody tr {
        background-color: var(--white);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s;
    }

    .table tbody tr:hover {
        transform: scale(1.01);
    }

    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border: none;
    }

    .table tbody td:first-child {
        border-radius: 10px 0 0 10px;
    }
    .table tbody td:last-child {
        border-radius: 0 10px 10px 0;
    }

    @media (max-width: 576px) {
        .table td, .table th {
            padding: 10px 5px !important;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 768px) {
        .table td {
            padding: 8px 4px !important;
            font-size: 0.8rem;
        }
    }


    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>

        <div class="overlay" id="overlay"></div>

        <div class="d-flex">
            @include('admin.partials.sidebar')

            <div id="content" class="w-100">
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 px-4">
                    <div class="container-fluid px-0">
                        <button type="button" id="sidebarCollapse" class="btn border-0 d-md-none">
                            <i class="fas fa-bars fa-lg"></i>
                        </button>

                        @auth
                        <div class="ms-auto d-flex align-items-center">
                            <span class="me-3 fw-semibold d-none d-sm-inline">
                                Halo, {{ auth()->user()->name }}
                            </span>

                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0c2140&color=fff"
                                class="rounded-circle"
                                width="35">
                        </div>
                        @endauth
                    </div>
                </nav>

                <div class="p-4">
                    @yield('content')
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('overlay');
                const btn = document.getElementById('sidebarCollapse');

                if(btn) {
                    btn.addEventListener('click', function() {
                        sidebar.classList.toggle('active');
                        overlay.classList.toggle('active');
                    });
                }

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        @stack('scripts')
    </body>
</html>