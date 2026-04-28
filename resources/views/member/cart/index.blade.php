@extends('member.layouts.member')

@section('title', 'Keranjang Sewa')

@section('content')
<div style="background-color: var(--navy); height: 120px;"></div>

@php $total = 0; @endphp


    {{-- ALERT NOTIFICATION --}}
    @if(session('error'))
        <div class="container" style="margin-top:-30px;">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="container" style="margin-top:-30px;">
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="container" style="margin-top:-30px;">
            <div class="alert alert-danger shadow-sm rounded-4">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
@endif

<div class="container mb-5" style="margin-top: -50px;">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4" style="color: var(--navy);">
                        <i class="fas fa-shopping-cart me-2"></i> Keranjang Sewa
                    </h4>

                    @if($cart->items->count())
                    <table class="table align-middle table-cart">
                        <thead class="text-muted small text-uppercase d-none d-md-table-header-group">
                            <tr>
                                <th>Produk</th>
                                <th>Harga/Hari</th>
                                <th width="120">Jumlah</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart->items as $item)
                            @php
                                $subtotal = $item->qty * $item->price_per_day;
                                $total += $subtotal; // Akumulasi total
                            @endphp
                            <tr>
                                <td data-label="Produk">
                                    <div class="d-flex align-items-center cart-item-info">
                                        <img src="{{ $item->product->images->first() ? asset('storage/'.$item->product->images->first()->image) : 'https://via.placeholder.com/100' }}"
                                             class="rounded-3 me-3" style="width: 70px; height: 70px; object-fit: cover;">
                                        <div>
                                            <h6 class="fw-bold mb-0 text-navy">{{ $item->product->name }}</h6>
                                            <small class="text-muted">Stok: {{ $item->product->stock }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Harga/Hari" class="fw-semibold text-nowrap">
                                    Rp {{ number_format($item->price_per_day) }}
                                </td>
                                <td data-label="Jumlah Alat">
                                    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="d-inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="qty" value="{{ $item->qty }}"
                                               min="1" max="{{ $item->product->stock }}"
                                               class="form-control form-control-sm rounded-pill text-center border-0 bg-light"
                                               style="width: 80px;"
                                               onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td data-label="Subtotal" class="fw-bold text-primary text-nowrap">
                                    Rp {{ number_format($subtotal) }}
                                </td>
                                <td class="text-md-end">
                                    <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-light btn-sm rounded-circle text-danger shadow-sm border-0">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center py-5">
                        <img src="https://illustrations.popsy.co/blue/shopping-cart.svg" style="width: 150px;" class="mb-4">
                        <h5 class="text-muted">Keranjangmu masih kosong nih...</h5>
                        <a href="{{ route('products.all') }}" class="btn btn-navy mt-3 rounded-pill px-4">Cari Alat Sekarang</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4" style="color: var(--navy);">Ringkasan Sewa</h5>

                    <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-3 rounded-3">
                        <span class="small fw-bold text-muted text-uppercase">Durasi Sewa:</span>
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            <span id="rental-days-count">1</span> Hari
                        </span>
                    </div>

                    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Pilih Tanggal</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="date" class="form-control form-control-sm border-0 bg-light rounded-3"
                                           id="start_date" name="pickup_date" required min="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-6">
                                    <input type="date" class="form-control form-control-sm border-0 bg-light rounded-3"
                                           id="end_date" name="return_date" required min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <div id="date-warning" class="small text-danger mt-2 d-none">
                            Tanggal tidak boleh sama
                        </div>

                        <hr class="opacity-25">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total / Hari</span>
                            <span class="fw-bold text-secondary">Rp {{ number_format($total) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted text-nowrap">Grand Total</span>
                            <h4 class="fw-bold mb-0" style="color: var(--navy);">
                                Rp <span id="grand-total-display">{{ number_format($total) }}</span>
                            </h4>
                        </div>

                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 mb-4">
                            <p class="small text-navy mb-0">
                                <i class="fas fa-info-circle me-1"></i> Biaya dihitung otomatis saat tanggal dipilih.
                            </p>
                        </div>

                        <button type="submit" id="checkout-btn"
                            class="btn btn-navy w-100 py-3 fw-bold shadow-sm rounded-pill"
                            disabled> CHECKOUT <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </form>

                    <a href="{{ route('products.all') }}" class="btn btn-link w-100 text-decoration-none text-muted mt-2 small text-center">
                        <i class="fas fa-plus me-1"></i> Tambah Alat Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const checkoutBtn = document.getElementById('checkout-btn'); // Ambil element tombol
    const daysDisplay = document.getElementById('rental-days-count');
    const totalDisplay = document.getElementById('grand-total-display');
    const totalPerDay = {{ $total ?? 0 }};

    function calculate() {
    const warning = document.getElementById('date-warning');

    if (!startDateInput.value || !endDateInput.value) {
        checkoutBtn.disabled = true;
        daysDisplay.innerText = '0';
        totalDisplay.innerText = new Intl.NumberFormat('id-ID').format(0);
        warning.classList.add('d-none');
        return;
    }

    // ✅ Cek langsung dari value (AMAN)
    if (startDateInput.value === endDateInput.value) {
        checkoutBtn.disabled = true;

        daysDisplay.innerText = '0';
        totalDisplay.innerText = new Intl.NumberFormat('id-ID').format(0);

        warning.classList.remove('d-none');
        return;
    } else {
        warning.classList.add('d-none');
    }

    const start = new Date(startDateInput.value);
    const end = new Date(endDateInput.value);

    if (end < start) {
        alert('Tanggal selesai tidak boleh sebelum tanggal mulai!');
        endDateInput.value = '';
        checkoutBtn.disabled = true;
        return;
    }

    const diffTime = end - start;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    daysDisplay.innerText = diffDays;
    totalDisplay.innerText = new Intl.NumberFormat('id-ID').format(diffDays * totalPerDay);

    @if($cart->items->count() > 0)
        checkoutBtn.disabled = false;
    @endif
}

    startDateInput.addEventListener('change', calculate);
    endDateInput.addEventListener('change', calculate);

    @if(session('error') || $errors->any())
        window.scrollTo({ top: 0, behavior: 'smooth' });
    @endif
});


</script>
@endpush