@extends('member.layouts.member')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div class="container my-5 py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('home')}}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
            <a href="{{route('home')}}" class="btn btn-outline-navy btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </nav>

    <div class="row g-5">
        <div class="col-lg-6">
            <div id="carouselDetail" class="carousel slide shadow-sm rounded-4 overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @forelse($product->images as $index => $img)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/'.$img->image) }}" class="product-detail-img" alt="{{ $product->name }}">
                        </div>
                    @empty
                        <div class="carousel-item active">
                            <img src="https://via.placeholder.com/600x450" class="product-detail-img" alt="no-image">
                        </div>
                    @endforelse
                </div>

                @if($product->images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselDetail" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon p-3" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselDetail" data-bs-slide="next">
                        <span class="carousel-control-next-icon p-3" aria-hidden="true"></span>
                    </button>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <div class="ps-lg-3">
                <span class="category-badge mb-3 d-inline-block">
                    <i class="fas fa-tag me-1"></i> {{ $product->category->name ?? 'Outdoor Gear' }}
                </span>

                <h2 class="fw-bold mb-2" style="color: var(--navy);">{{ $product->name }}</h2>

                <div class="d-flex align-items-center mb-4">
                    <div class="price-tag me-3">
                        Rp {{ number_format($product->price_per_day) }} <span class="fs-6 fw-normal text-muted">/ hari</span>
                    </div>
                    <span id="stock-badge-{{ $product->id }}"
                        class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }} py-2 px-3">
                      {{ $product->stock > 0 ? 'Stok Tersedia: '.$product->stock : 'Stok Habis' }}
                  </span>
                </div>

                <h6 class="fw-bold mb-2"><i class="fas fa-align-left me-2 text-primary"></i>Deskripsi Produk</h6>
                <div class="description-box mb-4 shadow-sm">
                    {!! nl2br(e($product->description)) !!}
                </div>

                <div class="card border-0 shadow-sm p-3 bg-light rounded-4">
                    <div class="row align-items-center">
                        <div class="col-md-7 mb-3 mb-md-0 text-muted small">
                            <i class="fas fa-info-circle me-1"></i> Pastikan Anda sudah membaca Syarat & Ketentuan sebelum menyewa.
                        </div>
                        <div class="col-md-5">
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-navy w-100 py-3 fw-bold shadow {{ $product->stock <= 0 ? 'disabled' : '' }}"
                                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-cart-plus me-2"></i> Tambah ke Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection