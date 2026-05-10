@extends('admin.layouts.dashboard')

@section('content')
<div style="background-color: var(--navy); min-height: 120px; padding-top: 30px; margin: -25px -25px 0 -25px;">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="text-light-blue fw-bold m-0"><i class="fas fa-undo me-2 text-light-blue"></i> Proses Pengembalian</h3>
            <a href="{{ route('admin.returns') }}" class="btn btn-light-blue btn-sm rounded-pill px-3 fw-bold">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="container-fluid mb-5" style="margin-top: -40px;">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-custom h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 d-flex align-items-center" style="color: var(--navy)">
                        <span class="bg-primary bg-opacity-10 p-2 rounded-3 me-2 text-primary">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        Info Transaksi
                    </h5>

                    <div class="bg-light p-3 rounded-4 mb-3 border-start border-4 border-primary shadow-sm">
                        <div class="profile-info-label">Penyewa</div>
                        <div class="profile-info-value mb-0 fs-5 text-navy">{{ $transaction->user->name }}</div>
                    </div>

                    <div class="bg-light p-3 rounded-4 mb-3 border-start border-4 border-danger shadow-sm">
                        <div class="profile-info-label">Jadwal Pengembalian</div>
                        <div class="profile-info-value mb-0 text-danger">
                            <i class="far fa-clock me-1"></i> {{ $transaction->return_datetime->format('d M Y, H:i') }}
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded-4 bg-white border border-dashed border-primary text-center">
                        <div class="small text-muted mb-1 text-uppercase fw-bold">Total Transaksi</div>
                        <div class="h4 fw-bold text-navy mb-0">Rp {{ number_format($transaction->total_price) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-custom border-0 shadow-sm">
                <div class="card-body p-4">
                    @if($transaction->payment_status == 'half')
<div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded-4 shadow-sm"
     style="background-color: #f0f7ff; border-left: 5px solid #9ac1f8;">

    <div class="fw-semibold" style="color: #0c2140;">
        <i class="fas fa-exclamation-circle me-2" style="color:#9ac1f8;"></i>
        Belum Lunas (DP)
    </div>

    <form method="POST" action="{{ route('admin.returns.pay', $transaction) }}">
        @csrf
        <button class="btn btn-sm fw-bold"
                style="background-color:#0c2140; color:#fff;">
            Lunasi Sekarang
        </button>
    </form>
</div>

@elseif($transaction->payment_status == 'paid')
<div class="mb-4 p-3 rounded-4 shadow-sm d-flex align-items-center"
     style="background-color: #f8f9fa; border-left: 5px solid #0c2140;">

    <div class="fw-semibold" style="color:#0c2140;">
        <i class="fas fa-check-circle me-2"></i>
        Pembayaran Sudah Lunas
    </div>
</div>
@endif
                    <h5 class="fw-bold mb-4 d-flex align-items-center" style="color: var(--navy)">
                        <span class="bg-primary bg-opacity-10 p-2 rounded-3 me-2 text-primary">
                            <i class="fas fa-clipboard-check"></i>
                        </span>
                        Cek Kelengkapan Barang
                    </h5>

                    <div class="table-responsive">
                        <table class="table align-middle table-items">
                            <thead class="bg-light">
                                <tr class="small text-muted text-uppercase">
                                    <th class="ps-3">Alat</th>
                                    <th class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $item)
                                <tr class="border-bottom">
                                    <td data-label="Alat" class="py-3 ps-3">
                                        <div class="d-flex align-items-center">
                                            @php
                                                $firstImage = $item->product->images->first();
                                            @endphp
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ $firstImage ? asset('storage/' . $firstImage->image) : 'https://via.placeholder.com/100' }}"
                                                     class="rounded-3 shadow-sm border"
                                                     style="width: 65px; height: 65px; object-fit: cover; border: 2px solid var(--light-blue) !important;">
                                            </div>
                                            <div>
                                                <div class="fw-bold text-navy">{{ $item->product->name }}</div>
                                                <small class="text-muted">ID: #PROD-{{ $item->product_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Jumlah" class="text-center">
                                        <span class="fw-bold text-navy">{{ $item->qty }} Unit</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="description-box mt-4 p-4 border-0 shadow-sm" style="background-color: #f0f7ff; border-left: 5px solid var(--navy) !important;">
                        <h6 class="fw-bold text-navy mb-4 text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-edit me-2"></i>Laporan Kondisi Barang Kembali
                        </h6>

                        <form method="POST" action="{{ route('admin.returns.confirm', $transaction) }}">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="profile-info-label fw-bold">Kondisi Fisik</label>
                                    <select name="condition" class="form-select form-select-lg border-0 shadow-sm fw-bold mt-1">
                                        <option value="aman">Semua Aman & Lengkap</option>
                                        <option value="rusak">Ada Kerusakan</option>
                                        <option value="hilang">Ada Yang Hilang</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="profile-info-label fw-bold">Total Denda (Rp)</label>
                                    <input type="number" name="fine" class="form-control form-control-lg border-0 shadow-sm fw-bold mt-1"
                                           placeholder="0" value="0" min="0">
                                    <small class="text-muted">Isi 0 jika tidak ada denda.</small>
                                </div>
                                <div class="col-12">
                                    <label class="profile-info-label fw-bold">Catatan Kerusakan/Kehilangan</label>
                                    <textarea name="notes" class="form-control border-0 shadow-sm mt-1" rows="3"
                                              placeholder="Tulis detail jika ada barang rusak atau hilang..."></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-navy btn-lg w-100 shadow fw-bold transition-all py-3">
                                        SELESAIKAN PENGEMBALIAN <i class="fas fa-check-circle ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection