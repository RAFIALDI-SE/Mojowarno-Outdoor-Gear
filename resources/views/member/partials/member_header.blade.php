<nav class="navbar navbar-expand-lg navbar-user sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="{{ asset('storage/MojowarnoGearOutdoor.jpeg') }}" alt="Logo" height="35" class="me-2 rounded">
            <h4 class="mb-0 fw-bold text-uppercase" style="font-size: 1.1rem; color: var(--white);">Mojowarno Outdoor</h4>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#userNav">
            <i class="fas fa-bars" style="color: var(--white); font-size: 1.5rem;"></i>
        </button>

        <div class="collapse navbar-collapse" id="userNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="#produk">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="#content">Promo</a></li>
                <li class="nav-item"><a class="nav-link" href="#lokasi">Lokasi</a></li>
            </ul>

            <div class="dropdown mt-3 mt-lg-0">
                @auth
                @php
                    $user = Auth::user();
                @endphp

                <a class="d-flex align-items-center text-decoration-none dropdown-toggle text-white"
                href="#" data-bs-toggle="dropdown">
                    <img src="{{ $user->avatar
                        ? asset('storage/'.$user->avatar)
                        : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
                        class="avatar me-2">

                    <span class="fw-bold d-none d-md-inline">{{ $user->name }}</span>
                </a>
                @endauth


                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3 mt-2">
                    <li><a class="dropdown-item py-2" href="#"><i class="fas fa-user-circle me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item py-2" href="#"><i class="fas fa-shopping-cart me-2"></i> Keranjang</a></li>
                    <li><a class="dropdown-item py-2" href="#"><i class="fas fa-history me-2"></i> Riwayat Sewa</a></li>

                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#modalTerms">
                        <i class="fas fa-file-contract me-2"></i> Syarat & Ketentuan</a>
                    </li>

                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger py-2">
                                <i class="fas fa-sign-out-alt me-2"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>