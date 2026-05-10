@extends('admin.layouts.dashboard')

@section('content')
<div style="background-color: var(--navy); min-height: 120px; padding-top: 30px; margin: -25px -25px 0 -25px;">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="text-light-blue fw-bold m-0"><i class="fas fa-file-invoice me-2 text-light-blue"></i> Validasi Pengambilan</h3>
            <a href="{{ route('admin.pickups') }}" class="btn btn-light-blue btn-sm rounded-pill px-3 fw-bold">
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
                            <i class="fas fa-user-tag"></i>
                        </span>
                        Info Penyewa
                    </h5>

                    <div class="bg-light p-3 rounded-4 mb-3 border-start border-4 border-primary">
                        <div class="profile-info-label">Nama Member</div>
                        <div class="profile-info-value mb-0 fs-5">{{ $transaction->user->name }}</div>
                        <small class="text-muted"><i class="fas fa-phone-alt me-1"></i> {{ $transaction->user->phone ?? 'Tidak ada No.HP' }}</small>
                    </div>

                    <div class="p-3 rounded-4 bg-light border">
                        <div class="profile-info-label">Status Saat Ini</div>
                        <div class="mt-1">
                            <span class="badge-status {{ $transaction->status == 'pending' ? 'bg-pending' : 'bg-paid' }} w-100 text-center py-2">
                                {{ strtoupper($transaction->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded-4 border border-dashed border-primary text-center">
                        <div class="small text-muted mb-1 text-uppercase fw-bold">Durasi Sewa</div>
                        <div class="h4 fw-bold text-navy mb-0">{{ $transaction->total_days }} Hari</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-custom border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 d-flex align-items-center" style="color: var(--navy)">
                        <span class="bg-primary bg-opacity-10 p-2 rounded-3 me-2 text-primary">
                            <i class="fas fa-boxes"></i>
                        </span>
                        Daftar Alat
                    </h5>

                    <div class="table-responsive">
                        <table class="table align-middle table-items">
                            <thead class="bg-light">
                                <tr class="small text-muted text-uppercase">
                                    <th class="ps-3">Produk</th>
                                    <th class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $item)
                                <tr class="border-bottom">
                                    <td data-label="Produk" class="py-3 ps-3">
                                        <div class="d-flex align-items-center">
                                            @php
                                                $firstImage = $item->product->images->first();
                                            @endphp
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ $firstImage ? asset('storage/' . $firstImage->image) : 'https://via.placeholder.com/100' }}"
                                                     class="rounded-3 shadow-sm border"
                                                     style="width: 65px; height: 65px; object-fit: cover;">
                                            </div>
                                            <div>
                                                <div class="fw-bold text-navy">{{ $item->product->name }}</div>
                                                <small class="text-muted">Kategori: {{ $item->product->category->name ?? 'Gear' }}</small>
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
                        <form method="POST" action="{{ route('admin.pickups.confirm', $transaction) }}">
                            @csrf
                            <label class="fw-bold mb-3 text-navy text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">
                                <i class="fas fa-cash-register me-2"></i>Validasi Pembayaran Akhir
                            </label>
                            <div class="row g-3">
                                <div class="col-12 col-sm-8">
                                    <select name="payment_status" class="form-select form-select-lg border-0 shadow-sm fw-bold">
                                        <option value="paid" {{ $transaction->payment_status == 'paid' ? 'selected' : '' }}>LUNAS (100%)</option>
                                        <option value="half" {{ $transaction->payment_status == 'half' ? 'selected' : '' }}>DP (Baru Bayar Sebagian)</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <button type="submit" class="btn btn-navy btn-lg w-100 shadow fw-bold transition-all">
                                        VALIDASI <i class="fas fa-check-circle ms-1"></i>
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