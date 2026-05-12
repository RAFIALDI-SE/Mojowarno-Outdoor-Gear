@extends('admin.layouts.dashboard')

@section('content')
<div style="background-color: var(--navy); min-height: 150px; padding-top: 30px; margin: -25px -25px 0 -25px;">
    <div class="container-fluid px-4">
        <h3 class="text-light-blue fw-bold m-0"><i class="fas fa-history me-2 text-light-blue"></i> Riwayat Semua Transaksi</h3>
        <p class="text-light-blue-50 small">Laporan lengkap seluruh penyewaan alat outdoor.</p>
    </div>
</div>

<div class="container-fluid mb-5" style="margin-top: -50px;">
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="small fw-bold text-muted mb-1 text-uppercase">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-0 bg-light"
                               placeholder="Cari kode atau nama member..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted mb-1 text-uppercase">Status</label>
                    <select name="status" class="form-select border-0 bg-light">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted mb-1 text-uppercase">Pembayaran</label>
                    <select name="payment_status" class="form-select border-0 bg-light">
                        <option value="">Semua Pembayaran</option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="half" {{ request('payment_status') == 'half' ? 'selected' : '' }}>DP</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-navy w-100 fw-bold rounded-pill">
                        <i class="fas fa-filter me-1"></i> TERAPKAN FILTER
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-admin-responsive">
                <thead class="bg-light">
                    <tr class="small text-muted text-uppercase">
                        <th class="ps-4">Transaksi</th>
                        <th>Member</th>
                        <th>Jadwal</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Total</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td data-label="Transaksi" class="ps-4">
                            <div class="fw-bold text-navy mb-0">{{ $trx->transaction_code }}</div>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $trx->created_at->format('d/m/y H:i') }}</small>
                        </td>
                        <td data-label="Member">
                            <div class="fw-semibold">{{ $trx->user->name }}</div>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $trx->user->email }}</small>
                        </td>
                        <td data-label="Jadwal">
                            <div class="small fw-bold text-navy">
                                <i class="far fa-calendar-check me-1 text-primary"></i> {{ $trx->pickup_datetime->format('d M') }}
                                <i class="fas fa-arrow-right mx-1 text-muted small"></i>
                                <i class="far fa-calendar-times me-1 text-danger"></i> {{ $trx->return_datetime->format('d M Y') }}
                            </div>
                        </td>
                        <td data-label="Status" class="text-md-center">
                            <div class="d-flex flex-column gap-1 align-items-md-center">
                                <span class="badge-status {{ $trx->status == 'pending' ? 'bg-pending' : ($trx->status == 'returned' ? 'bg-success-custom' : 'bg-paid') }}" style="font-size: 0.6rem;">
                                    {{ strtoupper($trx->status) }}
                                </span>
                                <span class="badge-status {{ $trx->payment_status == 'unpaid' ? 'bg-unpaid' : 'bg-paid' }}" style="font-size: 0.6rem;">
                                    {{ strtoupper($trx->payment_status) }}
                                </span>
                            </div>
                        </td>
                        <td data-label="Total" class="text-md-end fw-bold text-navy">
                            Rp {{ number_format($trx->total_price) }}
                        </td>
                        <td data-label="Opsi" class="text-end pe-4">
                            <a href="{{ route('admin.transactions.show', $trx) }}" class="btn btn-sm btn-navy rounded-pill px-4 fw-bold shadow-sm">
                                DETAIL
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted small">Data transaksi tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection