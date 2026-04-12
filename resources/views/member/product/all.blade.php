@extends('member.layouts.member')

@section('title', 'Katalog Produk')

@section('content')
<div style="background-color: var(--navy); height: 120px;"></div>

<div class="container mb-5" style="margin-top: -50px;">
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-4" style="color: var(--navy);">Cari Alat Outdoor</h3>
            <form method="GET" action="{{ route('products.all') }}" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-0 bg-light py-2"
                               placeholder="Mau sewa apa hari ini?" value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-filter text-muted"></i></span>
                        <select name="category" class="form-select border-0 bg-light py-2">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-navy w-100 py-2 fw-bold shadow-sm">
                        CARI
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h3 class="section-title mb-0">Katalog Lengkap</h3>
        <a href="{{ route('home') }}" class="btn btn-outline-navy px-4 py-2 rounded-pill shadow-sm fw-bold">
            <i class="fas fa-arrow-left me-2"></i> KEMBALI
        </a>
    </div>

    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-12 col-md-4 col-lg-3">
                <div class="card card-custom h-100 p-2 position-relative shadow-sm border-0">

                    <div id="carouselAll{{ $product->id }}" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-inner rounded">
                            @forelse($product->images as $index => $img)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/'.$img->image) }}"
                                         class="d-block w-100"
                                         style="height: 200px; object-fit: cover;">
                                </div>
                            @empty
                                <div class="carousel-item active">
                                    <img src="https://via.placeholder.com/300" class="d-block w-100" style="height: 200px; object-fit: cover;">
                                </div>
                            @endforelse
                        </div>

                        @if($product->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselAll{{ $product->id }}" data-bs-slide="prev" style="z-index: 11;">
                                <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 20px;"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselAll{{ $product->id }}" data-bs-slide="next" style="z-index: 11;">
                                <span class="carousel-control-next-icon" aria-hidden="true" style="width: 20px;"></span>
                            </button>
                        @endif

                        <span class="position-absolute top-0 start-0 m-2 badge rounded-pill bg-white text-dark shadow-sm" style="z-index: 5;">
                            <span id="stock-{{ $product->id }}">
                                Stok: {{ $product->stock }}
                            </span>
                        </span>
                    </div>

                    <div class="card-body px-2">
                        <a href="#" class="stretched-link"></a>

                        <h6 class="fw-bold mb-1 mt-2 text-navy">{{ $product->name }}</h6>
                        <p class="text-muted small mb-3">{{ Str::limit($product->description, 45) }}</p>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">Rp{{ number_format($product->price_per_day) }}<small class="text-muted">/hari</small></span>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 pb-3">
                        <form action="#" method="POST" class="position-relative" style="z-index: 11;">
                            @csrf
                            <button type="submit" class="btn btn-navy w-100 py-2 shadow-sm fw-bold">
                                <i class="fas fa-shopping-cart me-2"></i> Sewa Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <img src="https://illustrations.popsy.co/blue/searching.svg" style="width: 200px;" class="mb-4">
                <h5 class="text-muted">Maaf, produk "{{ request('search') }}" tidak ditemukan.</h5>
                <a href="{{ route('products.all') }}" class="btn btn-outline-navy mt-3">Lihat Semua Produk</a>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5 pagination-navy">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection