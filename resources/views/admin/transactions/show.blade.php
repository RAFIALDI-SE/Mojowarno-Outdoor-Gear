@extends('admin.layouts.dashboard')

@section('content')
<div style="background-color: var(--navy); height: 120px; margin: -25px -25px 0 -25px;"></div>

<div class="container-fluid mb-5" style="margin-top: -60px;">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-navy">Detail Produk Disewa</h6>
                    <span class="badge-status bg-paid">{{ $transaction->transaction_code }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            @php $img = $item->product->images->first(); @endphp
                                            <img src="{{ $img ? asset('storage/'.$img->image) : 'https://via.placeholder.com/100' }}"
                                                 class="rounded-3 shadow-sm border me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-navy small">{{ $item->product->name }}</div>
                                                <small class="text-muted">Rp {{ number_format($item->price_per_day) }} / hari</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold">{{ $item->qty }}</td>
                                    <td class="text-end pe-4 fw-bold text-primary">Rp {{ number_format($item->subtotal) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-4 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-muted">TOTAL PEMBAYARAN</span>
                        <h3 class="fw-bold text-navy mb-0">Rp {{ number_format($transaction->total_price) }}</h3>
                    </div>
                </div>
            </div>

            @if($transaction->returnItem)
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border-left: 5px solid #28a745 !important;">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-success mb-4 text-uppercase" style="letter-spacing: 1px;"><i class="fas fa-undo-alt me-2"></i>Informasi Pengembalian</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="profile-info-label">Kondisi Saat Kembali</div>
                            <div class="profile-info-value fs-6 text-uppercase">{{ $transaction->returnItem->condition }}</div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="profile-info-label">Waktu Kembali</div>
                            <div class="profile-info-value fs-6">{{ \Carbon\Carbon::parse($transaction->returnItem->returned_at)->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="col-12 bg-light p-3 rounded-3">
                            <div class="profile-info-label">Catatan Admin</div>
                            <div class="text-muted small">{{ $transaction->returnItem->notes ?? 'Tidak ada catatan.' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="fw-bold text-muted small text-uppercase">Denda Kerusakan/Hilang</span>
                                <span class="badge bg-danger px-3 py-2 rounded-pill">Rp {{ number_format($transaction->returnItem->fine) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-center">
                    <img src="{{ $transaction->user->avatar
                                    ? asset('storage/'.$transaction->user->avatar)
                                    : 'https://ui-avatars.com/api/?name='.urlencode($transaction->user->name).'&background=0c2140&color=fff' }}"
                         class="rounded-circle shadow-sm mb-3" width="80">
                    <h5 class="fw-bold text-navy mb-1">{{ $transaction->user->name }}</h5>
                    <p class="text-muted small mb-3">{{ $transaction->user->email }}</p>
                    <hr class="opacity-25">
                    <div class="row text-start g-3">
                        <div class="col-6">
                            <label class="profile-info-label">Status Sewa</label>
                            <span class="badge-status bg-pending w-100 text-center">{{ strtoupper($transaction->status) }}</span>
                        </div>
                        <div class="col-6">
                            <label class="profile-info-label">Pembayaran</label>
                            <span class="badge-status bg-paid w-100 text-center">{{ strtoupper($transaction->payment_status) }}</span>
                        </div>
                        <div class="col-12 mt-4 text-center">
                            <div class="bg-light p-3 rounded-4 border border-dashed border-primary">
                                <div class="profile-info-label">Durasi Sewa</div>
                                <h4 class="fw-bold text-navy mb-0">{{ $transaction->total_days }} Hari</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.transactions') }}" class="btn btn-navy w-100 py-3 rounded-pill fw-bold shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> KEMBALI KE DAFTAR
            </a>
        </div>
    </div>
</div>
@endsection