@extends('member.layouts.member')

@section('title', 'Beranda')

@section('content')
<div id="homeSlider" class="carousel slide carousel-fade shadow-sm" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach($sliders as $key => $slider)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/'.$slider->image) }}" class="d-block w-100" style="height: 450px; object-fit: cover; filter: brightness(70%);">
                <div class="carousel-caption d-none d-md-block text-start mb-5 pb-5">
                    <h1 class="display-4 fw-bold">{{ $slider->title }}</h1>
                    <p class="fs-5">{{ $slider->subtitle }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="container my-5 py-4" id="produk">
    <h3 class="section-title">Katalog Alat Outdoor</h3>
    <div class="row g-4 mt-2">
        @foreach($products as $product)
        <div class="col-12 col-md-4 col-lg-3">
            <div class="card card-custom h-100 p-2 position-relative shadow-sm border-0">

                <div id="carouselProduct{{ $product->id }}" class="carousel slide" data-bs-interval="false">
                    <div class="carousel-inner rounded">
                        @forelse($product->images as $index => $img)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/'.$img->image) }}" class="d-block w-100" style="height: 200px; object-fit: cover;">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img src="https://via.placeholder.com/300" class="d-block w-100" style="height: 200px; object-fit: cover;">
                            </div>
                        @endforelse
                    </div>
                    <span class="position-absolute top-0 start-0 m-2 badge rounded-pill bg-white text-dark shadow-sm" style="z-index: 5;">
                        <span id="stock-{{ $product->id }}">
                            Stok: {{ $product->stock }}
                        </span>
                    </span>
                </div>

                <div class="card-body px-2">
                    <a href="{{ route('products.show', $product->id) }}" class="stretched-link"></a>

                    <h6 class="fw-bold mb-1 mt-2 text-navy">{{ $product->name }}</h6>
                    <p class="text-muted small mb-3">{{ Str::limit($product->description, 45) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">Rp{{ number_format($product->price_per_day) }}<small class="text-muted">/hari</small></span>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 pb-3">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="position-relative" style="z-index: 11;">
                        @csrf
                        <button type="submit" class="btn btn-navy w-100 py-2 shadow-sm fw-bold">
                            <i class="fas fa-shopping-cart me-2"></i> Sewa Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    </div>
    <div class="text-center mt-5">
        <a href="{{ route('products.all') }}" class="btn btn-outline-navy px-5 py-3 shadow-sm rounded-pill fw-bold transition-all">
            Semua Produk <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
</div>

<div class="container my-5 py-4" id="content">
    <h3 class="section-title">Promo & Partner</h3>
    <div class="row g-4 mt-2">
        @foreach($contents as $content)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-custom h-100 overflow-hidden">
                    <img src="{{ asset('storage/'.$content->image) }}" class="w-100" style="height: 180px; object-fit: cover;">
                    <div class="card-body bg-white">
                        <h6 class="fw-bold mb-3">{{ $content->title }}</h6>
                        <a href="{{ $content->redirect_url }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-4">Selengkapnya</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="container my-5 py-4" id="lokasi">
    <h3 class="section-title">Lokasi Toko</h3>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="ratio ratio-21x9" style="min-height: 350px;">
            <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.241513222851!2d112.2882899740523!3d-7.657143475765664!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78696328372697%3A0xc33177699f7d264!2sMojowarno%20Outdoor!5e0!3m2!1sid!2sid!4v1715832000000!5m2!1sid!2sid"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        var modalEl = document.getElementById('modalTerms');
        if (modalEl) {
            var myModal = new bootstrap.Modal(modalEl);
            myModal.show();
        }
    });
</script>
@endpush