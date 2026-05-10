@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title">Validasi Pengembalian</h2>
        <button onclick="startScan()" class="btn btn-navy shadow-sm">
            <i class="fas fa-qrcode me-2"></i> Scan QR Retur
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div id="reader-container" class="mb-4 d-none">
        <div class="card card-custom">
            <div class="card-body text-center">
                <div id="reader" class="mx-auto" style="max-width: 400px;"></div>
                <button onclick="stopScan()" class="btn btn-danger btn-sm mt-3">Tutup Kamera</button>
            </div>
        </div>
    </div>

    <div class="card card-custom mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-12 col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama member..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-navy w-100">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-items mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Kode</th>
                            <th>Member</th>
                            <th>Jadwal Kembali</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        <tr>
                            <td class="ps-4 fw-bold" data-label="Kode">{{ $trx->transaction_code }}</td>
                            <td data-label="Member">
                                <div class="fw-semibold text-navy">{{ $trx->user->name }}</div>
                            </td>
                            <td data-label="Jadwal">
                                <div class="small text-muted"><i class="fas fa-sign-out-alt me-1"></i> Ambil: {{ $trx->pickup_datetime->format('d/m/y H:i') }}</div>
                                <div class="fw-bold text-danger"><i class="fas fa-undo me-1"></i> Kembali: {{ $trx->return_datetime->format('d/m/y H:i') }}</div>
                            </td>
                            <td class="text-center" data-label="Aksi">
                                <a href="{{ route('admin.returns.show', $trx) }}" class="btn btn-navy btn-sm px-4 shadow-sm">
                                    Proses Retur
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <p class="text-muted">Tidak ada transaksi yang menunggu pengembalian.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrCode;
    function startScan() {
        document.getElementById('reader-container').classList.remove('d-none');
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 }, (decodedText) => {
            window.location.href = "/admin/scan/" + decodedText;
        });
    }
    function stopScan() {
        if(html5QrCode) {
            html5QrCode.stop().then(() => {
                document.getElementById('reader-container').classList.add('d-none');
            });
        }
    }
</script>
@endpush
@endsection