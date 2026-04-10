<nav id="sidebar">
    <div class="sidebar-header d-flex align-items-center px-4 py-4">
        <img src="{{ url('storage/MojowarnoGearOutdoor.jpeg') }}" alt="Logo" width="35" class="me-2">

        <h4 class="fw-bold m-0" style="color: var(--light-blue); letter-spacing: 1px; font-size: 1.2rem;">
            OUTDOOR<span class="text-white">RENT</span>
        </h4>
    </div>

    <ul class="list-unstyled components mt-2">

        <li class="{{ request()->is('categories*') ? 'active' : '' }}">
            <a href="{{route('categories.index')}}"><i class="fas fa-tags"></i> Kategori</a>
        </li>

        <li class="{{ request()->is('products*') ? 'active' : '' }}">
            <a href="{{route('products.index')}}"><i class="fas fa-mountain"></i> Produk</a>
        </li>

        <li class="{{ request()->is('contents*') ? 'active' : '' }}">
            <a href="{{route('contents.index')}}"><i class="fas fa-edit"></i> Konten</a>
        </li>

        <li class="{{ request()->is('terms*') ? 'active' : '' }}">
            <a href="{{route('terms.index')}}"><i class="fas fa-book"></i> Syarat & Ketentuan</a>
        </li>

        <hr class="mx-3" style="color: rgba(255,255,255,0.1)">

        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </form>
        </li>
    </ul>
</nav>