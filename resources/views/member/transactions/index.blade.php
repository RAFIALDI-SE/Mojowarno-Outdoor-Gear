@extends('member.layouts.member')

@section('title', 'Riwayat Transaksi')

@section('content')
<div style="background-color: var(--navy); min-height: 160px; padding-top: 40px;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <div>
                <h3 class="text-white fw-bold mb-1" style="font-size: calc(1.1rem + 0.6vw);">
                    <i class="fas fa-history me-2 text-light-blue"></i> Riwayat Transaksi
                </h3>
                <p class="text-white-50 small mb-0 d-none d-md-block">Pantau status sewa dan pembayaran alat camping Anda.</p>
            </div>
            <!-- Hilangkan class d-none d-md-inline-block agar muncul di HP -->
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold flex-shrink-0">
                <i class="fas fa-home me-1"></i> <span class="d-none d-sm-inline">Beranda</span>
            </a>
        </div>
    </div>
</div>

<div class="container mb-5" style="margin-top: -50px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            @forelse($transactions as $trx)
            <div class="card trx-card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3 p-md-4">
                    <div class="row align-items-center">

                        <div class="col-12 col-md-4 mb-3 mb-md-0 text-center text-md-start">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="trx-icon me-3 shadow-sm flex-shrink-0">
                                    <i class="fas fa-file-invoice fa-lg"></i>
                                </div>
                                <div class="text-start">
                                    <h6 class="fw-bold mb-0 text-navy">{{ $trx->transaction_code }}</h6>
                                    <small class="text-muted small">ID Transaksi</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 mb-3 mb-md-0 text-center border-start-md">
                            <div class="small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem;">Periode Sewa</div>
                            <div class="text-navy fw-semibold d-flex align-items-center justify-content-center">
                                <span class="bg-light px-2 py-1 rounded small">{{ $trx->pickup_datetime->format('d/m/y') }}</span>
                                <i class="fas fa-arrow-right mx-2 text-primary small"></i>
                                <span class="bg-light px-2 py-1 rounded small">{{ $trx->return_datetime->format('d/m/y') }}</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 text-center text-md-end border-start-md">
                            <div class="fw-bold fs-5 text-navy mb-2">
                                Rp {{ number_format($trx->total_price) }}
                            </div>
                            <div class="d-flex gap-2 justify-content-center justify-content-md-end">
                                <span class="badge-status
                                    {{ $trx->status == 'pending' ? 'bg-pending' :
                                    ($trx->status == 'cancelled' ? 'bg-unpaid' : 'bg-success-custom') }}">
                                    {{ $trx->status }}
                                </span>
                                <span class="badge-status {{ $trx->payment_status == 'unpaid' ? 'bg-unpaid' : 'bg-paid' }}">
                                    <i class="fas fa-wallet me-1"></i> {{ $trx->payment_status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3 opacity-25">

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted" style="font-size: 0.75rem;">
                            <i class="far fa-clock me-1"></i> {{ $trx->created_at->format('d M, H:i') }}
                        </span>
                        <a href="{{ route('transactions.show', $trx->id) }}" class="btn btn-navy btn-sm px-4 rounded-pill fw-bold shadow-sm">
                            DETAIL <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="card border-0 shadow-sm rounded-4 text-center py-5 mt-4">
                <div class="card-body">
                    <img src="https://illustrations.popsy.co/blue/online-registration.svg" style="width: 150px;" class="mb-4">
                    <h5 class="text-muted fw-bold">Belum Ada Transaksi</h5>
                    <a href="{{ route('products.all') }}" class="btn btn-navy px-4 rounded-pill mt-3 shadow">Mulai Sewa</a>
                </div>
            </div>
            @endforelse

        </div>
    </div>
</div>
@endsection