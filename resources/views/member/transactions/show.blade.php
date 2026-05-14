@extends('member.layouts.member')

@section('title', 'Detail Transaksi ' . $transaction->transaction_code)

@section('content')
<div style="background-color: var(--navy); min-height: 180px; padding-top: 50px;">
    <div class="container text-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 text-center text-md-start">
            <div>
                <h3 class="fw-bold mb-1"><i class="fas fa-file-invoice-dollar me-2 text-light-blue"></i> Detail Transaksi</h3>
                <p class="small opacity-75 mb-0">Simpan halaman ini sebagai bukti sewa yang sah.</p>
            </div>
            <a href="{{route('transactions.index')}}" class="btn btn-outline-light btn-sm rounded-pill px-4 fw-bold shadow-sm">
                <i class="fas fa-chevron-left me-1"></i> Kembali ke Riwayat
            </a>
        </div>
    </div>
</div>

<div class="container mb-5" style="margin-top: -30px;">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 py-3 px-4 text-center text-md-start">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                        <h5 class="fw-bold mb-0 text-navy">{{ $transaction->transaction_code }}</h5>
                        <span class="badge-status
                            {{ $transaction->status == 'pending' ? 'bg-pending' :
                            ($transaction->status == 'cancelled' ? 'bg-unpaid' : 'bg-success-custom') }}">
                            {{ $transaction->status }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-3 p-md-4 pt-0">
                    <div class="row g-2 mb-4 p-3 bg-light rounded-4 mx-0">
                        <div class="col-6 col-md-4 border-end-md">
                            <label class="small text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 0.6rem;">Ambil</label>
                            <span class="fw-bold text-navy small"><i class="far fa-calendar-check me-1 text-primary"></i> {{ $transaction->pickup_datetime->format('d M Y') }}</span>
                        </div>
                        <div class="col-6 col-md-4 border-end-md">
                            <label class="small text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 0.6rem;">Kembali</label>
                            <span class="fw-bold text-navy small"><i class="far fa-calendar-times me-1 text-danger"></i> {{ $transaction->return_datetime->format('d M Y') }}</span>
                        </div>
                        <div class="col-12 col-md-4 mt-2 mt-md-0 text-center">
                            <label class="small text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 0.6rem;">Durasi</label>
                            <span class="fw-bold text-navy small">{{ $transaction->total_days }} Hari</span>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-navy"><i class="fas fa-boxes me-2 text-primary"></i> Daftar Alat yang Disewa</h6>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-muted text-uppercase">
                                    <th class="ps-3" style="min-width: 200px;">Alat</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end pe-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $item)
                                <tr class="border-bottom">
                                    <td class="ps-3 py-3">
                                        <div class="d-flex align-items-center">
                                            @php $firstImage = $item->product->images->first(); @endphp
                                            <img src="{{ $firstImage ? asset('storage/' . $firstImage->image) : 'https://via.placeholder.com/100' }}"
                                                 class="rounded-3 shadow-sm border flex-shrink-0"
                                                 style="width: 50px; height: 50px; object-fit: cover;">

                                            <div class="ms-3">
                                                <div class="fw-bold text-navy small text-wrap" style="max-width: 150px;">{{ $item->product->name }}</div>
                                                <small class="text-muted d-block" style="font-size: 0.7rem;">Rp {{ number_format($item->price_per_day) }} / hari</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold text-navy">
                                        {{ $item->qty }} <span class="small fw-normal text-muted">Unit</span>
                                    </td>
                                    <td class="text-end pe-3 fw-bold text-primary">
                                        Rp {{ number_format($item->subtotal) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 p-3 rounded-4" style="background-color: var(--navy); color: white;">
                        <span class="fw-bold small">TOTAL BAYAR</span>
                        <h4 class="fw-bold mb-0 text-light-blue" style="font-size: 1.25rem;">Rp {{ number_format($transaction->total_price) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 text-center mb-4 overflow-hidden">
                <div class="card-header py-3" style="background-color: var(--light-blue); color: var(--navy);">
                    <h6 class="fw-bold mb-0 text-uppercase small" style="letter-spacing: 1px;">QR Check-In</h6>
                </div>
                <div class="card-body p-4">
                    @if($transaction->qrCode)
                        <div class="bg-white p-3 d-inline-block rounded-4 shadow-sm mb-3 border border-light">
                            {!! QrCode::size(160)->generate($transaction->qrCode->code) !!}
                        </div>
                        <h6 class="fw-bold text-navy mb-1">{{ $transaction->qrCode->code }}</h6>
                        <p class="small text-muted mb-0" style="font-size: 0.75rem;">Tunjukkan QR ini ke admin toko saat mengambil atau mengembalikan alat.</p>
                    @endif
                </div>
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Pembayaran</span>
                        <span class="badge-status {{ $transaction->payment_status == 'unpaid' ? 'bg-unpaid' : 'bg-paid' }}">
                            {{ $transaction->payment_status }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-light">
                <div class="card-body p-4 text-start">
                    <h6 class="fw-bold mb-3 text-navy small"><i class="fas fa-info-circle me-1 text-primary"></i> Informasi Penting</h6>
                    <ul class="text-muted ps-3 mb-0" style="font-size: 0.75rem; line-height: 1.6;">
                        <li>Bawa kartu identitas asli (KTP/SIM).</li>
                        <li>Cek kondisi alat bersama admin toko.</li>
                        <li>Denda keterlambatan berlaku per hari.</li>
                    </ul>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted small fw-bold opacity-75">
                    <i class="fas fa-home me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection